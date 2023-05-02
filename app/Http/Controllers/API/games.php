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
                'activePlayer' => 1,
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
            'El player ' .  $game['players'][$request->idUser]['alias'] . ' ha robado card.'
        ];

        broadcast(new PublicActionUser($game['idGame'], $message));

        $response = [
            'status' => 'success',
            'message' => 'Juega una de tus cards.'
        ];

        return $response;
    }

    public function newRound1(){
        //TODO: apartar la primera card

        $deck = $this->getAllCards();

        $game = session('game');

        //apunta el puntero a la 1a posicion del array deck
        reset($deck);

        foreach ($game['players'] as $player) {
            $player['hand'] = [key($deck) => reset($deck)];
            unset($deck[key($deck)]);
        }

        //TODO: comprobar el numero de ronda y añadirlo a la sesion de game
        $game['deck'] = $deck;
        session(['game' => $game]);

        //TODO: devolver la sesion a todos los players, al frontend, com
//        return $deck;
    }

    public function resolvePlay(Request $request){

        //posibles parametros necesitados para operar los efectos de las cards:
//        $parameters = [
//            'game' => $request->game;
//            'idGame' => $request->idGame,
//            'idPlayer' => $request->idPlayer,
//            'jugada' => $request->playedCard
//                //card jugada:
//                [
//                'idCard',
//                'cardMano' => 'card1', //TAL VEZ ESTO SOBRA
//                'idRival',
//                'cardAdivinar'
//            ]
//        ];

        $game = $request->game;
        $players = $game['players'];
        $idPlayer = $request->idPlayer;

        $rivalCard = !empty($request->idRival) ? reset($players[$request->idRival]['hand']) : '';

        switch ($game['deckReference'][$request->idCard]['title']){
            case 'Espía':
                    $players[$idPlayer]['espia'] = true;
                    $players[$idPlayer]['hand'] = [];

                    array_push($game['thrownCards'], $request->idCard);

                    $status = 'success';
                    $message = 'El player '. $players[$idPlayer]['alias'] . ' ha jugado el espía.';
                break;
            case 'Guardia':
                if($request->cardToGuess === $rivalCard){
                    //la card ha sido adivinada, el rival queda eliminado
                    $game['players'][$jugada['idRival']]['activePlayer'] = 0;

                    $status = 'removeuser';
                    $message = 'Carta adivinada, el player _ ha sido eliminado.';
                    $user_to_remove = $jugada['idRival'];
                }else{
                    $status = true;
                    $message = 'El player _ no tiene la card _ en su hand.';
                }
                break;
            case 'Sacerdote':
                //TODO: girar la card por js al player
                $message = $rivalCard['title'];

                //TODO: devolver la card del rival al usuario que efectua la jugada por canal privado
                break;
            case 'Barón':
                unset($game['players'][$idUser]['hand'][$jugada['idCard']]);

                $card_player = reset($game['players'][$idUser]['hand']);
                $card_rival = reset($game['players'][$jugada['idRival']]['hand']);

                if($card_player < $card_rival){
                    $user_to_remove = $idUser;
                    $message = 'Jugador _ eliminado.';
                }else if($card_player > $card_rival){
                    $user_to_remove = $jugada['idRival'];
                    $message = 'Jugador _ eliminado.';
                }else if($card_player == $card_rival){
                    $status = true;
                    $message = 'Las cards de los players tal son iguales, ninguno eliminado.';
                }
                break;
            case 'Doncella':
                //TODO: pensar una forma distinta, más óptima, de comprobar si el player está protegido
                //TODO: pensar una forma de comprobar cuando ha acabado el turno para eliminar la proteccion
                $game['players'][$idUser]['protegidoJugador'] = 1;
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

                //vaciar el array de hand, para descardr la card
                $game['players'][$player_afectado]['hand'] = reset($game['deck']);
                array_shift($game['deck']);

                break;
            case 'Canciller':
                //TODO: robar dos cards del deck, mostrar las al usuario, decidir con cual de las 3 se queda
                break;
            case 'Rey':
                //TODO: conservar clave y valor de las cards al extraerlas del deck para añadirlas a la hand y viceversa
                unset($game['players'][$idUser]['hand'][$jugada['idCard']]);

                $card_player = reset($game['players'][$idUser]['hand']);
                $card_rival = reset($game['players'][$jugada['idRival']]['hand']);

                $game['players'][$jugada['idRival']]['hand'] = $card_rival;
                break;
            case 'Condesa':
                //TODO: revisar si tiene al rey o principe para jugar a la condesa,
                // esta card no tiene efecto, simplemente comprobar por frontend si tiene esa card en hand
                break;
            case 'Princesa':
                $user_to_remove = $idUser;
                $message = 'Jugador eliminado al descardr princesa.';
                break;
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

    public function resolvePlay1(Request $request){

        //posibles parametros necesitados para operar los efectos de las cards:
//        $parameters = [
//            'idGame' => $request->idGame,
//            'idPlayer' => $request->idPlayer,
//            'jugada' => $request->playedCard
//                //card jugada:
//                [
//                'idCard',
//                'cardMano' => 'card1', //TAL VEZ ESTO SOBRA
//                'idRival',
//                'cardAdivinar'
//            ]
//        ];

        $cards = $this->getAllCards();

        $jugada = $request->jugada;
        $idUser = auth()->id();

        $game = session('game');

        //segun la card ocurre un efecto u otro
        switch ($cards[$jugada['idCard']['card']]){
            case 'Espía':
                break;
            case 'Guardia':
                $card_rival = reset($game['players'][$jugada['idRival']]['hand']);

                if($jugada['cardAdivinar'] === $card_rival){
                    //la card ha sido adivinada, el rival queda eliminado
                    $game['players'][$jugada['idRival']]['activePlayer'] = 0;

                    $status = 'removeuser';
                    $message = 'Carta adivinada, el player _ ha sido eliminado.';
                    $user_to_remove = $jugada['idRival'];
                }else{
                    $status = true;
                    $message = 'El player _ no tiene la card _ en su hand.';
                }
                break;
            case 'Sacerdote':
                $card_rival = reset($game['players'][$jugada['idRival']]['hand']);

                //TODO: devolver la card del rival al usuario que efectua la jugada por canal privado
                break;
            case 'Barón':
                unset($game['players'][$idUser]['hand'][$jugada['idCard']]);

                $card_player = reset($game['players'][$idUser]['hand']);
                $card_rival = reset($game['players'][$jugada['idRival']]['hand']);

                if($card_player < $card_rival){
                    $user_to_remove = $idUser;
                    $message = 'Jugador _ eliminado.';
                }else if($card_player > $card_rival){
                    $user_to_remove = $jugada['idRival'];
                    $message = 'Jugador _ eliminado.';
                }else if($card_player == $card_rival){
                    $status = true;
                    $message = 'Las cards de los players tal son iguales, ninguno eliminado.';
                }
                break;
            case 'Doncella':
                //TODO: pensar una forma distinta, más óptima, de comprobar si el player está protegido
                //TODO: pensar una forma de comprobar cuando ha acabado el turno para eliminar la proteccion
                $game['players'][$idUser]['protegidoJugador'] = 1;
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

                //vaciar el array de hand, para descardr la card
                $game['players'][$player_afectado]['hand'] = reset($game['deck']);
                array_shift($game['deck']);

                break;
            case 'Canciller':
                //TODO: robar dos cards del deck, mostrar las al usuario, decidir con cual de las 3 se queda
                break;
            case 'Rey':
                //TODO: conservar clave y valor de las cards al extraerlas del deck para añadirlas a la hand y viceversa
                unset($game['players'][$idUser]['hand'][$jugada['idCard']]);

                $card_player = reset($game['players'][$idUser]['hand']);
                $card_rival = reset($game['players'][$jugada['idRival']]['hand']);

                $game['players'][$jugada['idRival']]['hand'] = $card_rival;
                break;
            case 'Condesa':
                //TODO: revisar si tiene al rey o principe para jugar a la condesa,
                // esta card no tiene efecto, simplemente comprobar por frontend si tiene esa card en hand
                break;
            case 'Princesa':
                $user_to_remove = $idUser;
                $message = 'Jugador eliminado al descardr princesa.';
                break;
        }

        //eliminar de la hand la card utilizada. TODO: tal vez vaciar la hand de quién haya sido eliminado
        unset($game['players'][$idUser]['hand'][$jugada['idCard']]);
        session(['game' => $game]);

        //TODO: revisar a quien le toca jugar el turno

        $response=[
            'message' => $message,
        ];
    }
}
