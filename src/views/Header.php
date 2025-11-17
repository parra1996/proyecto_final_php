<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../services/SessionService.php';
use services\SessionService;

$session = SessionService::getInstance();
$user = $session->getUser();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto Final</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<header class="bg-light py-3 mb-4 shadow-sm">
    <div class="container d-flex justify-content-between align-items-center">
        <h1 class="h4 mb-0">Mi Proyecto</h1>
        <nav>
            <ul class="nav">
                <li class="nav-item"><a class="nav-link" href="/proyecto_final/">Inicio</a></li>
                <?php if (!$session->isLoggedIn()): ?>
                    <li class="nav-item"><a class="nav-link" href="/proyecto_final/src/views/Login.php">Login</a></li>
                    <!-- <li class="nav-item"><a class="nav-link" href="/proyecto_final/src/views/Register.php">Register</a></li> -->
                <?php else: ?>
                    <li class="nav-item ms-auto">
                        <span class="nav-link fw-bold"> <?= htmlspecialchars($user['username'] ?? 'Invitado') ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="/proyecto_final/src/views/Logout.php">Cerrar sesión</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>
<main class="container">
