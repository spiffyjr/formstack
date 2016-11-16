<?php

namespace User\Handler;

use Psr\Http\Message;
use User\JsonResponse;
use User\Repository\Repository;

class GetAll implements Handler
{
    /**
     * @var Repository
     */
    private $userRepository;

    /**
     * GetAll constructor.
     * @param Repository $userRepository
     */
    public function __construct(Repository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Fulfills Handler contract.
     * @param Message\ServerRequestInterface $request
     * @return Message\ResponseInterface
     */
    public function __invoke(Message\ServerRequestInterface $request) : Message\ResponseInterface
    {
        return new JsonResponse($this->userRepository->findAll());
    }
}
