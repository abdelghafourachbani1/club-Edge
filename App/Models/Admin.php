<?php

namespace App\Models;

class Admin extends User
{
    public function __construct(
        int $id,
        string $firstName,
        string $lastName,
        string $email,
        string $password,
        \DateTime $createdAt
    ) {
        parent::__construct($id, $firstName, $lastName, $email, $password, 'admin', $createdAt);
    }
}