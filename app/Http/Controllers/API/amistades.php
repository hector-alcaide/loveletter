<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Amistad;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class amistades extends Controller
{
    public function addAmigo(Request $request){

        try{
            $amigo = new Amistad();
            $amigo->idUsuario1 = Auth::id();
            $amigo->idUsuario2 = $request->idAmigo;
            $amigo->estado = "solicitado";
            $amigo->save();

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

        return response()->json($response);

    }
    public function solicitudAmistad(Request $request){
        $result = Amistad::
            select('usuarios.alias', 'amistad.id')
            ->join('usuarios', 'amistad.idUsuario1', '=', 'usuarios.idUsuario')
            ->where('estado','solicitado')->where(function($query) {
                $query->where('idUsuario2',Auth::id());
        })->get();

        return $result;
    }
    public function aceptarSolicitudAmistad(Request $request){
        $result = Amistad::where('id', $request->solicitud)
            ->update([
                'estado' => 'aceptado'
            ]);

        return $result;
    }
    public function rechazarSolicitudAmistad(Request $request){
        $result = Amistad::where('id', $request->solicitud)
            ->update([
                'estado' => 'rechazado'
            ]);

        return $result;
    }
    public function tusAmigos(Request $request){

        $sql = "SELECT
                    CASE
                    WHEN a.alias != 'hector' THEN a.alias
                    ELSE b.alias
                    END AS amigo_id
                    FROM amistad
                    JOIN usuarios a ON (amistad.idUsuario1 = a.idUsuario)
                    JOIN usuarios b ON (amistad.idUsuario2 = b.idUsuario)
                    WHERE
                    (idUsuario1 = 2 AND idUsuario2 != 2 OR idUsuario2 = 2 AND idUsuario1 != 2)
                    AND estado = 'aceptado'";
        $result = DB::select($sql);

        /*$sql = 'SELECT
            CASE
            WHEN idUsuario1 != 2 THEN idUsuario1
            ELSE idUsuario2
            END AS amigo_id
            FROM amistad
            WHERE
            (idUsuario1 = 2 AND idUsuario2 != 2 OR idUsuario2 = 2 AND idUsuario1 != 2)
            AND estado = "aceptado"';
        $result = DB::select($sql);

        $res = User::whereIn("idUsuario",$result)->get();*/

        return $result;
    }

    public function getFriends(){

        $userid = auth()->id();

//        $friend = Amistad::select( function ($query) {
//           $query
//                ->where('idUser1', $userid)->select('User1')
//               ->orWhere('idUser2', $userid)->select('User2');
//        })->get();

//        $friends = Amistad::where('idUser1', $userid)->orWhere('idUser2', $userid)->get();
//        $friends = Amistad::where(function($query) use ($userid) {
//            $query->select('idUser2')->where('idUser1', $userid);
//        }, $userid)->get();

//        $friends = Amistad::where(function($query) use ($userid) {
//            $query->select('idUser')
//                ->from('friendships')
//                ->where('idUser2', $userid);
//        })->get();

//        $friends = Amistad::where(function($query) use ($userid) {
//            $query->select('idUser1')
//                ->from('friendships')
//                ->where('idUser2', $userid);
//        })->get();

        $friends = Amistad::select(DB::raw(
                'CASE
                        WHEN idUser1 = ' . $userid . ' THEN idUser2
                        WHEN idUser2 = ' . $userid . ' THEN idUser1
                      END as idFriend'
            ))->where('idUser1', $userid)
            ->orWhere('idUser2', $userid)
            ->get();

        $test = $friends->user;

        return $friends->user;
    }
}
