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
       
        // sale_lines migration
        Schema::create('sale_lines', function (Blueprint $table) {

            $table->id();
            $table->timestamps();
            $table->date('sale_date');
            $table->foreignId('recipe_id')->constrained();
            $table->decimal('price', 8, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_lines');
        // Schema::dropIfExists('sales');
    }
};
