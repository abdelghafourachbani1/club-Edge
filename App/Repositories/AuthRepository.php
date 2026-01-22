<?php
namespace App\Repositories;
use App\Models\Database;
use PDO;

class AuthRepository{

    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // public function createUser(array $userData) {
    //     $sql = "INSERT INTO users (first_name, last_name, email, password) VALUES (:first_name, :last_name, :email, :password)";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->execute([
    //         // $userData
    //         ':first_name' => $userData['first_name'],
    //         ':last_name'  => $userData['last_name'],
    //         ':email'      => $userData['email'],
    //         ':password'   => $userData['password'],
    //     ]);
    //     // return $this->db->lastInsertId();
    //     return (int) $stmt->fetchColumn();
    //     // return 1;
    // }

    public function createUser(array $userData): int
{
    $sql = "
        INSERT INTO users (first_name, last_name, email, password)
        VALUES (:first_name, :last_name, :email, :password)
        RETURNING id
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        ':first_name' => $userData['first_name'],
        ':last_name'  => $userData['last_name'],
        ':email'      => $userData['email'],
        ':password'   => $userData['password'],
    ]);

    return (int) $stmt->fetchColumn();
}

public function allUsers(): array
{
    $sql = "SELECT * FROM users";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}



    public function findByEmail(string $email): ?array{
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public function findById(int $id): ?array{
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

        public function emailExists(string $email): bool
    {
        $stmt = $this->db->prepare("SELECT 1 FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        return (bool) $stmt->fetchColumn();
    }
}