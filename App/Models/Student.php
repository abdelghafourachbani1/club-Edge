<?php

namespace App\Models;

class Student extends User
{
    private string $fieldOfStudy;
    private string $academicLevel;
    private ?int $clubId;

    public function __construct(
        int $id,
        string $firstName,
        string $lastName,
        string $email,
        string $password,
        \DateTime $createdAt,
        string $fieldOfStudy,
        string $academicLevel,
        ?int $clubId = null
    ) {
        parent::__construct($id, $firstName, $lastName, $email, $password, 'student', $createdAt);
        $this->fieldOfStudy = $fieldOfStudy;
        $this->academicLevel = $academicLevel;
        $this->clubId = $clubId;
    }

    public function getFieldOfStudy(): string {
        return $this->fieldOfStudy;

    }
    public function setFieldOfStudy(string $fieldOfStudy): void {}

    public function getAcademicLevel(): string {
        return $this->academicLevel;

    }
    public function setAcademicLevel(string $academicLevel): void {}

    public function getClubId(): ?int {
        return $this->clubId;

    }
    public function setClubId(?int $clubId): void {}
}