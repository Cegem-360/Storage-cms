<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Products;

use App\Filament\Resources\Products\Schemas\ProductForm;
use App\Livewire\Pages\Concerns\HasEditForm;
use App\Models\Product;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class EditProduct extends Component implements HasActions, HasSchemas
{
    use HasEditForm;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public function render(): View
    {
        return view('livewire.pages.products.edit-product');
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

    protected static function getResourceLabel(): string
    {
        return 'Product';
    }
}
