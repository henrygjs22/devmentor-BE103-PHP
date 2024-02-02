<?php

namespace App\Http\Repositories;

use App\Models\EventNotifyChannel;

class EventNotifyChannelRepository
{
    public function createByEventId(
        int $eventId,
        array $notifyChannelIds,
        string $messageJson
    ) {
        foreach ($notifyChannelIds as $notifyChannelId) {
            EventNotifyChannel::create([
                'event_id' => $eventId,
                'notify_channel_id' => $notifyChannelId,
                'message_json' => $messageJson,
            ]);
        }
    }
    
    public function deleteByEventId(int $eventId)
    {
        EventNotifyChannel::where('event_id', $eventId)->delete();
    }

    public function updateByEventId(
        int $eventId,
        array $notifyChannelIds,
        string $messageJson
    ) {
        foreach ($notifyChannelIds as $notifyChannelId) {
            EventNotifyChannel::create([
                'event_id' => $eventId,
                'notify_channel_id' => $notifyChannelId,
                'message_json' => $messageJson,
            ]);
        }
    }
}