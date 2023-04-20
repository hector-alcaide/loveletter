<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreateGame implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $idPartida;
    public $idJugador1;

    /**
     * Create a new event instance.
     */
    public function __construct($idPartida, $idJugador1)
    {
        $this->idPartida = $idPartida;
        $this->idJugador1 = $idJugador1;
    }

    public function broadcastWith()
    {
        return [
            'idPartida' => $this->idPartida,
            'idJugador1' => $this->idJugador1
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('games.list')
        ];
    }
}
