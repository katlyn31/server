<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionFeeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $subscriptionFee;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param string $subscriptionFee
     */
    public function __construct(User $user, $subscriptionFee)
    {
        $this->user = $user;
        $this->subscriptionFee = $subscriptionFee;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New User Subscription Details')
                    ->to('jerikoa50@compservmail.com')
                    ->view('emails.subscription_fee')
                    ->with([
                        'user' => $this->user,
                        'subscriptionFee' => $this->subscriptionFee,
                    ]);
    }
}
