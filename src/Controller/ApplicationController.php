<?php

namespace App\Controller;

use App\Entity\Application;
use App\Entity\Notification;
use App\Filter\NotificationsFilter;
use App\Form\Type\ApplicationType;
use App\Model\Paging;
use App\Model\Sort;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ApplicationController extends AbstractController
{
    private $entityManager;
    private $translator;

    public function __construct(EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
    }

    /**
     * @Route("/admin/application/{id}", name="admin application", requirements={"id"="\d+"})
     * @param Application $application
     * @return RedirectResponse|Response
     */
    public function fetch(Application $application)
    {
        $notificationsFilter = new NotificationsFilter();
        $notificationsFilter->setApplicationId($application->getId());

        $notificationsSort = new Sort();
        $notificationsSort->setType('DESC');
        $notificationsSort->setColumn('created');

        $notificationsPaging = new Paging();
        $notificationsPaging->setOffset(0);
        $notificationsPaging->setLimit(10);

        $notifications = $this->entityManager->getRepository(Notification::class)->fetchAll($notificationsFilter, $notificationsSort, $notificationsPaging);
        $notificationsCount = $this->entityManager->getRepository(Notification::class)->countAll($notificationsFilter);

        return $this->render('pages/application.html.twig', [
            'application' => $application,
            'notifications' => $notifications,
            'notificationsCount' => $notificationsCount
        ]);
    }

    /**
     * @Route("/admin/application/add", name="admin application add")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function add(Request $request)
    {
        $application = new Application();
        $form = $this->createForm(ApplicationType::class, $application);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($application);
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('application_is_successfully_added'));
            return $this->redirectToRoute('admin applications');
        }

        return $this->render('pages/application-add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/application/edit/{id}", name="admin application edit", requirements={"id"="\d+"})
     * @param Request $request
     * @param Application $application
     * @return RedirectResponse|Response
     */
    public function edit(Request $request, Application $application)
    {
        $form = $this->createForm(ApplicationType::class, $application);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('application_is_successfully_edited'));
            return $this->redirectToRoute('admin applications');
        }

        return $this->render('pages/application-edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/application/delete/{id}", name="admin application delete", requirements={"id"="\d+"})
     * @param Request $request
     * @param Application $application
     * @return RedirectResponse|Response
     */
    public function delete(Request $request, Application $application)
    {
        if ($request->getMethod() === Request::METHOD_POST) {
            $this->entityManager->remove($application);
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('application_is_successfully_deleted'));
            return $this->redirectToRoute('admin applications');
        }

        return $this->render('pages/application-delete.html.twig', [
            'application' => $application,
        ]);
    }
}