<?php

declare(strict_types=1);

use App\Models\Employee;
use App\Models\Team;
use App\Models\Warehouse;
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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Team::class)->nullable()->constrained();
            $table->string('inventory_number', 100);
            $table->foreignIdFor(Warehouse::class)->constrained();
            $table->foreignIdFor(Employee::class, 'conducted_by')->constrained();
            $table->date('inventory_date');
            $table->string('status', 50)->default('IN_PROGRESS');
            $table->string('type', 50);
            $table->string('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['team_id', 'inventory_number']);
            $table->index(['status']);
            $table->index(['inventory_date']);
            $table->index(['warehouse_id']);
            $table->index(['type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
