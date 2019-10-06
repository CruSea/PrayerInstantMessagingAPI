<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrayerMessage extends Model
{
    public function message_port() {
        return $this->belongsTo(MessagePort::class);
    }
}
