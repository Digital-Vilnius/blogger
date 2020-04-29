<?php

namespace App\Repository;

use App\Entity\Notification;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class NotificationRepository extends ServiceEntityRepository
{
    private $user;

    public function __construct(ManagerRegistry $registry, TokenStorageInterface $tokenStorage)
    {
        parent::__construct($registry, Notification::class);
        $this->user = $tokenStorage->getToken()->getUser();
    }

    public function fetchUserNotificationByType(User $user, string $type)
    {
        return $this->createQueryBuilder('notification')
            ->where('notification.user = :user')
            ->andWhere('notification.type = :type')
            ->setParameter('type', $type)
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }
}