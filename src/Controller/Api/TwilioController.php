<?php

namespace App\Controller\Api;

use App\Contract\BaseResponse;
use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class TwilioController extends AbstractController
{
    protected $entityManager;
    protected $translator;

    public function __construct(EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
    }

    /**
     * @Route("/admin/twilio/status", name="admin twilio status", methods={"POST"})
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function updateStatus(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $sid = $data['MessageSid'];
        $status = $data['MessageStatus'];
        $notification = $this->entityManager->getRepository(Notification::class)->findOneBy(['sid' => $sid]);
        $notification->setStatus($status);
        return new BaseResponse();
    }
}