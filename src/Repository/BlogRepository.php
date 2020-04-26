<?php

namespace App\Repository;

use App\Entity\Blog;
use App\Filter\BlogsFilter;
use App\Model\Paging;
use App\Model\Sort;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class BlogRepository extends ServiceEntityRepository
{
    private $user;

    public function __construct(ManagerRegistry $registry, TokenStorageInterface $tokenStorage)
    {
        parent::__construct($registry, Blog::class);
        $this->user = $tokenStorage->getToken()->getUser();
    }

    public function fetchUserBlog(int $id)
    {
        return $this->findOneBy(['id' => $id, 'user' => $this->user]);
    }

    public function fetchUserBlogs()
    {
        return $this->findBy(['user' => $this->user]);
    }

    public function fetchAll(BlogsFilter $filter, Sort $sort, Paging $paging)
    {
        $qb = $this->createQueryBuilder('blog')
            ->setFirstResult($paging->getOffset())
            ->setMaxResults($paging->getLimit())
            ->orderBy(sprintf('blog.%s', $sort->getColumn()), $sort->getType());

        $this->applyFilter($qb, $filter);
        return $qb->getQuery()->getResult();
    }

    public function countAll(BlogsFilter $filter)
    {
        $qb = $this->createQueryBuilder('blog')
            ->select('count(blog.id)');

        $this->applyFilter($qb, $filter);
        return $qb->getQuery()->getSingleScalarResult();
    }

    private function applyFilter(QueryBuilder $qb, BlogsFilter $filter)
    {
        if ($filter->getKeyword()) {
            $orX = $qb->expr()->orX();
            $orX->add($qb->expr()->like('blog.title', $filter->getKeyword()));
            $qb->andWhere($qb->expr()->orX($orX));
        }

        if ($filter->getUserId()) {
            $qb->join('blog.user', 'user')
                ->andWhere('user.id = :userId')
                ->setParameter('userId', $filter->getUserId());
        }
    }
}