<?php

namespace App\Twig;

use App\Service\BreadcrumbsServiceInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $entityManager;
    private $router;
    private $translator;
    private $breadcrumbsService;

    public function __construct(EntityManagerInterface $entityManager, TranslatorInterface $translator, UrlGeneratorInterface $router, BreadcrumbsServiceInterface $breadcrumbsService)
    {
        $this->breadcrumbsService = $breadcrumbsService;
        $this->translator = $translator;
        $this->entityManager = $entityManager;
        $this->router = $router;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('getSortRoute', [$this, 'getSortRoute']),
            new TwigFunction('getBreadcrumbs', [$this, 'getBreadcrumbs']),
            new TwigFunction('getPagingRoute', [$this, 'getPagingRoute'])
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('dateTimeFormat', [$this, 'dateTimeFormat'])
        ];
    }

    public function getSortRoute(string $column, Request $request)
    {
        $params = array_merge($request->attributes->get('_route_params'), $request->query->all());
        $sortType = $request->query->get('sortType', null);
        $sortColumn = $request->query->get('sortColumn', null);

        if ($sortColumn == $column && $sortType != null) {
            $sortType = $sortType == 'desc' ? 'asc' : 'desc';
        } else {
            $sortType = 'desc';
        }

        $params['sortColumn'] = $column;
        $params['sortType'] = $sortType;
        return $this->router->generate($request->get('_route'), $params);
    }

    public function getPagingRoute(int $page, Request $request)
    {
        $params = array_merge($request->attributes->get('_route_params'), $request->query->all());
        $params['page'] = $page;
        return $this->router->generate($request->get('_route'), $params);
    }

    public function dateTimeFormat(DateTime $dateTime = null)
    {
        if ($dateTime) return $dateTime->format('Y-m-d H:i:s');
        return $this->translator->trans('none');
    }

    public function getBreadcrumbs(Request $request)
    {
        return $this->breadcrumbsService->getBreadcrumbs($request);
    }
}