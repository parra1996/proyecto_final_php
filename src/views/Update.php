<?php 
require_once __DIR__ . '/Header.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../services/ProductoService.php';
require_once __DIR__ . '/../services/SessionService.php';
require_once __DIR__ . '/../services/CategoriaService.php';

use services\ProductoService;
use services\SessionService;
use services\CategoriaService;

$categoriaService = new CategoriaService($pdo);
$session_service = SessionService::getInstance();
$categorias = $categoriaService->getAllCategories();
if (!$session_service->isAdmin()) {
    echo "<script>alert('No tienes permisos'); window.location='../../index.php';</script>";
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID inválido');
}
$id = (int)$_GET['id'];

$productoService = new ProductoService($pdo);
$producto = $productoService->findById($id);

if (!$producto || empty($producto)) {
    die('Producto no encontrado');
}

$producto = $producto[0]; 
$upload_dir = __DIR__ . '/../uploads/';        
$upload_url = 'uploads/';                   

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $descripcion = trim($_POST['descripcion'] ?? '');
    $marca  = trim($_POST['marca'] ?? '');
    $modelo = trim($_POST['modelo'] ?? '');
    $precio = (float)($_POST['precio'] ?? 0);
    $stock  = (int)($_POST['stock'] ?? 0);
    $categoria_id  = ($_POST['categoria_id'] );
    print_r($categoria_id . "juuuuuuu");

    if (empty($descripcion) || empty($marca) || empty($modelo)) {
        $errores[] = 'Descripción, marca y modelo son obligatorios.';
    }
    if ($precio <= 0) $errores[] = 'El precio debe ser mayor que 0.';
    if ($stock < 0) $errores[] = 'El stock no puede ser negativo.';

    $imagen_actual = $producto->getImagen(); 
    $nueva_imagen = $imagen_actual; 

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $file_tmp  = $_FILES['imagen']['tmp_name'];
        $file_name = $_FILES['imagen']['name'];
        $file_size = $_FILES['imagen']['size'];
        $file_type = $_FILES['imagen']['type'];

        $allowed = [ 'image/jpeg','image/jpg', 'image/png'];
        if (!in_array($file_type, $allowed)) {
            $errores[] = 'Solo se permiten JPG,JPEG y PNG.';
        }

        if (empty($errores)) {
            $ext = pathinfo($file_name, PATHINFO_EXTENSION);
            $safe_name = uniqid('img_') . '.' . strtolower($ext);
            $target_file = $upload_dir . $safe_name;

            if (move_uploaded_file($file_tmp, $target_file)) {
                if ($imagen_actual && file_exists($upload_dir . basename($imagen_actual))) {
                    unlink($upload_dir . basename($imagen_actual));
                }
                $nueva_imagen = $upload_url . $safe_name;
            } else {
                $errores[] = 'Error al subir la imagen.';
            }
        }
    }

    if (empty($errores)) {
        try {
            $producto->setDescripcion($descripcion);
            $producto->setMarca($marca);
            $producto->setModelo($modelo);
            $producto->setPrecio($precio);
            $producto->setStock($stock);
            $producto->setCategoriaId($categoria_id);
            $producto->setImagen($nueva_imagen); 
            $producto->setUpdatedAt(date('Y-m-d H:i:s'));

            $productoService->update($producto);

            header("Location: ../../index.php");
            exit;
        } catch (Exception $e) {
            $errores[] = 'Error en base de datos: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Producto</title>
    <link rel="stylesheet" href="../assets/style.css">
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
</head>
<body>

<div class="container">
    <h2>Actualizar Producto</h2>

    <?php if (!empty($errores)): ?>
        <div class="error">
            <?php echo implode('<br>', $errores); ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Descripción</label>
            <input type="text" name="descripcion" value="<?= htmlspecialchars($producto->getDescripcion()) ?>" required>
        </div>

        <div class="form-group">
            <label>Marca</label>
            <input type="text" name="marca" value="<?= htmlspecialchars($producto->getMarca()) ?>" required>
        </div>

        <div class="form-group">
            <label>Modelo</label>
            <input type="text" name="modelo" value="<?= htmlspecialchars($producto->getModelo()) ?>" required>
        </div>

        <div class="form-group">
            <label>Precio (€)</label>
            <input type="number" step="0.01" name="precio" value="<?= number_format($producto->getPrecio(), 2) ?>" required>
        </div>

        <div class="form-group">
            <label>Stock</label>
            <input type="number" name="stock" value="<?= $producto->getStock() ?>" required>
        </div>

        <div class="form-group">
            <label for="categoria_id">Categoría:</label>
            <select name="categoria_id" id="categoria_id" required>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= (isset($categoria_id) && $categoria_id == $cat['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- no se si dejarlo -->
        <div class="form-group"> 
            <label>Imagen actual</label>
            <?php if ($producto->getImagen()): ?>
                <img src="../<?= htmlspecialchars($producto->getImagen()) ?>" class="preview-img" alt="Actual">
                <p><small><?= htmlspecialchars($producto->getImagen()) ?></small></p>
            <?php else: ?>
                <p><em>No hay imagen</em></p>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Nueva imagen</label>
            <input type="file" name="imagen" accept="image/jpg,image/png">
            <small>Formatos: JPG, PNG</small>
        </div>

        <button type="submit">Actualizar Producto</button>
    </form>
    <br>
</div>
</body>
</html>