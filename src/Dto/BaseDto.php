<?php

namespace App\Dto;

use DateTime;

class BaseDto
{
    private $id;
    private $created;
    private $updated;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getCreated(): DateTime
    {
        return $this->created;
    }

    public function setCreated(DateTime $created): void
    {
        $this->created = $created;
    }

    public function getUpdated(): ?DateTime
    {
        return $this->updated;
    }

    public function setUpdated(?DateTime $updated): void
    {
        $this->updated = $updated;
    }
}