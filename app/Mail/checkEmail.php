<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class checkEmail extends Mailable
{
    use Queueable, SerializesModels;
    private User $user;
    private string $code;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $code)
    {
        $this->user = $user;
        $this->code = $code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): checkEmail
    {
        $this->subject('Seu código de verificação é...');
        $this->to($this->user->email, $this->user->name);
        return $this->view('email', ['code' => $this->code]);
    }
}