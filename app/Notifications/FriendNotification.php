<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Http\Resources\User as UserResource;


class FriendNotification extends Notification
{
    use Queueable;

    //Create a new notification instance.
    public function __construct($user)
    {
        //For now we are notifying only one user's birthday that is today. We will deal with it later in detail for more users.
        $this->user = $user;
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
            'user' => new UserResource($this->user),
            'content' => 'sent',
            'message' => 'Has sent you Friend Request',
        ];
    }
}
