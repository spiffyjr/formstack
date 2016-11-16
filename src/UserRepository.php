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
     * Deletes a user by id. Returns null on success,.
     * @param int $userId
     * @return null
     */
    public function delete(int $userId)
    {
        $stmt = $this->pdo->prepare('DELETE FROM user WHERE id = ?');

        if (!$stmt->execute([$userId])) {
            return $stmt->errorInfo();
        }

        return $stmt->rowCount() > 0;
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
        $this->hashPassword($data);

        $stmt = $this->pdo->prepare(
            'INSERT INTO user (email, first_name, last_name, password) 
             VALUES (:email, :firstName, :lastName, :password)'
        );

        if (!$stmt->execute($data)) {
            return $stmt->errorInfo();
        }

        unset($data['password']);
        $data['id'] = $this->pdo->lastInsertId();

        return $data;
    }

    /**
     * Updates an existing user from input data. Assumes input data is valid
     * and contains one or more of the following:
     *   - firstName
     *   - lastName
     *   - email
     *   - password (unhashed)
     * @param int $userId
     * @param array $data
     * @return array
     */
    public function update(int $userId, array $data)
    {
        $this->hashPassword($data);

        $sql = 'UPDATE user SET ';
        foreach ($data as $key => $value) {
            $sql .= sprintf('%s=:%s, ', $this->camelToSnake($key), $key);
        }

        $data['userId'] = $userId;
        $stmt = $this->pdo->prepare(trim($sql, ', ') . ' WHERE id=:userId');

        if (!$stmt->execute($data)) {
            return $stmt->errorInfo();
        }

        if (isset($data['password'])) {
            unset($data['password']);
        }

        return $data;
    }

    /**
     * Hashes the password key in the array if it exists.
     * @param array $data
     */
    private function hashPassword(array &$data)
    {
        if (!isset($data['password'])) {
            return;
        }

        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
    }

    /**
     * Transforms camel to snake case.
     * @param string $input
     * @return string
     */
    private function camelToSnake(string $input)
    {
        $callback = function ($matches) {
            return $matches[1] . '_' . strtolower($matches[2]);
        };

        return preg_replace_callback('@([a-z])([A-Z])@', $callback, $input);
    }
}
