<?php

namespace App\Mail\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\User;

class InviteUser extends Mailable
{
    use Queueable, SerializesModels;
    
    private $user;
    private $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Instacrew CRM Invitation')
            ->from('hello@carcoop.com.au')
            ->markdown('emails.invite-user', [
                'url' => env('APP_URL') . '/setup-account-form?reset_key='.$this->token,
                'name' => !is_null($this->user) ? $this->user->name : ''
            ]);
    }
}