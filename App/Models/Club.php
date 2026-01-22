<?php

namespace App\Models;

class Club
{
    private int $id;
    private string $name;
    private string $description;
    private string $logoPath;
    private int $maxMembers;
    private \DateTime $createdAt;

    public function __construct(
        int $id,
        string $name,
        string $description,
        string $logoPath,
        int $maxMembers = 8,
        \DateTime $createdAt
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->logoPath = $logoPath;
        $this->maxMembers = $maxMembers;
        $this->createdAt = $createdAt;
    }

    public function getId(): int {
        return 0;
    }
    public function getName(): string {
        return "";

    }
    public function setName(string $name): void {}

    public function getDescription(): string {

        return "";
    }
    public function setDescription(string $description): void {}

    public function getLogoPath(): string {
        return "";

    }
    public function setLogoPath(string $logoPath): void {}

    public function getMaxMembers(): int {
        return 0;
    }
    public function setMaxMembers(int $maxMembers): void {}

    public function getCreatedAt(): \DateTime {
        return $this->createdAt;
    }
}