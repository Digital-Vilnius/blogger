<?php

namespace App\Controller;

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

        return $this->render('pages/subscriber-add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/subscriber/{id}/edit", name="admin subscriber edit", requirements={"id"="\d+"})
     * @param Request $request
     * @param Subscriber $subscriber
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

        return $this->render('pages/subscriber-edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/subscriber/{id}/toggle-email", name="admin subscriber toggle email", requirements={"id"="\d+"})
     * @param Request $request
     * @param Subscriber $subscriber
     * @return RedirectResponse|Response
     */
    public function toggleEmailNotification(Request $request, Subscriber $subscriber)
    {
        $subscriber->setEmailNotification(!$subscriber->getEmailNotification());
        $this->entityManager->flush();
        $this->addFlash('success', $this->translator->trans('subscriber_email_notification_is_successfully_updated'));
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/admin/subscriber/{id}/toggle-sms", name="admin subscriber toggle sms", requirements={"id"="\d+"})
     * @param Request $request
     * @param Subscriber $subscriber
     * @return RedirectResponse|Response
     */
    public function toggleSmsNotification(Request $request, Subscriber $subscriber)
    {
        $subscriber->setSmsNotification(!$subscriber->getSmsNotification());
        $this->entityManager->flush();
        $this->addFlash('success', $this->translator->trans('subscriber_sms_notification_is_successfully_updated'));
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/admin/subscriber/{id}/delete", name="admin subscriber delete", requirements={"id"="\d+"})
     * @param Request $request
     * @param Subscriber $subscriber
     * @return RedirectResponse|Response
     */
    public function delete(Request $request, Subscriber $subscriber)
    {
        if ($request->getMethod() === Request::METHOD_POST) {
            $this->entityManager->remove($subscriber);
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('subscriber_is_successfully_deleted'));
            return $this->redirectToRoute('admin subscribers');
        }

        return $this->render('pages/subscriber-delete.html.twig', [
            'subscriber' => $subscriber
        ]);
    }
}