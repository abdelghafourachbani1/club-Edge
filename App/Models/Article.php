<?php
namespace App\Models;
class Article{
    private int $id;
    private string $title;
    private string $content;
    private array $images;
    private int $eventId;
    private int $clubId;
    private int $authorId;
    private \DateTime $createdAt;

    public function __construct(int $id,string $title,string $content,array $images,int $eventId,int $clubId,int $authorId,\DateTime $createdAt) {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->images = $images;
        $this->eventId = $eventId;
        $this->clubId = $clubId;
        $this->authorId = $authorId;
        $this->createdAt = $createdAt;
    }

    public function getId(): int{
        return $this->id;
    }

    public function getTitle(): string{
        return $this->title;
    }

    public function getContent(): string{
        return $this->content;
    }

    public function getImages(): array{
        return $this->images;
    }

    public function getEventId(): int{
        return $this->eventId;
    }

    public function getClubId(): int{
        return $this->clubId;
    }

    public function getAuthorId(): int{
        return $this->authorId;
    }

    public function getCreatedAt(): \DateTime{
        return $this->createdAt;
    }

    public function setTitle(string $title): void{
        $this->title = htmlspecialchars(trim($title));
    }

    public function setContent(string $content): void{
        $this->content = htmlspecialchars(trim($content));
    }

    public function setImages(array $images): void{
        $this->images = $images;
    }
} 
