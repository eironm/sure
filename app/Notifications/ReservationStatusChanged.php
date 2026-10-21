<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use App\Mail\BrandedReservationMail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class ReservationStatusChanged extends Notification implements ShouldBroadcastNow
{


    public $reservation;
    public $status;

    /**
     * Create a new notification instance.
     */
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
        return ['database', 'broadcast', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * Uses a custom Mailable (BrandedReservationMail) so the brand logo
     * is properly embedded as a CID image — MailMessage doesn't support this.
     */
    public function toMail(object $notifiable): BrandedReservationMail
    {
        return new BrandedReservationMail($this->reservation, $this->status);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        // $message is removed from here since we generate it inside the array

        return [
            'reservation_id' => $this->reservation->id,
            'facility_name' => $this->reservation->facility->name,
            'status' => $this->status,
            'message' => $this->getMessage($notifiable),
            'action_url' => $notifiable->role === 'admin' 
                ? route('admin.reservations.show', $this->reservation->id, false)
                : route('guest.history', [], false),
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'reservation_id' => $this->reservation->id,
            'facility_name' => $this->reservation->facility->name,
            'status' => $this->status,
            'message' => $this->getMessage($notifiable),
            'unread_count' => $notifiable->unreadNotifications()->count() + 1,
        ]);
    }

    protected function getMessage(object $notifiable): string
    {
        $isAdmin = $notifiable->role === 'admin';
        
        switch ($this->status) {
            case 'submitted':
                return $isAdmin 
                    ? "A new reservation for {$this->reservation->facility->name} has been submitted by {$this->reservation->user->name}. Booking Reference: {$this->reservation->booking_reference}."
                    : "Your reservation for {$this->reservation->facility->name} has been successfully submitted. Booking Reference: {$this->reservation->booking_reference}.";
            case 'approved':
                return "Your reservation for {$this->reservation->facility->name} has been approved. You can now proceed with your booking. Booking Reference: {$this->reservation->booking_reference}.";
            case 'denied':
                $reason = $this->reservation->admin_remarks ? " Reason: {$this->reservation->admin_remarks}" : '';
                return "Your reservation for {$this->reservation->facility->name} has been denied.{$reason} Booking Reference: {$this->reservation->booking_reference}.";
            case 'cancelled':
                return $isAdmin
                    ? "The reservation for {$this->reservation->facility->name} by {$this->reservation->user->name} has been cancelled. Booking Reference: {$this->reservation->booking_reference}."
                    : "Your reservation for {$this->reservation->facility->name} has been cancelled. Booking Reference: {$this->reservation->booking_reference}.";
            case 'payment_received':
                return $isAdmin
                    ? "Payment received for reservation {$this->reservation->booking_reference} for {$this->reservation->facility->name} by {$this->reservation->user->name}."
                    : "Your payment for reservation {$this->reservation->booking_reference} for {$this->reservation->facility->name} has been successfully received. Your booking is now confirmed!";
            case 'payment_pending': // For online payments that are pending
                return $isAdmin
                    ? "Reservation {$this->reservation->booking_reference} for {$this->reservation->facility->name} by {$this->reservation->user->name} is awaiting online payment. Deadline: {$this->reservation->deadlineForHumans()}."
                    : "Your reservation for {$this->reservation->facility->name} is awaiting payment. Please complete your payment by {$this->reservation->deadlineForHumans()} to confirm your booking. Booking Reference: {$this->reservation->booking_reference}.";
            case 'cash_pending': // For cash payments that are pending
                return $isAdmin
                    ? "Reservation {$this->reservation->booking_reference} for {$this->reservation->facility->name} by {$this->reservation->user->name} is awaiting cash payment. Deadline: {$this->reservation->deadlineForHumans()}."
                    : "Your reservation for {$this->reservation->facility->name} is awaiting cash payment. Please pay at the cashier by {$this->reservation->deadlineForHumans()} to confirm your booking. Failure to pay by the deadline will result in automatic cancellation. Booking Reference: {$this->reservation->booking_reference}.";
            case 'expired_unpaid_cancelled': // For reservations cancelled due to expired payment deadline
                if ($this->reservation->amount_paid > 0) {
                    return $isAdmin
                        ? "Reservation {$this->reservation->booking_reference} for {$this->reservation->facility->name} by {$this->reservation->user->name} has been automatically cancelled. The partial payment was non-refundable."
                        : "Your reservation for {$this->reservation->facility->name} (Booking Reference: {$this->reservation->booking_reference}) has been automatically cancelled because the remaining balance was not paid before the 7-day deadline. Your partial payment is non-refundable.";
                }
                return $isAdmin
                    ? "Reservation {$this->reservation->booking_reference} for {$this->reservation->facility->name} by {$this->reservation->user->name} has been automatically cancelled due to an expired payment deadline."
                    : "Your reservation for {$this->reservation->facility->name} (Booking Reference: {$this->reservation->booking_reference}) has been automatically cancelled because the payment deadline of {$this->reservation->deadlineForHumans()} was missed.";
            case 'partially_paid':
                return $isAdmin
                    ? "Partial payment received for reservation {$this->reservation->booking_reference} for {$this->reservation->facility->name} by {$this->reservation->user->name}. Remaining balance: ₱" . number_format($this->reservation->remaining_balance, 2) . ". Deadline: {$this->reservation->deadlineForHumans()}."
                    : "Your partial payment (deposit) of ₱" . number_format($this->reservation->amount_paid, 2) . " has been received for reservation {$this->reservation->facility->name}. Please pay the remaining balance of ₱" . number_format($this->reservation->remaining_balance, 2) . " by {$this->reservation->deadlineForHumans()} to avoid cancellation without refund. Booking Reference: {$this->reservation->booking_reference}.";
            default:
                return "The status of your reservation for {$this->reservation->facility->name} (Booking Reference: {$this->reservation->booking_reference}) has been updated to {$this->status}.";
        }
    }
}
