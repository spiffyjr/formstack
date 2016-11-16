<?php

namespace User\TestAsset;

use User\Repository\Repository;

class MockRepository implements Repository
{
    /**
     * @var bool
     */
    private $shouldFail;

    /**
     * MockRepository constructor.
     * @param bool $shouldFail
     */
    public function __construct($shouldFail = false)
    {
        $this->shouldFail = $shouldFail;
    }

    /**
     * Deletes a user by id. Returns null on success,.
     * @param int $userId
     * @return null
     */
    public function delete(int $userId)
    {
        if ($this->shouldFail) {
            return false;
        }
        return true;
    }

    /**
     * Find a single user by id. Returns null if not found.
     * @param int $userId
     * @return array|null
     */
    public function find(int $userId)
    {
        return ['id' => '1', 'firstName' => 'foo', 'lastName' => 'bar', 'email' => 'foo@bar.com'];
    }

    /**
     * Finds a list of all users.
     * @return array
     */
    public function findAll()
    {
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
        return ['id' => '1', 'firstName' => 'foo', 'lastName' => 'bar', 'email' => 'foo@bar.com'];
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
    }
}