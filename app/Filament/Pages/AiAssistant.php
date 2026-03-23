<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Ai\Agents\StorageAssistant;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Override;

final class AiAssistant extends Page
{
    public string $message = '';

    public ?string $conversationId = null;

    /** @var array<int, array{role: string, content: string}> */
    public array $messages = [];

    protected string $view = 'filament.pages.ai-assistant';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static ?int $navigationSort = -1;

    #[Override]
    public static function getNavigationLabel(): string
    {
        return __('AI Assistant');
    }

    public function mount(): void
    {
        $this->loadLatestConversation();
    }

    #[Override]
    public function getTitle(): string
    {
        return __('AI Assistant');
    }

    public function sendMessage(): void
    {
        $userMessage = mb_trim($this->message);

        if ($userMessage === '') {
            return;
        }

        $this->messages[] = ['role' => 'user', 'content' => $userMessage];
        $this->message = '';

        $user = Auth::user();
        $team = $user->team;
        $apiKey = config('ai.providers.gemini.key');

        if (! $apiKey) {
            $this->messages[] = [
                'role' => 'assistant',
                'content' => __('AI API key is not configured. Please set GEMINI_API_KEY in the environment.'),
            ];

            return;
        }

        if ($team->hasExceededTokenLimit()) {
            $this->messages[] = [
                'role' => 'assistant',
                'content' => __('Monthly AI token limit has been reached. Please contact your administrator.'),
            ];

            return;
        }

        $agent = new StorageAssistant($team);
        $channel = new PrivateChannel('ai-chat.'.$user->id);

        if ($this->conversationId) {
            $agent->continue($this->conversationId, as: $user)
                ->broadcastOnQueue($userMessage, $channel)
                ->then(function ($response) use ($team): void {
                    $this->conversationId = $response->conversationId;
                    $team->recordTokenUsage(
                        $response->usage->promptTokens,
                        $response->usage->completionTokens,
                    );
                });
        } else {
            $agent->forUser($user)
                ->broadcastOnQueue($userMessage, $channel)
                ->then(function ($response) use ($team): void {
                    $this->conversationId = $response->conversationId;
                    $team->recordTokenUsage(
                        $response->usage->promptTokens,
                        $response->usage->completionTokens,
                    );
                });
        }
    }

    /**
     * @return array<int, object{id: string, title: string, created_at: string, message_count: int}>
     */
    public function getConversations(): array
    {
        return DB::table('agent_conversations')
            ->where('user_id', Auth::id())
            ->latest()
            ->limit(20)
            ->get()
            ->map(function ($c): object {
                $c->message_count = DB::table('agent_conversation_messages')
                    ->where('conversation_id', $c->id)
                    ->where('role', 'user')
                    ->count();

                return $c;
            })
            ->all();
    }

    public function loadConversation(string $id): void
    {
        $conversation = DB::table('agent_conversations')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (! $conversation) {
            return;
        }

        $this->conversationId = $conversation->id;
        $this->messages = DB::table('agent_conversation_messages')
            ->where('conversation_id', $conversation->id)
            ->orderBy('id')
            ->get()
            ->filter(fn ($m): bool => in_array($m->role, ['user', 'assistant']))
            ->map(fn ($m): array => ['role' => $m->role, 'content' => $m->content])
            ->values()
            ->all();

        $this->dispatch('ai-message-received');
    }

    public function newConversation(): void
    {
        $this->messages = [];
        $this->conversationId = null;
    }

    /**
     * @return array{used: int, limit: int, percentage: float, hasLimit: bool, exceeded: bool}
     */
    public function getTokenUsageInfo(): array
    {
        $team = Auth::user()->team;
        $limit = (int) $team->getSetting('ai_monthly_token_limit', 0);
        $usage = $team->aiTokenUsages()
            ->where('month', now()->format('Y-m'))
            ->first();

        return [
            'used' => $usage?->total_tokens ?? 0,
            'limit' => $limit,
            'percentage' => $team->getTokenUsagePercentage(),
            'hasLimit' => $limit > 0,
            'exceeded' => $team->hasExceededTokenLimit(),
        ];
    }

    public function clearChat(): void
    {
        $this->messages = [];
        $this->conversationId = null;
    }

    private function loadLatestConversation(): void
    {
        $conversation = DB::table('agent_conversations')
            ->where('user_id', Auth::id())
            ->latest()
            ->first();

        if (! $conversation) {
            return;
        }

        $this->conversationId = $conversation->id;
        $this->messages = DB::table('agent_conversation_messages')
            ->where('conversation_id', $conversation->id)
            ->orderBy('id')
            ->get()
            ->filter(fn ($m): bool => in_array($m->role, ['user', 'assistant']))
            ->map(fn ($m): array => ['role' => $m->role, 'content' => $m->content])
            ->values()
            ->all();
    }
}
