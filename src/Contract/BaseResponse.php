<?php

namespace App\Contract;

use Symfony\Component\HttpFoundation\JsonResponse;

class BaseResponse extends JsonResponse
{
    public function __construct($data = null, int $status = 200)
    {
        parent::__construct($data, $status, [], false);
    }
}
