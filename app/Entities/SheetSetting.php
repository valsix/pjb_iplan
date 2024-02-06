<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class SheetSetting extends Model
{
    public function sheet()
    {
        return $this->belongsTo('App\Entities\Sheet');
    }
}
