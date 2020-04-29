<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Filter\CommentsFilter;
use App\Model\Paging;
use App\Model\Sort;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CommentRepository extends ServiceEntityRepository
{
    private $user;

    public function __construct(ManagerRegistry $registry, TokenStorageInterface $tokenStorage)
    {
        parent::__construct($registry, Comment::class);
        $this->user = $tokenStorage->getToken()->getUser();
    }

    public function fetchUserComment(int $id)
    {
        return $this->createQueryBuilder('comment')
            ->join('comment.post', 'post')
            ->join('post.blog', 'blog')
            ->where('blog.user = :user')
            ->andWhere('comment.id = :id')
            ->setParameter('id', $id)
            ->setParameter('user', $this->user)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function fetchAll(CommentsFilter $filter, Sort $sort, Paging $paging)
    {
        $qb = $this->createQueryBuilder('comment')
            ->setFirstResult($paging->getOffset())
            ->setMaxResults($paging->getLimit())
            ->orderBy(sprintf('comment.%s', $sort->getColumn()), $sort->getType());

        $this->applyFilter($qb, $filter);
        return $qb->getQuery()->getResult();
    }

    public function countAll(CommentsFilter $filter)
    {
        $qb = $this->createQueryBuilder('comment')
            ->select('count(comment.id)');

        $this->applyFilter($qb, $filter);
        return $qb->getQuery()->getSingleScalarResult();
    }

    private function applyFilter(QueryBuilder $qb, CommentsFilter $filter)
    {
        if ($filter->getKeyword()) {
            $orX = $qb->expr()->orX();
            $orX->add($qb->expr()->like('comment.username', $filter->getKeyword()));
            $qb->andWhere($qb->expr()->orX($orX));
        }

        if ($filter->getPostId()) {
            $qb->join('comment.post', 'post')
                ->andWhere('post.id = :postId')
                ->setParameter('postId', $filter->getPostId());
        }
    }
}