<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class BookingsController extends Controller
{
    public function indexAdmin(Request $request) {
        $data_booking = Booking::with('bookMaker')->latest();
        $date = $request->query('date', now()->toDateString());
            $start = 7;
            $end = 22;

            $slots = [];

            for ($hour = $start; $hour < $end; $hour++) {
                $slots[] = [
                    'start' => sprintf('%02d:00', $hour),
                    'end' => sprintf('%02d:00', $hour + 1),
                ];
            }
            $bookedSlots = Booking::whereDate('start', $date)
                ->where('status', '!=', 'rejected')
                ->get()
                ->map(function ($item) {
                    return Carbon::parse($item->start)->format('H:i');
                })
                ->toArray();
                
        return view('admin.bookings',['title' => 'Booking', 'data' => $data_booking->get(), 'date' => $date, 'slots' => $slots, 'bookedSlots' => $bookedSlots ]);
    }

    public function listBooking(Request $request){
        if (!$request->start || !$request->end) {
            return response()->json([]);
        }

        $start = Carbon::createFromFormat(
            'Y-m-d\TH:i:sP',
            $request->start
        )->format('Y-m-d H:i:s');

        $end = Carbon::createFromFormat(
            'Y-m-d\TH:i:sP',
            $request->end
        )->format('Y-m-d H:i:s');

        $data_booking = Booking::whereBetween('start', [$start, $end])
            ->orWhereBetween('end', [$start, $end])
            ->get()
            ->map(fn ($item) => [
                'id' => $item->id,
                'title' => '',
                'start' => $item->start,
                'end' => $item->end,
                'allDay' => false,
                'classNames' => [$item->status],
                'extendedProps' => [
                    'status' => $item->status,
                ],
            ]);

        return response()->json($data_booking);
    }

    public function store(Request $request) {
        
        $request->validate([
            'start' => 'required',
            'end'   => 'required',
            'date' => 'required',
        ], [
            'start.required' => 'Jam mulai wajib diisi',
            'end.required'   => 'Jam selesai wajib diisi',
            'date.required'   => 'Tanggal wajib diisi',
        ]);
    
        $start = Carbon::parse($request->date . ' ' . trim($request->start));
        $end   = Carbon::parse($request->date . ' ' . trim($request->end));
        if ($start >= $end) {
            return back()
                ->withErrors(['end' => 'Jam selesai harus lebih besar dari jam mulai'])
                ->withInput();
        }
        
        $isConflict = Booking::where('status', '!=', 'rejected')
            ->where('start', '<', $end)
            ->where('end', '>', $start)
            ->exists();

        if ($isConflict) {
            return back()->withErrors([
                'time' => 'Jam sudah dibooking, silakan pilih jam lain.'
            ])->withInput();
        }

        $item = new Booking();
        $item -> user_id = Auth::id();
        $item->start = $start;
        $item->end = $end;
        if (Auth::user()->role === 'admin') {
            $item->status = 'approved';
        } else {
            $item->status = 'pending';
        }
        $item->save();

        return redirect()->route("admin.bookings");
    }

    public function edit($id) {
        $data_booking = Booking::with('bookMaker')->findOrFail($id);

    
        return response()->json([
            'id' => $data_booking->id,
            'date' => Carbon::parse($data_booking->start)->format('Y-m-d'),
            'start' => Carbon::parse($data_booking->start)->format('H:i'),
            'end' => Carbon::parse($data_booking->end)->format('H:i'),
            'status' => $data_booking->status,
            'user' => $data_booking->bookMaker
        ]);
    }

    public function update(Request $request, $id) {
        $data_booking = Booking::findOrFail($id);

        if ($request->has('status')) {
            $request->validate([
                'status' => 'required|in:approved,rejected',
            ]);

            $data_booking->status = $request->status;
            $data_booking->save();
            $data_booking->bookMaker->notifications()->create([
                'type' => 'booking_status',
                'data' => [
                    'start' => $data_booking->start,
                    'status' => $request->status,
                    'message' => 'Status booking',
                    'booking_id' => $data_booking->id
                ],
            ]);

            return back()->with('success', 'Status booking berhasil diubah.');
        }

        $request->validate([
            'date' => 'required|date',
            'start' => 'required',
            'end'   => 'required|after:start',
        ]);

        $start = Carbon::createFromFormat(
            'Y-m-d H:i',
            $request->date . ' ' . $request->start
        );
        $end = Carbon::createFromFormat(
            'Y-m-d H:i',
            $request->date . ' ' . $request->end
        );

        $isConflict = Booking::where('status', '!=', 'rejected')
            ->where('start', '<', $end)
            ->where('end', '>', $start)
            ->exists();

        if ($isConflict) {
            return back()->withErrors([
                'time' => 'Jam sudah dibooking, silakan pilih jam lain.'
            ])->withInput();
        }

        $data_booking->start = $start;
        $data_booking->end = $end;
        $data_booking->save();

        return back()->with('success', 'Booking berhasil diupdate');
    }

    public function destroy($id) {
        $booking = Booking::findOrFail($id);
        $booking->delete();
        //if ($user->profile_image && Storage::disk('public')->exists('images/'.$user->profile_image)) {
         //   Storage::disk('public')->delete('images/'.$user->profile_image);
        //}
        if (request()->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Booking berhasil dihapus'
            ]);
        }
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.bookings')->with('success', 'Data berhasil dihapus');
        }
        return redirect()->route('user.booking')->with('success', 'Booking berhasil dihapus');

    }

    public function indexUser(Request $request) {
        $data_booking = Booking::with('bookMaker')->where('user_id', Auth::id())->latest()->get();
        $user = Auth::user();
        $date = $request->query('date', now()->toDateString());
            $start = 7;
            $end = 22;

            $slots = [];
            $now = now();

            for ($hour = $start; $hour < $end; $hour++) {
                $slots[] = [
                    'start' => sprintf('%02d:00', $hour),
                    'end' => sprintf('%02d:00', $hour + 1),
                ];
            }
            $bookedSlots = Booking::whereDate('start', $date)
                ->where('status', '!=', 'rejected')
                ->get()
                ->map(function ($item) {
                    return Carbon::parse($item->start)->format('H:i');
                })
                ->toArray();
        return view('user.booking',['title' => 'Booking', 'data' => $data_booking, 'date' => $date, 'slots' => $slots, 'bookedSlots' => $bookedSlots, 'now' => $now, 'user' => $user ]);
    }

    public function userStore(Request $request) {
        $slots = $request->slots;
        $date  = $request->date;

        usort($slots, fn($a, $b) => strcmp($a['start'], $b['start']));

        $startSlot = $slots[0]['start'];
        $endSlot   = $slots[count($slots) - 1]['end'];

        $start = Carbon::createFromFormat('Y-m-d H:i:s', "$date $startSlot:00");
        $end   = Carbon::createFromFormat('Y-m-d H:i:s', "$date $endSlot:00");

        $isConflict = Booking::whereDate('start', $date)
            ->where('status', '!=', 'rejected')
            ->where('start', '<', $end)
            ->where('end', '>', $start)
            ->exists();

        if ($isConflict) {
            return response()->json([
                'status' => 'error',
                'message' => "Slot ".$start->format('H:i')." - ".$end->format('H:i')." sudah dibooking."
            ], 409);
        }

        Booking::create([
            'start'   => $start,
            'end'     => $end,
            'status'  => 'pending',
            'user_id' => Auth::id(),
        ]);


    return response()->json([
        'status' => 'success',
        'message' => 'Booking berhasil dibuat!'
    ]);
    }
    public function getBookingsByDate(Request $request) {
        $date = $request->query('date', now()->toDateString());

        $bookings = Booking::whereDate('start', $date)
            ->where('status', '!=', 'rejected')
            ->get();

        return response()->json($bookings);
    }
    public function approve($id) {
        $booking = Booking::findOrFail($id);
        $booking->status = 'approved';
        $booking->save();

        $booking->bookMaker->notifications()->create([
            'type' => 'booking_status',
            'data' => [
                'booking_id' => $booking->id,
                'start' => $booking->start,
                'status' => 'approved',
                'message' => 'Status booking',
            ],
        ]);

        return back()->with('success', 'Booking berhasil di-approve');
    }

    public function reject($id) {
        $booking = Booking::findOrFail($id);
        $booking->status = 'rejected';

        $booking->bookMaker->notifications()->create([
            'type' => 'booking_status',
            'data' => [
                'booking_id' => $booking->id,
                'start' => $booking->start,
                'status' => 'rejected',
                'message' => 'Status booking',
            ],
        ]);
        $booking->save();

        return back()->with('success', 'Booking berhasil di-reject');
    }

    public function markAllAsRead() {
        $user = Auth::user();

        Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back();
    }
}
