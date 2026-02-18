<?php

declare(strict_types=1);

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Product;
use App\Models\Receipt;
use App\Models\Supplier;
use App\Models\Team;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Team::class)->nullable()->constrained();
            $table->string('invoice_number', 100);
            $table->foreignIdFor(Order::class)->nullable()->constrained();
            $table->foreignIdFor(Receipt::class)->nullable()->constrained();
            $table->foreignIdFor(Supplier::class)->nullable()->constrained();
            $table->foreignIdFor(Customer::class)->nullable()->constrained();
            $table->foreignIdFor(Employee::class, 'issued_by')->constrained();
            $table->date('invoice_date');
            $table->date('due_date')->nullable();
            $table->string('status', 50)->default('draft');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('tax_total', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->string('currency', 3)->default('HUF');
            $table->string('payment_method')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['team_id', 'invoice_number']);
            $table->index(['status']);
            $table->index(['invoice_date']);
            $table->index(['due_date']);
            $table->index(['order_id']);
            $table->index(['receipt_id']);
        });

        Schema::create('invoice_lines', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Invoice::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Product::class)->constrained();
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('tax_percent', 5, 2)->default(0);
            $table->decimal('subtotal', 12, 2)->virtualAs('quantity * unit_price * (1 - discount_percent / 100.0)');
            $table->decimal('tax_amount', 12, 2)->virtualAs('quantity * unit_price * (1 - discount_percent / 100.0) * tax_percent / 100.0');
            $table->decimal('line_total', 12, 2)->virtualAs('quantity * unit_price * (1 - discount_percent / 100.0) * (1 + tax_percent / 100.0)');
            $table->string('note')->nullable();
            $table->timestamps();

            $table->index(['invoice_id']);
            $table->index(['product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_lines');
        Schema::dropIfExists('invoices');
    }
};
