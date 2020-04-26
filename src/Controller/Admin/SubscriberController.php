<?php

namespace App\Controller\Admin;

use App\Entity\Subscriber;
use App\Form\Type\SubscriberType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class SubscriberController extends AbstractController
{
    protected $entityManager;
    protected $translator;

    public function __construct(EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
    }

    /**
     * @Route("/admin/subscriber/add", name="admin subscriber add")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function add(Request $request)
    {
        $subscriber = new Subscriber();
        $form = $this->createForm(SubscriberType::class, $subscriber);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($subscriber);
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('subscriber_is_successfully_added'));
            return $this->redirectToRoute('admin subscribers');
        }

        return $this->render('admin/pages/subscriber-add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/subscriber/edit/{id}", name="admin subscriber edit")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function edit(Request $request, Subscriber $subscriber)
    {
        $form = $this->createForm(SubscriberType::class, $subscriber);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('subscriber_is_successfully_edited'));
            return $this->redirectToRoute('admin subscribers');
        }

        return $this->render('admin/pages/subscriber-edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}