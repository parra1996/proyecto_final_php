<?php
namespace Models;

class User {
    private int $id;
    private string $username;
    private string $password;
    private string $nombre;
    private string $apellido;
    private string $email;
    private $isDeleted;
    private string $roles;

    public function __construct(?int $id, string $username, string $password, string $roles) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->roles = $roles;
    }

    public function getId(): ?int { return $this->id; }
    public function getUsername(): string { return $this->username; }
    public function getPassword(): string { return $this->password; }
    public function getRoles(): string { return $this->roles; }

    public function setUsername(string $username): void { $this->username = $username; }
    public function setPassword(string $password): void { $this->password = $password; }
    public function setRoles(string $roles): void { $this->roles = $roles; }
}
