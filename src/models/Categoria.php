<?php
namespace Models;

use Ramsey\Uuid\Uuid;

class Categoria {
    private $id;
    private $uuid; 
    private $nombre;
    private $isDeleted;

    public function __construct(
        ?int $id = null,
        ?string $nombre = null,
        ?string $uuid = null,
        ?string $createdAt = null,
        ?string $updatedAt = null,
        bool $isDeleted = false
    ) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->uuid = $uuid ?? Uuid::uuid4()->toString(); 
        $this->createdAt = $createdAt ?? date("Y-m-d H:i:s");
        $this->updatedAt = $updatedAt ?? date("Y-m-d H:i:s");
        $this->isDeleted = $isDeleted;
    }

    public function getUuid() { return $this->uuid; }
    public function setUuid($uuid) { $this->uuid = $uuid; }
}
