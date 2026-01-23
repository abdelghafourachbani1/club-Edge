<?php

namespace App\Repositories;

use App\Models\Database;
use PDO;
use PDOException;

class ParticipationRepository {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function register(int $studentId, int $eventId): bool {
        try {
            $sql = "INSERT INTO participations (student_id, event_id, has_participated) 
                    VALUES (:student_id, :event_id, FALSE)
                    ON CONFLICT (student_id, event_id) DO NOTHING";
            
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':student_id' => $studentId,
                ':event_id' => $eventId
            ]);
        } catch (PDOException $e) {
            error_log("Error registering for event: " . $e->getMessage());
            return false;
        }
    }

    public function unregister(int $studentId, int $eventId): bool {
        try {
            $sql = "DELETE FROM participations 
                    WHERE student_id = :student_id AND event_id = :event_id";
            
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':student_id' => $studentId,
                ':event_id' => $eventId
            ]);
        } catch (PDOException $e) {
            error_log("Error unregistering from event: " . $e->getMessage());
            return false;
        }
    }

    public function isRegistered(int $studentId, int $eventId): bool {
        try {
            $sql = "SELECT EXISTS(
                        SELECT 1 FROM participations 
                        WHERE student_id = :student_id AND event_id = :event_id
                    ) as is_registered";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':student_id' => $studentId,
                ':event_id' => $eventId
            ]);

            return (bool) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error checking registration: " . $e->getMessage());
            return false;
        }
    }

    public function markAsParticipated(int $studentId, int $eventId): bool {
        try {
            $sql = "UPDATE participations 
                    SET has_participated = TRUE 
                    WHERE student_id = :student_id AND event_id = :event_id";
            
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':student_id' => $studentId,
                ':event_id' => $eventId
            ]);
        } catch (PDOException $e) {
            error_log("Error marking participation: " . $e->getMessage());
            return false;
        }
    }

    public function hasParticipated(int $studentId, int $eventId): bool {
        try {
            $sql = "SELECT has_participated FROM participations 
                    WHERE student_id = :student_id AND event_id = :event_id";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':student_id' => $studentId,
                ':event_id' => $eventId
            ]);

            return (bool) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error checking participation status: " . $e->getMessage());
            return false;
        }
    }

    public function getParticipants(int $eventId): array {
        try {
            $sql = "SELECT u.id, u.first_name, u.last_name, u.email, p.has_participated
                    FROM participations p
                    JOIN users u ON p.student_id = u.id
                    WHERE p.event_id = :event_id
                    ORDER BY u.first_name, u.last_name";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':event_id' => $eventId]);
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error getting participants: " . $e->getMessage());
            return [];
        }
    }

    public function getStudentEvents(int $studentId): array {
        try {
            $sql = "SELECT event_id FROM participations WHERE student_id = :student_id";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':student_id' => $studentId]);
            
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            error_log("Error getting student events: " . $e->getMessage());
            return [];
        }
    }

    public function countParticipants(int $eventId): int {
        try {
            $sql = "SELECT COUNT(*) FROM participations WHERE event_id = :event_id";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':event_id' => $eventId]);
            
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error counting participants: " . $e->getMessage());
            return 0;
        }
    }

    public function markAllAsParticipated(int $eventId): bool {
        try {
            $sql = "UPDATE participations 
                    SET has_participated = TRUE 
                    WHERE event_id = :event_id";
            
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([':event_id' => $eventId]);
        } catch (PDOException $e) {
            error_log("Error marking all as participated: " . $e->getMessage());
            return false;
        }
    }
}