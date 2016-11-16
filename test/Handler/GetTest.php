<?php

namespace User\Handler;

use User\TestAsset\MockRepository;
use Zend\Diactoros\ServerRequest;

class GetTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccess()
    {
        $repo = new MockRepository();

        $h = new Get($repo, 1);
        $req = new ServerRequest();
        $res = $h->__invoke($req);

        $content = json_decode($res->getBody()->getContents(), true);

        $this->assertSame(200, $res->getStatusCode());
        $this->assertArrayHasKey('data', $content);
        $this->assertSame($repo->find(1), $content['data']);
    }

    public function testFailure()
    {
        $repo = new MockRepository(true);

        $h = new Get($repo, 1);
        $req = new ServerRequest();
        $res = $h->__invoke($req);

        $this->assertSame(404, $res->getStatusCode());
    }
}
