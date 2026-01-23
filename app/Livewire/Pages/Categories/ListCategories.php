<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Categories;

use App\Filament\Resources\Categories\Tables\CategoriesTable;
use App\Models\Category;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ListCategories extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return CategoriesTable::configureDashboard(
            $table->query(Category::query())
        );
    }

    public function render(): View
    {
        return view('livewire.pages.categories.list-categories');
    }
}
