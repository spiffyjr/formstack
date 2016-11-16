<?php

namespace User\Handler;

use Psr\Http\Message;
use User\JsonResponse;
use User\UserRepository;
use User\UserValidator;

class Create implements Handler
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
     * Create constructor.
     * @param UserRepository $userRepository
     * @param UserValidator $userValidator
     */
    public function __construct(UserRepository $userRepository, UserValidator $userValidator)
    {
        $this->userRepository = $userRepository;
        $this->userValidator = $userValidator;
    }

    /**
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

        if (!$this->userValidator->validate($data)) {
            return new JsonResponse($this->userValidator->getMessages(), 400);
        }

        return new JsonResponse($this->userRepository->create($data));
    }
}
