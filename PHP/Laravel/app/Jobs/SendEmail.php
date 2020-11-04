<?php

namespace App\Jobs;

use App\Mail\EmailQueuing;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * @var
     * Details to build email(from,to,body,subject etc.)
     */
    protected $details;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(env('APP_DEBUG')){
            $this->details['to'] = env('DEBUG_EMAIL');
        }
        if(isset($this->details['to']) && $this->details['to']){
            $email = new EmailQueuing($this->details);
            Mail::to($this->details['to'])->send($email);
        }
    }
}
