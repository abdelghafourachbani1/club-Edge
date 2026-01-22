<?php

namespace App\Models;

class Participation
{
    private int $id;
    private int $studentId;
    private int $eventId;
    private bool $hasParticipated;
    private \DateTime $registeredAt;

    public function __construct(
        int $id,
        int $studentId,
        int $eventId,
        bool $hasParticipated,
        \DateTime $registeredAt
    ) {
        $this->id = $id;
        $this->studentId = $studentId;
        $this->eventId = $eventId;
        $this->hasParticipated = $hasParticipated;
        $this->registeredAt = $registeredAt;
    }

    public function getId(): int {return 1;}
    public function getStudentId(): int { return 1;}
    public function getEventId(): int {return -1;}

    public function hasParticipated(): bool {return true;}
    public function setHasParticipated(bool $hasParticipated): void {}

    public function getRegisteredAt(): \DateTime {return $this->registeredAt;}
}