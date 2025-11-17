<?php
namespace services;

use Exception;
use PDO;
use services\SessionService;

require_once __DIR__ . '/../models/Categoria.php';

class CategoriaService
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    
    public function getAllCategories(): array {
        $sql = "select id,nombre from categorias";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}