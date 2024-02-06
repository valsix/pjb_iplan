<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    public function sheet()
    {
        return $this->belongsTo('App\Entities\Sheet');
    }

    public function user()
    {
        return$this->belongsTo('App\User');
    }
}
