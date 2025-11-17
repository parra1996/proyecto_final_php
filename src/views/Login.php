<?php
session_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../services/UserServices.php';

use services\UsersService;

$userService = new UsersService($pdo);

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = 'Por favor, completa todos los campos.';
    } else {
        $user = $userService->authenticate($username, $password);

        if ($user) {
            $_SESSION['user'] = $user;
            header('Location: index.php');
            exit;
        } else {
            $error = 'Usuario o contraseña incorrectos.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - Gestión de Productos</title>
    <style>
        body {
            background: #f8f9fa;
        }
        .login-container {
            max-width: 420px;
            margin: 80px auto;
            padding: 30px;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .login-container img {
            display: block;
            margin: 0 auto 20px;
            width: 80px;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/Header.php'; ?>

    <div class="login-container">
        <!-- <img src="../assets/logo.png" alt="logo">
        <h4 class="text-center mb-4">Acceso al sistema</h4> -->

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger text-center">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Usuario</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="username" 
                    name="username" 
                    placeholder="Introduce tu usuario" 
                    required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input 
                    type="password" 
                    class="form-control" 
                    id="password" 
                    name="password" 
                    placeholder="Introduce tu contraseña" 
                    required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Iniciar sesión</button>
            </div>
        </form>
    </div>

    <?php include __DIR__ . '/Footer.php'; ?>
</body>
</html>