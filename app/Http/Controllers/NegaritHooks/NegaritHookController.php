<?php

namespace App\Http\Controllers\NegaritHooks;

use App\Jobs\OnReceivedMessageTask;
use App\MessagePort;
use App\ReceivedMessage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Monolog\Logger;

class NegaritHookController extends Controller
{

    /**
     * NegaritHookController constructor.
     */
    public function __construct()
    {
    }
    public function negaritWebHook() {
        $messageLogger = new Logger('NegaritHookController');
        try{
            $credential = request()->only('message_param', 'message', 'sent_from', 'coding', 'received_date', 'sms_port_id', 'message_id', 'gateway_id', 'id', 'display_name');
            $messageLogger->log(Logger::INFO, 'negarit-hook', ['data'=>$credential]);
            $rules = [
                'id' => 'required',
                'message' => 'required',
                'sent_from' => 'required',
                'sms_port_id' => 'required',
            ];
            $validator = Validator::make($credential, $rules);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status' => false], 500);
            }
            $messagePorts = MessagePort::where('sms_port_id', '=', $credential['sms_port_id'])->where('is_connected', '=', true)->where('is_active', '=', true)->where('is_deleted', '=', false)->get();
            if($messagePorts) {
                foreach ($messagePorts as $messagePort) {
                    if($messagePort instanceof MessagePort) {
                        $newReceivedMessage = new ReceivedMessage();
                        $newReceivedMessage->message_port_id = $messagePort->id;
                        $newReceivedMessage->message_id = $credential['id'];
                        $newReceivedMessage->sms_port_id = $credential['sms_port_id'];
                        $newReceivedMessage->message = $credential['message'];
                        $newReceivedMessage->phone = $credential['sent_from'];
                        $newReceivedMessage->gateway_id = isset($credential['gateway_id'])? $credential['gateway_id']: null;
                        $newReceivedMessage->display_name = isset($credential['display_name'])? $credential['display_name']: null;
                        $newReceivedMessage->received_date = isset($credential['received_date'])? $credential['received_date']: null;

                        $messageLogger->log(Logger::INFO, 'negarit-hook', ['message_ports'=>$newReceivedMessage]);
                        if($newReceivedMessage->save()){
                            dispatch(new OnReceivedMessageTask($newReceivedMessage));
                        } else {
                            print_r("sss");
                        }
                    }
                }
                return response()->json(['status' => true], 200);
            }
        }catch (\Exception $exception){
            return response()->json(['status' => false, 'error'=>$exception->getMessage()], 500);
        }
    }

}
