<?php
namespace App\Repositories;
use App\Models\Database;
use PDO;

class ReviewRepository{
    private PDO $pdo;
    public function __construct(){
        $this->pdo = Database::getInstance();
    }
    public function getEventIdByArticleId(int $articleId): ?int{
        $stmt = $this->pdo->prepare( "SELECT event_id FROM articles WHERE id = :id");
        $stmt->execute(['id' => $articleId]);
        $res = $stmt->fetch();
        return $res['event_id'] ?? null;
    }

    public function getByEvent(int $eventId): array{
        $stmt = $this->pdo->prepare("SELECT r.*, u.first_name, u.last_name FROM reviews r
             JOIN users u ON u.id = r.student_id
             WHERE r.event_id = :e
             ORDER BY r.id DESC");
        $stmt->execute(['e' => $eventId]);
        return $stmt->fetchAll();
    }

    public function getAverageRating(int $eventId): float{
        $stmt = $this->pdo->prepare("SELECT AVG(rating) as avg_rating FROM reviews WHERE event_id = :e");
        $stmt->execute(['e' => $eventId]);
        $res = $stmt->fetch();
        return floatval($res['avg_rating'] ?? 0);
    }

    public function create(array $data): void{
        $sql = "INSERT INTO reviews (rating, comment, student_id, event_id) VALUES (:rating, :comment, :student_id, :event_id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
    }

    public function delete(int $id): void{
        $stmt = $this->pdo->prepare("DELETE FROM reviews WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
