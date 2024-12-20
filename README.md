# Gestión de Recetas y Ventas para Restaurante

Este proyecto permite gestionar recetas (escandallos) y ventas en un restaurante, proporcionando funciones para registrar recetas con ingredientes, calcular márgenes de beneficio, y analizar ventas diarias.

**Autor:** Alejandro Ruesga

## Requisitos Previos

- PHP >= 8.0
- Composer
- SQLite o cualquier base de datos compatible con Laravel

## Instalación

1. Clonar el repositorio:

    ```bash
    git clone <URL_DEL_REPOSITORIO>
    ```

2. Instalar dependencias:

    ```bash
    composer install
    ```

3. Configurar el archivo `.env`:

    Crea el archivo `.env` (puedes copiar `.env.example`) y configura la base de datos para usar SQLite:

    ```env
    DB_CONNECTION=sqlite
    DB_DATABASE=/ruta/al/archivo/database.sqlite
    ```

    Luego, crea el archivo `database.sqlite` en la carpeta `database` para almacenar los datos:

    ```bash
    touch database/database.sqlite
    ```

4. Ejecutar migraciones y seeders:

    Ejecuta las migraciones y seeders para crear la estructura de la base de datos y agregar datos iniciales de ingredientes y recetas:

    ```bash
    php artisan migrate --seed
    ```

## Uso

### Comandos Disponibles

1. **Agregar una Receta**

    Para agregar una receta con ingredientes, utiliza el comando `recipe:add`:

    ```bash
    php artisan recipe:add "NombreReceta" <precio_venta>
    ```

    Durante la ejecución, el sistema te guiará para agregar ingredientes o recetas existentes como parte de la receta. Por ejemplo:

    ```bash
    php artisan recipe:add "Pizza Margarita" 12.50
    ```

    Sigue las instrucciones en pantalla para seleccionar ingredientes o recetas con sus cantidades.

2. **Registrar una Venta**

    Para registrar una venta de una receta, utiliza el comando `app:sale`:

    ```bash
    php artisan app:sale <fecha>,<id_receta>,<id_receta>,...
    ```

    Por ejemplo:

    ```bash
    php artisan app:sale 2024-11-20,1,2,3
    ```

    Este comando registra ventas para las recetas con los IDs indicados en la fecha especificada. Además, calculará los beneficios de cada receta involucrada.

3. **Consultar Márgenes de Beneficio**

    Para calcular los márgenes de beneficio de todas las recetas, utiliza el comando `app:get-margenes`:

    ```bash
    php artisan app:get-margenes
    ```

    Este comando mostrará una lista con los márgenes de cada receta, calculados como la diferencia entre el precio de venta y el costo total de los ingredientes.

## Pruebas

El proyecto incluye un conjunto de pruebas para asegurar el correcto funcionamiento. Para ejecutarlas, utiliza el siguiente comando:

```bash
php artisan test
```

Esto correrá las pruebas unitarias y funcionales definidas para la aplicación.

## Detalles de Implementación

El proyecto incluye los siguientes comandos personalizados:

- **recipe:add**
    - Permite agregar una nueva receta con ingredientes o recetas existentes.
    - Solicita el nombre de la receta, su precio de venta y los ingredientes con sus cantidades.

- **app:sale**
    - Registra una venta para una o varias recetas en una fecha específica.
    - Calcula y muestra el beneficio asociado para cada receta involucrada.

- **app:get-margenes**
    - Calcula los márgenes de beneficio de todas las recetas registradas.
    - Utiliza el precio de venta y el costo total de los ingredientes para el cálculo.