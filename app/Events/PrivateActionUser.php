<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PrivateActionUser implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $idGame;
    public $idPlayer;
    public $message;
    public $newRound;

    /**
     * Create a new event instance.
     */
    public function __construct($idGame,$idPlayer, $message, $newRound = false)
    {
        $this->idGame = $idGame;
        $this->idPlayer = $idPlayer;
        $this->message = $message;
        $this->newRound = $newRound;
    }

    public function broadcastWith()
    {
        return [
            'idGame' => $this->idGame,
            'idPlayer' => $this->idPlayer,
            'message' => $this->message,
            'newRound' => $this->newRound
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
            new PresenceChannel('play.game.'.$this->idGame.'.player.'.$this->idPlayer),
        ];
    }
}
