<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Intrastat;

use App\Filament\Resources\IntrastatDeclarations\Schemas\IntrastatDeclarationForm;
use App\Livewire\Pages\Concerns\HasEditForm;
use App\Models\IntrastatDeclaration;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class EditOutbound extends Component implements HasActions, HasSchemas
{
    use HasEditForm;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public function render(): View
    {
        return view('livewire.pages.intrastat.edit-outbound');
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
        return 'dashboard.intrastat-outbounds';
    }

    protected static function getResourceLabel(): string
    {
        return 'Intrastat Outbound';
    }
}
