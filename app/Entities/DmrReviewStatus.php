<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class DmrReviewStatus extends Model
{
    protected $table = "dmr_review_status";

    protected $fillable = ['name'];
}
