<?php

namespace App\Filter;

use Doctrine\Common\Collections\ArrayCollection;

class PostsFilter
{
    private $keyword;
    private $blogId;
    private $userId;
    private $tags;
    private $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

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

    public function getTags(): ?ArrayCollection
    {
        return $this->tags;
    }

    public function setTags(?ArrayCollection $tags): void
    {
        $this->tags = $tags;
    }

    public function getCategories(): ?ArrayCollection
    {
        return $this->categories;
    }

    public function setCategories(?ArrayCollection $categories): void
    {
        $this->categories = $categories;
    }
}