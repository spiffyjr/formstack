<?php

namespace User;

class UserRepository
{
    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * UserRepository constructor.
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Find a single user by id. Returns null if not found.
     * @param int $userId
     * @return array|null
     */
    public function find(int $userId)
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, email, first_name AS firstName, last_name AS lastName FROM user WHERE id = ? LIMIT 1'
        );

        if (!$stmt->execute([$userId])) {
            return $stmt->errorInfo();
        }

        if ($stmt->rowCount() == 0) {
            return null;
        }

        return $stmt->fetchAll(\PDO::FETCH_ASSOC)[0];
    }

    /**
     * Finds a list of all users.
     * @return array
     */
    public function findAll()
    {
        $stmt = $this->pdo->prepare('SELECT id, email, first_name as firstName, last_name as lastName FROM user');

        if (!$stmt->execute()) {
            return $stmt->errorInfo();
        }

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Creates a new user from input data. Assumes input data is valid
     * and contains:
     *   - firstName
     *   - lastName
     *   - email
     *   - password (unhashed)
     * @param array $data
     * @return array
     */
    public function create(array $data)
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO user (email, first_name, last_name, password) 
             VALUES (:email, :firstName, :lastName, :password)'
        );
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

        if (!$stmt->execute($data)) {
            return $stmt->errorInfo();
        }

        unset($data['password']);
        $data['id'] = $this->pdo->lastInsertId();

        return $data;
    }
}
