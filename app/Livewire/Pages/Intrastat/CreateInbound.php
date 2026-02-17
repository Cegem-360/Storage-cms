<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Intrastat;

use App\Filament\Resources\IntrastatDeclarations\Schemas\IntrastatDeclarationForm;
use App\Livewire\Pages\Concerns\HasCreateForm;
use App\Models\IntrastatDeclaration;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class CreateInbound extends Component implements HasActions, HasSchemas
{
    use HasCreateForm;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public function render(): View
    {
        return view('livewire.pages.intrastat.create-inbound');
    }

    protected static function getModel(): string
    {
        return IntrastatDeclaration::class;
    }

    protected static function getFormSchemaClass(): string
    {
        return IntrastatDeclarationForm::class;
    }

    protected static function getListRouteName(): string
    {
        return 'dashboard.intrastat-inbounds';
    }

    protected static function getEditRouteName(): string
    {
        return 'dashboard.intrastat-declarations.edit';
    }

    protected static function getResourceLabel(): string
    {
        return 'Intrastat Inbound';
    }
}
