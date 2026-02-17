<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Products;

use App\Filament\Resources\Products\Schemas\ProductForm;
use App\Livewire\Pages\Concerns\HasCreateForm;
use App\Models\Product;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class CreateProduct extends Component implements HasActions, HasSchemas
{
    use HasCreateForm;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public function render(): View
    {
        return view('livewire.pages.products.create-product');
    }

    protected static function getModel(): string
    {
        return Product::class;
    }

    protected static function getFormSchemaClass(): string
    {
        return ProductForm::class;
    }

    protected static function getListRouteName(): string
    {
        return 'dashboard.products';
    }

    protected static function getEditRouteName(): string
    {
        return 'dashboard.products.edit';
    }

    protected static function getResourceLabel(): string
    {
        return 'Product';
    }
}
