<?php

namespace App\Http\Controllers\Messages;

use App\Jobs\PrayerMessageTask;
use App\Jobs\ProcessPrayerMessageTask;
use App\PrayerMessage;
use App\RegisteredPrayer;
use App\SentMessage;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PrayerMessagesController extends Controller
{

    /**
     * PrayerMessagesController constructor.
     */
    public function __construct()
    {
    }

    public function getPrayersMessages()
    {
        try {
            $paginate_num = request()->input('PAGINATE_SIZE') ? request()->input('PAGINATE_SIZE') : 10;
            $prayersMessages = PrayerMessage::orderBy('id', 'DESC')->paginate($paginate_num);
            return response()->json(['status' => true, 'message' => 'prayers-messages successfully fetched', 'result' => $prayersMessages], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => 'Whoops! something went wrong', 'error' => $exception->getMessage()], 500);
        }
    }

    public function create()
    {
        try {
            $credential = request()->only('message_port_id', 'message', 'location', 'language');
            $rules = [
                'message_port_id' => 'required',
                'message' => 'required'
            ];
            $validator = Validator::make($credential, $rules);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['error' => $error], 500);
            }
            $newPrayerMessage = new PrayerMessage();
            $newPrayerMessage->message = $credential['full_name'];
            $newPrayerMessage->message_port_id = $credential['full_name'];
            $newPrayerMessage->location = isset($credential['location'])? $credential['location']: null;
            $newPrayerMessage->language = isset($credential['language'])? $credential['language']: null;
            if ($newPrayerMessage->save()) {
                dispatch(new PrayerMessageTask($newPrayerMessage));
                return response()->json(['status' => true, 'message' => 'successfully create prayer message', 'result' => $newPrayerMessage], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'Whoops! something went wrong try again'], 500);
            }
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => 'Whoops! something went wrong', 'error' => $exception->getMessage()], 500);
        }
    }
}
