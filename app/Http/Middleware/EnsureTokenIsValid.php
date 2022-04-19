<?php

namespace App\Http\Middleware;

use App\Models\Token;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return JsonResponse|RedirectResponse|Response
     */
    public function handle($request, Closure $next)
    {
        if ($request->header("X-Token") === null) return response()->json(["message" => "Missing 'X-Token' header"], 403);
        if (Token::find($request->header("X-Token")) === null) return response()->json(["message" => "Invalid token"], 403);

        return $next($request);
    }
}
