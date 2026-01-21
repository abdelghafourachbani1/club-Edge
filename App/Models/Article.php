<?php

namespace App\Models;

class Article
{
    private int $id;
    private string $title;
    private string $content;
    private array $images;
    private int $eventId;
    private int $clubId;
    private int $authorId;
    private \DateTime $createdAt;

    public function __construct(
        int $id,
        string $title,
        string $content,
        array $images,
        int $eventId,
        int $clubId,
        int $authorId,
        \DateTime $createdAt
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->images = $images;
        $this->eventId = $eventId;
        $this->clubId = $clubId;
        $this->authorId = $authorId;
        $this->createdAt = $createdAt;
    }

    public function getId(): int {return -1;}
    public function getTitle(): string {return "";}
    public function setTitle(string $title): void {}

    public function getContent(): string {return "";}
    public function setContent(string $content): void {}

    public function getImages(): array {return [];}
    public function setImages(array $images): void {}

    public function getEventId(): int {return -1;}
    public function getClubId(): int {return -1;}
    public function getAuthorId(): int {return -1;}

    public function getCreatedAt(): \DateTime {return $this->createdAt;}
}