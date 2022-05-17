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

    public function get(Request $request): JsonResponse
    {
        //TODO: If location is shared to user, he needs to be able to use this endpoint to get information (actually sends a 403)
        $location_id = $request['id']??null;
        $user_id = (Token::find($request->header("X-Token")))->user_id;

        if ($location_id === null) $location = Location::all();
        else $location = Location::find($location_id);

        if($location_id !== null and $location->user_id != $user_id) return response()->json(["message" => "Insufficient permissions for location '${location_id}'"],403);

        return response()->json($location);
    }

    public function store(Request $request): JsonResponse
    {
        $folder_id = $request['folder_id'];
        $user_id = (Token::find($request->header("X-Token")))->user_id;

        if (isset($folder_id)){
            $folder = Folder::find($folder_id);
            if($folder->user_id != $user_id) return response()->json(["message" => "Insufficient permissions for folder '${folder_id}'"],403);
        }

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

}
