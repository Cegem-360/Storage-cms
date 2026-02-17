<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Services\AiAssistantService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

final class AiAssistantChat extends Component
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

        $service = app(AiAssistantService::class);
        $team = auth()->user()->team;

        $result = $service->chat($this->messages, $team);

        $this->messages[] = [
            'role' => 'assistant',
            'content' => $result['message'],
        ];

        $this->isLoading = false;

        $this->dispatch('ai-message-received');
    }

    public function clearChat(): void
    {
        $this->messages = [];
    }

    public function render(): View
    {
        return view('livewire.ai-assistant-chat');
    }
}
