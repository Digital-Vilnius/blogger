<?php

namespace App\Controller\Admin;

use App\Entity\Blog;
use App\Form\Type\BlogType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class BlogController extends AbstractController
{
    protected $entityManager;
    protected $translator;

    public function __construct(EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
    }

    /**
     * @Route("/admin/blog/add", name="admin blog add")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function add(Request $request)
    {
        $blog = new Blog();
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($blog);
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('blog_is_successfully_added'));
            return $this->redirectToRoute('admin blogs');
        }

        return $this->render('admin/pages/blog-add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/blog/edit/{id}", name="admin blog edit")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function edit(Request $request, Blog $blog)
    {
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('blog_is_successfully_edited'));
            return $this->redirectToRoute('admin blogs');
        }

        return $this->render('admin/pages/blog-edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}