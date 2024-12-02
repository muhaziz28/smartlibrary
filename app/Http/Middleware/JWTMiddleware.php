<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Periksa apakah permintaan adalah untuk endpoint login atau register
        if ($request->is('api/login') || $request->is('api/register') || $request->is('api/check-nim') || $request->is('api/get-fakultas')) {
            return $next($request);
        }

        try {
            // Melakukan validasi token hanya untuk permintaan yang bukan login atau register
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found',
                    'data' => null
                ], 404);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            $newToken = JWTAuth::parseToken()->refresh(true, true);
            return response()->json([
                'success' => false,
                'message' => 'Token Expired',
                'data' => $newToken
            ], 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            // Tangani kasus ketika token tidak valid
            return response()->json([
                'success' => false,
                'message' => 'Token Invalid',
                'data' => null
            ], 401);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            // Tangani kasus ketika token tidak ditemukan atau kesalahan lainnya
            return response()->json([
                'success' => false,
                'message' => 'Token not found',
                'data' => null
            ], 401);
        }

        // Jika token valid, lanjutkan permintaan
        return $next($request);
    }
}
