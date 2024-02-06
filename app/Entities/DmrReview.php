<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class DmrReview extends Model
{
    protected $table = 'dmr_review';

    protected $fillable = [
        'dmr_id',
        'dmr_review_phase_id',
        'dmr_review_status_id',
        'created_by',
        'approved_at',
        'approved_by',
        'keterangan',
        'alasan',
        'alasan_latar_belakang',
        'alasan_sasaran_tujuan',
        'alasan_permasalahan',
        'alasan_alternatif_pencapaian',
        'alasan_benefit_operasional',
        'alasan_benefit_finansial',
        'is_reviewed',
        'is_new'
    ];

    public function dmr() {
        return $this->belongsTo('App\Entities\Dmr', 'dmr_id');
    }

    public function dmr_review_attachments() {
        return $this->hasMany('App\Entities\DmrReviewAttachment', 'dmr_review_id');
    }

    public function dmr_review_phase() {
        return $this->belongsTo('App\Entities\DmrReviewPhase', 'dmr_review_phase_id');
    }

    public function dmr_review_status() {
        return $this->belongsTo('App\Entities\DmrReviewStatus', 'dmr_review_status_id');
    }
    
    public function user_revised() {
        return $this->belongsTo('App\Entities\User', 'created_by');
    }

    //untuk appr baru pada kkp
    public function grupdiv()
    {
        return $this->belongsTo('App\Entities\GroupDivisiPembinaUnit','dmr_review_phase_id','id');
    }
    public function status_appr() {
        return $this->belongsTo('App\Entities\StatusAppr', 'dmr_review_status_id','id');
    }
}
