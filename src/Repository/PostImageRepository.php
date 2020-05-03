<?php

namespace App\Repository;

use App\Entity\PostImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PostImageRepository extends ServiceEntityRepository
{
    private $user;

    public function __construct(ManagerRegistry $registry, TokenStorageInterface $tokenStorage)
    {
        parent::__construct($registry, PostImage::class);
        $this->user = $tokenStorage->getToken()->getUser();
    }
}