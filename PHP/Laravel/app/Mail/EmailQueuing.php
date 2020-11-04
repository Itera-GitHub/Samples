<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailQueuing extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var
     * details to build email(from,to,body,subject etc.)
     */
    protected $details;



    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $prepared = $this;
        if(isset($this->details['from']) && $this->details['from']){
            $prepared->from($this->details['from']);
        }
        if(isset($this->details['subject']) && $this->details['subject']){
            $prepared->subject($this->details['subject'] ?? '');
        }
        if(isset($this->details['html_body']) && $this->details['html_body']){
           $prepared->html($this->details['html_body']);
        }
        if(isset($this->details['text_body']) && $this->details['text_body']){
           $prepared->text($this->details['text_body']);
        }
        if(isset($this->details['attachments']) && is_array($this->details['attachments']) && $this->details['attachments']){
            foreach ($this->details['attachments'] as $attachment){
                $prepared->attach($attachment);
            }
        }
        return  $prepared;
    }
}
