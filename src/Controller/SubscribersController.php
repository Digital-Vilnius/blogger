<?php

namespace App\Controller;

use App\Controller\Base\ListController;
use App\Entity\Blog;
use App\Entity\Subscriber;
use App\Filter\SubscribersFilter;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SubscribersController extends ListController
{
    private $entityManager;

    public function __construct(ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        parent::__construct($validator);
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/blog/{blogId}/subscribers/{page}", name="blog subscribers")
     * @Entity("blog", expr="repository.fetchUserBlog(blogId)")
     * @param Request $request
     * @param Blog $blog
     * @param int $page
     * @return Response
     */
    public function fetch(Request $request, Blog $blog, int $page = 1)
    {
        $filter = $this->initFilter($request);
        $sort = $this->initSort($request);
        $paging = $this->initPaging($request);

        $subscribers = $this->entityManager->getRepository(Subscriber::class)->fetchAll($filter, $sort, $paging);
        $subscribersCount = $this->entityManager->getRepository(Subscriber::class)->countAll($filter);

        return $this->render('user/pages/subscribers.html.twig', [
            'subscribers' => $subscribers,
            'subscribersCount' => $subscribersCount,
            'page' => $page,
            'blog' => $blog,
            'pagesCount' => ceil($subscribersCount / $paging->getLimit())
        ]);
    }

    private function initFilter(Request $request)
    {
        $filter = new SubscribersFilter();
        $filter->setKeyword($request->query->get('keyword', null));
        $filter->setBlogId($request->attributes->get('blogId', null));
        return $filter;
    }
}