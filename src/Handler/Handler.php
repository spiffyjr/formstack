<?php

namespace User\Handler;

use Psr\Http\Message;

interface Handler
{
    /**
     * Invokable class that returns a PSR-7 Message\ResponseInterface.
     * @param Message\ServerRequestInterface $request
     * @return Message\ResponseInterface
     */
    public function __invoke(Message\ServerRequestInterface $request) : Message\ResponseInterface;
}