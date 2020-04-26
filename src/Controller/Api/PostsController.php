<?php

namespace App\Controller\Api;

use App\Controller\Base\ListController;
use App\Entity\Post;
use App\Filter\PostsFilter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @Route("/api/posts", name="api get posts", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function fetch(Request $request)
    {
        $filter = $this->initFilter($request);
        $sort = $this->initSort($request);
        $paging = $this->initApiPaging($request);

        $posts = $this->entityManager->getRepository(Post::class)->fetchAll($filter, $sort, $paging);
        $postsCount = $this->entityManager->getRepository(Post::class)->countAll($filter);
        return new JsonResponse(['result' => $posts, 'count' => $postsCount]);
    }

    private function initFilter(Request $request)
    {
        $filter = new PostsFilter();
        $filter->setKeyword($request->query->get('keyword', null));
        $filter->setTagsSlugs($request->query->get('tagsSlugs', null));
        return $filter;
    }
}