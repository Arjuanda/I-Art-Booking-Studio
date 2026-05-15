<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingStatusNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $booking;
    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
        'message' => $this->booking->status == 'approved'
            ?   'Jadwal '.$this->booking->start->format('d M Y H:i') 
                . ' - ' 
                . $this->booking->end->format('H:i')
            :  'Jadwal '.$this->booking->start->format('d M Y H:i')
                . ' - ' 
                . $this->booking->end->format('H:i'),
            'status' => $this->booking->status,
            'date' => $this->booking->date,
            'booking_id' => $this->booking->id
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
