<?php

namespace App\Models;

class Event {
    private int $id;
    private string $title;
    private string $description;
    private \DateTime $eventDate;
    private string $location;
    private array $images; 
    private string $status; 
    private int $clubId;

    public function __construct(int $id, string $title, string $description, \DateTime $eventDate, string $location, array $images = [],string $status = 'active', int $clubId,) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->eventDate = $eventDate;
        $this->location = $location;
        $this->images = $images;
        $this->status = $status;
        $this->clubId = $clubId;
    }


    public function getId(): int { return $this->id;}

    public function getTitle(): string { return $this->title;}

    public function getDescription(): string { return $this->description;}

    public function getEventDate(): \DateTime { return $this->eventDate;}

    public function getLocation(): string { return $this->location;}

    public function getImages(): array { return $this->images;}

    public function getStatus(): string { return $this->status;}

    public function getClubId(): int { return $this->clubId;}

    public function setTitle(string $title): void { $this->title = $title;}

    public function setDescription(string $description): void { $this->description = $description;}

    public function setEventDate(\DateTime $eventDate): void {
        $this->eventDate = $eventDate;
    }

    public function setLocation(string $location): void { $this->location = $location;}

    public function setImages(array $images): void { $this->images = $images;}

    public function setStatus(string $status): void {
        if (!in_array($status, ['active', 'cancelled', 'finished'])) {
            throw new \InvalidArgumentException("Invalid status: {$status}");
        }
        $this->status = $status;
    }

    public function setClubId(int $clubId): void {
        $this->clubId = $clubId;
    }

    public function addImage(string $imagePath): void {
        $this->images[] = $imagePath;
    }

    public function removeImage(string $imagePath): void {
        $this->images = array_filter($this->images, fn($img) => $img !== $imagePath);
        $this->images = array_values($this->images); 
    }

    public function isPast(): bool {
        return $this->eventDate < new \DateTime();
    }

    public function isUpcoming(): bool {
        return $this->eventDate > new \DateTime();
    }

    public function isActive(): bool {
        return $this->status === 'active';
    }

    public function isCancelled(): bool {
        return $this->status === 'cancelled';
    }

    public function isFinished(): bool {
        return $this->status === 'finished';
    }

    public function cancel(): void {
        $this->status = 'cancelled';
    }

    public function finish(): void {
        $this->status = 'finished';
    }

    public function activate(): void {
        $this->status = 'active';
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'event_date' => $this->eventDate->format('c'), 
            'location' => $this->location,
            'images' => $this->images,
            'status' => $this->status,
            'club_id' => $this->clubId,
        ];
    }
}