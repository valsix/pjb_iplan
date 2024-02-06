<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class TorReviewPhase extends Model
{
    protected $table = "tor_review_phase";

    protected $fillable = ['role_id', 'urutan'];

    public function role()
    {
        return $this->belongsTo('App\Entities\Role','role_id','id');
    }

    public function nextPhase() {
        if ($this->urutan < 4) {
            return $this->where('urutan', $this->urutan+1)->first();
        } else {
            return false;
        }
    }
}
