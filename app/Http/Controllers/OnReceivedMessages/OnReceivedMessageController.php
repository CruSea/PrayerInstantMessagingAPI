<?php

namespace App\Http\Controllers\OnReceivedMessages;

use App\Jobs\SendToNegaritTask;
use App\RegisteredPrayer;
use App\SentMessage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OnReceivedMessageController extends Controller
{

    /**
     * OnReceivedMessageController constructor.
     */
    public function __construct()
    {
    }

    public function registerPrayer(string $phone, $full_name, $message_port_id)
    {
        $oldRegisteredPrayer = RegisteredPrayer::where('phone', '=', $phone)->first();
        if ($oldRegisteredPrayer instanceof RegisteredPrayer) {
            $oldRegisteredPrayer->status = true;
            $oldRegisteredPrayer->full_name = isset($full_name) ? $full_name : $oldRegisteredPrayer->full_name;
            $oldRegisteredPrayer->update();
        } else {
            $newPrayer = new RegisteredPrayer();
            $newPrayer->phone = $phone;
            $newPrayer->status = true;
            $newPrayer->full_name = isset($full_name) ? $full_name : "UNKNOWN";
            $newPrayer->location = isset($location) ? $location : "ETH";
            $newPrayer->language = isset($language) ? $language : "ENG";
            if($newPrayer->save()) {
                $newSentMessage = new SentMessage();
                $newSentMessage->message_port_id = $message_port_id;
                $newSentMessage->message = "Congratulation You Have Registered To Prayer Mobilization Platform.\nTo Change Your Language\n  LNG ENG\nTo Schedule Your Prayer Time\n  SCH MON 04:34\nTo Change Your Location\n  LOC ETH";
                $newSentMessage->phone = $phone;
                if($newSentMessage->save()){
                    dispatch(new SendToNegaritTask($newSentMessage));
                }
            }
        }
    }

    public function unSubscribePrayer(string $phone)
    {
        $oldPrayer = RegisteredPrayer::where('phone', '=', $phone)->first();
        if ($oldPrayer instanceof RegisteredPrayer) {
            $oldPrayer->status = false;
            $oldPrayer->update();
        }
    }

    public function schedulePrayerTime(string $phone, string $day_name, string $sch_time)
    {
        $oldPrayer = RegisteredPrayer::where('phone', '=', $phone)->first();
        if ($oldPrayer instanceof RegisteredPrayer) {
            $oldPrayer->day_name = isset($day_name) ? $day_name : null;
            $oldPrayer->sch_time = isset($sch_time) ? $sch_time : null;
            $oldPrayer->update();
        }
    }
    public function updateUserProfile(string $phone, string $first_name, string $last_name)
    {
        $oldPrayer = RegisteredPrayer::where('phone', '=', $phone)->first();
        if($oldPrayer instanceof RegisteredPrayer) {
            $last_name = isset($last_name) ? $last_name : null;
            $oldPrayer->furll_name = $first_name. ' '. $last_name;
            $oldPrayer->update();
        }
    }
}
