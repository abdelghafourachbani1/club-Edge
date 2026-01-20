<?php

namespace App\Models;

class Review
{
    private int $id;
    private int $rating;
    private string $comment;
    private int $studentId;
    private int $eventId;
    private \DateTime $createdAt;

    public function __construct(
        int $id,
        int $rating,
        string $comment,
        int $studentId,
        int $eventId,
        \DateTime $createdAt
    ) {
        $this->id = $id;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->studentId = $studentId;
        $this->eventId = $eventId;
        $this->createdAt = $createdAt;
    }

    public function getId(): int {return 0;}
    public function getRating(): int {return 0;}
    public function setRating(int $rating): void {}

    public function getComment(): string {return "";}
    public function setComment(string $comment): void {}

    public function getStudentId(): int {return -1;}
    public function getEventId(): int {return -1;}

    public function getCreatedAt(): \DateTime {return $this->createdAt;}
}