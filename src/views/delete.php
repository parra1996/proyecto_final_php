<?php
require_once __DIR__ . '/Header.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../services/ProductoService.php';

use services\ProductoService;

// Inicializamos el servicio de productos
$productoService = new ProductoService($pdo);

//  Control de permisos (solo admin)
// session_start();
// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//     echo "<script>alert('No tienes permisos para eliminar productos'); window.location='index.php';</script>";
//     exit;
// }

//  Recepción del parámetro id
$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    echo "<script>alert('ID inválido'); window.location='../../index.php';</script>";
    exit;
}

//  Obtener el producto antes de borrarlo (para eliminar la imagen)
$producto = $productoService->findById($id);
if (!$producto) {
    echo "<script>alert('Producto no encontrado'); window.location='index.php';</script>";
    exit;
}

// 4️⃣ Eliminar la imagen si existe
// if ($producto->getImagen()) {
//     $rutaImagen = __DIR__ . '/uploads/' . $producto->getImagen();
//     if (file_exists($rutaImagen)) {
//         unlink($rutaImagen); // Borra la imagen física
//     }
// }

// 5️⃣ Eliminar el producto de la base de datos
$productoService->deleteById($id);

// 6️⃣ Notificación y redirección
echo "<script>alert('Producto eliminado correctamente'); window.location='../../index.php';</script>";
exit;
?>
