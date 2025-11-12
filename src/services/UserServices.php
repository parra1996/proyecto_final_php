<?php
namespace services;

use Exception;
use models\User;
use PDO;
use services\SessionService;

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/SessionService.php';

class UsersService
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    
  public function authenticate(string $username, string $password): ?array
    {
    try {
        $sqlUser = "SELECT * FROM usuarios WHERE username = :username LIMIT 1";
        $stmtUser = $this->db->prepare($sqlUser);
        $stmtUser->bindParam(':username', $username, PDO::PARAM_STR);
        $stmtUser->execute();
        $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['password'])) {
            return null; 
        }

        $sqlRoles = "SELECT roles FROM user_roles WHERE user_id = :user_id";
        $stmtRoles = $this->db->prepare($sqlRoles);
        $stmtRoles->bindParam(':user_id', $user['id'], PDO::PARAM_INT);
        $stmtRoles->execute();
        $roles = $stmtRoles->fetchAll(PDO::FETCH_COLUMN); 

        $userData = [
            'id' => $user['id'],
            'username' => $user['username'],
            'roles' => $roles 
        ];

        $session = SessionService::getInstance();
        $session->login($userData);

        setcookie('user_id', $userData['id'], 0, '/');
        setcookie('username', $userData['username'], 0, '/');
        setcookie('roles', json_encode($userData['roles']), 0, '/');

        return $userData;

    } catch (\Exception $e) {
        error_log("Error al autenticar usuario: " . $e->getMessage());
        return null;
    }
    }

    public function createUser(array $user): bool {
        $sql = "INSERT INTO usuarios (nombre, apellidos, username, email, password, is_deleted, created_at, updated_at)
                VALUES (:nombre, :apellidos, :username, :email, :password, 0, NOW(), NOW())";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nombre' => $user['nombre'],
            ':apellidos' => $user['apellidos'],
            ':username' => $user['username'],
            ':email' => $user['email'],
            ':password' => $user['password']
        ]);
    }
}