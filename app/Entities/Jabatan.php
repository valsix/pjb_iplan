<?php namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use DB;

class Jabatan extends Model
{
    protected $table = "jabatan";

    protected $fillable = ['position_id','nama_posisi','superior_id','kode_kategori','kategori','kode_kelompok_jabatan','kelompok_jabatan','kode_jenjang_jabatan','jenjang_jabatan','kode_klasifikasi_unit','klasifikasi_unit','kode_unit','unit','kode_ditbid','ditbid','kode_bagian','bagian','occup_status','nama_lengkap','email','nid','posisi','change_reason','tipe','kode_distrik'];

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
		if ($nama_status_appr) $query->where('nama_posisi', $nama_status_appr);
	}

    // function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
    // {
    //     $str = "
    //         SELECT
    //         TRIM(A.POSITION_ID) POSITION_ID
    //         , A.NAMA_POSISI
    //         , TRIM(A.SUPERIOR_ID) SUPERIOR_ID
    //         , TRIM(A.KODE_KATEGORI) KODE_KATEGORI
    //         , A.KATEGORI
    //         , TRIM(A.KODE_KELOMPOK_JABATAN) KODE_KELOMPOK_JABATAN 
    //         , A.KELOMPOK_JABATAN
    //         , TRIM(A.KODE_JENJANG_JABATAN) KODE_JENJANG_JABATAN
    //         , A.JENJANG_JABATAN
    //         , TRIM(A.KODE_KLASIFIKASI_UNIT) KODE_KLASIFIKASI_UNIT
    //         , A.KLASIFIKASI_UNIT
    //         , TRIM(A.KODE_UNIT) KODE_UNIT
    //         , A.UNIT
    //         , TRIM(A.KODE_DITBID) KODE_DITBID
    //         , A.DITBID, TRIM(A.KODE_BAGIAN) KODE_BAGIAN
    //         , A.BAGIAN
    //         , A.OCCUP_STATUS
    //         , A.NAMA_LENGKAP
    //         , A.EMAIL
    //         , A.NID
    //         , A.POSISI
    //         , A.CHANGE_REASON
    //         , A.TIPE
    //         ,  CASE WHEN A.TIPE IS NULL THEN 'Eksternal' else 'Internal' END TIPE_INFO
    //         ,  A.KODE_DISTRIK
    //         , CASE WHEN A.TIPE IS NULL THEN 
    //         '<a onClick=\"openurl(''app/index/master_jabatan_add?reqId=&reqIdDetil=' || TRIM(A.POSITION_ID) || '&reqSuperiorId=TOP'')\" 
    //         style=\"cursor:pointer\" title=\"Tambah\"><img src=\"images/icn_add.gif\" width=\"15px\" heigth=\"15px\"></a>'
    //         '<a onClick=\"openurl(''app/index/master_jabatan_add?reqId=' || TRIM(A.POSITION_ID) || ''')\" 
    //         style=\"cursor:pointer\" title=\"Ubah\"><img src=\"images/icn_edit.gif\" width=\"15px\" heigth=\"15px\"></a>'
    //         '<a onClick=\"delete_detail(''' || TRIM(A.POSITION_ID) || ''')\" 
    //         style=\"cursor:pointer\" title=\"Hapus\"><img src=\"images/icon-hapus.png\" width=\"15px\" heigth=\"15px\"></a>'
    //         '<a onClick=\"import_child(''' || TRIM(A.POSITION_ID) || ''')\" 
    //         style=\"cursor:pointer\" title=\"Import\"><img src=\"images/icn-excel.png\" width=\"15px\" heigth=\"15px\"></a>'
    //         ELSE
    //         '<a onClick=\"openurl(''app/index/master_jabatan_add?reqId=' || TRIM(A.POSITION_ID) || ''')\" 
    //         style=\"cursor:pointer\" title=\"Ubah\"><img src=\"images/icn_edit.gif\" width=\"15px\" heigth=\"15px\"></a>'
    //         END LINK_URL_INFO
    //         FROM MASTER_JABATAN A
    //         LEFT JOIN DISTRIK B ON B.KODE = A.KODE_DISTRIK
    //         WHERE 1=1      
    //     ";

    //     while(list($key,$val) = each($paramsArray))
    //     {
    //         $str .= " AND $key = '$val' ";
    //     }

    //     $str .= $statement." ".$sOrder;
    //     $this->query = $str;

    //     return $this->selectLimit($str,$limit,$from); 
    // }

    public function selectbyparams($parameter=array(),$statement="",$order="")
    {
        $query= DB::table('jabatan as a')
            ->select(
                    DB::raw("TRIM(a.position_id) as position_id"), 'a.nama_posisi', DB::raw("TRIM(a.superior_id) as superior_id")
                    , DB::raw("TRIM(a.kode_kategori) as kode_kategori"), 'a.kategori'
                    , DB::raw("TRIM(a.kode_kelompok_jabatan) as kode_kelompok_jabatan"), 'a.kelompok_jabatan'
                    , DB::raw("TRIM(a.kode_jenjang_jabatan) as kode_jenjang_jabatan"), 'a.jenjang_jabatan'
                    , DB::raw("TRIM(a.kode_klasifikasi_unit) as kode_klasifikasi_unit"), 'a.klasifikasi_unit'
                    , DB::raw("TRIM(a.kode_unit) as kode_unit"), 'a.unit', DB::raw("TRIM(a.kode_ditbid) as kode_ditbid")
                    , 'a.ditbid', DB::raw("TRIM(kode_bagian) as kode_bagian"), 'a.bagian', 'a.occup_status', 'a.nama_lengkap', 'a.email'
                    , 'a.nid', 'a.posisi', 'a.change_reason', 'a.tipe'
                    , DB::raw("CASE WHEN a.tipe is null THEN 'Eksternal' else 'Internal' END as tipe_info"), 'a.kode_distrik'
                    , DB::raw("CASE WHEN a.tipe is null THEN 
                        '<a onClick=\"openurl(''app/index/master_jabatan_add?reqId=&reqIdDetil=' || TRIM(a.position_id) || '&reqSuperiorId=TOP'')\" 
                        style=\"cursor:pointer\" title=\"Tambah\"><img src=\"images/icn_add.gif\" width=\"15px\" heigth=\"15px\"></a>'
                        '<a onClick=\"openurl(''jabatan/update/' || TRIM(A.POSITION_ID) || ''')\" 
                        style=\"cursor:pointer\" title=\"Ubah\"><img src=\"images/icn_edit.gif\" width=\"15px\" heigth=\"15px\"></a>'
                        '<a onClick=\"delete_detail(''' || TRIM(A.POSITION_ID) || ''')\" 
                        style=\"cursor:pointer\" title=\"Hapus\"><img src=\"images/icon-hapus.png\" width=\"15px\" heigth=\"15px\"></a>'
                        '<a onClick=\"import_child(''' || TRIM(A.POSITION_ID) || ''')\" 
                        style=\"cursor:pointer\" title=\"Import\"><img src=\"images/icn-excel.png\" width=\"15px\" heigth=\"15px\"></a>'
                        ELSE
                        '<a onClick=\"openurl(''jabatan/update/' || TRIM(A.POSITION_ID) || ''')\" 
                        style=\"cursor:pointer\" title=\"Ubah\"><img src=\"images/icn_edit.gif\" width=\"15px\" heigth=\"15px\"></a>'
                        END LINK_URL_INFO")
            );

        if(!empty($statement))
        {
            $query->whereRaw($statement , $parameter);
        }

        if(!empty($order))
        {
            $query->orderByRaw($order);
        }
        
        return $query;
    }


    // public function strategi_bisnis()
    // {
    // 	return 
    // 	$this->belongsTo('App\Entities\Strategi_bisnis','strategi_bisnis_id','id');
    // }
}
 
