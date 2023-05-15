<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Friend;
use App\Models\User;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class invitations extends Controller
{
    public function inviteFriendGame(Request $request){

        $idReceptor = User::select('idUser')->where('alias', $request->aliasFriend)->first();

        $new_invitation = Invitation::create([
            'idSender' => $request->idSender,
            'idReceptor' => $idReceptor->idUser,
            'idGame' => $request->idGame
        ]);

        $message = $new_invitation ? 'Se ha enviado una invitación a ' . $request->aliasFriend : 'Error';

        $response = [
          'status' => true,
          'message' => $message
        ];

        return $response;
    }

    public function listInvitations(Request $request){

        $invitations = Invitation::where('idReceptor', $request->idReceptor)
            ->where('status', 'open')
            ->with(['game', 'sender'])
            ->get();

        $response = [
            'invitations' => $invitations,
            'status' => true,
            'message' => 'Success'
        ];

        return $response;
    }

    public function updateInvitation(Request $request){

        $invitation = Invitation::find($request->idInvitation);

        $invitation->update(['status' => 'close']);

        $response = [
            'status' => true,
            'message' => 'Success'
        ];

        return $response;
    }
}
