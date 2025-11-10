<?php
require_once __DIR__ . '/Header.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../services/ProductoService.php';

use services\ProductoService;

// Inicializamos el servicio
$productoService = new ProductoService($pdo);

// 1️⃣ Recepción y validación del parámetro id
$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    echo "<script>alert('ID inválido'); window.location='index.php';</script>";
    exit;
}

// 2️⃣ Buscar el producto
$producto = $productoService->findById($id);
$producto = $producto[0]; 

if (!$producto) {
    echo "<script>alert('Producto no encontrado'); window.location='index.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Producto</title>
    <link rel="stylesheet" href="style.css">
    <!-- Opcional: usar Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php require_once __DIR__ . '/header.php'; ?>

    <div class="container mt-4">
        <h2>Detalles del Producto</h2>
        <div class="card" style="max-width: 600px;">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($producto->getMarca() . ' ' . $producto->getModelo()) ?></h5>
                <p class="card-text"><strong>ID:</strong> <?= htmlspecialchars($producto->getId()) ?></p>
                <p class="card-text"><strong>Descripción:</strong> <?= htmlspecialchars($producto->getDescripcion()) ?></p>
                <p class="card-text"><strong>Precio:</strong> $<?= number_format($producto->getPrecio(), 2) ?></p>
                <p class="card-text"><strong>Stock:</strong> <?= $producto->getStock() ?></p>
                <p class="card-text"><strong>Categoría:</strong> <?= htmlspecialchars($producto->getCategoriaNombre()) ?></p>
                <?php if ($producto->getImagen()): ?>
                    <p><img src="<?= htmlspecialchars($producto->getImagen()) ?>" alt="<?= htmlspecialchars($producto->getDescripcion()) ?>" style="max-width:100%; height:auto;"></p>
                <?php else: ?>
                    <p>No hay imagen disponible</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="mt-3">
            <button class="btn btn-secondary" onclick="location.href='index.php'">Volver al listado</button>
        </div>
    </div>

    <?php require_once __DIR__ . '/footer.php'; ?>
</body>
</html>
