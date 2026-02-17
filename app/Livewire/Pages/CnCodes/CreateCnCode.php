<?php

declare(strict_types=1);

namespace App\Livewire\Pages\CnCodes;

use App\Filament\Resources\CnCodes\Schemas\CnCodeForm;
use App\Livewire\Pages\Concerns\HasCreateForm;
use App\Models\CnCode;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class CreateCnCode extends Component implements HasActions, HasSchemas
{
    use HasCreateForm;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public function render(): View
    {
        return view('livewire.pages.cn-codes.create-cn-code');
    }

    protected static function getModel(): string
    {
        return CnCode::class;
    }

    protected static function getFormSchemaClass(): string
    {
        return CnCodeForm::class;
    }

    protected static function getListRouteName(): string
    {
        return 'dashboard.cn-codes';
    }

    protected static function getEditRouteName(): string
    {
        return 'dashboard.cn-codes.edit';
    }

    protected static function getResourceLabel(): string
    {
        return 'CN Code';
    }
}
