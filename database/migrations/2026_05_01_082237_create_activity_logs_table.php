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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            // foreign key to users
            $table->foreignId('user_id')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();

            // ex:  created stock, updated stock
            $table->string('action');

            // description ng action
            $table->text('description')->nullable();

            // ex: product, supplier, category
            $table->string('subject_type')->nullable();

            // kung ano yung id na ginalaw
            $table->unsignedBigInteger('subject_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
