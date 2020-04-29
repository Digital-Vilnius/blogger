<?php

namespace App\Controller;

use App\Controller\Base\ListController;
use App\Form\Type\NotificationsTypes;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class NotificationsController extends ListController
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
     * @Route("/notifications", name="notifications")
     * @param Request $request
     * @return Response
     */
    public function fetch(Request $request)
    {
        $form = $this->createForm(NotificationsTypes::class, $this->getUser());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('notifications_are_successfully_updated'));
            return $this->redirectToRoute('notifications');
        }

        return $this->render('user/pages/notifications.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}