<?php

namespace App\Http\Services;

use App\Http\Requests\PostEventRequest;
use App\Http\Repositories\EventRepository;
use App\Http\Repositories\EventNotifyChannelRepository;

class EventService
{
    /**
     * @var EventRepository
     */
    private $eventRepository;

    /**
     * @var EventNotifyChannelRepository
     */
    private $eventNotifyChannelRepository;

    /**
     * @param  EventRepository  $eventRepository
     * @param  EventNotifyChannelRepository  $eventNotifyChannelRepository
     */
    public function __construct(
        EventRepository $eventRepository,
        EventNotifyChannelRepository $eventNotifyChannelRepository
    ) {
        $this->eventRepository = $eventRepository;
        $this->eventNotifyChannelRepository = $eventNotifyChannelRepository;
    }

    public function getAllEvents()
    {
        return $this->eventRepository->getAllEvents();
    }

    public function getEvent(int $eventId)
    {
        return $this->eventRepository->getEvent($eventId);
    }

    public function createEvent(PostEventRequest $request)
    {
        $user = auth()->user();
        $event = $this->eventRepository->createEvent(
            $request->only(['name', 'trigger_time']),
            $user->id
        );

        $this->eventNotifyChannelRepository->createByEventId(
            $event->id,
            $request->notify_channel_ids,
            json_encode($request->messages)
        );
    }
    
    public function updateEvent(int $eventId, PostEventRequest $request)
    {
        $user = auth()->user();
        $event = $this->eventRepository->updateEvent(
            $eventId,
            $request->only(['name', 'trigger_time'])
        );        
        if ($event->user_id !== $user->id) {
            throw new \Exception();
        }

        $this->eventNotifyChannelRepository->deleteByEventId($eventId);

        $this->eventNotifyChannelRepository->updateByEventId(
            $event->id,
            $request->notify_channel_ids,
            json_encode($request->messages)
        );
    }

    public function deleteEvent(int $eventId)
    {
        // Must delete model with foreign key first
        $this->eventNotifyChannelRepository->deleteByEventId($eventId);   
        
        $user = auth()->user();
        $event = $this->eventRepository->getEvent($eventId);
        if ($event->user_id !== $user->id) {
            throw new \Exception();
        }
        $this->eventRepository->deleteEvent($eventId);       
    }
}