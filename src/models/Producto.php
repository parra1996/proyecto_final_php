<?php
namespace models;

class Producto {
    public $id = null;
    public $uuid = null;
    public $descripcion = null;
    public $imagen = null;
    public $marca = null;
    public $modelo = null;
    public $precio = 0.0;
    public $stock = 0;
    public $createdAt = null;
    public $updatedAt = null;
    public $categoriaId = null;
    public $categoriaNombre = null;
    public $isDeleted = false;

    public function __construct(
        ?string $id = null,
        ?string $uuid= null,
        ?string $descripcion = null,
        ?string $imagen = null,
        ?string $marca = null,
        ?string $modelo = null,
        float $precio = 0.0,
        int $stock = 0,
        ?string $createdAt = null,
        ?string $updatedAt = null,
        ?string $categoriaId = null,
        ?string $categoriaNombre = null,
        bool $isDeleted = false
    ) {
        $this->id = $id;
        $this->uuid = $uuid ?? uniqid(); 
        $this->descripcion = $descripcion;
        $this->imagen = $imagen ?? "default.png"; 
        $this->marca = $marca;
        $this->modelo = $modelo;
        $this->precio = $precio;
        $this->stock = $stock;
        $this->createdAt = $createdAt ?? date("Y-m-d H:i:s");
        $this->updatedAt = $updatedAt ?? date("Y-m-d H:i:s");
        $this->categoriaId = $categoriaId;
        $this->categoriaNombre = $categoriaNombre;
        $this->isDeleted = $isDeleted;
    }

    //getters
    public function getId() { return $this->id; }
    public function getUuid() { return $this->uuid; }
    public function getDescripcion() { return $this->descripcion; }
    public function getImagen() { return $this->imagen; }
    public function getMarca() { return $this->marca; }
    public function getModelo() { return $this->modelo; }
    public function getPrecio() { return $this->precio; }
    public function getStock() { return $this->stock; }
    public function getCreatedAt() { return $this->createdAt; }
    public function getUpdatedAt() { return $this->updatedAt; }
    public function getCategoriaId() { return $this->categoriaId; }
    public function getCategoriaNombre() { return $this->categoriaNombre; }
    public function getIsDeleted() { return $this->isDeleted; }

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setUuid($uuid) { $this->uuid = $uuid; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }
    public function setImagen($imagen) { $this->imagen = $imagen; }
    public function setMarca($marca) { $this->marca = $marca; }
    public function setModelo($modelo) { $this->modelo = $modelo; }
    public function setPrecio($precio) { $this->precio = $precio; }
    public function setStock($stock) { $this->stock = $stock; }
    public function setCreatedAt($createdAt) { $this->createdAt = $createdAt; }
    public function setUpdatedAt($updatedAt) { $this->updatedAt = $updatedAt; }
    public function setCategoriaId($categoriaId) { $this->categoriaId = $categoriaId; }
    public function setCategoriaNombre($categoriaNombre) { $this->categoriaNombre = $categoriaNombre; }
    public function setIsDeleted($isDeleted) { $this->isDeleted = $isDeleted; }

}


?>