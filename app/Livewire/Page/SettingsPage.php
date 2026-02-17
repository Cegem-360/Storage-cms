<?php

declare(strict_types=1);

namespace App\Livewire\Page;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
#[Title('Settings')]
final class SettingsPage extends Component
{
    /** @var array<string, array<string, string>> */
    public const array PROVIDER_MODELS = [
        'openai' => [
            'gpt-4o-mini' => 'GPT-4o Mini',
            'gpt-4o' => 'GPT-4o',
            'gpt-4-turbo' => 'GPT-4 Turbo',
            'o1' => 'o1',
            'o1-mini' => 'o1 Mini',
            'o3-mini' => 'o3 Mini',
        ],
        'anthropic' => [
            'claude-sonnet-4-5-20250929' => 'Claude Sonnet 4.5',
            'claude-3-5-haiku-20241022' => 'Claude 3.5 Haiku',
            'claude-3-opus-20240229' => 'Claude 3 Opus',
        ],
        'gemini' => [
            'gemini-2.0-flash' => 'Gemini 2.0 Flash',
            'gemini-1.5-pro' => 'Gemini 1.5 Pro',
            'gemini-1.5-flash' => 'Gemini 1.5 Flash',
        ],
        'groq' => [
            'llama-3.3-70b-versatile' => 'Llama 3.3 70B',
            'llama-3.1-8b-instant' => 'Llama 3.1 8B Instant',
            'mixtral-8x7b-32768' => 'Mixtral 8x7B',
        ],
        'deepseek' => [
            'deepseek-chat' => 'DeepSeek Chat',
            'deepseek-reasoner' => 'DeepSeek Reasoner',
        ],
        'mistral' => [
            'mistral-large-latest' => 'Mistral Large',
            'mistral-small-latest' => 'Mistral Small',
            'open-mixtral-8x22b' => 'Mixtral 8x22B',
        ],
        'xai' => [
            'grok-2' => 'Grok 2',
            'grok-2-mini' => 'Grok 2 Mini',
        ],
        'openrouter' => [
            'openai/gpt-4o-mini' => 'GPT-4o Mini',
            'anthropic/claude-sonnet-4-5-20250929' => 'Claude Sonnet 4.5',
            'google/gemini-2.0-flash' => 'Gemini 2.0 Flash',
            'meta-llama/llama-3.3-70b' => 'Llama 3.3 70B',
        ],
    ];

    #[Validate('required|integer|min:1')]
    public int $lowStockThreshold = 10;

    public bool $autoReorderEnabled = false;

    #[Validate('nullable|email|max:255')]
    public ?string $notificationEmail = null;

    #[Validate('required|in:openai,anthropic,gemini,groq,deepseek,mistral,xai,openrouter')]
    public string $aiProvider = 'openai';

    #[Validate('nullable|string|max:255')]
    public ?string $aiApiKey = null;

    #[Validate('required|string|max:255')]
    public string $aiModel = 'gpt-4o-mini';

    public function updatedAiProvider(): void
    {
        $models = self::PROVIDER_MODELS[$this->aiProvider] ?? [];
        $this->aiModel = (string) array_key_first($models);
    }

    /**
     * @return array<string, string>
     */
    public function getAvailableModels(): array
    {
        return self::PROVIDER_MODELS[$this->aiProvider] ?? [];
    }

    public function mount(): void
    {
        $team = auth()->user()->team;
        $team->load('settings');

        $this->lowStockThreshold = (int) $team->getSetting('low_stock_threshold', 10);
        $this->autoReorderEnabled = (bool) $team->getSetting('auto_reorder_enabled', false);
        $this->notificationEmail = $team->getSetting('notification_email');
        $this->aiProvider = $team->getSetting('ai_provider', 'openai');
        $this->aiApiKey = $team->getSetting('ai_api_key');
        $this->aiModel = $team->getSetting('ai_model', 'gpt-4o-mini');
    }

    public function save(): void
    {
        $this->validate();

        $team = auth()->user()->team;

        $team->setSetting('low_stock_threshold', $this->lowStockThreshold);
        $team->setSetting('auto_reorder_enabled', $this->autoReorderEnabled);
        $team->setSetting('notification_email', $this->notificationEmail);
        $team->setSetting('ai_provider', $this->aiProvider);
        $team->setSetting('ai_api_key', $this->aiApiKey);
        $team->setSetting('ai_model', $this->aiModel);

        session()->flash('success', __('Settings saved'));
    }

    public function render(): Factory|View
    {
        return view('livewire.page.settings-page');
    }
}
