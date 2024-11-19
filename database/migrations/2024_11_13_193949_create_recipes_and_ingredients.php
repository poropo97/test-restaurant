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
        // recipes migration
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('sale_price', 8, 2);
            $table->decimal('cost', 8, 2)->default(0);
            $table->timestamps();
        });

        // ingredients migration
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('cost', 8, 2);
            $table->timestamps();
        });

        // recipe_ingredients pivot table migration (Escandallo)
        Schema::create('recipe_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained();
            $table->foreignId('ingredient_id')->constrained();
            $table->decimal('quantity', 8, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_ingredients');
        Schema::dropIfExists('ingredients');
        Schema::dropIfExists('recipes');
    }
};
