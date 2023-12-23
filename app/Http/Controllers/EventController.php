<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\EventNotifyChannel;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostEventRequest;

class EventController extends Controller
{
    public function indexN1()
    {
        // ORM
        $events = Event::all(); //Create collection object
        // dd($events);
        $response = [];
        foreach ($events as $event) {
            $notifyChannels = [];
            // SELECT * FROM event_notify_channel WHERE event_id = 1
            foreach ($event->eventNotifyChannels as $eventNotifyChannel) {
                $notifyChannels[] = [
                    'id' => $eventNotifyChannel->id,
                    'messages' => json_decode($eventNotifyChannel->message_json, true),
                ];
            }
            $response[] = [
                'id' => $event->id,
                'name' => $event->name,
                'trigger_time' => $event->trigger_time,
                'notify_channels' => $notifyChannels,
            ];
        }
        return response()->json($response); //Method of collection
    }

    public function index()
    {
        // query builder
        $events = Event::with('eventNotifyChannels')
            ->select('events.id', 'events.name', 'trigger_time')
            ->get();
        // dd($events);
        $response = [];
        foreach ($events as $event) {
            $notifyChannels = [];
            // SELECT * FROM event_notify_channel WHERE event_id = 1
            foreach ($event->eventNotifyChannels as $eventNotifyChannel) { 
                $notifyChannels[] = [
                    'id' => $eventNotifyChannel->id,
                    'messages' => json_decode($eventNotifyChannel->message_json, true),
                ];
            }
            $response[] = [
                'id' => $event->id,
                'name' => $event->name,
                'trigger_time' => $event->trigger_time,
                'notify_channels' => $notifyChannels,
            ];
        }
        return response()->json($response);
    }

    public function store(PostEventRequest $request)
    {
        $event = Event::create([
            'name' => $request->name,
            'trigger_time' => $request->trigger_time,
        ]);

        foreach ($request->notify_channel_ids as $notifyChannelId) {
            EventNotifyChannel::create([
                'event_id' => $event->id,
                'notify_channel_id' => $notifyChannelId,
                'message_json' => json_encode($request->messages),
            ]);
        }  
        return response()->json(['status' => 'OK']);
    }

    public function show(string $id)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }
        $response = [];
        $notifyChannels = [];
        foreach ($event->eventNotifyChannels as $eventNotifyChannel) {
            $notifyChannels[] = [
                'id' => $eventNotifyChannel->id,
                'messages' => json_decode($eventNotifyChannel->message_json, true),
            ];
        }
        $response[] = [
            'id' => $event->id,
            'name' => $event->name,
            'trigger_time' => $event->trigger_time,
            'notify_channels' => $notifyChannels,
        ];
        return response()->json($response);
    }

    public function update(string $id, PostEventRequest $request)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }
        $data = $request->only(['name', 'trigger_time']);
        $event->fill($data)->save();

        EventNotifyChannel::where('event_id', $event->id)->delete();
        foreach ($request->notify_channel_ids as $notifyChannelId)  {           
            $data = $request->only(['notify_channel_ids', 'messages']);
            EventNotifyChannel::create([
                'event_id' => $event->id,
                'notify_channel_id' => $notifyChannelId,
                'message_json' => json_encode($request->messages),
            ]);
        }
        return response()->json(['status' => 'OK']);
    }

    public function delete(string $id)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }       
        // Must delete model with foreign key first
        EventNotifyChannel::where('event_id', $event->id)->delete();
        $event->delete();
        return response()->json(['status' => 'OK']);
    }
}
