<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->decimal('purchase_price_usd', 10, 2)->nullable();
            $table->decimal('purchase_price_kzt', 10, 2)->nullable();
            $table->decimal('wholesale_price_usd', 10, 2)->nullable();
            $table->decimal('wholesale_price_kzt', 10, 2)->nullable();
            $table->integer('current_quantity')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
