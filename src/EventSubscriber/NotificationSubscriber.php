<?php

namespace App\EventSubscriber;

use App\Entity\Notification;
use App\Service\NotificationServiceInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class NotificationSubscriber implements EventSubscriber
{
    private $notificationService;
    private $entityManager;

    public function __construct(NotificationServiceInterface $notificationService, EntityManagerInterface $entityManager)
    {
        $this->notificationService = $notificationService;
        $this->entityManager = $entityManager;
    }

    public function getSubscribedEvents()
    {
        return [Events::postPersist];
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof Notification) {
            $this->notificationService->notify($entity);
        }
    }
}