<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\SentMessage;
use App\Http\Controllers\messages\ScheduleMessageController;
use App\RegisteredPrayer;
use DateTime;

class SendScheduleMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sendScheduleMessage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send schedule message for registered prayers according to their schedule';

    protected $schedule_message;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ScheduleMessageController $scheduleMessage)
    {
        $this->schedule_message = $scheduleMessage;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $date_now = date('Y-m-d');
        $date_format = new DateTime($date_now);
        $date_name = $date_format->format('l');
        $sent_message = SentMessage::orderBy('id', 'desc')->first();
        if($date_name == "Sunday") {
            $sunday_prayers =  RegisteredPrayer::where([['day_name', '=', 'su'], ['sch_time', '=', date('H:i')]])->get();
            $this->schedule_message->sundayScheduledMessages($sent_message, $sunday_prayers);
        }
        elseif($date_name == "Monday") {
            $monday_prayers = RegisteredPrayer::wehre([['day_name', '=', 'mo'], ['sch_time', '=', date('H:i')]])->get();
            $this->schedule_message->mondayScheduledMessages($sent_message, $monday_prayers);
        }
        elseif($date_name == "Tuesday") {
            $tuesday_prayers = RegisteredPrayer::where([['day_name', '=', 'tu'], ['sch_time', '=', date('H:i')]])->get();
            $this->schedule_message->tuesdayScheduledMessages($sent_message, $tuesday_prayers);
        }
        elseif($date_name == "Wednesday") {
            $wednesday_prayers = RegisteredPrayer::where([['day_name', '=', 'we'], ['sch_time', '=', date('H:i')]])->get();
            $this->schedule_message->wednesdayScheduledMessages($sent_message, $wednesday_prayers);
        }
        elseif($date_name == "Thursday") {
            $thursday_prayers = RegisteredPrayer::where([['day_name', '=', 'th'], ['sch_time', '=', date('H:i')]])->get();
            $this->schedule_message->thursdayScheduledMessages($sent_message, $thursday_prayers);
        }
        elseif($date_name == "Friday") {
            $friday_prayers = RegisteredPrayer::where([['day_name', '=', 'fr'], ['sch_time', '=', date('H:i')]])->get();
            $this->schedule_message->fridayScheduledMessages($sent_message, $friday_prayers);
        }
        elseif($date_name == "Saturday") {
            $saturday_prayers = RegisteredPrayer::where([['day_name', '=', 'sa'], ['sch_time', '=', date('H:i')]])->get();
            $this->schedule_message->saturdayScheduledMessages($sent_message, $saturday_prayers);
        }
    }
}
