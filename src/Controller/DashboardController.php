<?php

namespace App\Controller;

use App\Controller\Base\ListController;
use Doctrine\ORM\EntityManagerInterface;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class DashboardController extends ListController
{
    private $entityManager;
    private $translator;

    public function __construct(ValidatorInterface $validator, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        parent::__construct($validator);
        $this->translator = $translator;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/admin/dashboard", name="admin dashboard")
     * @return Response
     */
    public function dashboard()
    {
        return $this->render('pages/dashboard.html.twig');
    }
}