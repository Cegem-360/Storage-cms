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
