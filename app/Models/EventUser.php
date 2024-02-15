<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventUser extends Model
{
    use HasFactory;

    public $table = 'event_user';
    protected $guarded = [];

    public function subscribeUser()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
