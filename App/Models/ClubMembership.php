<?php

namespace App\Models;

class ClubMembership
{
    private int $id;
    private int $clubId;
    private int $studentId;
    private bool $isPresident;
    private \DateTime $joinedAt;

    public function __construct(
        int $id,
        int $clubId,
        int $studentId,
        bool $isPresident,
        \DateTime $joinedAt
    ) {
        $this->id = $id;
        $this->clubId = $clubId;
        $this->studentId = $studentId;
        $this->isPresident = $isPresident;
        $this->joinedAt = $joinedAt;
    }

    public function getId(): int {
        return $this->id;

    }
    public function getClubId(): int {
        return $this->clubId;

    }
    public function getStudentId(): int {
        return $this->studentId;

    }

    public function isPresident(): bool {
        return false;
    }
    public function setIsPresident(bool $isPresident): void {}

    public function getJoinedAt(): \DateTime {
        return $this->joinedAt;
    }
}