<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Batches;

use App\Filament\Resources\Batches\Schemas\BatchForm;
use App\Livewire\Pages\Concerns\HasCreateForm;
use App\Models\Batch;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class CreateBatch extends Component implements HasActions, HasSchemas
{
    use HasCreateForm;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public function render(): View
    {
        return view('livewire.pages.batches.create-batch');
    }

    protected static function getModel(): string
    {
        return Batch::class;
    }

    protected static function getFormSchemaClass(): string
    {
        return BatchForm::class;
    }

    protected static function getListRouteName(): string
    {
        return 'dashboard.batches';
    }

    protected static function getEditRouteName(): string
    {
        return 'dashboard.batches.edit';
    }

    protected static function getResourceLabel(): string
    {
        return 'Batch';
    }
}
