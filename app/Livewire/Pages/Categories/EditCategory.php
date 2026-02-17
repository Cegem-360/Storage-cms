<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Categories;

use App\Filament\Resources\Categories\Schemas\CategoryForm;
use App\Livewire\Pages\Concerns\HasEditForm;
use App\Models\Category;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class EditCategory extends Component implements HasActions, HasSchemas
{
    use HasEditForm;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public function render(): View
    {
        return view('livewire.pages.categories.edit-category');
    }

    protected static function getModel(): string
    {
        return Category::class;
    }

    protected static function getFormSchemaClass(): string
    {
        return CategoryForm::class;
    }

    protected static function getListRouteName(): string
    {
        return 'dashboard.categories';
    }

    protected static function getResourceLabel(): string
    {
        return 'Category';
    }
}
