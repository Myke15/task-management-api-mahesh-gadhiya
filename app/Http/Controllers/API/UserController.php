<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Exception;

class UserController extends Controller
{
    /**
     * User Profile API
     * @return UserResource|JsonResponse
     */
    public function __invoke(): UserResource|JsonResponse
    {
        try {

            return new UserResource(Auth::user());

        } catch (Exception $e) {

            Log::error('Error While Loading User Profile', [$e->getMessage(), $e->getTraceAsString()]);

            return $this->responseInternalServerError('Unable to load user profile, please try again later!');
        }
    }
}