<?php

namespace App\Contract;

class ListResponse extends BaseResponse
{
    public function __construct($result = null, int $count = 0, int $status = 200)
    {
        parent::__construct(['result' => $result, 'count' => $count], $status);
    }
}