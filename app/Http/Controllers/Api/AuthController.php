<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
  /**
   * Authenticate user and return JWT token.
   */
  public function login(Request $request): JsonResponse
  {
    $validator = Validator::make($request->all(), [
      'email' => 'required|email',
      'password' => 'required|string|min:6',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => 'Validation failed',
        'errors' => $validator->errors(),
      ], 422);
    }

    $credentials = $request->only('email', 'password');

    if (!$token = auth('api')->attempt($credentials)) {
      return response()->json([
        'success' => false,
        'message' => 'Invalid credentials',
      ], 401);
    }

    return $this->respondWithToken($token);
  }

  /**
   * Get the authenticated user.
   */
  public function me(): JsonResponse
  {
    $user = auth('api')->user();

    return response()->json([
      'success' => true,
      'user' => [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'is_admin' => $user->is_admin,
      ],
    ]);
  }

  /**
   * Log the user out (invalidate the token).
   */
  public function logout(): JsonResponse
  {
    auth('api')->logout();

    return response()->json([
      'success' => true,
      'message' => 'Successfully logged out',
    ]);
  }

  /**
   * Refresh a token.
   */
  public function refresh(): JsonResponse
  {
    return $this->respondWithToken(auth('api')->refresh());
  }

  /**
   * Get the token array structure.
   */
  protected function respondWithToken(string $token): JsonResponse
  {
    $user = auth('api')->user();

    return response()->json([
      'success' => true,
      'access_token' => $token,
      'token_type' => 'bearer',
      'expires_in' => auth('api')->factory()->getTTL() * 60,
      'user' => [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'is_admin' => $user->is_admin,
      ],
    ]);
  }
}



