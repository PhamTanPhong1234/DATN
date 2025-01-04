<?php 
// app/Events/MessageSent.php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $username;
    public $message;

    /**
     * Create a new event instance.
     *
     * @param string $username
     * @param string $message
     */
    public function __construct($username, $message)
    {
        $this->username = $username;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel
     */
    public function broadcastOn()
    {
        return new Channel('my-channel');
    }

    /**
     * Get the event data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'username' => $this->username,
            'message' => $this->message,
        ];
    }
}
