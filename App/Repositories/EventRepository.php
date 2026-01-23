<?php

namespace App\Repositories;

use App\Models\Database;
use App\Models\Event;
use PDO;
use PDOException;


class EventRepository {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    //returns the event object from findById

    public function create(array $data): ?Event {
        try {
            $sql = "INSERT INTO events (title, description, event_date, location, images, status, club_id) 
                    VALUES (:title, :description, :event_date, :location, :images, :status, :club_id) 
                    RETURNING id";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':title' => $data['title'],
                ':description' => $data['description'],
                ':event_date' => $data['event_date'],
                ':location' => $data['location'],
                ':images' => json_encode($data['images']),
                ':status' => $data['status'] ?? 'active',
                ':club_id' => $data['club_id']
            ]);

            $id = $stmt->fetchColumn();
            return $this->findById($id);
        } catch (PDOException $e) {
            error_log("Error creating event: " . $e->getMessage());
            return null;
        }
    }

    public function findById(int $id): ?Event {
        try {
            $sql = "SELECT * FROM events WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch();

            return $row ? $this->hydrate($row) : null;
        } catch (PDOException $e) {
            error_log("Error finding event by ID: " . $e->getMessage());
            return null;
        }
    }

    // fetch all the events with hydrate each row

    public function findAll(): array {
        try {
            $sql = "SELECT * FROM events ORDER BY event_date DESC";
            $stmt = $this->pdo->query($sql);
            $rows = $stmt->fetchAll();

            return array_map(fn($row) => $this->hydrate($row), $rows);
        } catch (PDOException $e) {
            error_log("Error finding all events: " . $e->getMessage());
            return [];
        }
    }

    // fetch all the clubs usong clubId (with hydrate them) 

    public function findByClubId(int $clubId): array {
        try {
            $sql = "SELECT * FROM events WHERE club_id = :club_id ORDER BY event_date DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':club_id' => $clubId]);
            $rows = $stmt->fetchAll();

            return array_map(fn($row) => $this->hydrate($row), $rows);
        } catch (PDOException $e) {
            error_log("Error finding events by club ID: " . $e->getMessage());
            return [];
        }
    }

    public function findByStatus(string $status): array {
        try {
            $sql = "SELECT * FROM events WHERE status = :status ORDER BY event_date DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':status' => $status]);
            $rows = $stmt->fetchAll();

            return array_map(fn($row) => $this->hydrate($row), $rows);
        } catch (PDOException $e) {
            error_log("Error finding events by status: " . $e->getMessage());
            return [];
        }
    }

    public function findUpcoming(?int $clubId = null): array {
        try {
            $sql = "SELECT * FROM events 
                    WHERE event_date > NOW() AND status = 'active'";
            
            if ($clubId !== null) {
                $sql .= " AND club_id = :club_id";
            }
            
            $sql .= " ORDER BY event_date ASC";

            $stmt = $this->pdo->prepare($sql);
            
            if ($clubId !== null) {
                $stmt->execute([':club_id' => $clubId]);
            } else {
                $stmt->execute();
            }

            $rows = $stmt->fetchAll();
            return array_map(fn($row) => $this->hydrate($row), $rows);
        } catch (PDOException $e) {
            error_log("Error finding upcoming events: " . $e->getMessage());
            return [];
        }
    }

    public function findPast(?int $clubId = null): array {
        try {
            $sql = "SELECT * FROM events 
                    WHERE (event_date < NOW() OR status = 'finished')";
            
            if ($clubId !== null) {
                $sql .= " AND club_id = :club_id";
            }
            
            $sql .= " ORDER BY event_date DESC";

            $stmt = $this->pdo->prepare($sql);
            
            if ($clubId !== null) {
                $stmt->execute([':club_id' => $clubId]);
            } else {
                $stmt->execute();
            }

            $rows = $stmt->fetchAll();
            return array_map(fn($row) => $this->hydrate($row), $rows);
        } catch (PDOException $e) {
            error_log("Error finding past events: " . $e->getMessage());
            return [];
        }
    }

    public function update(int $id, array $data): bool {
        try {
            $fields = [];
            $params = [':id' => $id];

            if (isset($data['title'])) {
                $fields[] = "title = :title";
                $params[':title'] = $data['title'];
            }
            if (isset($data['description'])) {
                $fields[] = "description = :description";
                $params[':description'] = $data['description'];
            }
            if (isset($data['event_date'])) {
                $fields[] = "event_date = :event_date";
                $params[':event_date'] = $data['event_date'];
            }
            if (isset($data['location'])) {
                $fields[] = "location = :location";
                $params[':location'] = $data['location'];
            }
            if (isset($data['images'])) {
                $fields[] = "images = :images";
                $params[':images'] = json_encode($data['images']);
            }
            if (isset($data['status'])) {
                $fields[] = "status = :status";
                $params[':status'] = $data['status'];
            }

            if (empty($fields)) {
                return false;
            }

            $sql = "UPDATE events SET " . implode(', ', $fields) . " WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Error updating event: " . $e->getMessage());
            return false;
        }
    }

    public function delete(int $id): bool {
        try {
            $sql = "DELETE FROM events WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Error deleting event: " . $e->getMessage());
            return false;
        }
    }

    public function isPresidentOfEventClub(int $eventId, int $studentId): bool {
        try {
            $sql = "SELECT EXISTS(
                        SELECT 1 FROM events e
                        JOIN club_memberships cm ON e.club_id = cm.club_id
                        WHERE e.id = :event_id 
                        AND cm.student_id = :student_id 
                        AND cm.is_president = TRUE
                    ) as is_president";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':event_id' => $eventId,
                ':student_id' => $studentId
            ]);

            return (bool) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error checking president status: " . $e->getMessage());
            return false;
        }
    }

    // count how many in the event 

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

    // hydrate the rows goes back from DB

    private function hydrate(array $row): Event {
        $images = json_decode($row['images'] ?? '[]', true) ?? [];
        
        return new Event(
            id: (int) $row['id'],
            title: $row['title'],
            description: $row['description'] ?? '',
            eventDate: new \DateTime($row['event_date']),
            location: $row['location'] ?? '',
            images: $images,
            status: $row['status'],
            clubId: (int) $row['club_id']
        );
    }

}