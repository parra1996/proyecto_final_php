<?php
namespace services;

class SessionService
{
    private static ?SessionService $instance = null;
    private int $expireAfterSeconds = 3600; 

  
    private function __construct()
    {
        if(session_status() === PHP_SESSION_NONE) {
            session_start(); 
        }

        $this->checkSessionExpired();
        if (!isset($_SESSION['loggedIn'])) {
            $_SESSION['loggedIn'] = false;
        }
        if (!isset($_SESSION['user'])) {
            $_SESSION['user'] = null;
        }
    }


    public static function getInstance(): SessionService
    {
        if (self::$instance === null) {
            self::$instance = new SessionService();
        }
        return self::$instance;
    }

    public function login(array $user): void
    {
        $_SESSION['user'] = $user;
        $_SESSION['loggedIn'] = true;
        $_SESSION['last_activity'] = time();
    }
 
    public function logout(): void
    {
        $this->destroySession();
    }
   
    public function isLoggedIn(): bool
    {
        return !empty($_SESSION['loggedIn']);
    }
  
    public function getUser(): ?array
    {
        return $_SESSION['user'] ?? null;
    }
  
    // public function isAdmin(): bool
    // {
    //     return $this->isLoggedIn() && ($_SESSION['user']['rol'] ?? '') === 'admin';
    // }

    private function checkSessionExpired(): void
    {
        $now = time();
        if (isset($_SESSION['last_activity'])) {
            $elapsed = $now - $_SESSION['last_activity'];
            if ($elapsed > $this->expireAfterSeconds) {
                $this->destroySession();
            }
        }
        $_SESSION['last_activity'] = $now; 
    }

    // private function destroySession(): void
    // {
    //     $_SESSION = [];
    //     if (ini_get("session.use_cookies")) {
    //         $params = session_get_cookie_params();
    //         setcookie(session_name(), '', time() - 42000,
    //             $params["path"], $params["domain"],
    //             $params["secure"], $params["httponly"]
    //         );
    //     }
    //     session_destroy();

    //     // setcookie('user_id', '', time() - 3600, '/', '', false, true);
    //     // setcookie('username', '', time() - 3600, '/', '', false, true);
    //     // setcookie('rol', '', time() - 3600, '/', '', false, true);
    // }
}
