<?php

namespace App\Controller\Api;

use App\Contract\ListResponse;
use App\Controller\Base\ListController;
use App\Entity\Category;
use App\Filter\CategoriesFilter;
use App\Form\Type\CategoriesFilterType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/api/blog/{blogId}/categories", name="api blog categories", methods={"GET"})
     * @Entity("blog", expr="repository.fetchUserBlog(blogId)")
     * @param Request $request
     * @return ListResponse
     */
    public function fetch(Request $request)
    {
        $filter = $this->initFilter($request);
        $sort = $this->initSort($request);
        $paging = $this->initApiPaging($request);

        $filterForm = $this->createForm(CategoriesFilterType::class, $filter);
        $filterForm->handleRequest($request);

        $categories = $this->entityManager->getRepository(Category::class)->fetchAll($filter, $sort, $paging);
        $categoriesCount = $this->entityManager->getRepository(Category::class)->countAll($filter);
        return new ListResponse($categories, $categoriesCount);
    }

    private function initFilter(Request $request)
    {
        $filter = new CategoriesFilter();
        $filter->setKeyword($request->query->get('keyword', null));
        $filter->setBlogId($request->attributes->get('blogId', null));
        return $filter;
    }
}