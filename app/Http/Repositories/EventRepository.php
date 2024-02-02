<?php

namespace App\Http\Repositories;

use App\Models\Event;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EventRepository
{
    public function getAllEvents()
    {
        // ORM: $events = Event::all() -> slow
        // Use query builder
        return Event::with('eventNotifyChannels')
            ->select('events.id', 'events.name', 'trigger_time')
            ->paginate(2);
    }

    public function getEvent(int $eventId)
    {
        return Event::findOrFail($eventId);
    }

    public function createEvent(array $data, int $userId)
    {
        $event = Event::create([
            'name' => $data['name'],
            'trigger_time' => $data['trigger_time'],
            'user_id' => $userId,
        ]);
        return $event;
    }

    public function updateEvent(int $eventId, array $data)
    {
        $event = Event::findOrFail($eventId);
        $event->fill($data)->save();
        return $event;
    }

    public function deleteEvent(int $eventId)
    {
        Event::where('id', $eventId)->delete();
    }
}