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

class FolderController extends Controller
{

    public function get(Request $request): JsonResponse
    {
        //TODO: Check for data leaks
        //TODO: Add errors for folder_id not
        $folder_id = $request['folder_id'];
        $user_id = (Token::find($request->header("X-Token")))->user_id;

        $folders = DB::table('folders')
            ->select("*")
            ->where('parent_id', '=', $folder_id)
            ->where('user_id', '=', $user_id)
            ->get();

        $locations = DB::table('locations')
            ->select('*')
            ->where('folder_id', "=", $folder_id)
            ->where('user_id', "=", $user_id)
            ->get();

        return response()->json([
            "folders" => $folders,
            "locations" => $locations
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        //TODO: Check data leaks
        $parent_id = $request['folder_id'];
        $name = $request['name'];
        $user_id = (Token::find($request->header("X-Token")))->user_id;

        if (isset($folder_id)){
            $folder = Folder::find($folder_id);
            if($folder->user_id != $user_id) return response()->json(["message" => "Insufficient permissions for folder '${folder_id}'"],403);
        }

        $folder = Folder::create([
            "id" => Str::random(5),
            "name" => $request['name'],
            "user_id" => $user_id,
            "parent_id" => $parent_id
        ]);

        return response()->json($folder, 201);


    }

}
