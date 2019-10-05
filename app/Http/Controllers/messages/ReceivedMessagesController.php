<?php

namespace App\Http\Controllers\messages;

use App\MessagePort;
use App\ReceivedMessage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReceivedMessagesController extends Controller
{
    public function getReceivedMessages_Paginated()
    {
        try {
            $paginate_num = request()->input('PAGINATE_SIZE') ? request()->input('PAGINATE_SIZE') : 10;
            $receivedMessages = ReceivedMessage::orderBy('id', 'DESC')->paginate($paginate_num);
            return response()->json(['status' => true, 'message' => 'received-messages successfully fetched', 'result' => $receivedMessages], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => 'Whoops! something went wrong', 'error' => $exception->getMessage()], 500);
        }
    }
}
