<?php namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Dmr extends Model
{
    protected $table = "dmr";

    protected $fillable = ['no_dokumen','no_prk', 'nama_prk','tahun_anggaran', 'lokasi_id', 'submitted_at', 'dmr_filepath','dmr_review_status_id', 'dmr_review_phase_id' ,'created_at', 'created_by' ,'updated_at', 'updated_by','is_submitted' ,'submitted_at', 'submitted_by','rejected_at','rejected_by', 'revised_at','revised_by','approved_at','approved_by','alasan','latar_belakang', 'sasaran_tujuan', 'permasalahan', 'alternatif_pencapaian', 'benefit_operasional', 'benefit_finansial', 'alasan_latar_belakang', 'alasan_sasaran_tujuan', 'alasan_permasalahan', 'alasan_alternatif_pencapaian', 'alasan_benefit_operasional', 'alasan_benefit_finansial','jumlah_anggaran', 'is_kantor_pusat', 'is_publish', 'judul_dokumen', 'no_prk_form', 'anggaran_prk_form', 'jenis_cluster', 'anggaran_percluster', 'is_kkp', 'status_appr_id', 'kondisi_aicluster_id', 'bidang_divisi_id'];

    public function dmr_reviews() {
        return $this->hasMany('App\Entities\DmrReview', 'dmr_id');
    }

    public function dmr_review_status()
    {
    	return
		$this->belongsTo('App\Entities\DmrReviewStatus','dmr_review_status_id','id');
    }

    public function dmr_attachment()
    {
    	return
		$this->hasMany('App\Entities\DmrAttachment','dmr_id');
    }

    public function dmr_review_phase()
    {
        return $this->belongsTo('App\Entities\DmrReviewPhase','dmr_review_phase_id','id');
    }

    public function lokasi()
    {
        return
        $this->belongsTo('App\Entities\Lokasi','lokasi_id','id');
    }

    public function status_appr()
    {
        return
        $this->belongsTo('App\Entities\StatusAppr','status_appr_id','id');
    }
}
