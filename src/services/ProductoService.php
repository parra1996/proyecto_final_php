<?php
namespace services;

use models\Producto;
use PDO;
use Ramsey\Uuid\Uuid;

require_once __DIR__ . '/../models/Producto.php';

class ProductoService
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }
  
    public function findAllWithCategoryName()
    {
        $stmt = $this->pdo->prepare("
            SELECT p.*, c.nombre AS categoria_nombre
            FROM productos p
            LEFT JOIN categorias c ON p.categoria_id = c.id
            WHERE p.is_deleted = 0
            ORDER BY p.id ASC
        ");
        $stmt->execute();

        $productos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $producto = new Producto(
            (int)$row['id'],
            $row['uuid'],
            $row['descripcion'],
            $row['imagen'],
            $row['marca'],
            $row['modelo'],
            (float)$row['precio'],
            (int)$row['stock'],
            $row['created_at'],
            $row['updated_at'],
            isset($row['categoria_id']) ? (int)$row['categoria_id'] : null,
            $row['categoria_nombre'] ?? null,
            (bool)$row['is_deleted']
        );

         
            $producto->categoria_nombre = $row['categoria_nombre'];
            $productos[] = $producto;
        }

        return $productos;
    }

   public function findByNombreOrMarca($filter)
    {
        $filter = trim($filter);
        if ($filter === '') {
            return $this->findAllWithCategoryName();
        }

        $sql = "
            SELECT p.*, c.nombre AS categoria_nombre
            FROM productos p
            LEFT JOIN categorias c ON p.categoria_id = c.id
            WHERE p.is_deleted = 0
            AND (
                LOWER(p.marca) LIKE LOWER(:filter1)
                OR LOWER(p.modelo) LIKE LOWER(:filter2)
            )
            ORDER BY p.descripcion ASC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'filter1' => '%' . $filter . '%',
            'filter2' => '%' . $filter . '%'
        ]);

        $productos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $producto = new Producto(
                $row['id'],
                $row['uuid'],
                $row['descripcion'],
                $row['imagen'],
                $row['marca'],
                $row['modelo'],
                $row['precio'],
                $row['stock'],
                $row['categoria_id'],
                $row['created_at'],
                $row['updated_at'],
                $row['is_deleted']
            );
            $producto->categoria_nombre = $row['categoria_nombre'] ?? 'Sin categoría';
            $productos[] = $producto;
        }

        return $productos;
    }

   
    public function findById($id)
    {
        $stmt = $this->pdo->prepare("
            SELECT p.*, c.nombre AS categoria_nombre
            FROM productos p
            LEFT JOIN categorias c ON p.categoria_id = c.id
            WHERE p.id = :id
            ORDER BY p.id ASC
        ");
        $stmt->execute([':id' => $id]);

        $productos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $producto = new Producto(
            (int)$row['id'],
            $row['uuid'],
            $row['descripcion'],
            $row['imagen'],
            $row['marca'],
            $row['modelo'],
            (float)$row['precio'],
            (int)$row['stock'],
            $row['created_at'],
            $row['updated_at'],
            isset($row['categoria_id']) ? (int)$row['categoria_id'] : null,
            $row['categoria_nombre'] ?? null,
            (bool)$row['is_deleted']
        );

            $producto->categoria_nombre = $row['categoria_nombre'];
            $productos[] = $producto;
        }

        return $productos;
    
    }
  
    public function save($data)
    {
        $uuid = Uuid::uuid4()->toString();

        $stmt = $this->pdo->prepare("
            INSERT INTO productos (uuid, descripcion, imagen, marca, modelo, precio, stock,categoria_id)
            VALUES (:uuid, :descripcion, :imagen, :marca, :modelo, :precio, :stock, :categoria_id)
        ");

        return $stmt->execute([
            'uuid' => $uuid,
            'descripcion' => $data['descripcion'],
            'imagen' => $data['imagen'],
            'marca' => $data['marca'],
            'modelo' => $data['modelo'],
            'precio' => $data['precio'],
            'stock' => $data['stock'],
            'categoria_id' => $data['categoria_id']
        ]);
    }

    public function update(Producto $producto)
    {   
        $stmt = $this->pdo->prepare("
            UPDATE productos 
            SET 
                descripcion = :descripcion,
                marca = :marca,
                modelo = :modelo,
                precio = :precio,
                imagen = :imagen,
                stock = :stock,
                categoria_id = :categoria_id,
                updated_at = :updated_at
            WHERE id = :id
        ");

        $stmt->execute([
            ':descripcion' => $producto->getDescripcion(),
            ':marca' => $producto->getMarca(),
            ':modelo' => $producto->getModelo(),
            ':precio' => $producto->getPrecio(),
            ':imagen' => $producto->getImagen(),
            ':categoria_id' => $producto->getCategoriaId(),
            ':stock' => $producto->getStock(),
            ':updated_at' => $producto->getUpdatedAt(),
            ':id' => $producto->getId()
        ]);
    }

      public function deleteById(string $id)
        {
            $stmt = $this->pdo->prepare("
               DELETE from productos
                WHERE id = :id
            ");

            $stmt->execute([':id' => $id]);
        }

}
