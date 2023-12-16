<?php


namespace App\Http\Controllers;

use Exception;


use App\Models\Account;
use Tymon\JWTAuth\JWTGuard;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use function App\Helpers\generateRefreshToken;

class AuthController extends Controller
{
    protected $table = 'customers';

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'refresh']]);
    }

    public function login()
    {
        $credentials = request(['email', 'password']);
        Log::info('Login attempt with credentials:', $credentials);
        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $refreshToken = generateRefreshToken();

        return $this->respondWithToken($token, $refreshToken);
    }
    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
    public function me()
    {
        $user = auth('api')->user();

        $user->load('customer');

        return response()->json($user);
    }
    public function refresh()
    {
        $refreshToken = request()->refreshToken;

        try {
            $decode = JWTAuth::getJWTProvider()->decode($refreshToken);
            $user = Account::find($decode['sub']);
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }
            $token = auth('api')->login($user);
            // $refreshToken = generateRefreshToken();

            return $this->respondWithAccessToken($token);
        } catch (Exception $e) {
            return response()->json(['error' => 'Refesh Token is invalid'], 403);
        }
    }

    protected function respondWithToken($token, $refreshToken)
    {
        return response()->json([
            'access_token' => $token,
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ]);
    }

    protected function respondWithAccessToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ]);
    }
}
