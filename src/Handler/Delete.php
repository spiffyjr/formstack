<?php

namespace User\Handler;

use Psr\Http\Message;
use User\JsonResponse;
use User\Repository\Repository;

class Delete implements Handler
{
    /**
     * @var Repository
     */
    private $userRepository;

    /**
     * @var int
     */
    private $userId;

    /**
     * @param Repository $userRepository
     * @param int $userId
     * @internal param UserValidator $userValidator
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
        $result = $this->userRepository->delete($this->userId);

        if (true === $result) {
            return new JsonResponse(null, 204);
        }

        return new JsonResponse('Not Found', 404);
    }
}
