<?php

namespace App\Filter;

class SubscribersFilter
{
    private $keyword;
    private $blogId;

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
}