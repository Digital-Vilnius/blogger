<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Form\Type\NotificationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class NotificationController extends AbstractController
{
    protected $entityManager;
    protected $translator;

    public function __construct(EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
    }

    /**
     * @Route("/admin/notification/add", name="admin notification add")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function add(Request $request)
    {
        $notification = new Notification();
        $form = $this->createForm(NotificationType::class, $notification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($notification);
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('notification_is_successfully_added'));
            return $this->redirectToRoute('admin notifications');
        }

        return $this->render('pages/notification-add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/notification/{id}/delete", name="admin notification delete", requirements={"id"="\d+"})
     * @param Request $request
     * @param Notification $notification
     * @return RedirectResponse|Response
     */
    public function delete(Request $request, Notification $notification)
    {
        if ($request->getMethod() === Request::METHOD_POST) {
            $this->entityManager->remove($notification);
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('notification_is_successfully_deleted'));
            return $this->redirectToRoute('admin notifications');
        }

        return $this->render('pages/notification-delete.html.twig', [
            'notification' => $notification
        ]);
    }
}