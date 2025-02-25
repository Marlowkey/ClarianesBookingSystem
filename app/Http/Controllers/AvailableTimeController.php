<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\AvailableTime;
use App\Models\UnavailableDate;
use App\Models\UnavailableTimeSlot;

class AvailableTimeController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->get('date', now()->toDateString());

        $dayOfWeek = Carbon::parse($date)->format('l');

        $today = Carbon::today();

        $unavailableDate = UnavailableDate::where('date', $date)->first();

        $isDateUnavailable = UnavailableDate::where('date', $date)->exists();

        if ($dayOfWeek === 'Sunday' && !$isDateUnavailable) {
            UnavailableDate::create(['date' => $date]);
            $isDateUnavailable = true;
        }
        // Query to get available times that do NOT have unavailable time slots for the given date.
        $availableTimes = AvailableTime::whereDoesntHave('unavailableTimeSlots', function ($query) use ($date) {
            $query->where('date', $date);
        })->get();

        $unavailableTimes = UnavailableTimeSlot::whereDate('date', $date)
            ->with('availableTime') // Eager load the related AvailableTime
            ->get();

        $timeSlots = AvailableTime::all();

        // Return the view with the updated data
        return view('admin.pages.available-times.index', compact('availableTimes', 'unavailableTimes', 'timeSlots', 'date', 'dayOfWeek', 'isDateUnavailable', 'unavailableDate'));
    }

    public function edit($id)
    {
        $availableTime = AvailableTime::findOrFail($id);

        return view('available-times.edit', compact('availableTime'));
    }

    public function update(Request $request, $id)
    {
        $request->merge([
            'start_time' => substr($request->start_time, 0, 5), // Extract "H:i"
            'end_time' => substr($request->end_time, 0, 5), // Extract "H:i"
        ]);

        $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $availableTime = AvailableTime::findOrFail($id);

        $availableTime->update([
            'start_time' => $request->start_time . ':00', // Add seconds to match database format
            'end_time' => $request->end_time . ':00',
        ]);

        return redirect()->route('available-times.index')->with('success', 'Available time updated successfully.');
    }

    public function destroy($id)
    {
        $availableTime = AvailableTime::findOrFail($id);
        $availableTime->delete();

        return redirect()->route('available-times.index')->with('success', 'Available time deleted successfully.');
    }

    public function makeUnavailable(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $availableTime = AvailableTime::findOrFail($id);

        $exists = UnavailableTimeSlot::where('date', $request->date)->where('available_time_id', $availableTime->id)->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'This time slot is already unavailable for the selected date.');
        }

        UnavailableTimeSlot::create([
            'date' => $request->date,
            'available_time_id' => $availableTime->id,
        ]);
        return redirect()
            ->route('available-times.index', ['date' => $request->date])
            ->with('success', 'Time slot marked as unavailable.');
    }

    public function makeAvailable(Request $request, $id)
    {
        $request->validate([
            'date' => 'nullable|date',
        ]);

        $unavailableTimeSlot = UnavailableTimeSlot::findOrFail($id);

        $unavailableTimeSlot->delete();

        $date = $request->get('date', now()->toDateString());

        return redirect()
            ->route('available-times.index', ['date' => $date]) // Pass the date as a query string
            ->with('success', 'Time slot marked as available again.');
    }

    public function makeDateUnavailable(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $exists = UnavailableDate::where('date', $request->date)->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'This date is already marked as unavailable.');
        }

        UnavailableDate::create([
            'date' => $request->date,
        ]);

        // Redirect with the 'date' parameter included
        return redirect()
            ->route('available-times.index', ['date' => $request->date])
            ->with('success', 'Date marked as unavailable.');
    }

    public function makeDateAvailable($id, Request $request)
    {
        $request->validate([
            'date' => 'nullable|date',
        ]);

        $unavailableDate = UnavailableDate::findOrFail($id);

        $unavailableDate->delete();

        $date = $request->get('date', now()->toDateString());

        return redirect()
            ->route('available-times.index', ['date' => $date])
            ->with('success', 'Date marked as available again.');
    }

}
