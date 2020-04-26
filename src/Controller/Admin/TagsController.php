<?php

namespace App\Controller\Admin;

use App\Controller\Base\ListController;
use App\Entity\Blog;
use App\Entity\Tag;
use App\Filter\TagsFilter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TagsController extends ListController
{
    private $entityManager;

    public function __construct(ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        parent::__construct($validator);
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/admin/tags/{page}", name="admin tags")
     * @param Request $request
     * @param int $page
     * @return Response
     */
    public function fetch(Request $request, int $page = 1)
    {
        $filter = $this->initFilter($request);
        $sort = $this->initSort($request);
        $paging = $this->initPaging($request);

        $tags = $this->entityManager->getRepository(Tag::class)->fetchAll($filter, $sort, $paging);
        $tagsCount = $this->entityManager->getRepository(Tag::class)->countAll($filter);

        return $this->render('admin/pages/tags.html.twig', [
            'tags' => $tags,
            'tagsCount' => $tagsCount,
            'page' => $page,
            'pagesCount' => ceil($tagsCount / $paging->getLimit())
        ]);
    }

    private function initFilter(Request $request)
    {
        $filter = new TagsFilter();
        $filter->setKeyword($request->query->get('keyword', null));
        $filter->setBlogId($request->query->get('blogId', null));
        return $filter;
    }
}