<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class users extends Controller
{
    public function login(Request $request){

        $credentials=[
            'email' => $request->email,
            'password' => $request->password
        ];

        if(Auth::attempt($credentials)){
            $success = true;
            $message = 'Logeado correctamente';
        }else{
            $success = false;
            $message = 'Error';
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


}
