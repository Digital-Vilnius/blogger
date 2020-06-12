<?php

namespace App\Controller\Api;

use App\Contract\BaseResponse;
use App\Entity\Application;
use App\Entity\Notification;
use App\Enum\Channels;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class NotificationController extends AbstractController
{
    private $entityManager;
    private $translator;
    private $validator;

    public function __construct(EntityManagerInterface $entityManager, TranslatorInterface $translator, ValidatorInterface $validator)
    {
        $this->validator = $validator;
        $this->entityManager = $entityManager;
        $this->translator = $translator;
    }

    /**
     * @Route("/api/notify", name="admin notify")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function notify(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $application = $this->getUser();

        if ($application instanceof Application) {
            $subscribers = $application->getSubscribers();
            foreach ($subscribers as $subscriber) {
                if ($subscriber->getEmailNotification()) {
                    $notification = new Notification();
                    $notification->setHtmlContent($data["htmlContent"]);
                    $notification->setTitle($data['title']);
                    $notification->setChannel(Channels::EMAIL);
                    $notification->setSubscriber($subscriber);
                    $this->entityManager->persist($notification);
                }
                if ($subscriber->getSmsNotification()) {
                    $notification = new Notification();
                    $notification->setContent($data["content"]);
                    $notification->setChannel(Channels::SMS);
                    $notification->setSubscriber($subscriber);
                    $this->entityManager->persist($notification);
                }
            }
            $this->entityManager->flush();
        }
        return new BaseResponse();
    }
}