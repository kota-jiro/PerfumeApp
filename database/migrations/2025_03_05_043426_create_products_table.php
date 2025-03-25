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
            $table->string('title');
            $table->string('category');
            $table->string('description');
            $table->string('image')->nullable();
            
            $table->integer('stock_small')->default(0)->nullable(); 
            $table->integer('stock_medium')->default(0)->nullable(); 
            $table->integer('stock_large')->default(0)->nullable();
    
            $table->decimal('price_small', 8, 2)->nullable();
            $table->decimal('price_medium', 8, 2)->nullable(); 
            $table->decimal('price_large', 8, 2)->nullable(); 
            
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
