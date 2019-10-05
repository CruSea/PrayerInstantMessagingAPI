<?php

namespace App\Http\Controllers\RegisteredPrayers;

use App\MessagePort;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PrayersController extends Controller
{

    /**
     * PrayersController constructor.
     */
    public function __construct()
    {
    }

    public function getPrayers()
    {
        try {
            $paginate_num = request()->input('PAGINATE_SIZE') ? request()->input('PAGINATE_SIZE') : 10;
            $messagePorts = MessagePort::where('is_deleted', '=', false)->orderBy('id', 'DESC')->paginate($paginate_num);
            return response()->json(['status' => true, 'message' => 'message-ports successfully fetched', 'result' => $messagePorts], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => 'Whoops! something went wrong', 'error' => $exception->getMessage()], 500);
        }
    }

    public function getPrayersPaginated()
    {
        try {
            $messagePorts = MessagePort::where('is_deleted', '=', false)->orderBy('id', 'DESC')->get();
            return response()->json(['status' => true, 'message' => 'message-ports successfully fetched', 'result' => $messagePorts], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => 'Whoops! something went wrong', 'error' => $exception->getMessage()], 500);
        }
    }
}
