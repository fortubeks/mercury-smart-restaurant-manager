<?php

namespace App\Mail;

use App\Models\Hotel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WeeklyProfitLossReport extends Mailable
{
    use Queueable, SerializesModels;

    public $hotel;
    public $start_date;
    public $end_date;
    public $pdf_path;

    public function __construct(Hotel $hotel, $start_date, $end_date, $pdf_path)
    {
        $this->hotel = $hotel;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->pdf_path = $pdf_path;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Weekly Profit Loss Report',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reports.weekly-profit-loss'
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->pdf_path)
                ->as('Weekly_Profit_Loss_Report.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
