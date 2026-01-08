<?php

namespace App\Contracts\User;

use App\Models\User;

interface UserRepoInterface
{
    /**
     * Create a new User.
     *
     * @param array{name: string, email: string, password: string} $data
     * @return User
     */
    public function create(array $data): User;

}
