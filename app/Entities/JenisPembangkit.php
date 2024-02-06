<?php namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class JenisPembangkit extends Model
{
    protected $table = "jenis_pembangkit";

    protected $fillable = ['name','keterangan'];

    // public function scopeSearchStrategiBisnis($query, $strategi_bisnis_id)
	// {
    //     $this->strategi_bisnis_id = $strategi_bisnis_id;
    //     if ($this->strategi_bisnis_id) {
    //         $query->whereHas('strategi_bisnis', function ($q) {
    //             $q->where('id', $this->strategi_bisnis_id);
    //         });
    //     }
	// }

	public function scopeSearchJenisPembangkit($query, $nama_jenis_pembangkit)
	{
		if ($nama_jenis_pembangkit) $query->where('name', $nama_jenis_pembangkit);
	}


    // public function strategi_bisnis()
    // {
    // 	return 
    // 	$this->belongsTo('App\Entities\Strategi_bisnis','strategi_bisnis_id','id');
    // }
}
 
