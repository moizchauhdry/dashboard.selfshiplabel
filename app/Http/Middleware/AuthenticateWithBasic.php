<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthenticateWithBasic
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $credentials = explode(':', base64_decode(substr($request->header('Authorization'), 6)));
        $username = $credentials[0] ?? null;
        $password = $credentials[1] ?? null;

        if ($username !== 'moizchauhdry' || $password !== '12345678') {
            return response()->json([
                'status' => 'false',
                'message' => 'Unauthorized.',
                'data' => []
            ], 401);
        }

        return $next($request);
    }
}
