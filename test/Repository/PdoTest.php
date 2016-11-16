<?php

namespace User\Repository;

use Mockery as m;

class PdoTest extends \PHPUnit_Extensions_Database_TestCase
{
    /**
     * @var \PDO
     */
    private static $pdo;

    /**
     * @var \PHPUnit_Extensions_Database_DB_DefaultDatabaseConnection
     */
    private $conn;

    public function testFind()
    {
        $repo = new Pdo(self::$pdo);
        $res = $repo->find(1);

        $this->assertArrayHasKey('id', $res);
        $this->assertEquals(['id' => 1, 'email' => 'foo@bar.com', 'firstName' => 'foo', 'lastName' => 'bar'], $res);

        $this->assertFalse($repo->find(2));
    }

    public function testDelete()
    {
        $repo = new Pdo(self::$pdo);
        $this->assertFalse($repo->delete(2));
        $this->assertTrue($repo->delete(1));

        $this->assertTableRowCount('user', 0);
    }

    public function testFindAll()
    {
        $repo = new Pdo(self::$pdo);
        $res = $repo->findAll();

        $this->assertTableRowCount('user', count($res));
        $this->assertCount(1, $res);
        $this->assertEquals([['id' => 1, 'email' => 'foo@bar.com', 'firstName' => 'foo', 'lastName' => 'bar']], $res);
    }

    public function testCreate()
    {
        $data = ['email' => 'foo@bar.com', 'firstName' => 'foo', 'lastName' => 'bar', 'password' => 'foobar'];

        $repo = new Pdo(self::$pdo);
        $res = $repo->create($data);

        $this->assertArrayNotHasKey('password', $res);
        $this->assertTableRowCount('user', 2);
    }

    public function testUpdate()
    {
        $data = ['email' => 'foo@bar.com', 'firstName' => 'foo', 'lastName' => 'bar', 'password' => 'foo'];

        $repo = new Pdo(self::$pdo);
        $res = $repo->update(1, $data);

        $this->assertArrayNotHasKey('password', $res);
        $this->assertTableRowCount('user', 1);

        $this->assertFalse($repo->update(2, $data));
    }

    public function testUpdateWithoutPassword()
    {
        $data = ['email' => 'foo@bar.com', 'firstName' => 'foo', 'lastName' => 'bar'];

        $repo = new Pdo(self::$pdo);
        $res = $repo->update(1, $data);

        $this->assertArrayNotHasKey('password', $res);
        $this->assertTableRowCount('user', 1);
    }

    protected function getConnection()
    {
        if (!self::$pdo) {
            self::$pdo = new \PDO('sqlite::memory:');
            self::$pdo->exec(
                'DROP TABLE IF EXISTS user;' .
                'CREATE TABLE user (id, email, first_name, last_name, password);'
            );
        }

        if (!$this->conn) {
            $this->conn = $this->createDefaultDBConnection(self::$pdo);
        }

        return $this->conn;
    }

    protected function getDataSet()
    {
        return $this->createArrayDataSet([
            'user' => [
                ['id' => 1, 'email' => 'foo@bar.com', 'first_name' => 'foo', 'last_name' => 'bar']
            ]
        ]);
    }
}
