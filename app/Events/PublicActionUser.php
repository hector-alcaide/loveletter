<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PublicActionUser implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $idGame;
    public $message;
    public $changeTurn;
    public $steal;

    /**
     * Create a new event instance.
     */
    public function __construct($idGame, $message, $changeTurn, $steal = false)
    {
        $this->idGame = $idGame;
        $this->message = $message;
        $this->changeTurn = $changeTurn;
        $this->steal = $steal;
    }

    public function broadcastWith()
    {
        return [
            'idGame' => $this->idGame,
            'message' => $this->message,
            'changeTurn' => $this->changeTurn,
            'steal' => $this->steal
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
            new PresenceChannel('play.game.'.$this->idGame),
        ];
    }
}
