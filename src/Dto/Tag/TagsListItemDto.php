<?php

namespace App\Dto\Tag;

use App\Dto\BaseDto;

class TagsListItemDto extends BaseDto
{
    private $title;
    private $slug;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }
}