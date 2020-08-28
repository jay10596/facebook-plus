<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoginNotification extends Notification
{
    use Queueable;

    //Create a new notification instance.
    public function __construct($user)
    {
        $this->user = $user;
    }

    //Get the notification's delivery channels.
    public function via($notifiable)
    {
        return ['mail'];
    }


    //Get the mail representation of the notification.
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->greeting('Hello '. $this->user->name.'!')
                    ->line('Welcome to Facebook Plus.')
                    ->action('Visit your profile', url('/users/'.$this->user->id))
                    ->line('Thank you for using our application!');
    }
}
