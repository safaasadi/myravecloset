<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetEmail extends Mailable
{
    use Queueable, SerializesModels;

    /** @var $user */
    private $user;

    /**
     * Create a new message instance.
     *
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self
    {
        \DB::table('password_resets')->where('email', $this->user->email)->delete();

        $token = \Str::random(20);
        $password_reset = \DB::table('password_resets')->insert([
            'email' => $this->user->email,
            'token' => $token, //change 60 to any length you want
            'created_at' => \Carbon\Carbon::now()
        ]);
    
        return $this
            ->subject('Password reset')
            ->markdown('emails.auth.password_reset', [
                'user' => $this->user,
                'reset_token' => $token
            ]);
    }
}
