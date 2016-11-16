<?php

namespace User\Handler;

use User\TestAsset\MockRepository;
use Zend\Diactoros\ServerRequest;

class DeleteTest extends \PHPUnit_Framework_TestCase
{
    public function testDeleteFailed()
    {
        $repo = new MockRepository(true);
        $h = new Delete($repo, 1);

        $r = new ServerRequest();
        $res = $h->__invoke($r);

        $this->assertSame(404, $res->getStatusCode());
        $this->assertSame('{"code":404,"data":"Not Found"}', $res->getBody()->getContents());
    }

    public function testDeleteSucceeded()
    {
        $repo = new MockRepository();
        $h = new Delete($repo, 1);

        $r = new ServerRequest();
        $res = $h->__invoke($r);

        $this->assertSame(204, $res->getStatusCode());
    }
}
