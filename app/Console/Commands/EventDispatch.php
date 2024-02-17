<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Jobs\LineNotify;
use App\Jobs\EmailNotify;
use App\Models\EventUser;
use App\Jobs\TelegramNotify;
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
        $events = Event::where('trigger_time', '<=', now())->get();
        foreach ($events as $event) {
            $subscriptions = EventUser::where('event_id', $event->id)->get();
            foreach ($subscriptions as $subscription) {
                $user = $subscription->subscribeUser;
                $eventNotifyChannels = $event->eventNotifyChannels;
                foreach ($eventNotifyChannels as $eventNotifyChannel) {
                    $id = $eventNotifyChannel->notify_channel_id;
                    if ($id === EventNotifyChannel::EMAIL) {
                        EmailNotify::dispatchSync($eventNotifyChannel, $user);
                        $this->info('Email dispatched successfully');      
                        return 0;
                    } else if ($id === EventNotifyChannel::LINE) {
                        LineNotify::dispatchSync($eventNotifyChannel, $user);
                        $this->info('LINE dispatched successfully');      
                        return 0;
                    } else if ($id === EventNotifyChannel::TELEGRAM) {
                        TelegramNotify::dispatchSync($eventNotifyChannel, $user);
                        $this->info('LINE dispatched successfully');      
                        return 0;
                    }
                }               
            }
        }       
    }
}
