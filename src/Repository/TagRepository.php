<?php

namespace App\Repository;

use App\Entity\Tag;
use App\Filter\TagsFilter;
use App\Model\Paging;
use App\Model\Sort;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TagRepository extends ServiceEntityRepository
{
    private $user;

    public function __construct(ManagerRegistry $registry, TokenStorageInterface $tokenStorage)
    {
        parent::__construct($registry, Tag::class);
        $this->user = $tokenStorage->getToken()->getUser();
    }

    public function fetchUserTag(int $id)
    {
        return $this->createQueryBuilder('tag')
            ->join('tag.blog', 'blog')
            ->where('blog.user = :user')
            ->andWhere('tag.id = :id')
            ->setParameter('id', $id)
            ->setParameter('user', $this->user)
            ->getQuery()
            ->getSingleResult();
    }

    public function fetchUserTagByName(string $name)
    {
        return $this->createQueryBuilder('tag')
            ->join('tag.blog', 'blog')
            ->where('blog.user = :user')
            ->andWhere('tag.name = :name')
            ->setParameter('name', $name)
            ->setParameter('user', $this->user)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function fetchAll(TagsFilter $filter, Sort $sort, Paging $paging)
    {
        $qb = $this->createQueryBuilder('tag')
            ->setFirstResult($paging->getOffset())
            ->setMaxResults($paging->getLimit())
            ->orderBy(sprintf('tag.%s', $sort->getColumn()), $sort->getType());

        $this->applyFilter($qb, $filter);
        return $qb->getQuery()->getResult();
    }

    public function countAll(TagsFilter $filter)
    {
        $qb = $this->createQueryBuilder('tag')
            ->select('count(tag.id)');

        $this->applyFilter($qb, $filter);
        return $qb->getQuery()->getSingleScalarResult();
    }

    private function applyFilter(QueryBuilder $qb, TagsFilter $filter)
    {
        if ($filter->getKeyword()) {
            $orX = $qb->expr()->orX();
            $orX->add($qb->expr()->like('tag.name', ':keyword'));
            $qb->andWhere($qb->expr()->orX($orX))
            ->setParameter('keyword', '%' . $filter->getKeyword() . '%');
        }

        if ($filter->getBlogId()) {
            $qb->join('tag.blog', 'blog')
                ->andWhere('blog.id = :blogId')
                ->setParameter('blogId', $filter->getBlogId());
        }
    }
}