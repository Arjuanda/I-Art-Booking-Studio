<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function show($id) {
    $booking = Booking::with('bookMaker')->findOrFail($id);
    
    if ($booking->status !== 'approved' && Auth::user()->role !== 'admin') {
        abort(403, 'Tiket belum tersedia');
    }

    return view('ticket', compact('booking'));
    }
}
