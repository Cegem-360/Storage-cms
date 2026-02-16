<?php

declare(strict_types=1);

namespace App\Livewire\Page;

use App\Models\Team;
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

    public function mount(): void
    {
        $team = $this->getTeam();
        $team->load('settings');

        $this->lowStockThreshold = (int) $team->getSetting('low_stock_threshold', 10);
        $this->autoReorderEnabled = (bool) $team->getSetting('auto_reorder_enabled', false);
        $this->notificationEmail = $team->getSetting('notification_email');
    }

    public function save(): void
    {
        $this->validate();

        $team = $this->getTeam();

        $team->setSetting('low_stock_threshold', $this->lowStockThreshold);
        $team->setSetting('auto_reorder_enabled', $this->autoReorderEnabled);
        $team->setSetting('notification_email', $this->notificationEmail);

        session()->flash('success', __('Settings saved'));
    }

    public function render(): Factory|View
    {
        return view('livewire.page.settings-page');
    }

    private function getTeam(): Team
    {
        return auth()->user()->team;
    }
}
