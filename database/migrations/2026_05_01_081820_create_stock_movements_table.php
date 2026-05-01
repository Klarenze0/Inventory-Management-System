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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();

            // foreign key to products table
            $table->foreignId('product_id')
                    ->contrained('products')
                    ->cascadeOnDelete();
            
            // foreign key to users table
            $table->foreignId('user_id')
                    ->constrained('users')
                    ->cascadeOnDelete();
            
            $table->enum('type', ['in', 'out', 'adjustment']);
            $table->integer('quantity');
            $table->text('reason')->nullable();

            // para malaman yung stock level at the time of movement
            $table->integer('stock_before');
            $table->integer('stock_after');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
