<?php

namespace User;

class UserValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UserValidator
     */
    private $v;

    public function testValidateFailsWithInvalidData()
    {
        $result = $this->v->validate(['firstName' => 'foo', 'lastName' => 'bar']);
        $this->assertFalse($result);
    }

    public function testValidatePassesWithValidData()
    {
        $result = $this->v->validate([
            'firstName' => 'foo',
            'lastName' => 'bar',
            'email' => 'foo@bar.com',
            'password' => 'abc'
        ]);
        $this->assertTrue($result);
    }

    public function testValidatePassesWithNoPasswordWhenNotRequired()
    {
        $result = $this->v->validate([
            'firstName' => 'foo',
            'lastName' => 'bar',
            'email' => 'foo@bar.com',
        ], false);
        $this->assertTrue($result);
    }

    public function testGetMessages()
    {
        $this->assertEmpty($this->v->getMessages());
        $this->v->validate(['foo' => 'bar']);

        $this->assertCount(4, $this->v->getMessages());
    }

    protected function setUp()
    {
        $this->v = new UserValidator();
    }
}
