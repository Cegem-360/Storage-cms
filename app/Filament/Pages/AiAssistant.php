<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Ai\Agents\StorageAssistant;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Override;
use Throwable;

final class AiAssistant extends Page
{
    public string $message = '';

    public bool $isLoading = false;

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
        $this->isLoading = true;

        $user = Auth::user();
        $team = $user->team;
        $apiKey = config('ai.providers.gemini.key');

        if (! $apiKey) {
            $this->messages[] = [
                'role' => 'assistant',
                'content' => __('AI API key is not configured. Please set GEMINI_API_KEY in the environment.'),
            ];
            $this->isLoading = false;
            $this->dispatch('ai-message-received');

            return;
        }

        if ($team->hasExceededTokenLimit()) {
            $this->messages[] = [
                'role' => 'assistant',
                'content' => __('Monthly AI token limit has been reached. Please contact your administrator.'),
            ];
            $this->isLoading = false;
            $this->dispatch('ai-message-received');

            return;
        }

        try {
            $agent = new StorageAssistant($team);

            $response = $this->conversationId
                ? $agent->continue($this->conversationId, as: $user)->prompt($userMessage)
                : $agent->forUser($user)->prompt($userMessage);

            $this->conversationId = $response->conversationId;

            $team->recordTokenUsage(
                $response->usage->promptTokens,
                $response->usage->completionTokens,
            );

            $this->messages[] = [
                'role' => 'assistant',
                'content' => (string) $response,
            ];
        } catch (Throwable $e) {
            $this->messages[] = [
                'role' => 'assistant',
                'content' => __('An error occurred while processing your request. Please check the AI configuration in Settings.'),
            ];

            report($e);
        }

        $this->isLoading = false;

        $this->dispatch('ai-message-received');
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
        $user = Auth::user();

        $conversation = DB::table('agent_conversations')
            ->where('user_id', $user->getKey())
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
            ->map(fn ($m): array => ['role' => $m->role, 'content' => $m->content])
            ->all();
    }
}
