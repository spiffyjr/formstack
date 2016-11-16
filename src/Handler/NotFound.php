<?php

namespace User\Handler;

use Psr\Http\Message;
use User\JsonResponse;

class NotFound implements Handler
{
    /**
     * Fulfills Handler contract.
     * @param Message\ServerRequestInterface $request
     * @return Message\ResponseInterface
     */
    public function __invoke(Message\ServerRequestInterface $request) : Message\ResponseInterface
    {
        return new JsonResponse('Not Found', 404);
    }
}
