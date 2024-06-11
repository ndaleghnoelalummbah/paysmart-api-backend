<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PayslipMail extends Mailable
{
    use Queueable, SerializesModels;

    public $employee;
    public $employeePayment;
    public $attendances;
    public $department;

    /**
     * Create a new message instance.
     */
     public function __construct($employee, $employeePayment, $attendances, $department)
    {
        $this->employee = $employee;
        $this->employeePayment = $employeePayment;
        $this->attendances = $attendances;
        $this->department = $department;

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Monthly Payslip Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
       return new Content(
            view: 'emails.sendPayslip',
            with: [
                'employee' => $this->employee,
                'employeePayment' => $this->employeePayment,
            ]
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
