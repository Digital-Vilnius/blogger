<?php

namespace App\Contract;

class ResultResponse extends BaseResponse
{
    public function __construct($result = null, int $status = 200)
    {
        parent::__construct(['result' => $result], $status);
    }
}