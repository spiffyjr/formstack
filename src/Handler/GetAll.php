<?php

namespace User\Handler;

use Psr\Http\Message;
use User\JsonResponse;
use User\UserRepository;

class GetAll implements Handler
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * GetAll constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param Message\ServerRequestInterface $request
     * @return Message\ResponseInterface
     */
    public function __invoke(Message\ServerRequestInterface $request) : Message\ResponseInterface
    {
        return new JsonResponse($this->userRepository->findAll());
    }
}