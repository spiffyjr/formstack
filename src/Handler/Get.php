<?php

namespace User\Handler;

use Psr\Http\Message;
use User\JsonResponse;
use User\Repository\Repository;

class Get implements Handler
{
    /**
     * @var Pdo
     */
    private $userRepository;

    /**
     * @var int
     */
    private $userId;

    /**
     * Get constructor.
     * @param Repository $userRepository
     * @param int $userId
     */
    public function __construct(Repository $userRepository, int $userId)
    {
        $this->userRepository = $userRepository;
        $this->userId = $userId;
    }

    /**
     * Fulfills Handler contract.
     * @param Message\ServerRequestInterface $request
     * @return Message\ResponseInterface
     */
    public function __invoke(Message\ServerRequestInterface $request) : Message\ResponseInterface
    {
        $user = $this->userRepository->find($this->userId);

        if ($user === null) {
            return new JsonResponse('Not Found', 404);
        }

        return new JsonResponse($user);
    }
}