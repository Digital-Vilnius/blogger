<?php

namespace App\EventSubscriber;

use App\Entity\Post;
use App\Entity\Subscriber;
use App\Notifier\PostNotification;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Notifier\NotifierInterface;

class PostSubscriber implements EventSubscriber
{
    private $notifier;

    public function __construct(NotifierInterface $notifier)
    {
        $this->notifier = $notifier;
    }

    public function getSubscribedEvents()
    {
        return [Events::postPersist];
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof Post) {
            $this->notifier->send(new PostNotification(), $entity->getSubscribersEmails());
        }
    }
}