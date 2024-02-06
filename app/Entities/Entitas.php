<?php namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Entitas extends Model
{
    protected $table = "entitas";

    protected $fillable = ['name', 'lokasi_id'];

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

	public function scopeSearchLokasi($query, $nama_lokasi)
	{
		if ($nama_lokasi) $query->where('name', $nama_lokasi);
	}

	public function scopeSearchEntitas($query, $nama_entitas)
	{
		if ($nama_entitas) $query->where('name', $nama_entitas);
	}

    public function lokasi()
    {
    	return 
    	$this->belongsTo('App\Entities\Lokasi','lokasi_id','id');
    }
}
