<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\Recipe\RecipeUtilityService;
use App\Models\Recipe;
use Illuminate\Support\Collection;

class RecipeUtilityServiceTest extends TestCase
{
    protected $recipeUtilityService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->recipeUtilityService = new RecipeUtilityService();
    }

    public function testCalculateCostOfRecipe()
    {
        // Configurar el entorno de prueba
        $recipe = Recipe::factory()->create();
        $this->recipeUtilityService = new RecipeUtilityService($recipe);

        // Llamar al método y verificar el resultado
        $cost = $this->recipeUtilityService->calculateCostOfRecipe();
        $this->assertIsNumeric($cost);
    }

   

    public function testGetRecetaMayorCoste()
    {
        // Configurar el entorno de prueba
        $recipes = Recipe::factory()->count(3)->create();
        $recipes->each(function ($recipe, $index) {
            $recipe->cost = ($index + 1) * 10;
            $recipe->save();
        });

        // Llamar al método y verificar el resultado
        $result = $this->recipeUtilityService->getRecetaMayorCoste($recipes);
        $this->assertEquals(30, $result->cost);
    }

    public function testGetRecetaMenorCoste()
    {
        // Configurar el entorno de prueba
        $recipes = Recipe::factory()->count(3)->create();
        $recipes->each(function ($recipe, $index) {
            $recipe->cost = ($index + 1) * 10;
            $recipe->save();
        });

        // Llamar al método y verificar el resultado
        $result = $this->recipeUtilityService->getRecetaMenorCoste($recipes);
        $this->assertEquals(10, $result->cost);
    }

    public function testCalcMargenes()
    {
        // Configurar el entorno de prueba
        $recipes = Recipe::factory()->count(3)->create();
        $recipes->each(function ($recipe, $index) {
            $recipe->cost = ($index + 1) * 10;
            $recipe->sale_price = ($index + 1) * 20;
            $recipe->save();
        });

        // Llamar al método y verificar el resultado
        $result = $this->recipeUtilityService->calcMargenes($recipes);
        $this->assertEquals("100%", $result->first()->margen);
    }

    public function testGetRecetaMayorMargen()
    {
        // Crear recetas con diferentes márgenes
        $recipes = Recipe::factory()->count(3)->create();
        $recipes->each(function ($recipe, $index) {
            $recipe->cost = ($index + 1) * 10; // Coste: 10, 20, 30
            $recipe->sale_price = ($index + 1) * 30; // Precio de venta: 30, 60, 90
            $recipe->save();
        });
    
        // Calcular márgenes esperados
        $recipes = $this->recipeUtilityService->calcMargenes($recipes);
    
        // Obtener la receta con el mayor margen
        $result = $this->recipeUtilityService->getRecetaMayorMargen($recipes);
    
        // Verificar que el margen es del 200%
        $expectedMargin = "200%"; // (30 - 10) / 10 * 100 = 200%
        $this->assertEquals($expectedMargin, $result->margen);
    }
}