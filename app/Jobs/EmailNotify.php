<?php

namespace App\Jobs;

use App\Models\User;
use App\Mail\EventMail;
use Illuminate\Support\Arr;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\EventNotifyChannel;

use Illuminate\Support\Facades\Mail;
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
        $messages = json_decode($this->eventNotifyChannel->message_json, true);
        $message = $messages[$this->user->language->code];

        // $eventMail = new EventMail($this->user, $msg);
        $eventMail = app(EventMail::class, ['user' => $this->user, 'msg'=> $message]);
        Mail::to($this->user->email)->send($eventMail);
    }
}
