<?php

namespace App\Controller\Admin;

use App\Entity\Tag;
use App\Entity\User;
use App\Form\Type\TagType;
use App\Form\Type\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserController extends AbstractController
{
    protected $entityManager;
    protected $translator;

    public function __construct(EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
    }

    /**
     * @Route("/admin/user/add", name="admin user add")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function add(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('user_is_successfully_added'));
            return $this->redirectToRoute('admin users');
        }

        return $this->render('admin/pages/user-add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/user/edit/{id}", name="admin user edit")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function edit(Request $request, User $user)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('user_is_successfully_edited'));
            return $this->redirectToRoute('admin users');
        }

        return $this->render('admin/pages/user-edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}