<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $user = User::create([
            'uuid' => Str::uuid(),
            'username' => $request['username'],
            'email' => $request['email'],
            'password' => bcrypt($request['password'])
        ]);

        $user->createToken();

        return response()->json([
            "uuid" => $user->uuid,
            "username" => $user->username,
            "email" => $user->email,
            "confirmed" => false,
            "token" => $user->getToken()->token
        ], 201);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $user = User::where('username', $request['username'])->first();
        if(!isset($user)) return response()->json(["message" => "Wrong username or password"], 401);
        if(!$user->passwordCheck($request['password'])) return response()->json(["message" => "Wrong username or password"], 401);

        $user->createToken();

        return response()->json([
            "uuid" => $user->uuid,
            "username" => $user->username,
            "email" => $user->email,
            "confirmed" => (bool)$user->confirmed,
            "token" => $user->getToken()->token
        ], 202);

    }

}
