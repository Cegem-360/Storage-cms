<?php

declare(strict_types=1);

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
        Schema::create('warehouse_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete();
            $table->string('code', 50);
            $table->string('zone', 50)->nullable();
            $table->string('row', 50)->nullable();
            $table->string('shelf', 50)->nullable();
            $table->string('level', 50)->nullable();
            $table->string('full_path')->virtualAs("concat(coalesce(zone, ''), '-', coalesce(`row`, ''), '-', coalesce(shelf, ''), '-', coalesce(level, ''))");
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['warehouse_id', 'code']);
        });

        Schema::table('stocks', function (Blueprint $table) {
            $table->foreignId('warehouse_location_id')->nullable()->after('warehouse_id')->constrained('warehouse_locations')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropConstrainedForeignId('warehouse_location_id');
        });

        Schema::dropIfExists('warehouse_locations');
    }
};
