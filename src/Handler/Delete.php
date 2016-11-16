<?php

namespace User\Handler;

use Psr\Http\Message;
use User\Response\Json;

class Delete implements Handler
{
    public function __invoke(Message\ServerRequestInterface $request) : Message\ResponseInterface
    {
        return new Json('DELETE');
    }
}