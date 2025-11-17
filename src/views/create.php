<?php
require_once __DIR__ . '/Header.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../services/ProductoService.php';
require_once __DIR__ . '/../services/CategoriaService.php';

use services\ProductoService;
use services\CategoriaService;

$productoService = new ProductoService($pdo);
$categoriaService = new CategoriaService($pdo);

$categorias = $categoriaService->getAllCategories();
$mensaje = '';
$errores = [];

$upload_dir = __DIR__ . '/../uploads/';
$upload_url = 'uploads/';

if (!is_dir($upload_dir)) {
    if (!mkdir($upload_dir, 0755, true)) {
        die("Error: No se pudo crear la carpeta uploads.");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $descripcion = trim($_POST['descripcion'] ?? '');
    $marca = trim($_POST['marca'] ?? '');
    $modelo = trim($_POST['modelo'] ?? '');
    $precio = (float)($_POST['precio'] ?? 0);
    $stock = (int)($_POST['stock'] ?? 0);
    $categoria_id = $_POST['categoria_id'] ?? null;

    if (empty($descripcion) || empty($marca) || empty($modelo)) {
        $errores[] = 'Los campos descripción, marca y modelo son obligatorios.';
    }

    if ($precio <= 0) {
        $errores[] = 'El precio debe ser mayor que 0.';
    }

    if ($stock < 0) {
        $errores[] = 'El stock no puede ser negativo.';
    }

    if (empty($categoria_id)) {
        $errores[] = 'Debe seleccionar una categoría.';
    }

    $imagen = null;

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['imagen']['tmp_name'];
        $file_name = $_FILES['imagen']['name'];
        $file_size = $_FILES['imagen']['size'];
        $file_type = $_FILES['imagen']['type'];

        $allowed = ['image/jpeg', 'image/png'];
        if (!in_array($file_type, $allowed)) {
            $errores[] = 'Solo se permiten imágenes (JPEG, PNG).';
        }

        if (empty($errores)) {
            $ext = pathinfo($file_name, PATHINFO_EXTENSION);
            // $modelo_sanitizado = preg_replace('/[^a-zA-Z0-9_-]/', '_', $modelo);
            $safe_name = $modelo . '.' . strtolower($ext);
            $target_file = $upload_dir . $safe_name;

            if (move_uploaded_file($file_tmp, $target_file)) {
                $imagen = $upload_url . $safe_name;
            } else {
                $errores[] = 'Error al mover la imagen al servidor.';
            }
        }
    }
    if (empty($errores)) {
        try {
            $productoService->save([
                'descripcion' => $descripcion,
                'imagen' => $imagen, 
                'marca' => $marca,
                'modelo' => $modelo,
                'precio' => $precio,
                'stock' => $stock,
                'categoria_id' => $categoria_id
            ]);

            header("Location: ../../index.php");
            exit;
        } catch (Exception $e) {
            $errores[] = 'Error al guardar el producto: ' . $e->getMessage();
        }
    }

    $mensaje = implode('<br>', $errores);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Producto</title>
    <style>
        .error { color: red; margin: 10px 0; font-size: 14px; }
        .success { color: green; }
        .form-group { margin: 15px 0; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="number"], input[type="file"] {
            width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;
        }
        button {
            background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;
        }
        button:hover { background: #0056b3; }
        .preview-img {
            max-width: 200px; max-height: 150px; margin-top: 10px; border-radius: 4px; box-shadow: 0 1px 5px rgba(0,0,0,0.2);
        }
    </style>
    </style>
</head>
<body>

<?php require_once __DIR__ . '/header.php'; ?>

<div class="container">
    <h2>Crear Producto</h2>

    <?php if ($mensaje): ?>
        <p class="<?= strpos($mensaje, 'correctamente') !== false ? 'success' : 'error' ?>">
            <?= $mensaje ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <input type="text" name="descripcion" id="descripcion" value="<?= htmlspecialchars($descripcion ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="imagen">Imagen (opcional):</label>
            <input type="file" name="imagen" id="imagen" accept="image/*">
            <small>Formatos: JPG, PNG</small>
        </div>

        <div class="form-group">
            <label for="marca">Marca:</label>
            <input type="text" name="marca" id="marca" value="<?= htmlspecialchars($marca ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="modelo">Modelo:</label>
            <input type="text" name="modelo" id="modelo" value="<?= htmlspecialchars($modelo ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="precio">Precio:</label>
            <input type="number" step="0.01" name="precio" id="precio" value="<?= $precio ?? '' ?>" required>
        </div>

        <div class="form-group">
            <label for="stock">Stock:</label>
            <input type="number" name="stock" id="stock" value="<?= $stock ?? '' ?>" required>
        </div>

        <div class="form-group">
            <label for="categoria_id">Categoría:</label>
            <select name="categoria_id" id="categoria_id" required>
                <option value=""> Seleccione una categoría </option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= (isset($categoria_id) && $categoria_id == $cat['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit">Crear Producto</button>
    </form>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
</body>
</html>