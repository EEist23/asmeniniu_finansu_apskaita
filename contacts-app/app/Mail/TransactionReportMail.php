<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransactionReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pdfContent;
    public $year;
    public $month;

    public function __construct($pdfContent, $year, $month)
    {
        $this->pdfContent = $pdfContent;
        $this->year = $year;
        $this->month = $month;
    }

    public function build()
    {
        return $this->subject("Jūsų finansų ataskaita už {$this->month}/{$this->year}")
                    ->markdown('emails.transactions.report')
                    ->attachData($this->pdfContent, "finansai_{$this->year}_{$this->month}.pdf", [
                        'mime' => 'application/pdf',
                    ]);
    }
}
