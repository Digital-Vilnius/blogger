<?php

namespace App\Repository;

use App\Entity\User;
use App\Filter\UsersFilter;
use App\Model\Paging;
use App\Model\Sort;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function fetchAll(UsersFilter $filter, Sort $sort, Paging $paging)
    {
        $qb = $this->createQueryBuilder('user')
            ->setFirstResult($paging->getOffset())
            ->setMaxResults($paging->getLimit())
            ->orderBy(sprintf('user.%s', $sort->getColumn()), $sort->getType());

        $this->applyFilter($qb, $filter);
        return $qb->getQuery()->getResult();
    }

    public function countAll(UsersFilter $filter)
    {
        $qb = $this->createQueryBuilder('user')
            ->select('count(user.id)');

        $this->applyFilter($qb, $filter);
        return $qb->getQuery()->getSingleScalarResult();
    }

    private function applyFilter(QueryBuilder $qb, UsersFilter $filter)
    {
        if ($filter->getKeyword()) {
            $orX = $qb->expr()->orX();
            $orX->add($qb->expr()->like('user.email', $filter->getKeyword()));
            $qb->andWhere($qb->expr()->orX($orX));
        }
    }
}