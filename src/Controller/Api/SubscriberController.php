<?php

namespace App\Controller\Api;

use App\Contract\BaseResponse;
use App\Contract\ResultResponse;
use App\Entity\Blog;
use App\Entity\Subscriber;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SubscriberController extends AbstractController
{
    private $entityManager;
    private $validator;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @Route("/api/blog/{blogId}/subscriber", name="api blog subscriber add", methods={"POST"})
     * @Entity("blog", expr="repository.fetchUserBlog(blogId)")
     * @param Request $request
     * @param Blog $blog
     * @return BaseResponse
     */
    public function fetch(Request $request, Blog $blog)
    {
        $data = json_decode($request->getContent(), true);

        $subscriber = new Subscriber();
        $subscriber->setEmail($data['email']);
        $subscriber->setBlog($blog);

        $errors = $this->validator->validate($subscriber);
        if (count($errors) > 0) return new ResultResponse($errors);

        $this->entityManager->persist($subscriber);
        $this->entityManager->flush();
        return new BaseResponse();
    }
}