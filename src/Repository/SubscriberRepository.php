<?php

namespace App\Repository;

use App\Entity\Subscriber;
use App\Filter\SubscribersFilter;
use App\Model\Paging;
use App\Model\Sort;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SubscriberRepository extends ServiceEntityRepository
{
    private $user;

    public function __construct(ManagerRegistry $registry, TokenStorageInterface $tokenStorage)
    {
        parent::__construct($registry, Subscriber::class);
        $this->user = $tokenStorage->getToken()->getUser();
    }

    public function fetchUserSubscriber(int $id)
    {
        return $this->createQueryBuilder('subscriber')
            ->join('subscriber.blog', 'blog')
            ->where('blog.user = :user')
            ->andWhere('subscriber.id = :id')
            ->setParameter('id', $id)
            ->setParameter('user', $this->user)
            ->getQuery()
            ->getSingleResult();
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
        if ($filter->getKeyword()) {
            $orX = $qb->expr()->orX();
            $orX->add($qb->expr()->like('subscriber.email', $filter->getKeyword()));
            $qb->andWhere($qb->expr()->orX($orX));
        }

        if ($filter->getBlogId()) {
            $qb->join('subscriber.blog', 'blog')
                ->andWhere('blog.id = :blogId')
                ->setParameter('blogId', $filter->getBlogId());
        }
    }
}