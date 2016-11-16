<?php

namespace User\Handler;

use Psr\Http\Message;
use User\UserService;
use User\Response\Json;

class GetAll implements Handler
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function __invoke(Message\ServerRequestInterface $request) : Message\ResponseInterface
    {
        return new Json($this->userService->findAll());
    }
}