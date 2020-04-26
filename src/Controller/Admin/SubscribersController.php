<?php

namespace App\Controller\Admin;

use App\Controller\Base\ListController;
use App\Entity\Blog;
use App\Entity\Subscriber;
use App\Filter\SubscribersFilter;
use Doctrine\ORM\EntityManagerInterface;
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
     * @Route("/admin/subscribers/{page}", name="admin subscribers")
     * @param Request $request
     * @param int $page
     * @return Response
     */
    public function fetch(Request $request, int $page = 1)
    {
        $filter = $this->initFilter($request);
        $sort = $this->initSort($request);
        $paging = $this->initPaging($request);

        $subscribers = $this->entityManager->getRepository(Subscriber::class)->fetchAll($filter, $sort, $paging);
        $subscribersCount = $this->entityManager->getRepository(Subscriber::class)->countAll($filter);

        return $this->render('admin/pages/subscribers.html.twig', [
            'subscribers' => $subscribers,
            'subscribersCount' => $subscribersCount,
            'page' => $page,
            'pagesCount' => ceil($subscribersCount / $paging->getLimit())
        ]);
    }

    private function initFilter(Request $request)
    {
        $filter = new SubscribersFilter();
        $filter->setKeyword($request->query->get('keyword', null));
        $filter->setBlogId($request->query->get('blogId', null));
        return $filter;
    }
}