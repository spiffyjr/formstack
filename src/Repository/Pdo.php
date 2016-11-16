<?php

namespace User\Repository;

class Pdo implements Repository
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
     * @return array|bool
     */
    public function delete(int $userId)
    {
        $stmt = $this->pdo->prepare('DELETE FROM user WHERE id = ?');
        $stmt->execute([$userId]);

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
        $stmt->execute([$userId]);

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Finds a list of all users.
     * @return array
     */
    public function findAll()
    {
        $stmt = $this->pdo->prepare('SELECT id, email, first_name as firstName, last_name as lastName FROM user');
        $stmt->execute();

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
        $stmt->execute($data);

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
     * @return array|bool
     */
    public function update(int $userId, array $data)
    {
        $this->hashPassword($data);

        $sql = 'UPDATE user SET ';
        foreach ($data as $key => $value) {
            $sql .= sprintf('%s=:%s, ', $this->camelToSnake($key), $key);
        }

        $data['id'] = $userId;
        $stmt = $this->pdo->prepare(trim($sql, ', ') . ' WHERE id=:id');
        $stmt->execute($data);

        if (isset($data['password'])) {
            unset($data['password']);
        }

        if ($stmt->rowCount() == 0) {
            return false;
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
