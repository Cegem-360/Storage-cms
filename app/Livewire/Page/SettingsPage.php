<?php

declare(strict_types=1);

namespace App\Livewire\Page;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
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

    public function mount(): void
    {
        $this->lowStockThreshold = (int) Cache::get('settings.low_stock_threshold', 10);
        $this->autoReorderEnabled = (bool) Cache::get('settings.auto_reorder_enabled', false);
        $this->notificationEmail = Cache::get('settings.notification_email');
    }

    public function save(): void
    {
        $this->validate();

        Cache::forever('settings.low_stock_threshold', $this->lowStockThreshold);
        Cache::forever('settings.auto_reorder_enabled', $this->autoReorderEnabled);
        Cache::forever('settings.notification_email', $this->notificationEmail);

        session()->flash('success', __('Settings saved'));
    }

    public function render(): Factory|View
    {
        return view('livewire.page.settings-page');
    }
}
