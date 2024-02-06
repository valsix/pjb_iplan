<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class DmrReviewPhase extends Model
{
    protected $table = "dmr_review_phase";

    protected $fillable = ['role_id', 'urutan'];

    public function role()
    {
        return $this->belongsTo('App\Entities\Role','role_id','id');
    }
}
