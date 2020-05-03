<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\Category;
use App\Form\Type\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class CategoryController extends AbstractController
{
    protected $entityManager;
    protected $translator;

    public function __construct(EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
    }

    /**
     * @Route("/blog/{blogId}/category/add", name="blog category add")
     * @Entity("blog", expr="repository.fetchUserBlog(blogId)")
     * @param Request $request
     * @param Blog $blog
     * @return RedirectResponse|Response
     */
    public function add(Request $request, Blog $blog)
    {
        $category = new Category();
        $category->setBlog($blog);
        $form = $this->createForm(CategoryType::class, $category, ['isAdmin' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($category);
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('category_is_successfully_added'));
            return $this->redirectToRoute('blog categories', ['blogId' => $blog->getId()]);
        }

        return $this->render('user/pages/category-add.html.twig', [
            'form' => $form->createView(),
            'blog' => $blog
        ]);
    }

    /**
     * @Route("/blog/{blogId}/category/{id}/edit", name="blog category edit")
     * @Entity("blog", expr="repository.fetchUserBlog(blogId)")
     * @Entity("category", expr="repository.fetchUserCategory(id)")
     * @param Request $request
     * @param Blog $blog
     * @param Category $category
     * @return RedirectResponse|Response
     */
    public function edit(Request $request, Blog $blog, Category $category)
    {
        $form = $this->createForm(CategoryType::class, $category, ['isAdmin' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('category_is_successfully_edited'));
            return $this->redirectToRoute('blog categories', ['blogId' => $blog->getId()]);
        }

        return $this->render('user/pages/category-edit.html.twig', [
            'form' => $form->createView(),
            'blog' => $blog,
            'category' => $category
        ]);
    }

    /**
     * @Route("/blog/{blogId}/category/{id}/delete", name="blog category delete")
     * @Entity("blog", expr="repository.fetchUserBlog(blogId)")
     * @Entity("category", expr="repository.fetchUserCategory(id)")
     * @param Request $request
     * @param Category $category
     * @param Blog $blog
     * @return RedirectResponse|Response
     */
    public function delete(Request $request, Category $category, Blog $blog)
    {
        if ($request->getMethod() === Request::METHOD_POST) {
            $this->entityManager->remove($category);
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('category_is_successfully_deleted'));
            return $this->redirectToRoute('blog categories', ['blogId' => $blog->getId()]);
        }

        return $this->render('user/pages/category-delete.html.twig', [
            'category' => $category,
            'blog' => $blog
        ]);
    }
}