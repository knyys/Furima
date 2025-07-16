<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Item;

class RatingCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $item;
    public $buyer;
    
    public function __construct(Item $item, $buyer)
    {
        $this->item = $item;
        $this->buyer = $buyer;
    }

    public function build()
    {
        return $this->subject('【COACHTECHフリマ】評価をしてください')
                    ->view('emails.rate_completed');
    }

}
