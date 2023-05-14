<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Friend;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class friends extends Controller
{
    public function searchFriend(Request $request){

        $results = User::where('alias', $request->alias)->get();

        // SELECT id FROM friend JOIN users a ON (friend.idUser1 = a.idUser)
		// 					JOIN users b ON (friend.idUser2 = b.idUser)
        //             	WHERE (idUser1 = 4 AND idUser2 = 1 OR idUser2 = 4 AND idUser1 = 1)
        //             	AND estado = "aceptado"

        return json_encode($results);
    }
    
    public function addFriend(Request $request){

        try{
            $friend = new Friend();
            $friend->idUser1 = Auth::id();
            $friend->idUser2 = $request->newFriend;
            $friend->estado = "solicitado";
            $friend->save();

            $success = true;
            $message = "Amistad solicitada";
        }catch(\Illuminate\Database\QueryException $ex){
            $success = false;
            $message = $ex->getMessage();
        }

        $response=[
            'success' => $success,
            'message' => $message,
        ];

        //return response()->json($response);
        return $request;

    }
    public function requestFriend(Request $request){
        $result = Friend::
            select('users.alias', 'friend.id')
            ->join('users', 'friend.idUser1', '=', 'users.idUser')
            ->where('estado','solicitado')->where(function($query) {
                $query->where('idUser2',Auth::id());
        })->get();

        return $result;
    }
    public function acceptRequestInvitation(Request $request){
        $result = Friend::where('id', $request->solicitud)
            ->update([
                'estado' => 'aceptado'
            ]);

        return $result;
    }
    public function rejectRequestInvitation(Request $request){
        $result = Friend::where('id', $request->solicitud)
            ->update([
                'estado' => 'rechazado'
            ]);

        return $result;
    }
    public function yourFriends(Request $request){
        $id = Auth::id();
        $sql = 'SELECT
                    CASE
                    WHEN idUser1 != '.$id.' THEN a.alias
                    ELSE b.alias
                    END AS friend_id
                    FROM friend
                    JOIN users a ON (friend.idUser1 = a.idUser)
                    JOIN users b ON (friend.idUser2 = b.idUser)
                    WHERE
                    (idUser1 = '.$id.' AND idUser2 != '.$id.' OR idUser2 = '.$id.' AND idUser1 != '.$id.')
                    AND estado = "aceptado"';
        $result = DB::select($sql);

        /*$sql = 'SELECT
            CASE
            WHEN idUser1 != 2 THEN idUser1
            ELSE idUser2
            END AS friend_id
            FROM Friend
            WHERE
            (idUser1 = 2 AND idUser2 != 2 OR idUser2 = 2 AND idUser1 != 2)
            AND estado = 'aceptado"';
        $result = DB::select($sql);

        $res = User::whereIn("idUser",$result)->get();*/

        return $result;
    }

}
