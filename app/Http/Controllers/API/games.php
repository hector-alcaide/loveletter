<?php

namespace App\Http\Controllers\API;

use App\Events\CreateGame;
use App\Events\PrepareGame;
use App\Events\PublicActionUser;
use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Game;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class games extends Controller
{
    public function newGame(Request $request){

        $new_game = Game::create([
            'idHost' => auth()->id(),
            'type' => $request->type,
            'numMaxWins' => $request->numMaxWins,
        ]);

        broadcast(new CreateGame($new_game->idGame));

        return $new_game;
    }

    public function getGamesActive(){
        $result = Game::select('idGame')->where('started', 0)->get();

        return $result;
    }

    public function getGameData(Request $request){
        $game = Game::select('idGame', 'idHost', 'numMaxWins', 'type', 'started', 'game')->find($request->idGame);
        $game->players;

        return $game;
    }

    public function prepareGame(Request $request){

        $gameObj = Game::find($request->idGame);

        $gameObj->players()->attach($request->ids_players);

        $players = [];

        foreach ($gameObj->players as $key => $player){
            $players[$player->idUser] = [
                'idPlayer' => $player->idUser,
                'alias' => $player->alias,
                'hand' => [],
                'activePlayer' => true,
                'playerNum' => ($key + 1)
            ];
        }

        $game = [
            'idGame' => $gameObj->idGame,
            'players' => $players,
            'deck' => [],
            'deckReference' => $this->getAllCards(),
            'roundNum' => null,
            'turnPlayerNum' => 1,
            'thrownCards' => []
        ];

        $game = $this->newRound($game);

        $gameObj->update([
            'game' => json_encode($game),
            'started' => 1
        ]);

        broadcast(new PrepareGame($game['idGame']));

        $response = [
            'status' => 'success',
            'message' => 'partida preparada'
        ];

        return $response;
    }

//    public function empezarPartida(){
//
////        $primera_ronda = $this->newRound();
//
//        $deck = $this->getAllCards();
//
//        /** TODO: guardar el objeto completo de card en la hand, en vez de sus ids.
//            Dónde, igual que en el deck, la clave es la id de la card
//         **/
//        $players = [
//            '2' => [
//                'hand' => [
//                    'card1' => 3,
//                    'card2' => 5,
//                ],
//                'activePlayer' => 1
//            ],
//            '7' => [
//                'hand' => [
//                    'card1' => 9,
//                ],
//                'activePlayer' => 1
//            ],
//        ];
//
//        $game = [
//            'idGame' => 1,
//            'players' => $players,
//            'deck' => $deck,
//            'roundNum' => 1,
//            'playerTurno' => 2,
//            'cards_jugadas' => []
//        ];
//
//        session(['game' => $game]);
//
//        $test = session('game');
//
//        $id = auth()->id();
//
//        $hand = [
//            3 => new Card(3, 1, 'Guardia', '/resources/images/cards/guardia.png'),
//            8 => new Card(8, 1, 'Guardia', '/resources/images/cards/guardia.png'),
//        ];
//
//        $test1_deck = $this->getAllCards();
//        $test2_deck = $this->getAllCards();
//
//        $primera_card = reset($test2_deck);
//        $clave_primera_card = key($test2_deck);
//
////        $posicion = array_search('7', array_keys($players));
//
////        if($posicion == $game['playerTurno']){
////
////        }
//
//        shuffle($deck);
//
//        return json_encode($deck);
//    }

    public function getAllCards(){

        //array de cards, deck por defecto ordenado por level, donde idCard es la key
        $cards = [
            1 => new Card(1, 0, 'Espía', '/resources/images/cards/espia.png'),
            2 => new Card(2, 0, 'Espía', '/resources/images/cards/espia.png'),
            3 => new Card(3, 1, 'Guardia', '/resources/images/cards/guardia.png'),
            4 => new Card(4, 1, 'Guardia', '/resources/images/cards/guardia.png'),
            5 => new Card(5, 1, 'Guardia', '/resources/images/cards/guardia.png'),
            6 => new Card(6, 1, 'Guardia', '/resources/images/cards/guardia.png'),
            7 => new Card(7, 1, 'Guardia', '/resources/images/cards/guardia.png'),
            8 => new Card(8, 1, 'Guardia', '/resources/images/cards/guardia.png'),
            9 => new Card(9, 2, 'Sacerdote', '/resources/images/cards/sacerdote.png'),
            10 => new Card(10, 2, 'Sacerdote', '/resources/images/cards/sacerdote.png'),
            11 => new Card(11, 3, 'Barón', '/resources/images/cards/baron.png'),
            12 => new Card(12, 3, 'Barón', '/resources/images/cards/baron.png'),
            13 => new Card(13, 4, 'Doncella', '/resources/images/cards/doncella.png'),
            14 => new Card(14, 4, 'Doncella', '/resources/images/cards/doncella.png'),
            15 => new Card(15, 5, 'Príncipe', '/resources/images/cards/principe.png'),
            16 => new Card(16, 5, 'Príncipe', '/resources/images/cards/principe.png'),
            17 => new Card(17, 6, 'Canciller', '/resources/images/cards/canciller.png'),
            18 => new Card(18, 6, 'Canciller', '/resources/images/cards/canciller.png'),
            19 => new Card(19, 7, 'Rey', '/resources/images/cards/rey.png'),
            20 => new Card(20, 8, 'Condesa', '/resources/images/cards/condesa.png'),
            21 => new Card(21, 9, 'Princesa', '/resources/images/cards/princesa.png'),
        ];

        return $cards;
    }

    public function newRound($game){

        $deck = array_column($this->getAllCards(), 'idCard');

        shuffle($deck);

        reset($deck);

        foreach ($game['players'] as $key => $player) {
            $game['players'][$player['idPlayer']]['hand'] = [
                reset($deck)
            ];
            unset($deck[key($deck)]);
        }

        $game['deck'] = $deck;
        $game['roundNum']++;

        return $game;
    }

    public function stealCard(Request $request){

        $game = $request->game;

        reset($game['deck']);

        $game['players'][$request->idUser]['hand'] += [key($game['deck']) => reset($game['deck'])];
        unset($game['deck'][key($game['deck'])]);

        $gameObj = Game::find($game['idGame']);
        $gameObj->update(['game' => $game]);

        $message = [
            'El jugador ' .  $game['players'][$request->idUser]['alias'] . ' ha robado carta.'
        ];

        broadcast(new PublicActionUser($game['idGame'], $message));

        $response = [
            'status' => 'success',
            'message' => 'Juega una de tus cartas.'
        ];

        return $response;
    }

    public function resolvePlay(Request $request){

        $game = $request->game;
        $players = $game['players'];
        $idPlayer = $request->idPlayer;
        $thrown_card = $request->idCard;


        $player_card = $players[$idPlayer]['hand'];
        $rival_card = !empty($request->idRival) ? reset($players[$request->idRival]['hand']) : '';

        //unset maid
        unset($players[$idPlayer]['maid']);

        switch ($game['deckReference'][$thrown_card]['title']){
            case 'Espía':
                $players[$idPlayer]['spy'] = true;
                $players[$idPlayer]['hand'] = [];

                $status = 'success';
                $message = 'El jugador '. $players[$idPlayer]['alias'] . ' ha jugado el Espía.';
                break;
            case 'Guardia':
                if($request->cardToGuess == $rival_card){
                    $player_to_remove = $request->idRival;

                    $message_result = 'Carta adivinada, jugador eliminado.';
                }else{
                    $message_result = 'Carta no adivinada.';
                }

                $status = 'success';
                $message = 'El jugador '. $players[$idPlayer]['alias'] . ' ha jugado el Guardia sobre el jugador '.$players[$request->idRival]['alias'].'. Adivina la carta '.$game['deckReference'][$request->cardToGuess]['title'].'. ' .$message_result;
                break;
            case 'Sacerdote':
                //TODO: girar la card por js al player

                $status = 'success';
                $message = 'El jugador '. $players[$idPlayer]['alias'] . ' ha jugado el Sacerdote.';
                break;
            case 'Barón':
                $player_card_level = $game['deckReference'][$player_card]['level'];
                $rival_card_level = $game['deckReference'][$rival_card]['level'];

                if($player_card_level < $rival_card_level){
                    $player_to_remove = $idPlayer;
                }else if($rival_card_level < $player_card_level){
                    $player_to_remove = $request->idRival;
                }

                if($player_card_level == $rival_card_level){
                    $message_result = 'Empate, el nivel de las cartas es el mismo.';
                }else{
                    $message_result = 'El jugador '.$players[$player_to_remove]['alias'].' ha sido eliminado.';
                }

                $status = 'success';
                $message = 'El jugador '. $players[$idPlayer]['alias'] . ' ha jugado el Barón. ' . $message_result;
                break;
            case 'Doncella':
                $players[$idPlayer]['maid'] = true;
                $status = 'success';
                $message = 'El jugador '. $players[$idPlayer]['alias'] . ' ha jugado la Doncella.';
                break;
            case 'Príncipe':
                //TODO: elemento afectado puede ser rival o player mismo, por lo que podria verse como enviar este dato
                // (idElemento, tanto para rival como player¿?) para hacer lo mas legible

                $player_afectado = $jugada['idRival'];

                //comprobar si tiene la princesa
                if(isset($game['players'][$player_afectado]['hand'][21])){
                    $user_to_remove = $player_afectado;
                    $message = 'Jugador eliminado al descardr princesa.';
                }

                //vaciar el array de hand, para descartar la card
                $game['players'][$player_afectado]['hand'] = reset($game['deck']);
                array_shift($game['deck']);

                break;
            case 'Canciller':
                //TODO: robar dos cards del deck, mostrar las al usuario, decidir con cual de las 3 se queda
                break;
            case 'Rey':
                //rival card
                $players[$request->idRival]['hand'] = $player_card;

                //player card
                $players[$idPlayer]['hand'] = $rival_card;

                $status = 'success';
                $message = 'El jugador '.$players[$idPlayer]['alias'].' ha jugado el rey, ha intercambiado mano con el jugador ' . $players[$request->idRival]['alias'] . '.';
                break;
            case 'Condesa':
                $status = 'success';
                $message = 'El jugador '. $players[$idPlayer]['alias'].' ha jugado la Condesa.';
                break;
            case 'Princesa':
                $player_to_remove = $idPlayer;

                $status = 'success';
                $message = 'El jugador '. $players[$idPlayer]['alias'].' ha sido eliminado al descartar la Princesa.';
                break;
        }

        array_push($game['thrownCards'], $thrown_card);

        if(isset($player_to_remove) && $player_to_remove){
            $players[$player_to_remove]['activePlayer'] = false;

            !empty($players[$player_to_remove]['hand']) ? array_push($game['thrownCards'], $players[$player_to_remove]['hand']) : '';
            $players[$player_to_remove]['hand'] = [];
        }

        $game['turnPlayerNum'] == count($game['players']) ? $game['turnPlayerNum'] = 1 : $game['turnPlayerNum']++;
        $game['players'] = $players;

        $gameObj = Game::find($game['idGame']);
        $gameObj->update(['game' => $game]);

        if(!empty($request->idRival)){
            broadcast(new PrivateActionUser($game['idGame'], $request->idRival, $privateMessage));
        }
        broadcast(new PublicActionUser($game['idGame'], $message));

        $response=[
            'status' => $status,
            'message' => $message,
        ];

        return $response;
    }
}
