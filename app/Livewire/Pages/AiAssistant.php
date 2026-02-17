<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Ai\Agents\StorageAssistant;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Throwable;

#[Layout('components.layouts.dashboard')]
final class AiAssistant extends Component
{
    public string $message = '';

    public bool $isLoading = false;

    /** @var array<int, array{role: string, content: string}> */
    public array $messages = [];

    public function sendMessage(): void
    {
        $userMessage = mb_trim($this->message);

        if ($userMessage === '') {
            return;
        }

        $this->messages[] = ['role' => 'user', 'content' => $userMessage];
        $this->message = '';
        $this->isLoading = true;

        $team = auth()->user()->team;
        $provider = $team->getSetting('ai_provider', 'openai');
        $apiKey = $team->getSetting('ai_api_key');
        $model = $team->getSetting('ai_model', 'gpt-4o-mini');

        if (! $apiKey) {
            $this->messages[] = [
                'role' => 'assistant',
                'content' => __('AI API key is not configured. Please set it in Settings.'),
            ];
            $this->isLoading = false;
            $this->dispatch('ai-message-received');

            return;
        }

        config()->set("ai.providers.{$provider}.key", $apiKey);

        try {
            $response = (new StorageAssistant($team))
                ->withHistory($this->messages)
                ->prompt($userMessage, provider: $provider, model: $model);

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

    public function clearChat(): void
    {
        $this->messages = [];
    }

    public function render(): View
    {
        return view('livewire.pages.ai-assistant');
    }
}
