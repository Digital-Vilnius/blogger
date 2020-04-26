<?php

namespace App\Controller\Admin;

use App\Entity\Blog;
use App\Entity\Tag;
use App\Form\Type\TagType;
use Doctrine\ORM\EntityManagerInterface;
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
     * @Route("/admin/tag/add", name="admin tag add")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function add(Request $request)
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($tag);
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('tag_is_successfully_added'));
            return $this->redirectToRoute('admin tags');
        }

        return $this->render('admin/pages/tag-add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/tag/edit/{id}", name="admin tag edit")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function edit(Request $request, Tag $tag)
    {
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('tag_is_successfully_edited'));
            return $this->redirectToRoute('admin tags');
        }

        return $this->render('admin/pages/tag-edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}