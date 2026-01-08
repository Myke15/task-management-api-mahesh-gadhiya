<?php

namespace App\Repositories;

use App\Contracts\User\UserRepoInterface;
use App\Models\User;

class UserRepository implements UserRepoInterface
{
    /**
     * Create a new user.
     *
     * @param array{name: string, email: string, password: string} $data
     * @return User
     */
    public function create(array $data): User
    {
        return User::create($data);
    }
}