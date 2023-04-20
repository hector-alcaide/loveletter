<?php

namespace App\Http\Controllers\API;

use App\Events\CreateGame;
use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Game;
use App\Models\User;
use Illuminate\Http\Request;

class games extends Controller
{
    public function getGamesActive(){
        $result = Game::select('idPartida')->where('empezada', 0)->get();

        return $result;
    }

    public function nuevaPartida(Request $request){

        $new_game = Game::create([
            'idJugador1' => auth()->id(),
            'tipo' => $request->tipo,
            'numeroVictoriasMaximas' => $request->numeroVictoriasMaximas,
        ]);

        $partida = [
            'idPartida' => $new_game->idPartida,
        ];

        session(['partida' => $partida]);

        broadcast(new CreateGame($new_game->idPartida));

        return $new_game;
    }

    public function unirsePartida(Request $request){
        
    }

    public function empezarPartida(){

//        $primera_ronda = $this->nuevaRonda();

        $mazo = $this->getCartas();

        /** TODO: guardar el objeto completo de carta en la mano, en vez de sus ids.
            Dónde, igual que en el mazo, la clave es la id de la carta
         **/
        $jugadores = [
            '2' => [
                'mano' => [
                    'carta1' => 3,
                    'carta2' => 5,
                ],
                'activoJugador' => 1
            ],
            '7' => [
                'mano' => [
                    'carta1' => 9,
                ],
                'activoJugador' => 1
            ],
        ];

        $partida = [
            'idPartida' => 1,
            'jugadores' => $jugadores,
            'mazo' => $mazo,
            'numRonda' => 1,
            'jugadorTurno' => 2
        ];

        session(['partida' => $partida]);

        $test = session('partida');

        $id = auth()->id();

        $mano = [
            3 => new Card(3, 1, 'Guardia', '/resources/images/cartas/guardia.png'),
            8 => new Card(8, 1, 'Guardia', '/resources/images/cartas/guardia.png'),
        ];

        $test1_mazo = $this->getCartas();
        $test2_mazo = $this->getCartas();

        $primera_carta = reset($test2_mazo);
        $clave_primera_carta = key($test2_mazo);

//        $posicion = array_search('7', array_keys($jugadores));

//        if($posicion == $partida['jugadorTurno']){
//
//        }

        shuffle($mazo);

        return json_encode($mazo);
    }

    public function getCartas(){

        //array de cartas, mazo por defecto ordenado por nivel, donde idCarta es la key
        $cartas = [
            1 => new Card(1, 0, 'Espía', '/resources/images/cartas/espia.png'),
            2 => new Card(2, 0, 'Espía', '/resources/images/cartas/espia.png'),
            3 => new Card(3, 1, 'Guardia', '/resources/images/cartas/guardia.png'),
            4 => new Card(4, 1, 'Guardia', '/resources/images/cartas/guardia.png'),
            5 => new Card(5, 1, 'Guardia', '/resources/images/cartas/guardia.png'),
            6 => new Card(6, 1, 'Guardia', '/resources/images/cartas/guardia.png'),
            7 => new Card(7, 1, 'Guardia', '/resources/images/cartas/guardia.png'),
            8 => new Card(8, 1, 'Guardia', '/resources/images/cartas/guardia.png'),
            9 => new Card(9, 2, 'Sacerdote', '/resources/images/cartas/sacerdote.png'),
            10 => new Card(10, 2, 'Sacerdote', '/resources/images/cartas/sacerdote.png'),
            11 => new Card(11, 3, 'Barón', '/resources/images/cartas/baron.png'),
            12 => new Card(12, 3, 'Barón', '/resources/images/cartas/baron.png'),
            13 => new Card(13, 4, 'Doncella', '/resources/images/cartas/doncella.png'),
            14 => new Card(14, 4, 'Doncella', '/resources/images/cartas/doncella.png'),
            15 => new Card(15, 5, 'Príncipe', '/resources/images/cartas/principe.png'),
            16 => new Card(16, 5, 'Príncipe', '/resources/images/cartas/principe.png'),
            17 => new Card(17, 6, 'Canciller', '/resources/images/cartas/canciller.png'),
            18 => new Card(18, 6, 'Canciller', '/resources/images/cartas/canciller.png'),
            19 => new Card(19, 7, 'Rey', '/resources/images/cartas/rey.png'),
            20 => new Card(20, 8, 'Condesa', '/resources/images/cartas/condesa.png'),
            21 => new Card(21, 9, 'Princesa', '/resources/images/cartas/princesa.png'),
        ];

        return $cartas;
    }

    public function nuevaRonda(){
        //TODO: apartar la primera carta

        $mazo = $this->getCartas();

        $partida = session('partida');

        //apunta el puntero a la 1a posicion del array mazo
        reset($mazo);

        foreach ($partida['jugadores'] as $jugador) {
            $jugador['mano'] = [key($mazo) => reset($mazo)];
            unset($mazo[key($mazo)]);
        }

        //TODO: comprobar el numero de ronda y añadirlo a la sesion de partida
        $partida['mazo'] = $mazo;
        session(['partida' => $partida]);

        //TODO: devolver la sesion a todos los jugadores, al frontend, com
//        return $mazo;
    }

