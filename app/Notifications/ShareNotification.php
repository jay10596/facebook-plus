<?php

namespace App\Notifications;

use App\Http\Resources\User as UserResource;
use App\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ShareNotification extends Notification
{
    use Queueable;

    //Create a new notification instance.
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    //Get the notification's delivery channels.
    public function via($notifiable)
    {
        return ['database'];
    }

    //Get the array representation of the notification.
    public function toArray($notifiable)
    {
        return [
            'user' => new UserResource($this->post->user),
            'content' => $this->post,
            'message' => 'Shared your post: '
        ];
    }
}
