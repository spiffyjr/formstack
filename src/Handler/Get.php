<?php

namespace User\Handler;

use Psr\Http\Message;
use User\JsonResponse;
use User\UserRepository;

class Get implements Handler
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var int
     */
    private $userId;

    /**
     * Get constructor.
     * @param UserRepository $userRepository
     * @param int $userId
     */
    public function __construct(UserRepository $userRepository, int $userId)
    {
        $this->userRepository = $userRepository;
        $this->userId = $userId;
    }

    public function __invoke(Message\ServerRequestInterface $request) : Message\ResponseInterface
    {
        $user = $this->userRepository->find($this->userId);

        if ($user === null) {
            return new JsonResponse('Not Found', 404);
        }

        return new JsonResponse($user);
    }
}