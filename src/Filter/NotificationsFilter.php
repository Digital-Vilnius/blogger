<?php

namespace App\Filter;

use App\Model\SearchableFilter;

class NotificationsFilter extends SearchableFilter
{
    private $applicationId;
    private $subscriberId;

    public function getApplicationId(): ?int
    {
        return $this->applicationId;
    }

    public function setApplicationId(?int $applicationId): void
    {
        $this->applicationId = $applicationId;
    }

    public function getSubscriberId(): ?int
    {
        return $this->subscriberId;
    }

    public function setSubscriberId(?int $subscriberId): void
    {
        $this->subscriberId = $subscriberId;
    }
}