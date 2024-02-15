<?php

namespace App\Console\Commands;

use App\Jobs\LineNotify;
use App\Models\EventUser;
use Illuminate\Console\Command;
use App\Models\EventNotifyChannel;

class EventDispatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event-dispatch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = EventUser::find(1)->subscribeUser()->first();
        
        $eventNotifyChannel = EventNotifyChannel::query()
            ->where('event_id', 4)
            ->where('notify_channel_id', EventNotifyChannel::LINE)
            ->first();

        LineNotify::dispatchSync($eventNotifyChannel, $user);

        return response()->json(['message' => 'Line Notify sent']);
    }
}
