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
        // sales migration
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->date('sale_date');
            $table->timestamps();
        });

        // sale_lines migration
        Schema::create('sale_lines', function (Blueprint $table) {

            $table->id();
            $table->timestamps();

            $table->foreignId('sale_id')->constrained();
            $table->foreignId('recipe_id')->constrained();
            $table->integer('quantity');
            $table->decimal('price', 8, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_lines');
        Schema::dropIfExists('sales');
    }
};
