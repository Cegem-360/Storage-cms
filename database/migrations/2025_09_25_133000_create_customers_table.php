<?php

declare(strict_types=1);

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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Team::class)->nullable()->constrained();
            $table->string('customer_code');
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->json('billing_address')->nullable();
            $table->json('shipping_address')->nullable();
            $table->decimal('credit_limit', 10, 2)->default(0);
            $table->decimal('balance', 10, 2)->default(0);
            $table->string('type')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['team_id', 'customer_code']);
            $table->unique(['team_id', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
