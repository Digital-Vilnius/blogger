<?php

namespace App\Filter;

use Doctrine\Common\Collections\ArrayCollection;

class PostsFilter
{
    private $tagsSlugs;
    private $keyword;
    private $blogId;
    private $userId;
    private $tags;

    public function getKeyword(): ?string
    {
        return $this->keyword;
    }

    public function setKeyword(?string $keyword): void
    {
        $this->keyword = $keyword;
    }

    public function getBlogId(): ?int
    {
        return $this->blogId;
    }

    public function setBlogId(?int $blogId): void
    {
        $this->blogId = $blogId;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): void
    {
        $this->userId = $userId;
    }

    public function getTags(): ?array
    {
        return $this->tags;
    }

    public function setTags(?array $tags): void
    {
        $this->tags = $tags;
    }
}