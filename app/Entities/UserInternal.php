<?php namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class UserInternal extends Model
{
    protected $table = "user_internal";

    protected $fillable = ['nid','nama_lengkap','email','occup_status','kode_bagian','bagian','kode_ditbid','ditbid','kode_unit','unit','kode_klasifikasi_unit','klasifikasi_unit','position_id','nama_posisi'];

    // public function scopeSearchStrategiBisnis($query, $strategi_bisnis_id)
	// {
    //     $this->strategi_bisnis_id = $strategi_bisnis_id;
    //     if ($this->strategi_bisnis_id) {
    //         $query->whereHas('strategi_bisnis', function ($q) {
    //             $q->where('id', $this->strategi_bisnis_id);
    //         });
    //     }
	// }

	public function scopeSearchStatusAppr($query, $nama_status_appr)
	{
		if ($nama_status_appr) $query->where('nama_lengkap', $nama_status_appr);
	}


    // public function strategi_bisnis()
    // {
    // 	return 
    // 	$this->belongsTo('App\Entities\Strategi_bisnis','strategi_bisnis_id','id');
    // }
}
 
