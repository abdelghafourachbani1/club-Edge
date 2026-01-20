<?php

namespace App\Repositories;

use App\Models\Database;
use PDO;

class ClubRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }
}
