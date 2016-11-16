<?php

namespace User;

class JsonResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testMessageInjected()
    {
        $json = new JsonResponse('not found', 404);
        $this->assertSame(
            $json->getBody()->getContents(),
            json_encode(['code' => 404, 'data' => 'not found'])
        );
    }
}