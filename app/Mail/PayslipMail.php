<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PayslipMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $name;
    public float $totalHonorarium;
    public ?string $period;
    public string $employeeType;

    public function __construct(string $name, float $totalHonorarium, ?string $period = null, string $employeeType = '')
    {
        $this->name = $name;
        $this->totalHonorarium = $totalHonorarium;
        $this->period = $period;
        $this->employeeType = $employeeType;
    }

    public function build()
    {
        $label = $this->period ?: now()->format('M Y');
        $type = $this->employeeType ? " ({$this->employeeType})" : '';
        return $this->subject("Payslip - {$label}{$type}")
            ->view('emails.payslip');
    }
}