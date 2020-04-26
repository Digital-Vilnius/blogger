<?php

namespace App\Controller\Admin;

use App\Controller\Base\ListController;
use App\Entity\Blog;
use App\Filter\BlogsFilter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BlogsController extends ListController
{
    private $entityManager;

    public function __construct(ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        parent::__construct($validator);
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/admin/blogs/{page}", name="admin blogs")
     * @param Request $request
     * @param int $page
     * @return Response
     */
    public function fetch(Request $request, int $page = 1)
    {
        $filter = $this->initFilter($request);
        $sort = $this->initSort($request);
        $paging = $this->initPaging($request);

        $blogs = $this->entityManager->getRepository(Blog::class)->fetchAll($filter, $sort, $paging);
        $blogsCount = $this->entityManager->getRepository(Blog::class)->countAll($filter);

        return $this->render('admin/pages/blogs.html.twig', [
            'blogs' => $blogs,
            'blogsCount' => $blogsCount,
            'page' => $page,
            'pagesCount' => ceil($blogsCount / $paging->getLimit())
        ]);
    }

    private function initFilter(Request $request)
    {
        $filter = new BlogsFilter();
        $filter->setKeyword($request->query->get('keyword', null));
        $filter->setUserId($request->query->get('userId', null));
        return $filter;
    }
}