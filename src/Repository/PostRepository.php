<?php

namespace App\Repository;

use App\Entity\Post;
use App\Filter\PostsFilter;
use App\Model\Paging;
use App\Model\Sort;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PostRepository extends ServiceEntityRepository
{
    private $user;

    public function __construct(ManagerRegistry $registry, TokenStorageInterface $tokenStorage)
    {
        parent::__construct($registry, Post::class);
        $this->user = $tokenStorage->getToken()->getUser();
    }

    public function fetchUserPost(int $id)
    {
        return $this->createQueryBuilder('post')
            ->join('post.blog', 'blog')
            ->where('blog.user = :user')
            ->andWhere('post.id = :id')
            ->setParameter('id', $id)
            ->setParameter('user', $this->user)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function fetchUserBlogPost(int $blogId, int $postId)
    {
        return $this->createQueryBuilder('post')
            ->join('post.blog', 'blog')
            ->where('blog.user = :user')
            ->andWhere('post.id = :postId')
            ->andWhere('blog.id = :blogId')
            ->setParameter('postId', $postId)
            ->setParameter('blogId', $blogId)
            ->setParameter('user', $this->user)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function fetchAll(PostsFilter $filter, Sort $sort, Paging $paging)
    {
        $qb = $this->createQueryBuilder('post');
        $this->applyFilter($qb, $filter);
        $qb->setFirstResult($paging->getOffset())
            ->setMaxResults($paging->getLimit())
            ->orderBy(sprintf('post.%s', $sort->getColumn()), $sort->getType());

        return $qb->getQuery()->getResult();
    }

    public function countAll(PostsFilter $filter)
    {
        $qb = $this->createQueryBuilder('post')
            ->select('count(post.id)');
        $this->applyFilter($qb, $filter);
        return $qb->getQuery()->getSingleScalarResult();
    }

    private function applyFilter(QueryBuilder $qb, PostsFilter $filter)
    {
        if ($filter->getKeyword()) {
            $orX = $qb->expr()->orX();
            $orX->add($qb->expr()->like('post.title', $filter->getKeyword()));
            $orX->add($qb->expr()->like('post.summary', $filter->getKeyword()));
            $qb->andWhere($qb->expr()->orX($orX));
        }

        if ($filter->getTags()->count() > 0) {
            $qb->andWhere('tags in (:tags)')
                ->setParameter('tags', $filter->getTags());
        }

        if ($filter->getCategories()->count() > 0) {
            $qb
                ->join('post.category', 'category')
                ->andWhere('category in (:categories)')
                ->setParameter('categories', $filter->getCategories());
        }

        if ($filter->getBlogId()) {
            $qb->join('post.blog', 'blog')
                ->andWhere('blog.id = :blogId')
                ->setParameter('blogId', $filter->getBlogId());
        }
    }
}