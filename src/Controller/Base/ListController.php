<?php

namespace App\Controller\Base;

use App\Model\Paging;
use App\Model\Sort;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ListController extends AbstractController
{
    protected $validator;
    protected $limit;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
        $this->limit = 20;
    }

    protected function initApiPaging(Request $request)
    {
        $paging = new Paging();
        $paging->setLimit($request->query->get('limit', $this->limit));
        $paging->setOffset($request->query->get('offset', 0));

        $errors = $this->validator->validate($paging);
        if (count($errors) > 0) return new JsonResponse($errors);

        return $paging;
    }

    protected function initPaging(Request $request)
    {
        $paging = new Paging();
        $paging->setLimit($request->query->get('limit', $this->limit));
        $paging->setOffset(($request->attributes->get('page', 1) - 1) * $this->limit);

        $errors = $this->validator->validate($paging);
        if (count($errors) > 0) return new JsonResponse($errors);

        return $paging;
    }

    protected function initSort(Request $request)
    {
        $sort = new Sort();
        $sort->setColumn($request->query->get('sortColumn', 'created'));
        $sort->setType($request->query->get('sortType', 'desc'));

        $errors = $this->validator->validate($sort);
        if (count($errors) > 0) return new JsonResponse($errors);

        return $sort;
    }
}