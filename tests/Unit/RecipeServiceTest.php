<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\Recipe\RecipeService;
use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\Escandallo;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RecipeServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $recipeService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->recipeService = new RecipeService();
    }

    public function testCreateRecipe()
    {
        // Crear datos de prueba
        $name = 'Receta de Prueba';
        $salePrice = 100.0;
        $ingredients = [
            new Escandallo(['ingredient_id' => 1, 'quantity' => 2]),
            new Escandallo(['recipe_id' => 2, 'quantity' => 1])
        ];

        // Crear ingredientes y recetas de prueba
        Ingredient::factory()->create(['id' => 1, 'cost' => 10]);
        Recipe::factory()->create(['id' => 2, 'cost' => 20]);

        // Ejecutar el método
        $recipe = $this->recipeService->createRecipe($name, $salePrice, $ingredients);

        // Verificar el resultado
        $this->assertInstanceOf(Recipe::class, $recipe);
        $this->assertEquals($name, $recipe->name);
        $this->assertEquals($salePrice, $recipe->sale_price);
    }

    public function testGetMargenes()
    {
        // Crear ingredientes para las recetas
        $ingredient1 = Ingredient::factory()->create(['cost' => 5]);  // Costo por unidad: 5
        $ingredient2 = Ingredient::factory()->create(['cost' => 10]); // Costo por unidad: 10
        $ingredient3 = Ingredient::factory()->create(['cost' => 15]); // Costo por unidad: 15

        // Crear recetas a través del caso de uso (RecipeService->createRecipe)
        
        // Receta 1: margen 100%
        // Coste: 2 * 5 = 10
        // Precio de venta: 20
        $recipe1 = $this->recipeService->createRecipe('Receta 1', 20, [
            new Escandallo(['ingredient_id' => $ingredient1->id, 'quantity' => 2]),
        ]);
        // dd($recipe1->cost);

        // Receta 2: margen 200%
        // Coste: 2 * 10 = 20
        // Precio de venta: 60
        $recipe2 = $this->recipeService->createRecipe('Receta 2', 60, [
            new Escandallo(['ingredient_id' => $ingredient2->id, 'quantity' => 2]),
        ]);

        // Receta 3: margen 50%
        // Coste: 2 * 15 = 30
        // Precio de venta: 45
        $recipe3 = $this->recipeService->createRecipe('Receta 3', 45, [
            new Escandallo(['ingredient_id' => $ingredient3->id, 'quantity' => 2]),
        ]);
        // dd($recipe3->escandallos);
        $recipesBuilder = Recipe::whereIn('id', [$recipe1->id, $recipe2->id, $recipe3->id]);

        // Ejecutar el método getMargenes
        $result = $this->recipeService->getMargenes($recipesBuilder);

        // Verificar que el resultado contenga las claves esperadas
        $this->assertArrayHasKey('recetaMayorCoste', $result);
        $this->assertArrayHasKey('recetaMenorCoste', $result);
        $this->assertArrayHasKey('recetaMayorMargen', $result);
        $this->assertArrayHasKey('recetaMenorMargen', $result);

        // Verificar los costos
        $this->assertEquals(10, $result['recetaMenorCoste']['cost']); 
        $this->assertEquals(30, $result['recetaMayorCoste']['cost']);
        // verificar los margenes 
        $this->assertEquals("200%", $result['recetaMayorMargen']['margen']); 
        $this->assertEquals("50%", $result['recetaMenorMargen']['margen']);


    

    
    }
}