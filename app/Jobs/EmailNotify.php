<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\EventNotifyChannel;
use Illuminate\Queue\SerializesModels;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Notifications\Messages\MailMessage;

class EmailNotify extends Notification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var EventNotifyChannel
     */
    private $eventNotifyChannel;

    /**
     * @var User
     */
    private $user;
    
    /**
     * Create a new job instance.
     */
    public function __construct(EventNotifyChannel $eventNotifyChannel, User $user)
    {
        $this->eventNotifyChannel = $eventNotifyChannel;
        $this->user = $user;
    }
    
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }
    
    public function toMail($notifiable)
    {
        $messages = json_decode($this->eventNotifyChannel->message_json, true);
        $message = Arr::get($messages, $this->user->language->code);

        return (new MailMessage)
            ->line($message)
            ->action('View Event', url('/events/'.$this->eventNotifyChannel->event->id));
    }

    public function toArray($notifiable)
    {
        return [];
    }
}
