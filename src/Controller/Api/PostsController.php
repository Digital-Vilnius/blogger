<?php

namespace App\Controller\Api;

use App\Contract\ListResponse;
use App\Controller\Base\ListController;
use App\Entity\Post;
use App\Filter\PostsFilter;
use App\Form\Type\PostsFilterType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/api/blog/{blogId}/posts", name="api blog posts", methods={"GET"})
     * @Entity("blog", expr="repository.fetchUserBlog(blogId)")
     * @param Request $request
     * @return ListResponse
     */
    public function fetch(Request $request)
    {
        $filter = $this->initFilter($request);
        $sort = $this->initSort($request);
        $paging = $this->initApiPaging($request);

        $filterForm = $this->createForm(PostsFilterType::class, $filter);
        $filterForm->handleRequest($request);

        $posts = $this->entityManager->getRepository(Post::class)->fetchAll($filter, $sort, $paging);
        $postsCount = $this->entityManager->getRepository(Post::class)->countAll($filter);
        return new ListResponse($posts, $postsCount);
    }

    private function initFilter(Request $request)
    {
        $filter = new PostsFilter();
        $filter->setKeyword($request->query->get('keyword', null));
        $filter->setBlogId($request->attributes->get('blogId', null));
        $filter->setVisible(true);
        return $filter;
    }
}