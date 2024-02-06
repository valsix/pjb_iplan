<?php namespace App\Entities;

use App\Entities\TorAttachment;
use Illuminate\Database\Eloquent\Model;

class Tor extends Model
{
    protected $table = "tor";

    // protected $fillable = ['no_dokumen','no_prk', 'nama_prk','tahun_anggaran', 'lokasi_id', 'tor_filepath','tor_review_status_id', 'tor_review_phase_id' ,'created_at', 'created_by' ,'updated_at', 'updated_by','is_submitted' ,'submitted_at', 'submitted_by','rejected_at','rejected_by', 'revised_at','revised_by','approved_at','approved_by','alasan','latar_belakang', 'sasaran_tujuan', 'permasalahan', 'alternatif_pencapaian', 'benefit_operasional', 'benefit_finansial', 'alasan_latar_belakang', 'alasan_sasaran_tujuan', 'alasan_permasalahan', 'alasan_alternatif_pencapaian', 'alasan_benefit_operasional', 'alasan_benefit_finansial','jumlah_anggaran'];

    protected $fillable = [
        'created_at',
        'created_by',
        'is_published',
        'is_submitted',
        'judul_dokumen',
        'lokasi_id',
        'manager_user_id', // Approved To
        'no_dokumen',
        'approved_at',
        'approved_by',
        'rejected_at',
        'rejected_by',
        'revised_at',
        'revised_by',
        'submitted_at',
        'submitted_by',
        'tahun_anggaran',
        'tor_filepath',
        'tor_review_phase_id',
        'tor_review_status_id',
        'no_dokumen_dmr',

        'aspek_keamanan_k3',
        'data_teknis',
        'delivery',
        'detail_pelaksanaan_pekerjaan',
        'garansi',
        'kelengkapan_pelaksanaan_pekerjaan',
        'kualifikasi_calon_pelaksanaan_pekerjaan',
        'lain_lain',
        'laporan_hasil_pekerjaan',
        'lingkup_pekerjaan',
        'material_sisa_limbah',
        'pendahuluan',
        'performance_desain',
        'quality_acceptance',

        'alasan',
        'alasan_aspek_keamanan_k3',
        'alasan_data_teknis',
        'alasan_delivery',
        'alasan_detail_pelaksanaan_pekerjaan',
        'alasan_garansi',
        'alasan_kelengkapan_pelaksanaan_pekerjaan',
        'alasan_kualifikasi_calon_pelaksanaan_pekerjaan',
        'alasan_lain_lain',
        'alasan_laporan_hasil_pekerjaan',
        'alasan_lingkup_pekerjaan',
        'alasan_material_sisa_limbah',
        'alasan_pendahuluan',
        'alasan_performance_desain',
        'alasan_quality_acceptance',
    ];


    public function tor_review_status()
    {
    	return
		$this->belongsTo('App\Entities\TorReviewStatus','tor_review_status_id','id');
    }

    public function tor_attachment()
    {
    	return
		$this->hasMany('App\Entities\TorAttachment', 'tor_id', 'id');
    }

    public function tor_review_phase()
    {
        return
        $this->belongsTo('App\Entities\TorReviewPhase','tor_review_phase_id','id');
    }
    public function lokasi()
    {
        return
        $this->belongsTo('App\Entities\Lokasi','lokasi_id','id');
    }

    public function approved_by()
    {
        return
        $this->belongsTo('App\Entities\User','approved_by','id');
    }
    public function rejected_by()
    {
        return
        $this->belongsTo('App\Entities\User','rejected_by','id');
    }
    public function revised_by()
    {
        return
        $this->belongsTo('App\Entities\User','revised_by','id');
    }
    public function submitted_by()
    {
        return
        $this->belongsTo('App\Entities\User','submitted_by','id');
    }
    public function dmr()
    {
        return
        $this->belongsTo('App\Entities\Dmr','no_dokumen_dmr','no_dokumen');
    }


    public function getZipName($zip_name = null)
    {
        if ( !$zip_name ) {
            $zip_name = 'tor_attachment';
            if ($this->no_dokumen) {
                $zip_name .= '_'. $this->no_dokumen;
            }
        }
        return $zip_name .'.zip';
    }
    public function getZipPath($zip_name = null)
    {
        return public_path('tor') .'/'. $this->getZipName($zip_name);
    }
    public function getZip($zip_name = null)
    {
        $attachment = TorAttachment::where('tor_id', $this->id)
            ->where('for_review', 0)
            ->whereNotNull('filepath')
            ->where('filepath', '<>', '')->get();

        if (count($attachment) == 0) {
            return false;
        }

        $zip_path = $this->getZipPath($zip_name);

        // dd($zip_path);

        // arsip sebagai zip
        $zip = new \ZipArchive();
        $status = $zip->open($zip_path, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
            // dd($status);
            // dd(count($attachment));
            $sucess_path = [];
            $fail_path = [];
            foreach ($attachment as $key => $file) {
                $base_path = basename($file->filepath);
                $file_path = public_path($file->filepath);
                // dd($base_path, $file_path);
                if ( file_exists($file->filepath) ) {
                    $zip->addFromString($base_path, file_get_contents($file_path));
                    $zip->setCompressionName($base_path, \ZipArchive::CM_STORE);
                    $sucess_path[] = $file_path;
                } else {
                    $zip->addFromString($base_path . ' [hilang].txt', 'FILE ASLI TELAH RUSAK/TIDAK DITEMUKAN');
                    // $zip->setCompressionName($base_path, \ZipArchive::CM_STORE);
                    $fail_path[] = $file_path;
                }
            }

            // dd(['success' => $sucess_path, 'failed' => $fail_path]);
            // if (count($sucess_path) == 0) {
            //     // abort(404);
            //     return false;
            // }

        $zip->close();


        return $zip_path;
    }
}
