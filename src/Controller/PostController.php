<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\Post;
use App\Form\Type\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class PostController extends AbstractController
{
    protected $entityManager;
    protected $translator;

    public function __construct(EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
    }

    /**
     * @Route("/blog/{blogId}/post/add", name="blog post add")
     * @Entity("blog", expr="repository.fetchUserBlog(blogId)")
     * @param Request $request
     * @param Blog $blog
     * @return RedirectResponse|Response
     */
    public function add(Request $request, Blog $blog)
    {
        $post = new Post();
        $post->setBlog($blog);
        $form = $this->createForm(PostType::class, $post, ['isAdmin' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($post);
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('post_is_successfully_added'));
            return $this->redirectToRoute('blog posts', ['blogId' => $blog->getId()]);
        }

        return $this->render('user/pages/post-add.html.twig', [
            'form' => $form->createView(),
            'blog' => $blog,
        ]);
    }

    /**
     * @Route("/blog/{blogId}/post/{id}/edit", name="blog post edit")
     * @Entity("blog", expr="repository.fetchUserBlog(blogId)")
     * @Entity("post", expr="repository.fetchUserPost(id)")
     * @param Request $request
     * @param Post $post
     * @param Blog $blog
     * @return RedirectResponse|Response
     */
    public function edit(Request $request, Post $post, Blog $blog)
    {
        $form = $this->createForm(PostType::class, $post, ['isAdmin' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('post_is_successfully_edited'));
            return $this->redirectToRoute('blog posts', ['blogId' => $blog->getId()]);
        }

        return $this->render('user/pages/post-edit.html.twig', [
            'form' => $form->createView(),
            'blog' => $blog,
            'post' => $post,
        ]);
    }

    /**
     * @Route("/blog/{blogId}/post/{id}/visibility", name="blog post visibility", methods={"POST"})
     * @Entity("blog", expr="repository.fetchUserBlog(blogId)")
     * @Entity("post", expr="repository.fetchUserPost(id)")
     * @param Request $request
     * @param Post $post
     * @param Blog $blog
     * @return RedirectResponse|Response
     */
    public function toggleVisibility(Request $request, Post $post, Blog $blog)
    {
        $post->setVisible(!$post->getVisible());
        $this->entityManager->flush();
        $this->addFlash('success', $this->translator->trans('post_visibility_is_successfully_updated'));
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/blog/{blogId}/post/{id}/delete", name="blog post delete")
     * @Entity("blog", expr="repository.fetchUserBlog(blogId)")
     * @Entity("post", expr="repository.fetchUserPost(id)")
     * @param Request $request
     * @param Post $post
     * @param Blog $blog
     * @return RedirectResponse|Response
     */
    public function delete(Request $request, Post $post, Blog $blog)
    {
        if ($request->getMethod() === Request::METHOD_POST) {
            $this->entityManager->remove($post);
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('post_is_successfully_deleted'));
            return $this->redirectToRoute('blog posts', ['blogId' => $blog->getId()]);
        }

        return $this->render('user/pages/post-delete.html.twig', [
            'post' => $post,
            'blog' => $blog
        ]);
    }
}