
# Gestión de Recetas y Ventas para Restaurante

Este proyecto permite gestionar recetas (escandallos) y ventas en un restaurante, proporcionando funciones para registrar recetas con ingredientes, calcular márgenes de beneficio, y analizar ventas diarias.

**Autor**: Alejandro Ruesga

## Requisitos Previos

- **PHP** >= 8.0
- **Composer**
- **SQLite** o cualquier base de datos compatible con Laravel

## Instalación

1. **Clonar el repositorio**

2. **Instalar dependencias**

    ```bash
    composer install
    ```

3. **Configurar el archivo `.env`**

    Crea el archivo `.env` (puedes copiar `.env.example`) y configura la base de datos para usar SQLite:

    ```env
    DB_CONNECTION=sqlite
    DB_DATABASE=/ruta/al/archivo/database.sqlite
    ```

    Luego, crea el archivo `database.sqlite` en la carpeta `database` para almacenar los datos.

4. **Ejecutar migraciones y seeders**

    Ejecuta las migraciones y seeders para crear la estructura de la base de datos y agregar datos iniciales de ingredientes y recetas:

    ```bash
    php artisan migrate --seed
    ```

## Uso

### Comandos Disponibles

#### 1. Agregar una Receta

Para agregar una receta con ingredientes, utiliza el comando `recipe:add`.

```bash
php artisan recipe:add "NombreReceta" <precio_venta> "<id_ingrediente,cantidad>" ...
```

**Ejemplo**:

```bash
php artisan recipe:add "Guacamole" 0 "1,2" "2,1" "3,1" "4,0.5" "5,0.01"
```

Este comando agregará la receta "Guacamole" con los ingredientes especificados y calculará su coste total y margen de beneficio.

#### 2. Agregar una Venta

Para registrar una venta, utiliza el comando `sale:add`. Esto calculará automáticamente los márgenes de beneficio de cada receta vendida y el día con el mayor y menor volumen de ventas.

```bash
php artisan sale:add "<fecha_venta>" "<id_receta,cantidad,precio_opcional>" ...
```

**Ejemplo**:

```bash
php artisan sale:add "2024-07-02" "2,10" "3,5,10" "2,8" "3,2"
```

Este comando registrará una venta en la fecha especificada con las recetas, cantidades y precios proporcionados. Si no se especifica un precio, se usará el precio de venta de la receta.

## Explicación Breve del Código

1. **Migraciones**: Crean tablas para ingredientes, recetas, ventas y líneas de venta, incluyendo relaciones y estructuras necesarias para almacenar los datos.
2. **Seeders**: Añaden ingredientes y recetas iniciales a la base de datos para facilitar el uso y evitar errores de referencia.
3. **Modelos**: Definen las relaciones entre las tablas:
    - `Recipe` (recetas) se conecta con `Ingredient` (ingredientes) y otras `Recipe` como sub-recetas.
    - `Sale` (ventas) tiene varias `SaleLine` (líneas de venta), que incluyen recetas vendidas, cantidades y precios.
4. **Casos de Uso**:
    - `AddRecipe`: Agrega recetas y calcula su coste, así como los márgenes de beneficio.
    - `AddSale`: Registra ventas, calcula los márgenes de cada receta vendida y analiza el día con mayor y menor volumen de ventas.
5. **Comandos Artisan**:
    - `AddRecipeCommand`: Permite agregar recetas y mostrar el análisis de costes desde la consola.
    - `AddSaleCommand`: Registra ventas y calcula el margen de beneficio y análisis de ventas desde la consola.

Este proyecto permite gestionar recetas y ventas en un restaurante de manera eficiente a través de la línea de comandos, facilitando el cálculo de costes y márgenes de beneficio. No se ha seguido ninguna metodología específica como hexagonal, más allá de las convenciones de Laravel.