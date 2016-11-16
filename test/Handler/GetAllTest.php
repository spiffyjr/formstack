<?php

namespace User\Handler;

use User\TestAsset\MockRepository;
use User\UserValidator;
use Zend\Diactoros\ServerRequest;

class GetAllTest extends \PHPUnit_Framework_TestCase
{
    public function testInvoke()
    {
        $repo = new MockRepository();

        $h = new GetAll($repo);
        $req = new ServerRequest();
        $res = $h->__invoke($req);

        $content = json_decode($res->getBody()->getContents(), true);

        $this->assertSame(200, $res->getStatusCode());
        $this->assertArrayHasKey('data', $content);
        $this->assertSame($repo->findAll(), $content['data']);
    }
}
