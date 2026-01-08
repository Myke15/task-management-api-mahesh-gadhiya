<?php

namespace App\Contracts\User;

use App\Models\User;

interface UserServiceInterface
{
    /**
     * Create a new user.
     *
     * @param array{name: string, email: string, password: string} $data
     * @return User
     */
    public function createUser(array $data): User;

}
