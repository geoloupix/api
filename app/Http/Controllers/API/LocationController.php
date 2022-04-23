<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Token;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

}
