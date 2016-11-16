<?php

namespace User\Handler;

use Psr\Http\Message;
use User\JsonResponse;

class NotFound implements Handler
{
    public function __invoke(Message\ServerRequestInterface $request) : Message\ResponseInterface
    {
        return new JsonResponse('Not Found');
    }
}