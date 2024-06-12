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
    public  $totalDaysWorked, $totalSickRest, $totalHolidays, $totalAbsence ;

    /**
     * Create a new message instance.
     */
     public function __construct($employee, $employeePayment, $attendances, $department,  $totalDaysWorked, $totalSickRest, $totalHolidays, $totalAbsence )
    {
        $this->employee = $employee;
        $this->employeePayment = $employeePayment;
        $this->attendances = $attendances;
        $this->department = $department;
        $this->totalDaysWorked = $totalDaysWorked;
        $this->totalSickRest = $totalSickRest;
        $this->totalHolidays = $totalHolidays;
        $this->totalAbsence  = $totalAbsence ;

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
                'attendances' =>  $this->attendances,
                'totalDaysWorked' => $this-> totalDaysWorked,
                'totalSickRest' =>  $this->totalSickRest,
                'totalHolidays' =>  $this->totalHolidays,
                'totalAbsence' =>  $this->totalAbsence


            ]
        );
    }

    /**
     * Get the attachments for the message.A
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
