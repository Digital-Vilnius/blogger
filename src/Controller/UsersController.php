<?php

namespace App\Controller;

use App\Controller\Base\ListController;
use App\Entity\User;
use App\Filter\UsersFilter;
use App\Form\Type\SearchType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UsersController extends ListController
{
    private $entityManager;

    public function __construct(ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        parent::__construct($validator);
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/admin/users/{page}", name="admin users")
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

        $users = $this->entityManager->getRepository(User::class)->fetchAll($filter, $sort, $paging);
        $usersCount = $this->entityManager->getRepository(User::class)->countAll($filter);

        return $this->render('pages/users.html.twig', [
            'users' => $users,
            'usersCount' => $usersCount,
            'page' => $page,
            'searchForm' => $searchForm->createView(),
            'pagesCount' => ceil($usersCount / $paging->getLimit())
        ]);
    }

    private function initFilter(Request $request)
    {
        $filter = new UsersFilter();
        $filter->setKeyword($request->query->get('keyword', null));
        return $filter;
    }
}