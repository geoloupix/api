<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Token;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{

    /**
     * Function used by the API to create a new user
     * @param Request $request Verified by middleware
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        //Creation of the user object and instantly insert it in the database
        $user = User::create([
            'uuid' => Str::uuid(),
            'username' => $request['username'],
            'email' => $request['email'],
            'password' => bcrypt($request['password'])
        ]);

        //Now we create a new token for this user to be able to be "login"
        $user->createToken();

        //Build a response, it's an array that is converted in json; http code is "201 created"
        return response()->json([
            "uuid" => $user->uuid,
            "username" => $user->username,
            "email" => $user->email,
            "confirmed" => false,
            "token" => $user->getToken()->token
        ], 201);
    }

    /**
     * Funtion used by the API to log in an existing used
     * @param Request $request Username NOT checked by middleware !
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        //Find the first user by the username given
        $user = User::where('username', $request['username'])->first();
        //If not found we need to tell like the user exists to avoid security leak
        if(!isset($user)) return response()->json(["message" => "Wrong username or password"], 401);
        //If wrong password, reject
        if(!$user->passwordCheck($request['password'])) return response()->json(["message" => "Wrong username or password"], 401);

        //Create a token for the end user
        $user->createToken();

        //Build the response as an array, transform it into json and send
        return response()->json([
            "uuid" => $user->uuid,
            "username" => $user->username,
            "email" => $user->email,
            "confirmed" => (bool)$user->confirmed,
            "token" => $user->getToken()->token
        ], 200);

    }

    /**
     * Function used by the API to get an user from a request token given
     * @param Request $request Token checked by middleware
     * @return JsonResponse
     */
    public function get(Request $request): JsonResponse
    {
        //Get the token from the header, find it in the tokens table and take the user_id linked
        $user_id = (Token::find($request->header("X-Token")))->user_id;
        //Then find the user in the users table which have the same user_id
        return response()->json(User::find($user_id));
    }

    /**
     * Function used by the API to patch an existing user (email/username)
     * @param Request $request
     * @return JsonResponse
     */
    public function patch(Request $request): JsonResponse
    {
        //REUSE the function to get an user from a token bc I'm lazy
        $user = $this->get($request);

        //Set the new value if there is one given
        $user->email = $request['email']??$user->email; //TODO: Add reverification check after changing email adress.
        $user->username = $request['username']??$user->username;
        $user->save();

        //Build the response as an array, transform it into json and send
        return response()->json([
            "uuid" => $user->user_id,
            "username" => $user->username,
            "email" => $user->email,
            "confirmed" => (bool)$user->confirmed
        ], 200);
    }

}
