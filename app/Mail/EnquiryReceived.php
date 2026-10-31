<?php

namespace App\Mail;

use App\Models\Enquiry;
use App\Support\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EnquiryReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Enquiry $enquiry) {}

    public function envelope(): Envelope
    {
        // A project names its priority, so the right person opens it first.
        $subject = $this->enquiry->journey === 'project'
            ? "New project enquiry {$this->enquiry->reference} — {$this->enquiry->interest} · ".Quote::priorityLabel($this->enquiry->priority)
            : "New enquiry {$this->enquiry->reference} — {$this->enquiry->interest}";

        return new Envelope(
            subject: $subject,
            // Reply goes to the prospect, not to our own sales address.
            replyTo: [new Address($this->enquiry->email, $this->enquiry->name)],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.enquiry-received',
            with: [
                'url' => url('/admin/enquiries/'.$this->enquiry->getKey().'/edit'),
                'answers' => $this->enquiry->journey === 'project' ? Quote::describe($this->enquiry) : [],
            ],
        );
    }
}
