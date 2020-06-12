<?php

namespace App\Controller;

use App\Controller\Base\ListController;
use App\Entity\Notification;
use App\Filter\NotificationsFilter;
use App\Form\Type\SearchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class NotificationsController extends ListController
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
     * @Route("/admin/notifications/{page}", name="admin notifications", requirements={"page"="\d+"})
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

        $notifications = $this->entityManager->getRepository(Notification::class)->fetchAll($filter, $sort, $paging);
        $notificationsCount = $this->entityManager->getRepository(Notification::class)->countAll($filter);

        return $this->render('pages/notifications.html.twig', [
            'notifications' => $notifications,
            'notificationsCount' => $notificationsCount,
            'page' => $page,
            'searchForm' => $searchForm->createView(),
            'pagesCount' => ceil($notificationsCount / $paging->getLimit())
        ]);
    }

    private function initFilter(Request $request)
    {
        $filter = new NotificationsFilter();
        $filter->setKeyword($request->query->get('keyword', null));
        $filter->setApplicationId($request->query->get('applicationId', null));
        $filter->setSubscriberId($request->query->get('subscriberId', null));
        return $filter;
    }
}