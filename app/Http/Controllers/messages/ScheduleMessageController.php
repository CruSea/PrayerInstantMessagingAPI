<?php

namespace App\Http\Controllers\messages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\SentMessage;
use App\RegisteredPrayer;

class ScheduleMessageController extends Controller
{
    public function sundayScheduledMessages(SentMessage $sent_message, RegisteredPrayer $sunday_prayers) {
    	foreach ($sunday_prayers as $prayer) {
    		if($prayer instanceof RegisteredPrayer) {
    			$newSentMessage = new SentMessage();
    			$newSentMessage->message = $sent_message->message;
    			$newSentMessage->message_port_id = $sent_message->message_port_id;
    			$newSentMessage->phone = $prayer->phone;
    			if($newSentMessage->save()) {
    				dispatch(new SendToNegaritTask($newSentMessage));
    			}
    		}
    	}
    }
    public function mondayScheduledMessages(SentMessage $sent_message, RegisteredPrayer $monday_prayers) {
    	foreach ($monday_prayers as $prayer) {
    		if($prayer instanceof RegisteredPrayer) {
    			$newSentMessage = new SentMessage();
    			$newSentMessage->message = $sent_message->message;
    			$newSentMessage->message_port_id = $sent_message->message_port_id;
    			$newSentMessage->phone = $prayer->phone;
    			if($newSentMessage->save()) {
    				dispatch(new SendToNegaritTask($newSentMessage));
    			}
    		}
    	}
    }
    public function tuesdayScheduledMessages(SentMessage $sent_message, RegisteredPrayer $tuesday_prayers) {
    	foreach ($tuesday_prayers as $prayer) {
    		if($prayer instanceof RegisteredPrayer) {
    			$newSentMessage = new SentMessage();
    			$newSentMessage->message = $sent_message->message;
    			$newSentMessage->message_port_id = $sent_message->message_port_id;
    			$newSentMessage->phone = $prayer->phone;
    			if($newSentMessage->save()) {
    				dispatch(new SendToNegaritTask($newSentMessage));
    			}
    		}
    	}
    }
    public function wednesdayScheduledMessages(SentMessage $sent_message, RegisteredPrayer $wednesday_prayers)
    {
    	foreach ($wednesday_prayers as $prayer) {
    		if($prayer instanceof RegisteredPrayer) {
    			$newSentMessage = new SentMessage();
    			$newSentMessage->message = $sent_message->message;
    			$newSentMessage->message_port_id = $sent_message->message_port_id;
    			$newSentMessage->phone = $prayer->phone;
    			if($newSentMessage->save()) {
    				dispatch(new SendToNegaritTask($newSentMessage));
    			}
    		}
    	}
    }
    public function thursdayScheduledMessages(SentMessage $sent_message, RegisteredPrayer $thursday_prayers)
    {
    	foreach ($thursday_prayers as $prayer) {
    		if($prayer instanceof RegisteredPrayer) {
    			$newSentMessage = new SentMessage();
    			$newSentMessage->message = $sent_message->message;
    			$newSentMessage->message_port_id = $sent_message->message_port_id;
    			$newSentMessage->phone = $prayer->phone;
    			if($newSentMessage->save()) {
    				dispatch(new SendToNegaritTask($newSentMessage));
    			}
    		}
    	}
    }
    public function fridayScheduledMessages(SentMessage $sent_message, RegisteredPrayer $friday_prayers)
    {
    	foreach ($friday_prayers as $prayer) {
    		if($prayer instanceof RegisteredPrayer) {
    			$newSentMessage = new SentMessage();
    			$newSentMessage->message = $sent_message->message;
    			$newSentMessage->message_port_id = $sent_message->message_port_id;
    			$newSentMessage->phone = $prayer->phone;
    			if($newSentMessage->save()) {
    				dispatch(new SendToNegaritTask($newSentMessage));
    			}
    		}
    	}
    }
    public function saturdayScheduledMessages(SentMessage $sent_message, RegisteredPrayer $saturday_prayers)
    {
    	foreach ($saturday_prayers as $prayer) {
    		if($prayer instanceof RegisteredPrayer) {
    			$newSentMessage = new SentMessage();
    			$newSentMessage->message = $sent_message->message;
    			$newSentMessage->message_port_id = $sent_message->message_port_id;
    			$newSentMessage->phone = $prayer->phone;
    			if($newSentMessage->save()) {
    				dispatch(new SendToNegaritTask($newSentMessage));
    			}
    		}
    	}
    }
}
