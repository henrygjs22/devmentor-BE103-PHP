<?php

namespace App\Http\Controllers;

use App\Jobs\LineNotify;
use App\Jobs\EmailNotify;
use Illuminate\Http\Request;
use App\Models\EventNotifyChannel;
use App\Http\Controllers\Controller;

class EventDispatchController extends Controller
{
    public function lineNotify(Request $request)
    {
        $eventNotifyChannel = EventNotifyChannel::find(
            $request->event_notify_channel_id
        );
        $user = auth()->user();

        LineNotify::dispatchSync($eventNotifyChannel, $user);

        return response()->json(['message' => 'Line Notify sent']);
    }

    public function emailNotify(Request $request)
    {
        $eventNotifyChannel = EventNotifyChannel::find(
            $request->event_notify_channel_id
        );
        $user = auth()->user();
    
        EmailNotify::dispatchSync($eventNotifyChannel, $user);
    
        return response()->json(['message' => 'Email Notification sent']);
    }
}
