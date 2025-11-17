<?php
require_once __DIR__ . '/Header.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../services/ProductoService.php';

use services\ProductoService;

$productoService = new ProductoService($pdo);

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    echo "<script>alert('ID inválido'); window.location='index.php';</script>";
    exit;
}

$producto = $productoService->findById($id);
$producto = $producto[0]; 

if (!$producto) {
    echo "<script>alert('Producto no encontrado'); window.location='../../index.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Producto</title>
</head>
<body>
    <?php require_once __DIR__ . '/header.php'; ?>

    <div class="container mt-4">
        <h2>Detalles del Producto</h2>
        <div class="card" style="max-width: 600px;">
            <div class="card-body">
                <p class="card-text"><strong>Marca:</strong><?= htmlspecialchars($producto->getMarca() ) ?></p>
                <p class="card-text"><strong>Modelo:</strong><?= htmlspecialchars($producto->getModelo() ) ?></p>
                <!-- <p class="card-text"><strong>ID:</strong> <?= htmlspecialchars($producto->getId()) ?></p> -->
                <p class="card-text"><strong>Descripción:</strong> <?= htmlspecialchars($producto->getDescripcion()) ?></p>
                <p class="card-text"><strong>Precio:</strong> $<?= number_format($producto->getPrecio(), 2) ?></p>
                <p class="card-text"><strong>Stock:</strong> <?= $producto->getStock() ?></p>
                <p class="card-text"><strong>Categoría:</strong> <?= htmlspecialchars($producto->getCategoriaNombre()) ?></p>
                <?php if ($producto->getImagen()): ?>
                    <img 
                        src="<?= htmlspecialchars('/proyecto_final/src/' . $producto->getImagen()) ?>" 
                        style="width:100px; height:auto; object-fit: cover; border-radius: 5px;" 
                        alt="<?= htmlspecialchars($producto->getDescripcion()) ?>"
                    >
                <?php else: ?>
                    <p>No hay imagen disponible</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php require_once __DIR__ . '/footer.php'; ?>
</body>
</html>
