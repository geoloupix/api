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
            $fail = $validator->failed();
            $message = "Invalid fields (unknown error)";
            $code = 400;
            switch (array_key_first($fail)) {
                case "password":
                    $message = "Field 'password' is required";
                    if (array_key_first($fail['password']) === "Min") $message = "Password must be at least 8 characters long";
                    break;
                case "email":
                    $message = "Field 'email' is required";
                    if (array_key_first($fail['email']) === "Email") $message = "Field 'email' must be an email";
                    if (array_key_first($fail['email']) === "Unique") $message = "Email already in use"; $code = 409;
                    break;
                case "username":
                    $message = "Field 'username' is required";
                    if (array_key_first($fail['username']) === "Unique") $message = "Username already in use"; $code = 409;
                    if (array_key_first($fail['username']) === "Max") $message = "Username can't be over 100 characters long";
                    break;
            }
            return response()->json(["message" => $message], $code);
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
            "confirmed" => false,
            "token" => $user->getToken()->token
        ], 201);
    }

    public function login(Request $request){
        // La validation de données
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            $fail = $validator->failed();
            $message = match (array_key_first($fail)) {
                "password" => "Field 'password' is required",
                "username" => "Field 'username' is required",
                default => "Invalid fields (unknown error)",
            };
            return response()->json(["message" => $message], 400);
        }

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
