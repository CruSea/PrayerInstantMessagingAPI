<?php

namespace App\Http\Controllers\messages;

use App\ReceivedMessage;
use App\SentMessage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SentMessagesController extends Controller
{
    public function getReceivedMessages_Paginated()
    {
        try {
            $paginate_num = request()->input('PAGINATE_SIZE') ? request()->input('PAGINATE_SIZE') : 10;
            $sentMessages = SentMessage::orderBy('id', 'DESC')->paginate($paginate_num);
            return response()->json(['status' => true, 'message' => 'sent-messages successfully fetched', 'result' => $sentMessages], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => 'Whoops! something went wrong', 'error' => $exception->getMessage()], 500);
        }
    }
}
