<?php

namespace User\Handler;

use User\TestAsset\MockRepository;
use User\UserValidator;

class NotFoundTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Create
     */
    private $h;

    protected function setUp()
    {
        $this->h = new Create(new MockRepository(), new UserValidator());
    }
}