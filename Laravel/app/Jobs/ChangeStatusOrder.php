<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ChangeStatusOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
        $this->delay = now()->addMinute(30);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $model = Order::find($this->id);
        if($model->order_status == Order::$XAC_NHAN) {
            $model->order_status = Order::$CHUAN_BI_HANG;
            $model->save();
        }
    }
}
