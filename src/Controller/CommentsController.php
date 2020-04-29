<?php

namespace App\Controller;

use App\Controller\Base\ListController;
use App\Entity\Blog;
use App\Entity\Comment;
use App\Entity\Post;
use App\Filter\CommentsFilter;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class CommentsController extends ListController
{
    private $entityManager;
    private $translator;

    public function __construct(ValidatorInterface $validator, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        parent::__construct($validator);
        $this->entityManager = $entityManager;
        $this->translator = $translator;
    }

    /**
     * @Route("/blog/{blogId}/post/{postId}/comments/{page}", name="blog post comments")
     * @Entity("blog", expr="repository.fetchUserBlog(blogId)")
     * @Entity("post", expr="repository.fetchUserBlogPost(blogId, postId)")
     * @param Request $request
     * @param Blog $blog
     * @param Post $post
     * @param int $page
     * @return Response
     */
    public function fetch(Request $request, Blog $blog, Post $post, int $page = 1)
    {
        $filter = $this->initFilter($request);
        $sort = $this->initSort($request);
        $paging = $this->initPaging($request);

        $comments = $this->entityManager->getRepository(Comment::class)->fetchAll($filter, $sort, $paging);
        $commentsCount = $this->entityManager->getRepository(Comment::class)->countAll($filter);

        return $this->render('user/pages/comments.html.twig', [
            'comments' => $comments,
            'commentsCount' => $commentsCount,
            'page' => $page,
            'blog' => $blog,
            'post' => $post,
            'pagesCount' => ceil($commentsCount / $paging->getLimit())
        ]);
    }

    private function initFilter(Request $request)
    {
        $filter = new CommentsFilter();
        $filter->setKeyword($request->query->get('keyword', null));
        $filter->setPostId($request->attributes->get('postId', null));
        return $filter;
    }
}