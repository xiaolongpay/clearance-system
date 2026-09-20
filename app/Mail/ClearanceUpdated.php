<?php

namespace App\Mail;

use App\Models\ClearanceItem;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClearanceUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public ClearanceItem $clearanceItem;

    public function __construct(ClearanceItem $clearanceItem)
    {
        $this->clearanceItem = $clearanceItem;
    }

    public function build()
    {
        $subject = $this->clearanceItem->status === 'cleared'
            ? "You've been cleared by {$this->clearanceItem->department->name}"
            : "Update on your {$this->clearanceItem->department->name} clearance";

        return $this->subject($subject)
            ->view('emails.clearance-updated');
    }
}