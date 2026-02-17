<?php

declare(strict_types=1);

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Order::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Product::class)->constrained();
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('tax_percent', 5, 2)->default(27.00);
            $table->decimal('subtotal', 12, 2)->virtualAs('quantity * unit_price * (1 - discount_percent / 100.0)');
            $table->decimal('discount_amount', 12, 2)->virtualAs('quantity * unit_price * (discount_percent / 100.0)');
            $table->decimal('tax_amount', 12, 2)->virtualAs('quantity * unit_price * (1 - discount_percent / 100.0) * (tax_percent / 100.0)');
            $table->decimal('total_with_tax', 12, 2)->virtualAs('quantity * unit_price * (1 - discount_percent / 100.0) * (1 + tax_percent / 100.0)');
            $table->string('note')->nullable();
            $table->timestamps();

            $table->index(['order_id']);
            $table->index(['product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_lines');
    }
};
