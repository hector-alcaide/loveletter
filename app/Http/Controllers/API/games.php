<?php

namespace App\Http\Controllers\API;

use App\Events\CreateGame;
use App\Events\PrepareGame;
use App\Events\PublicActionUser;
use App\Events\PrivateActionUser;
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
        $idsPlayersByTurnNum = [];

        foreach ($gameObj->players as $key => $player){
            $players[$player->idUser] = [
                'idPlayer' => $player->idUser,
                'alias' => $player->alias,
                'hand' => [],
                'activePlayer' => true,
                'playerNum' => ($key + 1),
                'spy' => false,
                'maid' => false
            ];

            $idsPlayersByTurnNum += [
                ($key + 1) => $player->idUser
            ];
        }

        $game = [
            'idGame' => $gameObj->idGame,
            'players' => $players,
            'deck' => [],
            'deckReference' => $this->getAllCards(),
            'roundNum' => null,
            'turnPlayerNum' => 1,
            'idsPlayersByTurnNum' => $idsPlayersByTurnNum,
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
//            3 => new Card(3, 1, 'Guardia', 'http://[::1]:5173/resources/images/cards/card1.png'),
//            8 => new Card(8, 1, 'Guardia', 'http://[::1]:5173/resources/images/cards/card1.png'),
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

    public function getCardsByLevel(){

        $cards = [
            0 => new Card(0, 0, 'Espía', 'http://[::1]:5173/resources/images/cards/card0.jpg'),
            1 => new Card(1, 1, 'Guardia', 'http://[::1]:5173/resources/images/cards/card1.jpg'),
            2 => new Card(2, 2, 'Sacerdote', 'http://[::1]:5173/resources/images/cards/card2.jpg'),
            3 => new Card(3, 3, 'Barón', 'http://[::1]:5173/resources/images/cards/card3.jpg'),
            4 => new Card(4, 4, 'Doncella', 'http://[::1]:5173/resources/images/cards/card4.jpg'),
            5 => new Card(5, 5, 'Príncipe', 'http://[::1]:5173/resources/images/cards/card5.jpg'),
            6 => new Card(6, 6, 'Canciller', 'http://[::1]:5173/resources/images/cards/card6.jpg'),
            7 => new Card(7, 7, 'Rey', 'http://[::1]:5173/resources/images/cards/card7.jpg'),
            8 => new Card(8, 8, 'Condesa', 'http://[::1]:5173/resources/images/cards/card8.jpg'),
            9 => new Card(9, 9, 'Princesa', 'http://[::1]:5173/resources/images/cards/card9.jpg'),
        ];

        return $cards;
    }

    public function getAllCards(){

        //array de cards, deck por defecto ordenado por level, donde idCard es la key
        $cards = [
            1 => new Card(1, 0, 'Espía', 'http://[::1]:5173/resources/images/cards/card0.jpg'),
            17 => new Card(17, 6, 'Canciller', 'http://[::1]:5173/resources/images/cards/card6.jpg'),
            18 => new Card(18, 6, 'Canciller', 'http://[::1]:5173/resources/images/cards/card6.jpg'),
            2 => new Card(2, 0, 'Espía', 'http://[::1]:5173/resources/images/cards/card0.jpg'),
            3 => new Card(3, 1, 'Guardia', 'http://[::1]:5173/resources/images/cards/card1.jpg'),
            4 => new Card(4, 1, 'Guardia', 'http://[::1]:5173/resources/images/cards/card1.jpg'),
            5 => new Card(5, 1, 'Guardia', 'http://[::1]:5173/resources/images/cards/card1.jpg'),
            6 => new Card(6, 1, 'Guardia', 'http://[::1]:5173/resources/images/cards/card1.jpg'),
            7 => new Card(7, 1, 'Guardia', 'http://[::1]:5173/resources/images/cards/card1.jpg'),
            8 => new Card(8, 1, 'Guardia', 'http://[::1]:5173/resources/images/cards/card1.jpg'),
            9 => new Card(9, 2, 'Sacerdote', 'http://[::1]:5173/resources/images/cards/card2.jpg'),
            10 => new Card(10, 2, 'Sacerdote', 'http://[::1]:5173/resources/images/cards/card2.jpg'),
            11 => new Card(11, 3, 'Barón', 'http://[::1]:5173/resources/images/cards/card3.jpg'),
            12 => new Card(12, 3, 'Barón', 'http://[::1]:5173/resources/images/cards/card3.jpg'),
            13 => new Card(13, 4, 'Doncella', 'http://[::1]:5173/resources/images/cards/card4.jpg'),
            14 => new Card(14, 4, 'Doncella', 'http://[::1]:5173/resources/images/cards/card4.jpg'),
            15 => new Card(15, 5, 'Príncipe', 'http://[::1]:5173/resources/images/cards/card5.jpg'),
            16 => new Card(16, 5, 'Príncipe', 'http://[::1]:5173/resources/images/cards/card5.jpg'),
            19 => new Card(19, 7, 'Rey', 'http://[::1]:5173/resources/images/cards/card7.jpg'),
            20 => new Card(20, 8, 'Condesa', 'http://[::1]:5173/resources/images/cards/card8.jpg'),
            21 => new Card(21, 9, 'Princesa', 'http://[::1]:5173/resources/images/cards/card9.jpg'),
        ];

        return $cards;
    }

    public function newRound($game){

        $deck = array_column($this->getAllCards(), 'idCard');

//       shuffle($deck);

        foreach ($game['players'] as $key => $player) {
            $game['players'][$player['idPlayer']]['hand'] = [
                array_shift($deck)
            ];
        }

        $game['deck'] = $deck;
        $game['roundNum']++;

        return $game;
    }

    public function stealCard(Request $request){

        $game = $request->game;

//        reset($game['deck']);

//        $game['players'][$request->idUser]['hand'] += [key($game['deck']) => reset($game['deck'])];
//        unset($game['deck'][key($game['deck'])]);

        array_push($game['players'][$request->idUser]['hand'], array_shift($game['deck']));

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
        $changeTurn = true;

        $game = $request->game;
        $players = $game['players'];
        $idPlayer = $request->idPlayer;
        $thrown_card = $request->idCard;

        $player_card = reset($players[$idPlayer]['hand']);
        $rival_card = !empty($request->idRival) ? reset($players[$request->idRival]['hand']) : '';

        //reset maid condition
        $players[$idPlayer]['maid'] = false;

        switch ($game['deckReference'][$thrown_card]['title']){
            case 'Espía':
                $players[$idPlayer]['spy'] = true;

                $status = 'success';
                $message = 'El jugador '. $players[$idPlayer]['alias'] . ' ha jugado el Espía.';
                break;
            case 'Guardia':
                if($request->levelCardToGuess == $game['deckReference'][$rival_card]['level']){
                    $player_to_remove = $request->idRival;
                    $discarded_card = $players[$player_to_remove]['hand'];

                    $message_result = 'Carta adivinada, jugador eliminado.';
                }else{
                    $message_result = 'Carta no adivinada.';
                }

                $cardsByLevel = $this->getCardsByLevel();

                $status = 'success';
                $message = 'El jugador '. $players[$idPlayer]['alias'] . ' ha jugado el Guardia sobre el jugador '.$players[$request->idRival]['alias'].'. Adivina la carta '.$cardsByLevel[$request->levelCardToGuess]->title.'. ' .$message_result;
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
                    $discarded_card = $players[$player_to_remove]['hand'];
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

                if($game['deckReference'][$player_card]['level'] == 9){
                    $player_to_remove = $request->idRival;
                    $message_result = 'El jugador '. $players[$request->idRival]['alias'] .' ha sido eliminado al descartar la Princesa.';
                }else{
                    $discarded_card = $players[$request->idRival]['hand'];
                    $players[$request->idRival]['hand'] = [
                        array_shift($game['deck'])
                    ];

                    $message_result = 'El jugador' . $players[$request->idRival]['alias'] . ' ha descartado su mano y ha robado una nueva carta';
                }

                $status = 'success';
                $message = 'El jugador '. $players[$idPlayer]['alias'] . ' ha jugado el Príncipe. ' . $message_result;
                break;
            case 'Canciller':

                $players[$idPlayer]['hand'] += array_slice($game['deck'], 0, 2);
                $game['deck'] = array_slice($game['deck'], 2);

                $changeTurn = false;
                $status = 'success';
                $message = 'El jugador '. $players[$idPlayer]['alias'] . ' ha jugado el Canciller. Debe escoger que carta conservar.';
                break;
            case 'Rey':
                //rival card
                $players[$request->idRival]['hand'] = [
                    $player_card
                ];

                //player card
                $players[$idPlayer]['hand'] = [
                    $rival_card
                ];

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
            $players[$player_to_remove]['hand'] = [];
        }

        isset($discarded_card) && $discarded_card ? array_push($game['thrownCards'], $discarded_card) : '';

        isset($changeTurn) && $changeTurn === true ? $game['turnPlayerNum'] == count($game['players']) ? $game['turnPlayerNum'] = 1 : $game['turnPlayerNum']++ : '';
        $game['players'] = $players;

        $gameObj = Game::find($game['idGame']);
        $gameObj->update(['game' => $game]);

        if(!empty($request->idRival) && isset($privateMessage)){
            broadcast(new PrivateActionUser($game['idGame'], $request->idRival, $privateMessage));
        }
        broadcast(new PublicActionUser($game['idGame'], $message));

        $response=[
            'status' => $status,
            'message' => $message,
        ];

        return $response;
    }

    public function resolveChancellor(Request $request){
        $game = $request->game;

        $hand = $game['players'][$request->idPlayer]['hand'];
        array_push($game['deck'], $hand[0]);
        array_push($game['deck'], $hand[1]);

        $game['players'][$request->idPlayer]['hand'] = $request->idCard;

        $game['turnPlayerNum'] == count($game['players']) ? $game['turnPlayerNum'] = 1 : $game['turnPlayerNum']++;

        $gameObj = Game::find($game['idGame']);
        $gameObj->update(['game' => $game]);

        $message = 'El jugador'. $game['players'][$request->idPlayer]['alias'] .'ha conservado una de sus cartas y ha descartado las demás.';

        broadcast(new PublicActionUser($game['idGame'], $message));

        $response=[
            'status' => 'success',
            'message' => $message,
        ];

        return $response;
    }

}
