<?php

namespace App\Jobs;

use App\Http\Controllers\OnReceivedMessages\OnReceivedMessageController;
use App\ReceivedMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class OnReceivedMessageTask implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $receivedMessage;
    public $messageActionCtl;

    /**
     * Create a new job instance.
     *
     * @param ReceivedMessage $message
     */
    public function __construct(ReceivedMessage $message)
    {
        $this->receivedMessage = $message;
        $this->messageActionCtl = new OnReceivedMessageController();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $keyWords = explode(' ', trim($this->receivedMessage->message));
        if($keyWords && count($keyWords) > 0) {
            if (strtolower($keyWords[0]) == "reg") {
                // REG
                $this->messageActionCtl->registerPrayer($this->receivedMessage->phone, $keyWords[1], $this->receivedMessage->message_port_id);
            } elseif (strtolower($keyWords[0]) == "stop") {
                // STOP
                $this->messageActionCtl->unSubscribePrayer($this->receivedMessage->phone);
            } elseif (strtolower($keyWords[0]) == "sch") {
                // SCH MON 10:34
                $this->messageActionCtl->schedulePrayerTime($this->receivedMessage->phone, $keyWords[1], $keyWords[2]);
            } elseif (strtolower($keyWords[0]) == "upd" && count($keyWords) > 2) {
                // UPDATE profile
                $this->messageActionCtl->updateUserProfile($this->receivedMessage->phone, $keyWords[1], $keyWords[2]);
            } elseif (strtolower($keyWords[0]) == "loc" && count($keyWords) > 1) {
                // UPDATE profile
                $this->messageActionCtl->updateUserLocation($this->receivedMessage->phone, $keyWords[1]);
            } elseif (strtolower($keyWords[0]) == "lng" && count($keyWords) > 1) {
                // UPDATE profile
                $this->messageActionCtl->updateUserLanguage($this->receivedMessage->phone, $keyWords[1]);
            }
        }
    }
}
