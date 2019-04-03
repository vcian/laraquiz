<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Log;

class QuizDashboardEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Information about the shipping status update.
     *
     * @var string
     */
    public $quizSlug;

    /**
     * @var object
     */
    public $players;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(String $quizSlug)
    {
        $this->quizSlug = $quizSlug;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $quizRepo = \App::make('App\Repositories\QuizRepository');
        $this->players = $quizRepo->getPlayersList($this->quizSlug);
        return new Channel('quiz-dashboard.' . $this->quizSlug);
    }
}
