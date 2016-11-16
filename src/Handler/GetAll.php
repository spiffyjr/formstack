<?php

namespace User\Handler;

use Psr\Http\Message;
use User\Response\Json;

class GetAll implements Handler
{
    public function __invoke(Message\ServerRequestInterface $request) : Message\ResponseInterface
    {
        return new Json('GET ALL');
    }
}