<?php

namespace User;

class UserService
{
    private $pdo;
    
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll()
    {
        $stmt = $this->pdo->prepare('SELECT id, email, first_name, last_name FROM user');

        if (!$stmt->execute()) {
            return $stmt->errorInfo();
        }

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}