<?php
namespace App\Repositories;
use App\Models\Database;
use PDO;

class ParticipationRepository{
    private PDO $pdo;
    public function __construct(){
        $this->pdo = Database::getInstance();
    }
    public function hasParticipated(int $studentId, int $eventId): bool{
        $stmt = $this->pdo->prepare(
            "SELECT * FROM participations WHERE student_id = :s AND event_id = :e AND has_participated = TRUE");
        $stmt->execute(['s' => $studentId, 'e' => $eventId]);
        return (bool) $stmt->fetch();
    }
}