    public function robarCarta(Request $request){

        $partida = session('partida');

        reset($partida['mazo']);

        $partida[$jugadores][$request->idJugador]['mano'] = [key($mazo) => reset($mazo)];
        unset($mazo[key($mazo)]);
    }

    public function resolverJugada(Request $request){

        //posibles parametros necesitados para operar los efectos de las cartas:
//        $parameters = [
//            'idPartida' => $request->idPartida,
//            'idJugador' => $request->idJugador,
//            'jugada' => $request->cartaJugada
//                //carta jugada:
//                [
//                'idCarta',
//                'cartaMano' => 'carta1', //TAL VEZ ESTO SOBRA
//                'idRival',
//                'cartaAdivinar'
//            ]
//        ];

        $cartas = $this->getCartas();

        $jugada = $request->jugada;
        $idUsuario = auth()->id();

        $partida = session('partida');

        //segun la carta ocurre un efecto u otro
        switch ($cartas[$jugada['idCarta']['carta']]){
            case 'Espía':
                break;
            case 'Guardia':
                $carta_rival = reset($partida['jugadores'][$jugada['idRival']]['mano']);

                if($jugada['cartaAdivinar'] === $carta_rival){
                    //la carta ha sido adivinada, el rival queda eliminado
                    $partida['jugadores'][$jugada['idRival']]['activoJugador'] = 0;

                    $status = 'removeuser';
                    $message = 'Carta adivinada, el jugador _ ha sido eliminado.';
                    $user_to_remove = $jugada['idRival'];
                }else{
                    $status = true;
                    $message = 'El jugador _ no tiene la carta _ en su mano.';
                }
                break;
            case 'Sacerdote':
                $carta_rival = reset($partida['jugadores'][$jugada['idRival']]['mano']);

                //TODO: devolver la carta del rival al usuario que efectua la jugada por canal privado
                break;
            case 'Barón':
                unset($partida['jugadores'][$idUsuario]['mano'][$jugada['idCarta']]);

                $carta_jugador = reset($partida['jugadores'][$idUsuario]['mano']);
                $carta_rival = reset($partida['jugadores'][$jugada['idRival']]['mano']);

                if($carta_jugador < $carta_rival){
                    $user_to_remove = $idUsuario;
                    $message = 'Jugador _ eliminado.';
                }else if($carta_jugador > $carta_rival){
                    $user_to_remove = $jugada['idRival'];
                    $message = 'Jugador _ eliminado.';
                }else if($carta_jugador == $carta_rival){
                    $status = true;
                    $message = 'Las cartas de los jugadores tal son iguales, ninguno eliminado.';
                }
                break;
            case 'Doncella':
                //TODO: pensar una forma distinta, más óptima, de comprobar si el jugador está protegido
                //TODO: pensar una forma de comprobar cuando ha acabado el turno para eliminar la proteccion
                $partida['jugadores'][$idUsuario]['protegidoJugador'] = 1;
                break;
            case 'Príncipe':
                //TODO: elemento afectado puede ser rival o jugador mismo, por lo que podria verse como enviar este dato
                // (idElemento, tanto para rival como jugador¿?) para hacer lo mas legible

                $jugador_afectado = $jugada['idRival'];

                //comprobar si tiene la princesa
                if(isset($partida['jugadores'][$jugador_afectado]['mano'][21])){
                    $user_to_remove = $jugador_afectado;
                    $message = 'Jugador eliminado al descartar princesa.';
                }

                //vaciar el array de mano, para descartar la carta
                $partida['jugadores'][$jugador_afectado]['mano'] = reset($partida['mazo']);
                array_shift($partida['mazo']);

                break;
            case 'Canciller':
                //TODO: robar dos cartas del mazo, mostrar las al usuario, decidir con cual de las 3 se queda
                break;
            case 'Rey':
                //TODO: conservar clave y valor de las cartas al extraerlas del mazo para añadirlas a la mano y viceversa
                unset($partida['jugadores'][$idUsuario]['mano'][$jugada['idCarta']]);

                $carta_jugador = reset($partida['jugadores'][$idUsuario]['mano']);
                $carta_rival = reset($partida['jugadores'][$jugada['idRival']]['mano']);

                $partida['jugadores'][$jugada['idRival']]['mano'] = $carta_rival;
                break;
            case 'Condesa':
                //TODO: revisar si tiene al rey o principe para jugar a la condesa,
                // esta carta no tiene efecto, simplemente comprobar por frontend si tiene esa carta en mano
                break;
            case 'Princesa':
                $user_to_remove = $idUsuario;
                $message = 'Jugador eliminado al descartar princesa.';
                break;
        }

        //eliminar de la mano la carta utilizada. TODO: tal vez vaciar la mano de quién haya sido eliminado
        unset($partida['jugadores'][$idUsuario]['mano'][$jugada['idCarta']]);
        session(['partida' => $partida]);

        //TODO: revisar a quien le toca jugar el turno

        $response=[
            'message' => $message,
        ];
    }
}