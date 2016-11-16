<?php

namespace User\Handler;

use User\TestAsset\JsonStream;
use User\TestAsset\MockRepository;
use User\UserValidator;
use Zend\Diactoros\ServerRequest;

class UpdateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Create
     */
    private $h;

    public function testInvokeWithJsonError()
    {
        $r = new ServerRequest();
        $res = $this->h->__invoke($r);

        $this->assertSame(400, $res->getStatusCode());
        $this->assertSame('{"code":400,"data":"Syntax error"}', $res->getBody()->getContents());
    }

    public function testInvokeWithFailedValidation()
    {
        $r = new ServerRequest([], [], null, null, new JsonStream(['firstName' => 'foo']));
        $res = $this->h->__invoke($r);

        $this->assertSame(400, $res->getStatusCode());
        $this->assertSame(
            '{"code":400,"data":["lastName is required","email is required"]}',
            $res->getBody()->getContents()
        );
    }

    public function testInvokeSuccess()
    {
        $body = new JsonStream([
            'firstName' => 'foo',
            'lastName' => 'bar',
            'email' => 'foo@bar.com',
            'password' => 'foobar'
        ]);
        $r = new ServerRequest([], [], null, null, $body);
        $res = $this->h->__invoke($r);

        $this->assertSame(200, $res->getStatusCode());
        $this->assertSame(
            '{"code":200,"data":{"id":"1","firstName":"foo","lastName":"bar","email":"foo@bar.com"}}',
            $res->getBody()->getContents()
        );
    }

    public function testInvokeNotFound()
    {
        $this->h = new Update(new MockRepository(true), new UserValidator(), 1);
        $body = new JsonStream([
            'firstName' => 'foo',
            'lastName' => 'bar',
            'email' => 'foo@bar.com',
            'password' => 'foobar'
        ]);
        $r = new ServerRequest([], [], null, null, $body);
        $res = $this->h->__invoke($r);

        $this->assertSame(404, $res->getStatusCode());
        $this->assertSame('{"code":404,"data":"Not Found"}', $res->getBody()->getContents());
    }

    protected function setUp()
    {
        $this->h = new Update(new MockRepository(), new UserValidator(), 1);
    }
}
