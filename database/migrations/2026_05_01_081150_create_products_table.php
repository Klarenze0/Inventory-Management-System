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
            
            // foreign key to category table
            $table->foreignId('category_id')
                    ->nullable()
                    ->constrained('categories')
                    ->nullOnDelete();

            // basic info ng product
            $table->string('name');
            $table->string('sku')->unique();
            $table->string('barcode')->nullable()->unique();
            $table->text('description')->nullable();    

            // pricing at stock
            $table->decimal('unit_price', 10, 2)->default(0.00);
            $table->integer('stock_quantity')->default(9);
            $table->integer('low_stock_threshold')->default(10);

            // image
            $table->string('image_path')->nullable();

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
