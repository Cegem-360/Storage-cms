<?php

declare(strict_types=1);

use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Team;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Team::class)->nullable()->constrained();
            $table->string('order_number', 100);
            $table->string('type', 50); // PURCHASE, SALES, TRANSFER, RETURN
            $table->foreignIdFor(Customer::class)->nullable()->constrained();
            $table->foreignIdFor(Supplier::class)->nullable()->constrained();
            $table->string('status', 50)->default('DRAFT');
            $table->date('order_date');
            $table->date('delivery_date')->nullable();
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->json('shipping_address')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['team_id', 'order_number']);
            $table->index(['type']);
            $table->index(['status']);
            $table->index(['order_date']);
            $table->index(['customer_id']);
            $table->index(['supplier_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
