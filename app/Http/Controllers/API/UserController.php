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
    public function store(Request $request)
    {

        // La validation de données
        $validator = Validator::make($request->all(), [
            'username' => 'required|max:100|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8'
        ]);
//        $validator->validate();

        if ($validator->fails()) {
            return response()->json(["message" => "Fields incorrect"], 400);
        }

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
//            "rank" => $user->rank,
            "token" => $user->getToken()->token
        ], 201);
    }

}
