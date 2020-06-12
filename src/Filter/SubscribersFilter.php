<?php

namespace App\Filter;

use App\Model\SearchableFilter;

class SubscribersFilter extends SearchableFilter
{
    private $applicationId;

    public function getApplicationId(): ?int
    {
        return $this->applicationId;
    }

    public function setApplicationId(?int $applicationId): void
    {
        $this->applicationId = $applicationId;
    }
}