<?php

namespace App\Controller\Api;

use App\Controller\Base\ListController;
use App\Entity\Post;
use App\Entity\Tag;
use App\Filter\PostsFilter;
use App\Filter\TagsFilter;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/api/blog/{blogId}/tags", name="api get blog tags", methods={"GET"})
     * @Entity("blog", expr="repository.fetchUserBlog(blogId)")
     * @param Request $request
     * @return JsonResponse
     */
    public function fetch(Request $request)
    {
        $filter = $this->initFilter($request);
        $sort = $this->initSort($request);
        $paging = $this->initApiPaging($request);

        $tags = $this->entityManager->getRepository(Tag::class)->fetchAll($filter, $sort, $paging);
        $tagsCount = $this->entityManager->getRepository(Tag::class)->countAll($filter);
        return new JsonResponse(['result' => $tags, 'count' => $tagsCount]);
    }

    private function initFilter(Request $request)
    {
        $filter = new TagsFilter();
        $filter->setKeyword($request->query->get('keyword', null));
        $filter->setBlogId($request->attributes->get('blogId', null));
        return $filter;
    }
}