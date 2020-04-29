<?php

namespace App\Controller;

use App\Controller\Base\ListController;
use App\Entity\Blog;
use App\Entity\Category;
use App\Filter\CategoriesFilter;
use App\Form\Type\CategoriesFilterType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CategoriesController extends ListController
{
    private $entityManager;

    public function __construct(ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        parent::__construct($validator);
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/blog/{blogId}/categories/{page}", name="blog categories")
     * @Entity("blog", expr="repository.fetchUserBlog(blogId)")
     * @param Request $request
     * @param int $page
     * @param Blog $blog
     * @return Response
     */
    public function fetch(Request $request, Blog $blog, int $page = 1)
    {
        $filter = $this->initFilter($request);
        $sort = $this->initSort($request);
        $paging = $this->initPaging($request);

        $filterForm = $this->createForm(CategoriesFilterType::class, $filter);
        $filterForm->handleRequest($request);

        $categories = $this->entityManager->getRepository(Category::class)->fetchAll($filter, $sort, $paging);
        $categoriesCount = $this->entityManager->getRepository(Category::class)->countAll($filter);

        return $this->render('user/pages/categories.html.twig', [
            'categories' => $categories,
            'categoriesCount' => $categoriesCount,
            'page' => $page,
            'blog' => $blog,
            'filterForm' => $filterForm->createView(),
            'pagesCount' => ceil($categoriesCount / $paging->getLimit())
        ]);
    }

    private function initFilter(Request $request)
    {
        $filter = new CategoriesFilter();
        $filter->setKeyword($request->query->get('keyword', null));
        $filter->setBlogId($request->attributes->get('blogId', null));
        return $filter;
    }
}