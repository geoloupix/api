<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class EnsureAllRequiredParams
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string $params
     * @return JsonResponse
     */
    public function handle(Request $request, Closure $next, string $params): JsonResponse
    {
        $validator = Validator::make($request->all(), unserialize($params));

        if ($validator->fails()) {
            $fail = $validator->failed();
            $field = array_key_first($fail);
            $error = array_key_first($fail[array_key_first($fail)]);

            switch ($error) {
                case "Required":
                    $message = ["Field ${field} is required", 400];
                    break;
                case "Min":
                case "Max":
                    $l = $fail[$field][$error][0];
                    $message = ["Field '${field}' ". (($error === "Min")?"must be at least":"can't exceed") ." ${l} characters", 400];
                    break;
                case 'Unique':
                    $message = ["Field '${field}' already in use", 409];
                    break;
                default:
                    $message = ["Unknown error", 500];
                    break;
            }

            return response()->json(["message" => $message[0]], $message[1]);
        }

        return $next($request);
    }
}
