<?php

namespace App\Http\Controllers;

use App\Jobs\LineNotify;
use App\Jobs\EmailNotify;
use App\Jobs\TelegramNotify;
use Illuminate\Http\Request;
use App\Models\EventNotifyChannel;
use App\Http\Controllers\Controller;

class EventDispatchController extends Controller
{
    public function lineNotify(int $eventId)
    {
        $eventNotifyChannel = EventNotifyChannel::query()
            ->where('event_id', $eventId)
            ->where('notify_channel_id', EventNotifyChannel::LINE)
            ->first();

        if (!$eventNotifyChannel) {
            return response()->json(['message' => 'Event not found'], 404);
        }
        $user = auth()->user();

        LineNotify::dispatchSync($eventNotifyChannel, $user);

        return response()->json(['message' => 'Line Notify sent']);
    }

    public function emailNotify(int $eventId)
    {
        $eventNotifyChannel = EventNotifyChannel::query()
            ->where('event_id', $eventId)
            ->where('notify_channel_id', EventNotifyChannel::EMAIL)
            ->first();

        if (!$eventNotifyChannel) {
            return response()->json(['message' => 'Event not found'], 404);
        }
        $user = auth()->user();
    
        EmailNotify::dispatchSync($eventNotifyChannel, $user);
    
        return response()->json(['message' => 'Email Notification sent']);
    }

    public function telegramNotify(int $eventId)
    {
        $eventNotifyChannel = EventNotifyChannel::query()
            ->where('event_id', $eventId)
            ->where('notify_channel_id', EventNotifyChannel::TELEGRAM)
            ->first();

        if (!$eventNotifyChannel) {
            return response()->json(['message' => 'Event not found'], 404);
        }
        $user = auth()->user();
    
        TelegramNotify::dispatchSync($eventNotifyChannel, $user);
    
        return response()->json(['message' => 'Telegram Notification sent']);
    }
}
