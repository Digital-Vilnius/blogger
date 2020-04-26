<?php

namespace App\Controller\Admin;

use App\Controller\Base\ListController;
use App\Entity\Blog;
use App\Entity\Post;
use App\Filter\PostsFilter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PostsController extends ListController
{
    private $entityManager;

    public function __construct(ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        parent::__construct($validator);
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/admin/posts/{page}", name="admin posts")
     * @param Request $request
     * @param int $page
     * @return Response
     */
    public function fetch(Request $request, int $page = 1)
    {
        $filter = $this->initFilter($request);
        $sort = $this->initSort($request);
        $paging = $this->initPaging($request);

        $posts = $this->entityManager->getRepository(Post::class)->fetchAll($filter, $sort, $paging);
        $postsCount = $this->entityManager->getRepository(Post::class)->countAll($filter);

        return $this->render('admin/pages/posts.html.twig', [
            'posts' => $posts,
            'postsCount' => $postsCount,
            'page' => $page,
            'pagesCount' => ceil($postsCount / $paging->getLimit())
        ]);
    }

    private function initFilter(Request $request)
    {
        $filter = new PostsFilter();
        $filter->setKeyword($request->query->get('keyword', null));
        $filter->setTagsSlugs($request->query->get('tagsSlugs', null));
        $filter->setBlogId($request->query->get('blogId', null));
        return $filter;
    }
}