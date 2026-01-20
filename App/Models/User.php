<?php

namespace App\Models;

abstract class User
{
    protected int $id;
    protected string $firstName;
    protected string $lastName;
    protected string $email;
    protected string $password;
    protected string $role;
    protected \DateTime $createdAt;

    public function __construct(
        int $id,
        string $firstName,
        string $lastName,
        string $email,
        string $password,
        string $role,
        \DateTime $createdAt
    ) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->createdAt = $createdAt;
    }

    public function getId(): int {
        return $this->id;
    }
    public function getFirstName(): string {
        return $this->firstName;

    }
    public function setFirstName(string $firstName): void {}

    public function getLastName(): string {
        return $this->lastName;

    }
    public function setLastName(string $lastName): void {}

    public function getEmail(): string {
        return $this->email;

    }
    public function setEmail(string $email): void {}

    public function getPassword(): string {
        return $this->password;

    }
    public function setPassword(string $password): void {}

    public function getRole(): string {
        return $this->role;

    }
    public function getCreatedAt(): \DateTime { 
        return $this->createdAt;
    }
}