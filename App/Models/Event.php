<?php

namespace App\Models;

class Event
{
    private int $id;
    private string $title;
    private string $description;
    private \DateTime $eventDate;
    private string $location;
    private array $images;
    private string $status;
    private int $clubId;

    public function __construct(
        int $id,
        string $title,
        string $description,
        \DateTime $eventDate,
        string $location,
        array $images,
        string $status,
        int $clubId
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->eventDate = $eventDate;
        $this->location = $location;
        $this->images = $images;
        $this->status = $status;
        $this->clubId = $clubId;
    }

    public function getId(): int {
        return 0;
    }
    public function getTitle(): string {
        return "";
    }
    public function setTitle(string $title): void {}

    public function getDescription(): string {return "";}
    public function setDescription(string $description): void {}

    public function getEventDate(): \DateTime { return $this->getEventDate();}
    public function setEventDate(\DateTime $eventDate): void {}

    public function getLocation(): string {return "";}
    public function setLocation(string $location): void {}

    public function getImages(): array {
        return [];
    }
    public function setImages(array $images): void {}

    public function getStatus(): string {return "";}
    public function setStatus(string $status): void {}

    public function getClubId(): int {return 0;}
}