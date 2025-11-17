<?php
require_once __DIR__ . '/Header.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../services/ProductoService.php';

use services\ProductoService;
use services\SessionService;

$productoService = new ProductoService($pdo);
$session_service = SessionService::getInstance();

if (!$session_service->isAdmin()) {
    echo "<script>alert('No tienes permisos para eliminar productos'); window.location='../../index.php';</script>";
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    echo "<script>alert('ID inválido'); window.location='../../index.php';</script>";
    exit;
}

$producto = $productoService->findById($id);
if (!$producto) {
    echo "<script>alert('Producto no encontrado'); window.location='../../index.php';</script>";
    exit;
}else {
   $productoService->deleteById($id);
}

echo "<script>alert('Producto eliminado correctamente'); window.location='../../index.php';</script>";
exit;
?>
