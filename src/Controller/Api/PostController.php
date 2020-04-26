<?php

namespace App\Controller\Api;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/api/post", name="api add post", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $post = new Post();
        $post->setTitle($data['title']);
        $post->setContent($data['content']);
        $post->setSummary($data['summary']);

        $errors = $this->validator->validate($post);
        if (count($errors) > 0) return new JsonResponse($errors);

        $this->entityManager->persist($post);
        $this->entityManager->flush();
        return new JsonResponse();
    }

    /**
     * @Route("/api/post/id", name="api edit post", methods={"PUT"})
     * @param Request $request
     * @param Post $post
     * @return JsonResponse
     */
    public function edit(Request $request, Post $post)
    {
        $data = json_decode($request->getContent(), true);

        $post->setTitle($data['title']);
        $post->setContent($data['content']);
        $post->setSummary($data['summary']);

        $errors = $this->validator->validate($post);
        if (count($errors) > 0) return new JsonResponse($errors);

        $this->entityManager->flush();
        return new JsonResponse();
    }

    /**
     * @Route("/api/post/{id}", name="api get post", methods={"GET"})
     * @param Post $post
     * @return JsonResponse
     */
    public function fetch(Post $post)
    {
        return new JsonResponse($post);
    }

    /**
     * @Route("/api/post/{id}", name="api delete post", methods={"DELETE"})
     * @param Post $post
     * @return JsonResponse
     */
    public function delete(Post $post)
    {
        $this->entityManager->remove($post);
        $this->entityManager->flush();
        return new JsonResponse();
    }
}