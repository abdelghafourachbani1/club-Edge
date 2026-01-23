<?php

namespace App\Repositories;

class StudentRepository {
    // private  PDO $pdo;
    // public function __construct(PDO $pdo)
    // {
    //     $this->pdo = $pdo;
    // }

    // test
    public function allStudents(): array
    {
        $sql = "SELECT * FROM users";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // statistiques
    public function countEventsParticipated($studentId): array
    {
        $sql = "SELECT COUNT(*) FROM participations WHERE has_participated = true AND student_id = :student_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':student_id' => $studentId,
        ]);
        return $stmt->fetchColumn();
    }

    public function countEventsNotParticipated($studentId): array
    {
        $sql = "SELECT COUNT(*) FROM participations WHERE has_participated = false AND student_id = :student_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':student_id' => $studentId,
        ]);
        return $stmt->fetchColumn();
    }

    // inscire et desinscire des events
    public function sinscireEvent(int $studentId, int $eventId): bool
    {
        $sql = "INSERT INTO participations (student_id, event_id, has_participated) VALUES (:student_id, :event_id, :has_participated)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':student_id' => $studentId,
            ':event_id' => $eventId,
            ':has_participated' => true,
        ]);
    }

    public function desinscireEvent(int $studentId, int $eventId): bool
    {
        $sql = "UPDATE participations SET has_participated = false WHERE student_id = :student_id AND event_id = :event_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':student_id' => $studentId,
            ':event_id' => $eventId,
        ]);
    }

    public function isParticipated(int $studentId, int $eventId): bool
    {
        $sql = "SELECT 1 FROM participations WHERE student_id = :student_id AND event_id = :event_id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':student_id' => $studentId,
            ':event_id' => $eventId,
        ]);
        return (bool) $stmt->fetchColumn();
    }
}
