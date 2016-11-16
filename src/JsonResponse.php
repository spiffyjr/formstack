<?php

namespace User;

use Zend\Diactoros\Response\JsonResponse as BaseJsonResponse;

class JsonResponse extends BaseJsonResponse
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