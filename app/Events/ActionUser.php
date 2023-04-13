<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ActionUser implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $user;
    public $data;
    public $userid;
    public $action;
    public $partidas =23243;
    public static $turno =0;

    public function __construct($data, $userid,$action,$user)
    {
        $this->data = $data;
        //$userid = 5;
        $this->userid = $userid;
        $this->action = $action;
        $this->user = $user;

        Action::$turno = Action::$turno + 1;
    }


    public function broadcastWith()
    {
        //echo"broadcastWith";
        return  [
            'userid' => $this->userid,
            'action' => $this->action,
            'test' => $this->user];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('gameuser.'.$this->data.'.'.$this->userid);
    }


}
