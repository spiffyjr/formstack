<?php

namespace User\Handler;

use User\TestAsset\MockRepository;
use User\UserValidator;
use Zend\Diactoros\ServerRequest;

class NotFoundTest extends \PHPUnit_Framework_TestCase
{
    public function testInvoke()
    {
        $h = new NotFound();
        $req = new ServerRequest();

        $res = $h->__invoke($req);
        $content = json_decode($res->getBody()->getContents(), true);

        $this->assertSame(404, $res->getStatusCode());
        $this->assertArrayHasKey('data', $content);
        $this->assertSame('Not Found', $content['data']);
    }
}
