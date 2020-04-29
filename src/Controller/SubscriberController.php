<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\Post;
use App\Entity\Subscriber;
use App\Form\Type\SubscriberType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
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
     * @Route("/blog/{blogId}/subscriber/add", name="blog subscriber add")
     * @Entity("blog", expr="repository.fetchUserBlog(blogId)")
     * @param Request $request
     * @param Blog $blog
     * @return RedirectResponse|Response
     */
    public function add(Request $request, Blog $blog)
    {
        $subscriber = new Subscriber();
        $subscriber->setBlog($blog);
        $form = $this->createForm(SubscriberType::class, $subscriber, ['isAdmin' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($subscriber);
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('subscriber_is_successfully_added'));
            return $this->redirectToRoute('blog subscribers', ['blogId' => $blog->getId()]);
        }

        return $this->render('user/pages/subscriber-add.html.twig', [
            'form' => $form->createView(),
            'blog' => $blog
        ]);
    }

    /**
     * @Route("/blog/{blogId}/subscriber/{id}/edit", name="blog subscriber edit")
     * @Entity("blog", expr="repository.fetchUserBlog(blogId)")
     * @Entity("subscriber", expr="repository.fetchUserSubscriber(id)")
     * @param Request $request
     * @param Blog $blog
     * @param Subscriber $subscriber
     * @return RedirectResponse|Response
     */
    public function edit(Request $request, Blog $blog, Subscriber $subscriber)
    {
        $form = $this->createForm(SubscriberType::class, $subscriber, ['isAdmin' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('subscriber_is_successfully_edited'));
            return $this->redirectToRoute('blog subscribers', ['blogId' => $blog->getId()]);
        }

        return $this->render('user/pages/subscriber-edit.html.twig', [
            'form' => $form->createView(),
            'blog' => $blog
        ]);
    }

    /**
     * @Route("/blog/{blogId}/subscriber/{id}/delete", name="blog subscriber delete")
     * @Entity("blog", expr="repository.fetchUserBlog(blogId)")
     * @Entity("subscriber", expr="repository.fetchUserSubscriber(id)")
     * @param Request $request
     * @param Subscriber $subscriber
     * @param Blog $blog
     * @return RedirectResponse|Response
     */
    public function delete(Request $request, Subscriber $subscriber, Blog $blog)
    {
        if ($request->getMethod() === Request::METHOD_POST) {
            $this->entityManager->remove($subscriber);
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('subscriber_is_successfully_deleted'));
            return $this->redirectToRoute('blog subscribers', ['blogId' => $blog->getId()]);
        }

        return $this->render('user/pages/subscriber-delete.html.twig', [
            'subscriber' => $subscriber,
            'blog' => $blog
        ]);
    }
}