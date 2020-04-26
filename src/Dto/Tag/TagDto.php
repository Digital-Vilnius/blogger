<?php

namespace App\Dto\Tag;

use App\Dto\BaseDto;

class TagDto extends BaseDto
{
    private $title;
    private $slug;
    private $posts;

    public function __construct()
    {
        $this->posts = [];
    }

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

    public function getPosts(): array
    {
        return $this->posts;
    }

    public function setPosts(array $posts): void
    {
        $this->posts = $posts;
    }
}