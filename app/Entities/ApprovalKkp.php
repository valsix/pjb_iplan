<?php namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class ApprovalKkp extends Model
{
    protected $table = "approval_kkp";

    protected $fillable = ['kkp_id', 'grupdiv_id', 'peran', 'urutan', 'status', 'pegawai_id', 'pegawai_nama', 'tanggal'];


    public function kkp()
    {
        return
        $this->belongsTo('App\Entities\Dmr','kkp_id','id');
    }

    public function grupdiv()
    {
        return
        $this->belongsTo('App\Entities\GroupDivisiPembinaUnit','grupdiv_id','id');
    }

    // public function fase()
    // {
    // 	return
    // 	$this->belongsTo('App\Entities\Fase','fase_id','id');
    // }

    // public function role()
    // {
    // 	return
	// 	$this->belongsTo('App\Entities\Role','role_id','id');
    // }

    // public function grup()
    // {
    // 	return
    // 	$this->belongsTo('App\Entities\Grup','grup_id','id');
    // }

}
