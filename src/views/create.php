<?php
require_once __DIR__ . '/Header.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../services/ProductoService.php';

use services\ProductoService;

$productoService = new ProductoService($pdo);

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Tomar datos del formulario
    $descripcion = trim($_POST['descripcion'] ?? '');
    $imagen = trim($_POST['imagen'] ?? '');
    $marca = trim($_POST['marca'] ?? '');
    $modelo = trim($_POST['modelo'] ?? '');
    $precio = (float)($_POST['precio'] ?? 0);
    $stock = (int)($_POST['stock'] ?? 0);
    $categoria_id = trim($_POST['categoria_id'] ?? null);

    // Validaciones simples
    if ($descripcion === '' || $marca === '' || $modelo === '') {
        $mensaje = 'Los campos descripción, marca y modelo son obligatorios.';
    } else {
        // Insertar producto
        $productoService->save([
            'descripcion' => $descripcion,
            'imagen' => $imagen,
            'marca' => $marca,
            'modelo' => $modelo,
            'precio' => $precio,
            'stock' => $stock,
            'categoria_id' => $categoria_id
        ]);
        $mensaje = 'Producto creado correctamente.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Producto</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php require_once __DIR__ . '/header.php'; ?>

<div class="container">
    <h2>Crear Producto</h2>

    <?php if ($mensaje): ?>
        <p><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <input type="text" name="descripcion" id="descripcion" required>
        </div>

        <div class="form-group">
            <label for="imagen">URL Imagen:</label>
            <input type="text" name="imagen" id="imagen">
        </div>

        <div class="form-group">
            <label for="marca">Marca:</label>
            <input type="text" name="marca" id="marca" required>
        </div>

        <div class="form-group">
            <label for="modelo">Modelo:</label>
            <input type="text" name="modelo" id="modelo" required>
        </div>

        <div class="form-group">
            <label for="precio">Precio:</label>
            <input type="number" step="0.01" name="precio" id="precio" required>
        </div>

        <div class="form-group">
            <label for="stock">Stock:</label>
            <input type="number" name="stock" id="stock" required>
        </div>

        <div class="form-group">
            <label for="categoria_id">ID Categoría:</label>
            <input type="text" name="categoria_id" id="categoria_id">
        </div>

        <button type="submit">Crear Producto</button>
        <button type="button" onclick="location.href='index.php'">Volver</button>
    </form>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>

</body>
</html>
