<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\Type\PostType;
use Doctrine\ORM\EntityManagerInterface;
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
     * @Route("/admin/post/add", name="admin post add")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function add(Request $request)
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($post);
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('post_is_successfully_added'));
            return $this->redirectToRoute('admin posts');
        }

        return $this->render('admin/pages/post-add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/post/edit/{id}", name="admin post edit")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function edit(Request $request, Post $post)
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('post_is_successfully_edited'));
            return $this->redirectToRoute('admin posts');
        }

        return $this->render('admin/pages/post-edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}