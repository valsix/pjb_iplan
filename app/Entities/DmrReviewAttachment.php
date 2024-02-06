<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class DmrReviewAttachment extends Model
{
    protected $table = 'dmr_review_attachment';
    protected $fillable = ['dmr_review_id', 'filepath'];

    public function dmr_review() {
        return $this->belongsTo('App\Entities\DmrReview', 'dmr_review_id');
    }
}
