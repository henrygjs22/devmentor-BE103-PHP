<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Bus\Queueable;
use App\Models\EventNotifyChannel;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class LineNotify implements ShouldQueue
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
        $accessToken = env('LINE_NOTIFY_TOKEN');
        $messages = json_decode($this->eventNotifyChannel->message_json, true);
        $message = Arr::get($messages, $this->user->language->code);

        $response = Http::asForm()->withHeaders([
            'Authorization' => "Bearer {$accessToken}",
        ])->post(
            'https://notify-api.line.me/api/notify',
            [
                'message' => $message,
            ]
        )->json();
    }
}
