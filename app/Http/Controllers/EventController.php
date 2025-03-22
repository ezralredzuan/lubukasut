<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('created_at', 'desc')->paginate(10);
        return view('content.pages.event-list', compact('events'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Title' => 'required|string|max:255',
            'Status' => 'required|string|max:50',
        ]);

        $event = Event::create([
            'Title' => $request->Title,
            'Status' => $request->Status,
            'StaffID' => 1,
        ]);

        return redirect()->route('events.builder', ['id' => $event->EventID]);
    }

    public function update(Request $request, $id)
    {
        $event = Event::where('EventID', $id)->firstOrFail();
        $event->update([
            'Title' => $request->Title,
            'Status' => $request->Status,
        ]);

        return redirect()->route('events.index')->with('success', 'Event updated successfully.');
    }

    public function destroy($id)
    {
        $event = Event::where('EventID', $id)->firstOrFail();
        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
    }
}