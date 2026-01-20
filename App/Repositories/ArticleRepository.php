<?php
namespace App\Repositories;
use PDO;
use App\Models\Article;

class ArticleRepository{
    private PDO $db;
    public function __construct(){
        $this->db = require __DIR__ . '/../core/Database.php';
    }
    public function create(array $data){
        $sql = "INSERT INTO articles 
        (title, content, images, event_id, club_id, author_id)
        VALUES (:title, :content, :images, :event_id, :club_id, :author_id)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
    }
    public function findById(int $id): ?Article{
        $stmt = $this->db->prepare("SELECT * FROM articles WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        return new Article(
            $row['id'],
            $row['title'],
            $row['content'],
            json_decode($row['images'], true),
            $row['event_id'],
            $row['club_id'],
            $row['author_id'],
            new \DateTime($row['created_at'])
        );
    }

    public function delete(int $id){
        $stmt = $this->db->prepare("DELETE FROM articles WHERE id = ?");
        $stmt->execute([$id]);
    }
}

