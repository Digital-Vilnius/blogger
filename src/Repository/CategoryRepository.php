<?php

namespace App\Repository;

use App\Entity\Category;
use App\Filter\CategoriesFilter;
use App\Model\Paging;
use App\Model\Sort;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CategoryRepository extends ServiceEntityRepository
{
    private $user;

    public function __construct(ManagerRegistry $registry, TokenStorageInterface $tokenStorage)
    {
        parent::__construct($registry, Category::class);
        $this->user = $tokenStorage->getToken()->getUser();
    }

    public function fetchUserCategory(int $id)
    {
        return $this->createQueryBuilder('category')
            ->join('category.blog', 'blog')
            ->where('blog.user = :user')
            ->andWhere('category.id = :id')
            ->setParameter('id', $id)
            ->setParameter('user', $this->user)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function fetchAll(CategoriesFilter $filter, Sort $sort, Paging $paging)
    {
        $qb = $this->createQueryBuilder('category')
            ->setFirstResult($paging->getOffset())
            ->setMaxResults($paging->getLimit())
            ->orderBy(sprintf('category.%s', $sort->getColumn()), $sort->getType());

        $this->applyFilter($qb, $filter);
        return $qb->getQuery()->getResult();
    }

    public function countAll(CategoriesFilter $filter)
    {
        $qb = $this->createQueryBuilder('category')
            ->select('count(category.id)');

        $this->applyFilter($qb, $filter);
        return $qb->getQuery()->getSingleScalarResult();
    }

    private function applyFilter(QueryBuilder $qb, CategoriesFilter $filter)
    {
        if ($filter->getKeyword()) {
            $orX = $qb->expr()->orX();
            $orX->add($qb->expr()->like('category.name', $filter->getKeyword()));
            $qb->andWhere($qb->expr()->orX($orX));
        }

        if ($filter->getTags()) {
            $qb->andWhere('tags in (:tags)')
                ->setParameter('tags', $filter->getTags());
        }

        if ($filter->getBlogId()) {
            $qb->join('category.blog', 'blog')
                ->andWhere('blog.id = :blogId')
                ->setParameter('blogId', $filter->getBlogId());
        }
    }
}