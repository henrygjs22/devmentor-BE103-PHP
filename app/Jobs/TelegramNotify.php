<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use App\Models\EventNotifyChannel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use NotificationChannels\Telegram\TelegramMessage;

class TelegramNotify implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $eventNotifyChannel;
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
        $telegramUserId = '6176808259';
        $messages = json_decode($this->eventNotifyChannel->message_json, true);
        $message = $messages[$this->user->language->code];
        
        $response = TelegramMessage::create()
            ->to($telegramUserId)
            ->content($message)
            ->send();
    }
}
