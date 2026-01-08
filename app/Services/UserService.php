<?php

namespace App\Services;

use App\Models\User;
use App\Contracts\User\UserRepoInterface;
use App\Contracts\User\UserServiceInterface;
use App\Events\UserRegistered;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Hash;

class UserService implements UserServiceInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public UserRepoInterface $userRepo
    ) {}

    
    /**
     * Create a new user.
     *
     * @param array{name: string, email: string, password: string} $data
     * @return User
     * @throws Exception
     */
    public function createUser(array $data): User
    {
        try {
            DB::beginTransaction();

            $data['password'] = Hash::make($data['password']);
            // Create User via Repository
            $user = $this->userRepo->create($data);

            DB::commit();

            event(new UserRegistered($user));

            return $user;

        } catch (Exception $e) {
            
            DB::rollBack();
            
            // Re-throw the exception so the Controller can handle the error response
            throw $e;
        }
    }
}
