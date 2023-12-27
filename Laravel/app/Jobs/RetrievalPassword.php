<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class RetrievalPassword implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $store;
    protected $email;
    protected $username;
    protected $password;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($store, $email, $username, $password)
    {
        $this->store = $store;
        $this->email = $email;
        $this->username = $username;
        $this->password = $password;
        $this->delay = now()->addSeconds(10);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $storeTo = $this->store;
        $emailTo = $this->email;
        $usernameTo = $this->username;
        $passwordTo = $this->password;
        Mail::send('pages.sendPassword', compact('usernameTo', 'passwordTo'), function($email) use($emailTo, $storeTo){
            $email->subject("[".$storeTo." Shop] Lấy mật khẩu");
            $email->to($emailTo);
        });
    }
}
