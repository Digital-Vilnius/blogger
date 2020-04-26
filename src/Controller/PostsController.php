<?php

namespace App\Controller;

use App\Controller\Base\ListController;
use App\Entity\Blog;
use App\Entity\Post;
use App\Filter\PostsFilter;
use App\Form\Type\PostsFilterType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
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
     * @Route("/blog/{blogId}/posts/{page}", name="blog posts")
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

        $filterForm = $this->createForm(PostsFilterType::class, $filter);
        $filterForm->handleRequest($request);

        $posts = $this->entityManager->getRepository(Post::class)->fetchAll($filter, $sort, $paging);
        $postsCount = $this->entityManager->getRepository(Post::class)->countAll($filter);

        return $this->render('user/pages/posts.html.twig', [
            'posts' => $posts,
            'postsCount' => $postsCount,
            'page' => $page,
            'blog' => $blog,
            'filterForm' => $filterForm->createView(),
            'pagesCount' => ceil($postsCount / $paging->getLimit())
        ]);
    }

    private function initFilter(Request $request)
    {
        $filter = new PostsFilter();
        $filter->setKeyword($request->query->get('keyword', null));
        $filter->setBlogId($request->attributes->get('blogId', null));
        return $filter;
    }
}