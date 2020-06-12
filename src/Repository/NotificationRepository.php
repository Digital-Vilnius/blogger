<?php

namespace App\Repository;

use App\Entity\Application;
use App\Entity\Notification;
use App\Filter\NotificationsFilter;
use App\Model\Paging;
use App\Model\Sort;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    public function findApplicationSubscriberNotification(int $id, int $applicationId, int $subscriberId)
    {
        return $this->createQueryBuilder('notification')
            ->join('notification.subscriber', 'subscriber')
            ->join('subscriber.application', 'application')
            ->where('application.id = :applicationId')
            ->andWhere('subscriber.id = :subscriberId')
            ->andWhere('notification.id = :id')
            ->setParameter('id', $id)
            ->setParameter('subscriberId', $subscriberId)
            ->setParameter('applicationId', $applicationId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function fetchAllByApplication(Application $application)
    {
        return $this->createQueryBuilder('notification')
            ->join('notification.subscriber', 'subscriber')
            ->join('subscriber.application', 'application')
            ->where('application.id = :applicationId')
            ->setParameter('applicationId', $application->getId())
            ->setFirstResult(0)
            ->setMaxResults(10)
            ->orderBy('notification.created', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function fetchAll(NotificationsFilter $filter, Sort $sort, Paging $paging)
    {
        $qb = $this->createQueryBuilder('notification')
            ->setFirstResult($paging->getOffset())
            ->setMaxResults($paging->getLimit())
            ->orderBy(sprintf('notification.%s', $sort->getColumn()), $sort->getType());

        $this->applyFilter($qb, $filter);
        return $qb->getQuery()->getResult();
    }

    public function countAll(NotificationsFilter $filter)
    {
        $qb = $this->createQueryBuilder('notification')
            ->select('count(notification.id)');

        $this->applyFilter($qb, $filter);
        return $qb->getQuery()->getSingleScalarResult();
    }

    private function applyFilter(QueryBuilder $qb, NotificationsFilter $filter)
    {
        $qb->join('notification.subscriber', 'subscriber')
            ->join('subscriber.application', 'application');

        if ($filter->getKeyword()) {
            $orX = $qb->expr()->orX();
            $orX->add($qb->expr()->like('notification.title', ':keyword'));
            $orX->add($qb->expr()->like('notification.content', ':keyword'));
            $orX->add($qb->expr()->like('notification.htmlContent', ':keyword'));
            $orX->add($qb->expr()->like('subscriber.email', ':keyword'));
            $orX->add($qb->expr()->like('subscriber.phone', ':keyword'));
            $qb->andWhere($qb->expr()->orX($orX))->setParameter('keyword', '%' . $filter->getKeyword() . '%');
        }


        if ($filter->getSubscriberId()) {
            $qb->andWhere('subscriber.id = :subscriberId')
                ->setParameter('subscriberId', $filter->getSubscriberId());
        }

        if ($filter->getApplicationId()) {
            $qb->andWhere('application.id = :applicationId')
                ->setParameter('applicationId', $filter->getApplicationId());
        }
    }
}