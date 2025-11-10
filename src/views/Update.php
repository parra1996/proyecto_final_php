<?php 
require_once __DIR__ . '/Header.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../services/ProductoService.php';

use services\ProductoService;

if (!isset($_GET['id'])) {
    die('ID de producto no especificado');
}

$id = $_GET['id'];
$productoService = new ProductoService($pdo);
$producto = $productoService->findById($id);
$producto = $producto[0]; 

if (!$producto) {
    die('Producto no encontrado');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //  $imagen = $_FILES['imagen'] ?? null;

    $producto->setDescripcion($_POST['descripcion']);
    $producto->setMarca($_POST['marca']);
    $producto->setModelo($_POST['modelo']);
    $producto->setPrecio((float)$_POST['precio']);
    $producto->setImagen($_POST['imagen']);
    $producto->setStock((int)$_POST['stock']);
    $producto->setUpdatedAt(date('Y-m-d H:i:s'));

    $productoService->update($producto);

    header("Location: ../../index.php");

    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar producto</title>
    <link rel="stylesheet" href="../assets/style.css"> <!-- opcional -->
</head>
<body>
    <h2>Actualizar producto</h2>

    <form method="POST" enctype="multipart/form-data">
        <label>Descripción</label><br>
        <input type="text" name="descripcion" value="<?= htmlspecialchars($producto->getDescripcion()) ?>" required><br><br>

        <label>Marca</label><br>
        <input type="text" name="marca" value="<?= htmlspecialchars($producto->getMarca()) ?>" required><br><br>

        <label>Modelo</label><br>
        <input type="text" name="modelo" value="<?= htmlspecialchars($producto->getModelo()) ?>" required><br><br>

        <label>Precio (€)</label><br>
        <input type="number" step="0.01" name="precio" value="<?= htmlspecialchars($producto->getPrecio()) ?>" required><br><br>

        <label>Stock</label><br>
        <input type="number" name="stock" value="<?= htmlspecialchars($producto->getStock()) ?>" required><br><br>

        <label>Imagen</label><br>
        <input type="text" name="imagen" value="<?= htmlspecialchars($producto->getImagen()) ?>" ><br><br>
        <!-- <label>Imagen (opcional)</label><br> -->
        <!-- <input type="file" name="imagen" ><br><br> -->

        <button type="submit">Actualizar</button>
    </form>

    <br>
    <a href="index.php">⬅ Volver al listado</a>
</body>
</html>
