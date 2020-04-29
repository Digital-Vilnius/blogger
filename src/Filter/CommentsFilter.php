<?php

namespace App\Filter;

class CommentsFilter
{
    private $keyword;
    private $postId;

    public function getKeyword(): ?string
    {
        return $this->keyword;
    }

    public function setKeyword(?string $keyword): void
    {
        $this->keyword = $keyword;
    }

    public function getPostId(): ?int
    {
        return $this->postId;
    }

    public function setPostId(?int $postId): void
    {
        $this->postId = $postId;
    }
}