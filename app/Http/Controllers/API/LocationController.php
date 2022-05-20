<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use App\Models\Location;
use App\Models\Token;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Psy\Util\Json;

class LocationController extends Controller
{

    /**
     * Function used by the API to get all or a specific location(s)
     * @param Request $request (location) id & (user) token checked by middleware
     * @return JsonResponse
     */
    public function get(Request $request): JsonResponse
    {
        //TODO: If location is shared to user, he needs to be able to use this endpoint to get information (actually sends a 403)
        $location_id = $request['id']??null; //Take the location id asked
        $user_id = (Token::find($request->header("X-Token")))->user_id;//Find user_id

        if ($location_id === null) $location = Location::all(); //If there is no location_id given then we send all of the users locations
        else $location = Location::find($location_id); //Else we send the one asked

        //If the used asked for a specific location BUT doesn't have the rights to access it we send a 403
        if($location_id !== null and $location->user_id != $user_id) return response()->json(["message" => "Insufficient permissions for location '${location_id}'"],403);

        //Send the array, transform to json, and we're done for the day
        return response()->json($location);
    }

    /**
     * Function used by the API to create a new location
     * @param Request $request checked by middleware
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        //Take the folder_id if present
        $folder_id = $request['folder_id'];
        $user_id = (Token::find($request->header("X-Token")))->user_id;

        //If a folder was given we check if the user have the rights to the folder
        if (isset($folder_id)){
            $folder = Folder::find($folder_id);
            if($folder->user_id != $user_id) return response()->json(["message" => "Insufficient permissions for folder '${folder_id}'"],403);
        }

        //create the location, insert it in the database
        $location = Location::create([
            "id" => Str::random(5),
            "name" => $request['name'],
            "latitude" => $request['lat'],
            "longitude" => $request['long'],
            "folder_id" => $folder_id,
            "user_id" => $user_id
        ]);

        return response()->json($location, 201);
    }

    public function delete(Request $request): JsonResponse
    {
        //TODO: Check for flaws
        $id = $request['id'];
        $user_id = (Token::find($request->header("X-Token")))->user_id;

        $location = Location::find($id);
        if($location->user_id != $user_id) return response()->json(["message" => "Insufficient permissions for location '${id}'"],403);

        $location->delete();
        return response()->json('', 204);

    }

}
