<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserRescource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Função responsável pelo cadastro de um novo usuário
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = JWTAuth::fromUser($user);

        $response = [
            'user'         => new UserRescource($user),
            'access_token' => $token,
        ];

        return $this->success($response, 'User created', 201);
    }

    /**
     * Função responsável pelo login de um usuário
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (!$token = auth('api')->attempt($credentials)) {
            return $this->error('Invalid Credentials', 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Função responsável por retornar do dado de um usuário
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        return $this->success(new UserRescource(auth('api')->user()), 'User found');
    }

    /**
     * Função responsável pelo logout de um usuário
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth('api')->logout();

        return $this->success(null, "Logout successful");
    }

    /**
     * Função responsável pelo retorno do refresh token
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    protected function respondWithToken($token): JsonResponse
    {
        return $this->success(
            [
                'access_token' => $token,
                'token_type'   => 'bearer',
                'expires_in'   => auth('api')->factory()->getTTL() * 60,
                'user'         => new UserRescource(auth('api')->user())
            ],
            "Login successful"
        );
    }
}
