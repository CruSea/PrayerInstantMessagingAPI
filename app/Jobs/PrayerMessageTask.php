<?php

namespace App\Jobs;

use App\PrayerMessage;
use App\ReceivedMessage;
use App\RegisteredPrayer;
use App\SentMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PrayerMessageTask implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $prayerMessage;

    /**
     * Create a new job instance.
     *
     * @param PrayerMessage $prayerMessage
     */
    public function __construct(PrayerMessage $prayerMessage)
    {
        $this->prayerMessage = $prayerMessage;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $prayers = RegisteredPrayer::where('location', '=', $this->prayerMessage->location)->get();
        foreach ($prayers as $prayer) {
            if($prayer instanceof RegisteredPrayer) {
                $newSentMessage = new SentMessage();
                $newSentMessage->message = $this->prayerMessage->message;
                $newSentMessage->message_port_id = $this->prayerMessage->message_port_id;
                $newSentMessage->phone = $prayer->phone;
                if($newSentMessage->save()){
                    dispatch(new SendToNegaritTask($newSentMessage));
                }
            }
        }
    }
}
