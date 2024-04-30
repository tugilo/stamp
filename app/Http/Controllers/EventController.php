<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Organizer;
use App\Models\Venue;
use App\Models\Area;
use App\Models\City;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('organizer', 'venue', 'area', 'city')->get();
        return view('events.index', compact('events'));
    }

    public function create()
    {
        $organizers = Organizer::all();
        $venues = Venue::all();
        $areas = Area::activeOrdered()->get();
        $cities = City::activeOrdered()->get();

        return view('events.create', compact('organizers', 'venues', 'areas', 'cities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_name' => 'required|string|max:255',
            'organizer_id' => 'required|exists:organizers,id',
            'venue_id' => 'required|exists:venues,id',
            'area_id' => 'required|exists:areas,id',
            'city_id' => 'required|exists:cities,id',
            'event_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'description' => 'nullable|string',
            'announcement_url' => 'nullable|url',
            'code' => 'required|string|max:4',
            'stamp_count' => 'required|integer|min:1',
        ]);

        Event::create($request->all());

        return redirect()->route('events.index')->with('success', '新しいイベントが登録されました。');
    }

    public function edit(Event $event)
    {
        $organizers = Organizer::all();
        $venues = Venue::all();
        $areas = Area::activeOrdered()->get(); // スコープを使用して並べ替え
        $cities = City::activeOrdered()->get(); // スコープを使用して並べ替え
    
        return view('events.edit', compact('event', 'organizers', 'venues', 'areas', 'cities'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'event_name' => 'required|string|max:255',
            'organizer_id' => 'required|exists:organizers,id',
            'venue_id' => 'required|exists:venues,id',
            'area_id' => 'required|exists:areas,id',
            'city_id' => 'required|exists:cities,id',
            'event_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'description' => 'nullable|string',
            'announcement_url' => 'nullable|url',
            'code' => 'required|string|max:4',
            'stamp_count' => 'required|integer|min:1',
        ]);

        $event->update($request->all());

        return redirect()->route('events.index')->with('success', 'イベント情報が更新されました。');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('events.index')->with('success', 'イベントが削除されました。');
    }
}
