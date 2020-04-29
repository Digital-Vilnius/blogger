<?php

namespace App\EventSubscriber;

use App\Entity\Notification;
use App\Entity\Post;
use App\Enum\NotificationTypes;
use App\Service\NotificationServiceInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class PostSubscriber implements EventSubscriber
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

        if ($entity instanceof Post) {
            $user = $entity->getBlog()->getUser();
            $notification = $this->entityManager->getRepository(Notification::class)->fetchUserNotificationByType($user, NotificationTypes::ADD_SUBSCRIBER);
            $this->notificationService->notify($notification);
        }
    }
}