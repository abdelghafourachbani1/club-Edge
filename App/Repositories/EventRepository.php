<?php
namespace App\Repositories;
use App\Models\Database;
use PDO;

class EventRepository{
    private PDO $pdo;
    public function __construct(){
        $this->pdo = Database::getInstance();
    }

    public function find(int $id): ?array{
        $stmt = $this->pdo->prepare(" SELECT * FROM events WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $event = $stmt->fetch();
        return $event ?: null;
    }
}
