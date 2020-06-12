<?php

namespace App\Controller;

use App\Controller\Base\ListController;
use App\Entity\Subscriber;
use App\Filter\SubscribersFilter;
use App\Form\Type\SearchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class SubscribersController extends ListController
{
    private $entityManager;
    private $translator;

    public function __construct(ValidatorInterface $validator, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        parent::__construct($validator);
        $this->translator = $translator;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/admin/subscribers/{page}", name="admin subscribers", requirements={"page"="\d+"})
     * @param Request $request
     * @param int $page
     * @return Response
     */
    public function fetch(Request $request, int $page = 1)
    {
        $filter = $this->initFilter($request);
        $sort = $this->initSort($request);
        $paging = $this->initPaging($request);

        $searchForm = $this->createForm(SearchType::class, $filter);
        $searchForm->handleRequest($request);

        $subscribers = $this->entityManager->getRepository(Subscriber::class)->fetchAll($filter, $sort, $paging);
        $subscribersCount = $this->entityManager->getRepository(Subscriber::class)->countAll($filter);

        return $this->render('pages/subscribers.html.twig', [
            'subscribers' => $subscribers,
            'subscribersCount' => $subscribersCount,
            'page' => $page,
            'searchForm' => $searchForm->createView(),
            'pagesCount' => ceil($subscribersCount / $paging->getLimit())
        ]);
    }

    private function initFilter(Request $request)
    {
        $filter = new SubscribersFilter();
        $filter->setKeyword($request->query->get('keyword', null));
        $filter->setApplicationId($request->query->get('applicationId', null));
        return $filter;
    }
}