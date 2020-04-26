<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Sort
{
    /**
     * @Assert\NotBlank(message="field_is_required")
     */
    private $type;

    /**
     * @Assert\NotBlank(message="field_is_required")
     */
    private $column;

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type)
    {
        $this->type = $type;
    }

    public function getColumn(): string
    {
        return $this->column;
    }

    public function setColumn(string $column)
    {
        $this->column = $column;
    }
}