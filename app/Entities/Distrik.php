<?php namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Distrik extends Model
{
    protected $table = "distrik";

    protected $fillable = ['strategi_bisnis_id','code1','code2', 'name'];

    public function scopeSearchStrategiBisnis($query, $strategi_bisnis_id)
	{
        $this->strategi_bisnis_id = $strategi_bisnis_id;
        if ($this->strategi_bisnis_id) {
            $query->whereHas('strategi_bisnis', function ($q) {
                $q->where('id', $this->strategi_bisnis_id);
            });
        }
	}

	public function scopeSearchDistrik($query, $nama_distrik)
	{
		if ($nama_distrik) $query->where('name', $nama_distrik);
	}


    public function strategi_bisnis()
    {
    	return 
    	$this->belongsTo('App\Entities\Strategi_bisnis','strategi_bisnis_id','id');
    }
}
 
