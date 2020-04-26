<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\Subscriber;
use App\Entity\Tag;
use App\Form\Type\TagType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class TagController extends AbstractController
{
    protected $entityManager;
    protected $translator;

    public function __construct(EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
    }

    /**
     * @Route("/blog/{blogId}/tag/add", name="blog tag add")
     * @Entity("blog", expr="repository.fetchUserBlog(blogId)")
     * @param Request $request
     * @param Blog $blog
     * @return RedirectResponse|Response
     */
    public function add(Request $request, Blog $blog)
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag, ['isAdmin' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tag->setBlog($blog);
            $this->entityManager->persist($tag);
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('tag_is_successfully_added'));
            return $this->redirectToRoute('blog tags', ['blogId' => $blog->getId()]);
        }

        return $this->render('user/pages/tag-add.html.twig', [
            'form' => $form->createView(),
            'blog' => $blog
        ]);
    }

    /**
     * @Route("/blog/{blogId}/tag/{id}/edit", name="blog tag edit")
     * @Entity("blog", expr="repository.fetchUserBlog(blogId)")
     * @Entity("tag", expr="repository.fetchUserTag(id)")
     * @param Request $request
     * @param Tag $tag
     * @param Blog $blog
     * @return RedirectResponse|Response
     */
    public function edit(Request $request, Tag $tag, Blog $blog)
    {
        $form = $this->createForm(TagType::class, $tag, ['isAdmin' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('tag_is_successfully_edited'));
            return $this->redirectToRoute('blog tags', ['blogId' => $blog->getId()]);
        }

        return $this->render('user/pages/tag-edit.html.twig', [
            'form' => $form->createView(),
            'blog' => $blog
        ]);
    }

    /**
     * @Route("/blog/{blogId}/tag/{id}/delete", name="blog tag delete")
     * @Entity("blog", expr="repository.fetchUserBlog(blogId)")
     * @Entity("tag", expr="repository.fetchUserTag(id)")
     * @param Request $request
     * @param Tag $tag
     * @param Blog $blog
     * @return RedirectResponse|Response
     */
    public function delete(Request $request, Tag $tag, Blog $blog)
    {
        if ($request->getMethod() === Request::METHOD_POST) {
            $this->entityManager->remove($tag);
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('tag_is_successfully_deleted'));
            return $this->redirectToRoute('blog tags', ['blogId' => $blog->getId()]);
        }

        return $this->render('user/pages/tag-delete.html.twig', [
            'tag' => $tag,
            'blog' => $blog
        ]);
    }
}