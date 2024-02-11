<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventNotifyChannel extends Model
{
    use HasFactory;
    
    const EMAIL = 1;
    const LINE = 2;
    const TELEGRAM = 3;

    public $table = 'event_notify_channel';
    protected $guarded = [];
    public $timestamps = false;
}
