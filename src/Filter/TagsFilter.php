<?php

namespace App\Filter;

use Doctrine\Common\Collections\ArrayCollection;

class TagsFilter
{
    private $keyword;
    private $blogId;
    private $posts;

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

    public function getPosts(): ?ArrayCollection
    {
        return $this->posts;
    }

    public function setPosts(?ArrayCollection $posts)
    {
        $this->posts = $posts;
    }
}