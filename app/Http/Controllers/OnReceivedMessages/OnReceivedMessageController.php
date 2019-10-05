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
            $oldPrayer->status = false;
            $oldPrayer->update();
        }
    }
}
