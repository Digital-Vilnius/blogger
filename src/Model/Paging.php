<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Paging
{
    /**
     * @Assert\NotBlank(message="field_is_required")
     */
    private $limit;

    /**
     * @Assert\NotBlank(message="field_is_required")
     */
    private $offset;

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setLimit(int $limit)
    {
        $this->limit = $limit;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function setOffset(int $offset)
    {
        $this->offset = $offset;
    }
}