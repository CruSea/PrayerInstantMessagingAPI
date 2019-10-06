<?php

namespace App\Jobs;

use App\Http\Controllers\Controller;
use App\MessagePort;
use App\SentMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendToNegaritTask implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $sentMessage;
    public $negarit_url = "";
    public $myController;

    /**
     * Create a new job instance.
     *
     * @param SentMessage $message
     */
    public function __construct(SentMessage $message)
    {
        $this->sentMessage = $message;
        $this->myController = new Controller();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $messagePort = MessagePort::where('id', "=", $this->sentMessage->message_port_id)->first();
        if($messagePort instanceof MessagePort) {
            $send_request = array();
            $send_request['API_KEY'] = $messagePort->api_key;
            $send_request['campaign_id'] = $messagePort->campaign_id;
            $send_request['message'] = $this->sentMessage->message;
            $send_request['sent_to'] = $this->sentMessage->phone;
            $response = $this->myController->sendPostRequest("api_request/sent_message", json_encode($send_request));
            $response_data = json_decode($response);
            if ($response_data) {
                if (isset($response_data->status)) {
                    $this->sentMessage->is_sent = true;
                    $this->sentMessage->update();
                }
            }
        }
    }
}
