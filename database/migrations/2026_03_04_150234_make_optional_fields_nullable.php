<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('sku', 100)->nullable()->change();
            $table->unsignedBigInteger('supplier_id')->nullable()->change();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->string('code', 50)->nullable()->change();
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('code', 50)->nullable()->change();
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->string('customer_code')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('sku', 100)->nullable(false)->change();
            $table->unsignedBigInteger('supplier_id')->nullable(false)->change();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->string('code', 50)->nullable(false)->change();
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('code', 50)->nullable(false)->change();
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->string('customer_code')->nullable(false)->change();
        });
    }
};
