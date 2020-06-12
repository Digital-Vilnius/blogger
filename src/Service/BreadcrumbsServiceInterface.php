<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

interface BreadcrumbsServiceInterface
{
    public function getBreadcrumbs(Request $request): array;
}