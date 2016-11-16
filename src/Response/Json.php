<?php

namespace User\Response;

use Zend\Diactoros\Response\JsonResponse;

class Json extends JsonResponse
{
    public function __construct(
        $data,
        $status = 200,
        array $headers = [],
        $encodingOptions = self::DEFAULT_JSON_FLAGS
    ) {
        parent::__construct(['code' => $status, 'data' => $data], $status, $headers, $encodingOptions);
    }
}