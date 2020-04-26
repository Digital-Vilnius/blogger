<?php

namespace App\Controller;

use App\Controller\Base\ListController;
use App\Entity\Blog;
use App\Entity\Tag;
use App\Filter\TagsFilter;
use App\Form\Type\PostsFilterType;
use App\Form\Type\TagsFilterType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
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
     * @Route("/blog/{blogId}/tags/{page}", name="blog tags")
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

        $tags = $this->entityManager->getRepository(Tag::class)->fetchAll($filter, $sort, $paging);
        $tagsCount = $this->entityManager->getRepository(Tag::class)->countAll($filter);

        $filterForm = $this->createForm(TagsFilterType::class, $filter);
        $filterForm->handleRequest($request);

        return $this->render('user/pages/tags.html.twig', [
            'tags' => $tags,
            'tagsCount' => $tagsCount,
            'page' => $page,
            'blog' => $blog,
            'filterForm' => $filterForm->createView(),
            'pagesCount' => ceil($tagsCount / $paging->getLimit())
        ]);
    }

    private function initFilter(Request $request)
    {
        $filter = new TagsFilter();
        $filter->setKeyword($request->query->get('keyword', null));
        $filter->setBlogId($request->attributes->get('blogId', null));
        return $filter;
    }
}