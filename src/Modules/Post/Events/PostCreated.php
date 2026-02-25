<?php

namespace Nasirkhan\ModuleManager\Modules\Post\Events;

use Illuminate\Queue\SerializesModels;
use Nasirkhan\ModuleManager\Modules\Post\Models\Post;

class PostCreated
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public Post $post)
    {
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
