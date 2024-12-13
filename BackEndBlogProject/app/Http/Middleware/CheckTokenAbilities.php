<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class CheckTokenAbilities
{
    public function handle(Request $request, Closure $next, ...$requiredAbilities): Response
    {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['message' => 'Token not provided'], 401);
        }

        // Find the token in the database
        $personalAccessToken = PersonalAccessToken::findToken($token);

        if (!$personalAccessToken || !$personalAccessToken->tokenable) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        //Get the abilities from the token
        $tokenAbilities = $personalAccessToken->abilities;

        //Check if the token has the required abilities
        foreach ($requiredAbilities as $requiredAbility) {
            if (!in_array($requiredAbility, $tokenAbilities)) {
                return response()->json(['message' => 'Unauthorized: missing required ability'], 403);
            }
        }

        //Pass the tokenable entity (e.g., the user) to the request
        $request->merge([
            'tokenable' => $personalAccessToken->tokenable,
            'requiredAbilities' => $requiredAbilities, // Adiciona requiredAbilities à requisição
        ]);

        return $next($request);
    }
}
