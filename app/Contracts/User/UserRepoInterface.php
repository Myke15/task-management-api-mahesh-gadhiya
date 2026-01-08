<?php

namespace App\Contracts\User;

use App\Models\User;

interface UserRepoInterface
{
    /**
     * Create a new User.
     *
     * @param array $data
     * @return User
     */
    public function create(array $data): User;

}
