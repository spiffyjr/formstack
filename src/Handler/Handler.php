<?php

namespace User\Handler;

use Psr\Http\Message;

interface Handler
{
    public function __invoke(Message\ServerRequestInterface $request) : Message\ResponseInterface;
}