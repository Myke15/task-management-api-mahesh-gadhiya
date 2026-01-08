<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Contracts\User\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Exception;

class AuthController extends Controller
{
    /**
     * AuthController constructor.
     *
     * @param UserServiceInterface $userService
     */
    public function __construct(
        public UserServiceInterface $userService
    ) { }

    /**
     * Registration API
     * @param RegistrationRequest $request
     * @return JsonResponse
     */
    public function register(RegistrationRequest $request): JsonResponse 
    {
        try {

            $this->userService->createUser($request->validated());

            return $this->responseSuccess('Registration success.');


        } catch (Exception $e) {

            Log::error('Error While Registering User', [$e->getMessage(), $e->getTraceAsString()]);

            return $this->responseInternalServerError('Unable to register user, please try again later!');

        }
    }

    /**
     * Login API
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse 
    {
        try {

            $credentials = $request->validated();

            if (!Auth::attempt($credentials)) {
                return $this->responseUnauthorized('Invalid credentials or unauthorized access');
            }

            $user = Auth::user();
            
            $token = $user->createToken('API-TOKEN')->plainTextToken;

            return $this->responseSuccessWithData([
                'message'   => 'Welcome to ' . config('app.name'),
                'token'     => $token 
            ]);

        } catch (Exception $e) {
            
            Log::error('Error While Authenticating User', [$e->getMessage(), $e->getTraceAsString()]);

            return $this->responseInternalServerError('Unable to authentice user, please try again later!');

        }
        
    }
}
