<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Traits\IssuesToken;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use IssuesToken;

    public function login(LoginRequest $request) {
        return response()->json($this->generateTokens($request));
    }

    public function register(RegisterRequest $request) {
        return response()->json($this->generateTokens($request,'phone'));
    }

    public function logout(Request $request) {
        $token = $request->user()->currentAccessToken();
        $token->revoke();
        app('Laravel\Passport\RefreshTokenRepository')->revokeRefreshTokensByAccessTokenId($token->id);

        return response()->noContent();
    }

    private function generateTokens($request, $grant = 'password') {
        return json_decode($this->issueToken($request, $grant)->getContent());
    }
}
