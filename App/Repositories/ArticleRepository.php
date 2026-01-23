<?php
namespace App\Repositories;
use App\Models\Database;
use PDO;

class ArticleRepository{
    private PDO $pdo;
    public function __construct(){
        $this->pdo = Database::getInstance();
    }

    public function getByClub(int $clubId): array{
        $stmt = $this->pdo->prepare('SELECT id, title, content, event_id, club_id, author_id, images 
            FROM articles WHERE club_id = :club_id ORDER BY id DESC');
        $stmt->execute(['club_id' => $clubId]);
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array{
        $stmt = $this->pdo->prepare("SELECT * FROM articles WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function create(array $data): void{
        $sql = " INSERT INTO articles (title, content, images, event_id, club_id, author_id) 
        VALUES (:title, :content, :images, :event_id, :club_id, :author_id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
    }

    public function update(int $id, array $data): void{
        $sql = "UPDATE articles SET title = :title, content = :content, images = :images WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id'=> $id,
            'title'=> $data['title'],
            'content' => $data['content'],
            'images'=> $data['images'],
        ]);
    }

    public function delete(int $id): void{
        $stmt = $this->pdo->prepare("DELETE FROM articles WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
