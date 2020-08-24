<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Http\Resources\User as UserResource;

use App\Post;


class TagNotification extends Notification
{
    use Queueable;

    //Create a new notification instance.
    public function __construct($comment)
    {
        $this->comment = $comment;
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
            'user' => new UserResource($this->comment->user),
            'content' => $this->comment,
            'message' => 'Mentioned you in a comment: '
        ];
    }
}
