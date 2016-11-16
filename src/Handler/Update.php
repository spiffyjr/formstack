<?php

namespace User\Handler;

use Psr\Http\Message;
use User\JsonResponse;
use User\UserRepository;
use User\UserValidator;

class Update implements Handler
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserValidator
     */
    private $userValidator;

    /**
     * @var int
     */
    private $userId;

    /**
     * Create constructor.
     * @param UserRepository $userRepository
     * @param UserValidator $userValidator
     * @param int $userId
     */
    public function __construct(UserRepository $userRepository, UserValidator $userValidator, int $userId)
    {
        $this->userRepository = $userRepository;
        $this->userValidator = $userValidator;
        $this->userId = $userId;
    }

    /**
     * Fulfills Handler contract.
     * @param Message\ServerRequestInterface $request
     * @return Message\ResponseInterface
     */
    public function __invoke(Message\ServerRequestInterface $request) : Message\ResponseInterface
    {
        // warning suppression is intentional and handled below
        $data = @json_decode($request->getBody()->getContents(), true);

        if (json_last_error()) {
            return new JsonResponse(json_last_error_msg(), 400);
        }

        if (!$this->userValidator->validate($data, false)) {
            return new JsonResponse($this->userValidator->getMessages(), 400);
        }

        return new JsonResponse($this->userRepository->update($this->userId, $data));
    }
}
