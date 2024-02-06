<?php namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class BidangDivisi extends Model
{
    protected $table = "bidang_divisi";

    protected $fillable = ['kode','name','keterangan'];

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
		if ($nama_status_appr) $query->where('name', $nama_status_appr);
	}


    // public function strategi_bisnis()
    // {
    // 	return 
    // 	$this->belongsTo('App\Entities\Strategi_bisnis','strategi_bisnis_id','id');
    // }
}
 
