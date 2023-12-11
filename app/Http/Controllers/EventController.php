<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all(); //生成Colletion物件 
        // return $events;
        return response()->json($events->all()); //Colletion的方法
    }

    public function store(Request $request)
    {
        $data = $request->only(['name', 'trigger_time']);
        Event::create($data);
        return response()->json(['status' => 'OK']);
    }

    public function show(string $id)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }
        return response()->json($event);
    }

    public function update(string $id, Request $request)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }
        $data = $request->only(['name', 'trigger_time']);
        $event->fill($data)->save();
        return response()->json($event);
    }

    public function delete(string $id)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }
        $event->delete();
        return response()->json(['status' => 'deleted']);
    }
}
