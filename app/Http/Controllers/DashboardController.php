<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Documentation;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function index() {
        $total = Booking::count();
        $pending = Booking::where('status', 'pending')->count();
        $approved = Booking::where('status', 'approved')->count();
        $rejected = Booking::where('status', 'rejected')->count();

        $admin = User::where('role', 'admin')->count();
        $karyawan = User::where('role', 'karyawan')->count();

        $events = Event::count();
        $documentations = Documentation::count();

        $pendingBookings = Booking::with('bookMaker')
            ->where('status', 'pending')
            ->latest()
            ->take(3)
            ->get();

        $todayBookings = Booking::with('bookMaker')
            ->whereDate('start', today())
            ->whereIn('status', ['approved', 'pending'])
            ->orderBy('start', 'asc')
            ->get();
        
        $peakHours = Booking::select(
                DB::raw('HOUR(start) as hour'),
                DB::raw('count(*) as total')
            )
            ->groupBy('hour')
            ->orderByDesc('total')
            ->take(3)
            ->get();

        $topUsers = Booking::select(
                'user_id',
                DB::raw('count(*) as total')
            )
            ->with('bookMaker')
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->take(3)
            ->get();

        return view('dashboard', compact(
            'pending', 'approved', 'rejected',
            'admin', 'karyawan','total','topUsers','peakHours',
            'events', 'documentations',
            'pendingBookings', 'todayBookings'
        ), [
            'title' => 'Dashboard'
        ]);
    }
}
