<?php

namespace User;

class UserValidator
{
    /**
     * @var array
     */
    private $messages = [];

    /**
     * Simple validator that ensures all fields are present. Does not test validity, length, etc.
     *
     * @param array $data
     * @param bool $passwordRequired
     * @return bool
     */
    public function validate(array $data, bool $passwordRequired = true)
    {
        foreach (['firstName', 'lastName', 'email', 'password'] as $required) {
            if (!isset($data[$required]) && ($passwordRequired || $required !== 'password')) {
                $this->messages[] = sprintf('%s is required', $required);
            }
        }

        return empty($this->messages);
    }

    /**
     * Get list of error messages if any.
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }
}