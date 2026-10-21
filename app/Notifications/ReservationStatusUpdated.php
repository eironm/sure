<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Reservation;
use Illuminate\Notifications\Notification;

class ReservationStatusUpdated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $reservation;
    public $status;

    public function __construct(Reservation $reservation, string $status)
    {
        $this->reservation = $reservation;
        $this->status = $status;
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
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'reservation_id' => $this->reservation->id,
            'facility_name' => $this->reservation->facility->name,
            'status' => $this->status,
            'message' => "The status of your reservation for {$this->reservation->facility->name} (Booking Reference: {$this->reservation->booking_reference}) has been updated to {$this->status}.",
            'link' => route('guest.reservations.show', $this->reservation->id),
        ];
    }
}
