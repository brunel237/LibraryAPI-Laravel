<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommandRequest;
use App\Models\Request as ModelsRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    public function newCommand(CommandRequest $request){

        ModelsRequest::create($request->toArray());
        return response()->json(["message" => "Request sent successfully."], 201); //created

    }
    public function getCommand(Request $request, $id=null){

        if ($id){
            $req = ModelsRequest::find($id);
            if (!$req){
                return response()->json(["message" => "Request not found."], 404);
            }
        }
        else{
            $req = ModelsRequest::all();
        }

        return response()->json(["requests" => $req]);
    }

    public function confirmCommand(Request $request, $id){

        $data = $request->validate([
            'duration' => "numeric|min:0|nullable"
        ]);

        $req = ModelsRequest::find($id);
        if (!$req){
            return response()->json(["message" => "Request not found."], 404);
        }

        //DB::beginTransaction();
        // return $msg = DB::transaction(function() use($req, $data) {
            // try{
        $msg = $req->confirmRequest($data['duration']);
                // DB::commit();
                // return $msg;

            // }
            // catch (Exception $e){
                // DB::rollBack();
            // }
        // });


        if (json_decode($msg)->status)
            return response()->json(["message" => "{$req->action} request confirmed successfully"]);
        else{
            return response()->json(["message" => json_decode($msg)->message], 400);
        }
    }

    public function rejectCommand($id){

        $req = ModelsRequest::find($id);
        if (!$req){
            return response()->json(["message" => "Request not found."], 404);
        }

        $req->delete();

        return response()->json(["message" => "Request deleted successfully."]);
    }


}
