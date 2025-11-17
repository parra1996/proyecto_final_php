<?php
require_once __DIR__ . '/Header.php'; 
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../services/ProductoService.php';

use services\ProductoService;

$productoService = new ProductoService($pdo);
$productos = $productoService->findAllWithCategoryName();

$termino = trim($_GET['filter'] ?? '');

if ($termino !== '') {
    $productos = $productoService->findByNombreOrMarca($termino);
} else {
    $productos = $productoService->findAllWithCategoryName();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Productos</title>
    
    <!-- <link rel="stylesheet" href="../../style.css"> -->
</head>
<body>
    <div class="container">
         <div class="acciones">
            <h2>Listado de Productos</h2>
        </div>

        <div class="d-flex justify-content-between mb-3">
            <form method="GET" action="" class="d-flex">
            <input 
                type="text" 
                name="filter" 
                placeholder="Buscar por nombre o marca..." 
                value="<?= htmlspecialchars($_GET['filter'] ?? '') ?>" 
                class="form-control me-2"
                autofocus
            >
            <button type="submit" class="btn btn-primary">Buscar</button>
            </form>
            <button onclick="location.href='/proyecto_final/src/views/create.php'" class="btn btn-success">
                Crear
            </button>
        </div>
        <table class="table table-striped table-hover table-bordered align-middle">
            <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Descripción</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Imagen</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Categoría</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($productos as $p): ?>
            <tr>
            <td><?= htmlspecialchars($p->getId()) ?></td>
            <td><?= htmlspecialchars($p->getDescripcion()) ?></td>
            <td><?= htmlspecialchars($p->getMarca()) ?></td>
            <td><?= htmlspecialchars($p->getModelo()) ?></td>
            <td>
                <img 
                    src="<?= htmlspecialchars( '/proyecto_final/src/' . $p->getImagen()) ?>" 
                    style="width:100px; height:auto; object-fit: cover; border-radius: 5px;" 
                    alt="<?= htmlspecialchars($p->getDescripcion()) ?>"
                >
            </td>
            <td><?= number_format($p->getPrecio(), 2) ?> €</td>
            <td><?= $p->getStock() ?></td>
            <td><?= htmlspecialchars($p->getCategoriaNombre()) ?></td>
            <td>
                <div class="d-flex gap-2 flex-wrap" role="group">
                    <button onclick="location.href='src/views/Details.php?id=<?= $p->getId() ?>'" class="btn btn-info btn-sm">Detalles</button>
                    <button onclick="location.href='src/views/Update.php?id=<?= $p->getId() ?>'" class="btn btn-warning btn-sm">Editar</button>
                    <button onclick="confirmarEliminacion(<?= $p->getId() ?>)" class="btn btn-danger btn-sm">Eliminar</button>
                </div>
            </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <script>
        function confirmarEliminacion(id) {
            if (confirm("¿Seguro que deseas eliminar este producto?")) {
                location.href = 'src/views/delete.php?id=' + id;
            }
        }
    </script>
</body>
<?php require_once __DIR__ . '/Footer.php';  ?>
</html>