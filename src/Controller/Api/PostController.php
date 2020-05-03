<?php

namespace App\Controller\Api;

use App\Contract\ResultResponse;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PostController extends AbstractController
{
    private $entityManager;
    private $validator;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @Route("/api/blog/{blogId}/post/{id}", name="api blog post", methods={"GET"})
     * @Entity("blog", expr="repository.fetchUserBlog(blogId)")
     * @Entity("post", expr="repository.fetchUserBlogPost(blogId, id)")
     * @param Post $post
     * @return ResultResponse
     */
    public function fetch(Post $post)
    {
        return new ResultResponse($post);
    }
}