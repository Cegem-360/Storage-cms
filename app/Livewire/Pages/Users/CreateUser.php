<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Users;

use App\Filament\Resources\Users\Schemas\UserForm;
use App\Livewire\Pages\Concerns\HasCreateForm;
use App\Models\User;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class CreateUser extends Component implements HasActions, HasSchemas
{
    use HasCreateForm;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public function render(): View
    {
        return view('livewire.pages.users.create-user');
    }

    protected static function getModel(): string
    {
        return User::class;
    }

    protected static function getFormSchemaClass(): string
    {
        return UserForm::class;
    }

    protected static function getListRouteName(): string
    {
        return 'dashboard.users';
    }

    protected static function getEditRouteName(): string
    {
        return 'dashboard.users.edit';
    }

    protected static function getResourceLabel(): string
    {
        return 'User';
    }
}
