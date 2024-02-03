<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\EventNotifyChannel;
use Illuminate\Support\Facades\DB;
use App\Http\Services\EventService;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostEventRequest;

class EventController extends Controller
{
    public function index(EventService $eventService)
    {
        $events = $eventService->getAllEvents();       
        $response = [];
        foreach ($events as $event) {
            $notifyChannels = [];
            foreach ($event->eventNotifyChannels as $eventNotifyChannel) {
                $notifyChannels[] = [
                    'id' => $eventNotifyChannel->notify_channel_id,
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
        /** @var EventService $eventService */    
        $eventService = app(EventService::class);
        
        try {
            DB::beginTransaction();
            $eventService->createEvent($request);
            DB::commit();   
            return response()->json(['status' => 'OK']);
        } 
        catch (\Exception $e) {
            report($e);           
            DB::rollBack();
            return response()->json(['error' => 'Failed to create event'], 500);
        }
    }

    public function show(EventService $eventService, string $eventId)
    {
        $event = $eventService->getEvent($eventId);
        $response = [];
        $notifyChannels = [];
        foreach ($event->eventNotifyChannels as $eventNotifyChannel) {
            $notifyChannels[] = [
                'id' => $eventNotifyChannel->notify_channel_id,
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

    public function update(string $eventId, PostEventRequest $request)
    {
        /** @var EventService $eventService */    
        $eventService = app(EventService::class);
        
        try {
            DB::beginTransaction();   
            $eventService->updateEvent($eventId, $request);
            DB::commit();
            return response()->json(['status' => 'OK']);
        } catch (\Exception $e) {
            report($e);
            DB::rollBack();
            return response()->json(['error' => 'Failed to update event'], 500);
        }
    }

    public function delete(string $eventId)
    {                
        /** @var EventService $eventService */    
        $eventService = app(EventService::class);

        try {
            DB::beginTransaction();   
            $eventService->deleteEvent($eventId);
            DB::commit();
            return response()->json(['status' => 'OK']);
        } catch (\Exception $e) {
            report($e);
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete event'], 500);
        }   
    }
}
