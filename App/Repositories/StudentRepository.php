<?php

namespace App\Repositories;
use PDO;

class StudentRepository {
    private  PDO $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

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

    // Inscription / Désinscription à un club (Règle : 1 étudiant = 1 club)
    public function sincrireDunClub ($studentId, $clubId, $is_president = false):bool {
        $sql = "INSERT INTO club_memberships (club_id, student_id, is_president)
        VALUES (:club_id, :student_id)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':club_id' => $clubId,
            ':student_id' => $studentId,
            ':is_president' => $is_president,
        ]);
    }

    public function desinscireDunClub ($studentId, $clubId):bool {
        $sql = "DELETE FROM club_memberships WHERE club_id = :club_id AND student_id = :student_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':club_id' => $clubId,
            ':student_id' => $studentId,
        ]);
    }

    // nombre club
    public function nombreClub ($clubId) {
        $sql = "SELECT COUNT(*) FROM club_memberships WHERE club_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($clubId);
        return $stmt->fetchColumn();
    }

    // check étudiant peut être inscrit dans un seul club
    public function isParticipatedInClub(int $studentId): bool
    {
        $sql = "SELECT 1 FROM club_memberships WHERE student_id = :student_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':student_id' => $studentId,
        ]);
        return (bool) $stmt->fetchColumn();
    }

    
}
