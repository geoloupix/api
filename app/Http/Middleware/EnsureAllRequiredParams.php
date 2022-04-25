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
    public function handle(Request $request, Closure $next, string $params): JsonResponse|Response
    {
        $params = str_replace(".", ",", $params);
//        dd(unserialize($params));
        $validator = Validator::make($request->all(), unserialize($params));

        if ($validator->fails()) {
            $fail = $validator->failed();
            $field = array_key_first($fail);
            $error = array_key_first($fail[array_key_first($fail)]);

            switch ($error) {
                case "Required":
                    $message = ["Field '${field}' is required", 400];
                    break;
                case "Min":
                case "Max":
                    $l = $fail[$field][$error][0];
                    $message = ["Field '${field}' ". (($error === "Min")?"must be at least":"can't exceed") ." ${l} characters", 400];
                    break;
                case 'Unique':
                    $field = ucfirst($field);
                    $message = ["${field} already in use", 409];
                    break;
                case 'Exists':
                    $message = ["The '${field}' given does not exist", 400];
                    break;
                case 'Numeric':
                    $message = ["Field '${field}' must be numeric", 400];
                    break;
                case 'Size':
                    $l = $fail[$field][$error][0];
                    $message = ["Field '${field}' must be exactly ${l} characters long", 400];
                    break;
                case 'AlphaNum':
                    $message = ["Field '{$field}' must be entirely alpha-numeric characters", 400];
                    break;
                default:
                    $message = ["Unknown error", 456];
//                    dd($fail);
                    break;
            }

            return response()->json(["message" => $message[0]], $message[1]??400);
        }

        return $next($request);
    }
}
