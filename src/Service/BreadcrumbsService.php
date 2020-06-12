<?php

namespace App\Service;

use App\Entity\Application;
use App\Entity\Subscriber;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class BreadcrumbsService implements BreadcrumbsServiceInterface
{
    private $entityManager;
    private $urlGenerator;
    private $translator;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, TranslatorInterface $translator)
    {
        $this->translator = $translator;
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
    }

    public function getBreadcrumbs(Request $request): array
    {
        $breadcrumbs = [];

        $applicationId = $request->query->get('applicationId', null);
        if ($applicationId) {
            $application = $this->entityManager->getRepository(Application::class)->find($applicationId);
            if ($application) {
                $breadcrumbs = [
                    ['link' => $this->urlGenerator->generate('admin applications'), 'title' => $this->translator->trans('applications')],
                    ['link' => $this->urlGenerator->generate('admin application', ['id' => $application->getId()]), 'title' => $application->getName()]
                ];
            }
        }

        $subscriberId = $request->query->get('subscriberId', null);
        if ($subscriberId) {
            $subscriber = $this->entityManager->getRepository(Subscriber::class)->find($subscriberId);
            if ($subscriber) {
                $application = $subscriber->getApplication();
                $breadcrumbs = [
                    ['link' => $this->urlGenerator->generate('admin applications'), 'title' => $this->translator->trans('applications')],
                    ['link' => $this->urlGenerator->generate('admin application', ['id' => $application->getId()]), 'title' => $application->getName()],
                    ['link' => $this->urlGenerator->generate('admin subscribers', []), 'title' => $subscriber->getEmail()]
                ];
            }
        }

        return $breadcrumbs;
    }
}