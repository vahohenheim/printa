<?php

namespace App\Http\Controllers;

use App\Command;
use App\Action;
use App\Oid;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class CommandsController extends Controller
{
    public function index() {
        $commands = Command::get();
        return Response::json($commands, 200, [], JSON_NUMERIC_CHECK);
    }

    public function show($id) {
        $command = Command::find($id);
        return Response::json($command, 200, [], JSON_NUMERIC_CHECK);
    }

    public function store(Request $request) {
        $command = [];
        $action_id = $request->action_id;
        $oid_id = $request->oid_id;

        $action = Action::where(['id' => $action_id])->get();
        $oid = Oid::where(['id' => $oid_id])->get();

        // On resgarde si l'action et l'oid sont présent dans la base
        if($action->count() == 1 & $oid->count() == 1){
            $command = Command::where([
                'action_id' => $action_id,
                'model_id' => $request->model_id,
                'oid_id' => $oid_id
            ])->get();
             // On test si la page à déjà été ajouté
            if($command->count() == 0){
                $command = Command::create([
                    'action_id' => $action_id,
                    'model_id' => $request->model_id,
                    'oid_id' => $oid_id
                ]);
            } else {
                return Response::json($command, 200, [], JSON_NUMERIC_CHECK);
            }
        } else {
            return Response::json("L'action ou l'OID associé n'est pas valide", 200, [], JSON_NUMERIC_CHECK);
        }
        return Response::json($command, 200, [], JSON_NUMERIC_CHECK);
    }

    public function update(Request $request, $id){
        $command = Command::find($id);
        $action_id = $request->action_id;
        $oid_id = $request->oid_id;

        $action = Action::where(['id' => $action_id])->get();
        $oid = Oid::where(['id' => $oid_id])->get();

        // On regarde si l'action et l'oid sont présent dans la base
        if($action->count() == 1 & $oid->count() == 1){

            $command->action_id = $action_id;
            $command->model_id = $request->model_id;
            $command->oid_id = $oid_id;

            $command->save();

            return Response::json($command, 200, [], JSON_NUMERIC_CHECK);

        } else {

            return Response::json("L'action ou l'OID associé n'est pas valide", 200, [], JSON_NUMERIC_CHECK);

        }

    }
}
