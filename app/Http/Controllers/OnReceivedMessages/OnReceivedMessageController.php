<?php

namespace App\Http\Controllers\OnReceivedMessages;

use App\RegisteredPrayer;
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

    public function registerPrayer(string $phone, string $location, string $language)
    {
        $oldRegisteredPrayer = RegisteredPrayer::where('phone', '=', $phone)->first();
        if ($oldRegisteredPrayer instanceof RegisteredPrayer) {
            $oldRegisteredPrayer->status = true;
            $oldRegisteredPrayer->location = isset($location) ? $location : "ETH";
            $oldRegisteredPrayer->language = isset($language) ? $language : "ENG";
            $oldRegisteredPrayer->update();
        } else {
            $newPrayer = new RegisteredPrayer();
            $newPrayer->phone = $phone;
            $newPrayer->status = true;
            $newPrayer->location = isset($location) ? $location : "ETH";
            $newPrayer->language = isset($language) ? $language : "ENG";
            $newPrayer->save();
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
            $last_name = isset($last_name) ? $last_name : null
            $oldPrayer->furll_name = $first_name. ' '. $last_name;
            $oldPrayer->update();
        }
    }
}
