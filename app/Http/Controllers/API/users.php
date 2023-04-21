<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class users extends Controller
{
    public function login(Request $request){

        $credentials=[
            'alias' => $request->alias,
            'password' => $request->password
        ];

        if(Auth::attempt($credentials)){
            $success = true;
            $message = 'Logueado correctamente';
        }else{
            $success = false;
            $message = 'Usuario o contraseña no válidos';
        }

        $response=[
            'success' => $success,
            'message' => $message,
        ];

        return response()->json($response);
    }

    public function register(Request $request){

        try{
            $user = new User();
            $user->alias = $request->alias;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            $success = true;
            $message = "Usuario registrado correctamente";
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

    public function logout(){

        try{
            Session::flush();

            $success = true;
            $message = "Logout correcto";
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
    public function amigo(Request $request){

        $results = User::where('alias', $request->alias)->get();

        return json_encode($results);
    }


}
