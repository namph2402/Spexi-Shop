<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $capcha;
    protected $name;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $capcha, $name)
    {
        $this->email = $email;
        $this->capcha = $capcha;
        $this->name = $name;
        $this->delay = now()->addSeconds(10);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $emailTo = $this->email;
        $code = $this->capcha;
        $store = $this->name;
        Mail::send('pages.sendCode', compact('code','store'), function($email) use($emailTo, $store){
            $email->subject("[Thời trang ".$store."] Xác nhận đăng ký tài khoản");
            $email->to($emailTo);
        });
    }
}
