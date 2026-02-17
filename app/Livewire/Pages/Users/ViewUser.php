<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Users;

use App\Filament\Resources\Users\Schemas\UserInfolist;
use App\Livewire\Pages\Concerns\HasViewInfolist;
use App\Models\User;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ViewUser extends Component implements HasActions, HasSchemas
{
    use HasViewInfolist;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public function render(): View
    {
        return view('livewire.pages.users.view-user');
    }

    protected static function getModel(): string
    {
        return User::class;
    }

    protected static function getListRouteName(): string
    {
        return 'dashboard.users';
    }

    protected static function getResourceLabel(): string
    {
        return 'User';
    }

    protected static function getInfolistSchemaClass(): string
    {
        return UserInfolist::class;
    }
}
