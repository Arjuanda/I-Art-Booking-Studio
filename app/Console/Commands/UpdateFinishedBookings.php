<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Booking;
use Illuminate\Console\Command;

class UpdateFinishedBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:update-finished';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update bookings from approved to finished if end time has passed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $updated = Booking::where('status', 'approved')
            ->where('end_time', '<', now())
            ->update(['status' => 'finished']);

        $this->info("$updated bookings updated to finished");
    }
}
