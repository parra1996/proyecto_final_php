<?php
require_once __DIR__ . '/Header.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../services/UserServices.php';

use services\UsersService;

$userService = new UsersService($pdo);
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($nombre === '' || $apellidos === '' || $username === '' || $email === '' || $password === '') {
        $mensaje = 'Todos los campos son obligatorios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensaje = 'El correo electrónico no es válido.';
    } else {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $userService->createUser([
                'nombre' => $nombre,
                'apellidos' => $apellidos,
                'username' => $username,
                'email' => $email,
                'password' => $passwordHash,
            ]);
            $mensaje = 'Usuario registrado correctamente.';
            header('Location: login.php');
            exit;
        } catch (Exception $e) {
            $mensaje = 'Error al registrar el usuario: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php require_once __DIR__ . '/header.php'; ?>

<div class="container">
    <h2>Registrar nuevo usuario</h2>

    <?php if ($mensaje): ?>
        <p><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" required>
        </div>

        <div class="form-group">
            <label for="apellidos">Apellidos:</label>
            <input type="text" name="apellidos" id="apellidos" required>
        </div>

        <div class="form-group">
            <label for="username">Usuario:</label>
            <input type="text" name="username" id="username" required>
        </div>

        <div class="form-group">
            <label for="email">Correo electrónico:</label>
            <input type="email" name="email" id="email" required>
        </div>

        <div class="form-group">
            <label for="password">Contraseña:</label>
            <input type="password" name="password" id="password" required>
        </div>

        <button type="submit">Registrar</button>
        <button type="button" onclick="location.href='login.php'">Ir a Login</button>
    </form>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>

</body>
</html>