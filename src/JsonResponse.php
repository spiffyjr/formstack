<?php

namespace User;

use Zend\Diactoros\Response\JsonResponse as BaseJsonResponse;

class JsonResponse extends BaseJsonResponse
{
    /**
     * JsonResponse constructor. Extends the Diactoros JsonResponse and provides
     * some limited basic output structure.
     * @param mixed $data
     * @param int $status
     * @param array $headers
     * @param int $encodingOptions
     */
    public function __construct(
        $data,
        $status = 200,
        array $headers = [],
        $encodingOptions = self::DEFAULT_JSON_FLAGS
    ) {
        parent::__construct(['code' => $status, 'data' => $data], $status, $headers, $encodingOptions);
    }
}
