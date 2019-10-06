<?php

namespace App\Http\Controllers\RegisteredPrayers;

use App\MessagePort;
use App\RegisteredPrayer;
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
            $registeredPrayers = RegisteredPrayer::where('is_deleted', '=', false)->orderBy('id', 'DESC')->paginate($paginate_num);
            return response()->json(['status' => true, 'message' => 'registered-prayers successfully fetched', 'result' => $registeredPrayers], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => 'Whoops! something went wrong', 'error' => $exception->getMessage()], 500);
        }
    }
}
