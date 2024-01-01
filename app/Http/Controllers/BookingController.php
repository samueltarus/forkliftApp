<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::all();
        return View::make('$bookings.forklifts.index')
            ->with('bookings', $bookings);

    }
    public function create()
    {
        return view('bookings.create');
    }

    public function store(Request $request)
    {
        Booking::create($request->all());
        return redirect()->route('frontend.forklifts.index')
            ->with('success', 'Forklift created successfully.');

    }

    public function show($id)
    {
        $booking = Booking::find($id);
        return view('bookings.show', compact('booking'));
    }

    public function edit($id)
    {
        $booking = Booking::find($id);
        return view('booking.edit', compact('booking'));
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::find($id);
        $booking->update($request->all());
        return redirect()->route('booking.index')
            ->with('success', 'Updated successfully.');

    }

    public function destroy($id)
    {
        $booking = Booking::find($id);
        $booking->delete();
        return redirect()->route('booking.index')
            ->with('success', 'Deleted successfully');

    }
}
