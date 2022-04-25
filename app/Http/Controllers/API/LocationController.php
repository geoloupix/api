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
        $folder_id = $request['folder_id'];
//        dd($folder_id);

        $user_id = (Token::find($request->header("X-Token")))->user_id;
//        dd($user_id);
        $locations =
            DB::table("locations")
                ->where("user_id", $user_id)
                ->where("folder_id", $folder_id)
                ->get();
//        dd($locations,$user_id,$folder_id);

        return response()->json($locations);
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
