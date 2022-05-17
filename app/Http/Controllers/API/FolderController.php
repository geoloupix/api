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

}
