<?php

namespace App\Controller;

use App\Controller\Base\ListController;
use App\Entity\Application;
use App\Filter\ApplicationsFilter;
use App\Form\Type\ApplicationsSearchType;
use App\Form\Type\SearchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApplicationsController extends ListController
{
    private $entityManager;

    public function __construct(ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        parent::__construct($validator);
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/admin/applications/{page}", name="admin applications", requirements={"page"="\d+"})
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

        $applications = $this->entityManager->getRepository(Application::class)->fetchAll($filter, $sort, $paging);
        $applicationsCount = $this->entityManager->getRepository(Application::class)->countAll($filter);

        return $this->render('pages/applications.html.twig', [
            'applications' => $applications,
            'applicationsCount' => $applicationsCount,
            'page' => $page,
            'searchForm' => $searchForm->createView(),
            'pagesCount' => ceil($applicationsCount / $paging->getLimit())
        ]);
    }

    private function initFilter(Request $request)
    {
        $filter = new ApplicationsFilter();
        $filter->setKeyword($request->query->get('keyword', null));
        return $filter;
    }
}