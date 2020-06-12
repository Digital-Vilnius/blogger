<?php

namespace App\Repository;

use App\Entity\Subscriber;
use App\Filter\SubscribersFilter;
use App\Model\Paging;
use App\Model\Sort;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

class SubscriberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subscriber::class);
    }

    public function findApplicationSubscriber(int $id, int $applicationId)
    {
        return $this->createQueryBuilder('subscriber')
            ->join('subscriber.application', 'application')
            ->where('application.id = :applicationId')
            ->andWhere('subscriber.id = :id')
            ->setParameter('id', $id)
            ->setParameter('applicationId', $applicationId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function fetchAll(SubscribersFilter $filter, Sort $sort, Paging $paging)
    {
        $qb = $this->createQueryBuilder('subscriber')
            ->setFirstResult($paging->getOffset())
            ->setMaxResults($paging->getLimit())
            ->orderBy(sprintf('subscriber.%s', $sort->getColumn()), $sort->getType());

        $this->applyFilter($qb, $filter);
        return $qb->getQuery()->getResult();
    }

    public function countAll(SubscribersFilter $filter)
    {
        $qb = $this->createQueryBuilder('subscriber')
            ->select('count(subscriber.id)');

        $this->applyFilter($qb, $filter);
        return $qb->getQuery()->getSingleScalarResult();
    }

    private function applyFilter(QueryBuilder $qb, SubscribersFilter $filter)
    {
        $qb->join('subscriber.application', 'application');

        if ($filter->getKeyword()) {
            $orX = $qb->expr()->orX();
            $orX->add($qb->expr()->like('subscriber.email', ':keyword'));
            $orX->add($qb->expr()->like('subscriber.phone', ':keyword'));
            $orX->add($qb->expr()->like('application.name', ':keyword'));
            $qb->andWhere($qb->expr()->orX($orX))->setParameter('keyword', '%' . $filter->getKeyword() . '%');
        }

        if ($filter->getApplicationId()) {
            $qb->andWhere('application.id = :applicationId')
                ->setParameter('applicationId', $filter->getApplicationId());
        }
    }
}