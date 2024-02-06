<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class TorReviewStatus extends Model
{
    protected $table = "tor_review_status";

    protected $fillable = ['name'];
}
