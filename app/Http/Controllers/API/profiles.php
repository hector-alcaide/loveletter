<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Amistad;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class profiles extends Controller
{
    public function yourProfile(){
        $id = Auth::id();
        $results = User::where('idUser', $id)->get();

        return json_encode($results);

    }

    public function findAlias(Request $request){
        $id = Auth::id();
        $result = User::
            select('idUser')
            ->where( 'idUser' , '!=' , $id)
            ->where( 'alias' , $request->alias)
            ->get();
        
        if(sizeof($result)){
            $message = 1;
            return $message;
        }else{
            $message = 0;            
        } 
        
        $result = User::
            select('idUser')
            ->where( 'idUser' , '!=' , $id)
            ->where( 'email' , $request->email)
            ->get();

        if(sizeof($result)){
            $message = 2;
        }else{
            $message = 0;
        }

        return $message;
    }

    public function updateProfile(Request $request){
        $id = Auth::id();
        if(!empty($request->password)){
            $message = $request->password;
            $result = User::where('idUser', $id)
            ->update([
                'alias' => $request->alias,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'name' => $request->name,
                'surnames' => $request->surnames
            ]);
        }else{
            $result = User::where('idUser', $id)
            ->update([
                'alias' => $request->alias,
                'email' => $request->email,
                'name' => $request->name,
                'surnames' => $request->surnames
            ]);
        }
        

        return $result;

    }

}