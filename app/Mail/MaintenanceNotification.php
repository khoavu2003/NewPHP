<?php

namespace App\Mail;

use App\Models\MaintenanceShedule;
use App\Models\Vehicle;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MaintenanceNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $maintenance;
    public $vehicle;
    public function __construct(MaintenanceShedule $maintenance, Vehicle $vehicle)
    {
        $this->maintenance = $maintenance;
        $this->vehicle = $vehicle;
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        return $this->subject('Thông báo lịch bảo trì xe')
                    ->view('emails.maintenance_notification')
                    ->with([
                        'maintenance' => $this->maintenance,
                        'vehicle' => $this->vehicle,
                    ]);
    }
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Maintenance Notification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.maintenance_notification',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
