<?php

namespace App\Repository;

use App\Entity\Application;
use App\Filter\ApplicationsFilter;
use App\Model\Paging;
use App\Model\Sort;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

class ApplicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Application::class);
    }

    public function fetchAll(ApplicationsFilter $filter, Sort $sort, Paging $paging)
    {
        $qb = $this->createQueryBuilder('application')
            ->setFirstResult($paging->getOffset())
            ->setMaxResults($paging->getLimit())
            ->orderBy(sprintf('application.%s', $sort->getColumn()), $sort->getType());

        $this->applyFilter($qb, $filter);
        return $qb->getQuery()->getResult();
    }

    public function countAll(ApplicationsFilter $filter)
    {
        $qb = $this->createQueryBuilder('application')
            ->select('count(application.id)');

        $this->applyFilter($qb, $filter);
        return $qb->getQuery()->getSingleScalarResult();
    }

    private function applyFilter(QueryBuilder $qb, ApplicationsFilter $filter)
    {
        if ($filter->getKeyword()) {
            $orX = $qb->expr()->orX();
            $orX->add($qb->expr()->like('application.name', ':keyword'));
            $qb->andWhere($qb->expr()->orX($orX))->setParameter('keyword', '%' . $filter->getKeyword() . '%');
        }
    }
}