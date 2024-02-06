<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class FileApproval extends Model
{
    protected $table = "file_approval";

    protected $fillable = ['id', 'tahun_anggaran', 'lokasi_id', 'approval_id', 'file_import_id', 'file_approval_status', 'keterangan', 'approval_by', 'approval_at', 'jenis_id'];

    public function lokasi()
    {
        return
            $this->belongsTo('App\Entities\Lokasi', 'lokasi_id', 'id');
    }

    public function approval()
    {
        return
            $this->belongsTo('App\Entities\Approval', 'approval_id', 'id');
    }

    public function fileImport()
    {
        return
            $this->belongsTo('App\Entities\FileImport', 'file_import_id', 'id');
    }

    public function fileapprovalstatus()
    {
        # code...
        return
            $this->belongsTo('App\Entities\FileApprovalStatus', 'file_approval_status_id', 'id');
    }

    public function konseptor($value = '')
    {
        # code...
        return
            $this->belongsTo('App\Entities\User', 'created_by', 'id');
    }

    public function approvalby()
    {
        return
            $this->belongsTo('App\Entities\Role', 'approval_by', 'id');
    }

    public function approvalByOnFase()
    {
        return
            // $this->belongsTo('App\Entities\Approval','approval_by','id');
            $this->belongsTo('App\Entities\Approval', 'approval_id', 'id');
    }

    public function jenis()
    {
        # code...
        return
            $this->belongsTo('App\Entities\Jenis', 'jenis_id', 'id');
    }

    public function parent()
    {
        return
            $this->belongsTo('App\Entities\FileApproval', 'file_approval_parent_id', 'id');
    }

    public function lokasiterakhir($id)
    {
        // $approval = FileApproval::find($id)->approval;
        // $nilai = $approval->role->name;
        // $anak = FileApproval::where('file_approval_parent_id',$id)->first();
        // if ($anak) {
        //     $nilai = $anak->approval->role->name;
        //     # code...
        // }
        // // jika bukan proses pertama 
        // if ( $approval->id > 1 ) $nilai = "-";
        $file_approval = FileApproval::find($id);
        $latest_approval_id = $file_approval->latest_approval_id;

        //jika fase = usulan unit & masih di staff unit
        if ($latest_approval_id == NULL) {
            $approval = $file_approval->approval;
            $nilai = $approval->role->name;
        } else {
            //jika returned
            // if($file_approval->file_approval_status==2) {

            // }
            // else {
            $approval = Approval::find($latest_approval_id);

            //fase ketetapan dengan urutan terakhir
            $approval_paling_akhir = Approval::where('fase_id', '3')->orderBy('urutan', 'desc')->first();
            // if($approval_paling_akhir->id == $approval->id && $file_approval->file_approval_status_id=='4') {
            if ($approval_paling_akhir->id == $latest_approval_id) {
                $finished =
                    FileApproval::where('file_import_id', $file_approval->file_import_id)
                    ->where('latest_approval_id', $file_approval->latest_approval_id)
                    ->where('approval_id', $approval_paling_akhir->id)
                    ->where('file_approval_status_id', '4')
                    ->first();

                if ($finished)
                    $nilai = "-";
                else
                    $nilai = $approval->role->name;
            } else {
                $nilai = $approval->role->name;
            }
            // }
        }

        return $nilai;
    }

    public function lokasiterakhir_role_id($id)
    {
        // $approval = FileApproval::find($id)->approval;
        // $nilai = $approval->role->name;
        // $anak = FileApproval::where('file_approval_parent_id',$id)->first();
        // if ($anak) {
        //     $nilai = $anak->approval->role->name;
        //     # code...
        // }
        // // jika bukan proses pertama 
        // if ( $approval->id > 1 ) $nilai = "-";
        $file_approval = FileApproval::find($id);
        $latest_approval_id = $file_approval->latest_approval_id;

        //jika fase = usulan unit & masih di staff unit
        if ($latest_approval_id == NULL) {
            $approval = $file_approval->approval;
            $nilai = $approval->role->id;
        } else {
            //jika returned
            // if($file_approval->file_approval_status==2) {

            // }
            // else {
            $approval = Approval::find($latest_approval_id);

            //fase ketetapan dengan urutan terakhir
            $approval_paling_akhir = Approval::where('fase_id', '3')->orderBy('urutan', 'desc')->first();
            // if($approval_paling_akhir->id == $approval->id && $file_approval->file_approval_status_id=='4') {
            if ($approval_paling_akhir->id == $latest_approval_id) {
                $finished =
                    FileApproval::where('file_import_id', $file_approval->file_import_id)
                    ->where('latest_approval_id', $file_approval->latest_approval_id)
                    ->where('approval_id', $approval_paling_akhir->id)
                    ->where('file_approval_status_id', '4')
                    ->first();

                if ($finished)
                    $nilai = "-";
                else
                    $nilai = $approval->role->id;
            } else {
                $nilai = $approval->role->id;
            }
            // }
        }

        return $nilai;
    }

    public function cek_fase_interchange($file_approval, $lokasi_id, $fase_id, $jenis_id, $tahun_anggaran)
    {
        $urutan_terakhir_fase = Approval::where('fase_id', $fase_id)->orderBy('urutan', 'desc')->first();
        // $file_approval = FileApproval::find($id);

        if ($file_approval->approval->fase_id != 4) {
            return '';
        }

        // tampilkan centang jika pada sequence terakhir dan berstatus 'approved'
        if (
            $file_approval->latest_approval_id == $urutan_terakhir_fase->id
            && $file_approval->file_approval_status_id == 4
        ) {

            return 'centang';
        } else {
            return 'minus';
        }
    }

    public function cek_fase($file_approval_selected, $lokasi_id, $fase_id, $jenis_id, $tahun_anggaran)
    {
        if ($file_approval_selected->approval->fase_id > 3) {
            return '';
        }

        $urutan_terakhir_fase_skrg = Approval::where('fase_id', $fase_id)->orderBy('urutan', 'desc')->first()->id;
        $urutan_pertama_fase_skrg = Approval::where('fase_id', $fase_id)->orderBy('urutan', 'asc')->first()->id;
        $approval = Approval::select('id')->where('fase_id', $fase_id)->get();

        $file_approval = FileApproval::select('file_import_id')
            ->where('lokasi_id', $lokasi_id)
            ->whereIn('approval_id', $approval)
            ->where('jenis_id', $jenis_id)
            ->where('tahun_anggaran', $tahun_anggaran)
            ->groupBy('file_import_id')->get();

        //ambil data file_import_id, latest_approval_id
        $file_approval_all = FileApproval::select('file_import_id', 'latest_approval_id')
            ->where('lokasi_id', $lokasi_id)
            ->whereIn('approval_id', $approval)
            ->where('jenis_id', $jenis_id)
            ->where('tahun_anggaran', $tahun_anggaran)
            ->groupBy('file_import_id', 'latest_approval_id')->get();

        // $file_approval_selected = FileApproval::find($id);

        //fase ketetapan dengan urutan terakhir
        $approval_paling_akhir = Approval::where('fase_id', '3')->orderBy('urutan', 'desc')->first();

        if ($fase_id == '1' || $fase_id == '2') {
            //jika ada 2 draft
            if (count($file_approval) > 1) {
                $sudah_ada_yg_disetujui = 0;
                foreach ($file_approval_all as $fa) {
                    if ($fa->latest_approval_id > $urutan_terakhir_fase_skrg) {
                        $sudah_ada_yg_disetujui++;
                    }
                }

                if ($file_approval_selected->latest_approval_id > $urutan_terakhir_fase_skrg) {
                    // $hasil = 'centang';
                    $hasil = $this->cek_parent_dibuat_pada_fase($file_approval_selected->id, $fase_id, $urutan_pertama_fase_skrg);
                } else {
                    if ($sudah_ada_yg_disetujui > 0) {
                        // $hasil = 'silang';

                        //cek apakah parent di buat pada fase 1 atau 2
                        $hasil = $this->cek_parent_dibuat_pada_fase($file_approval_selected->id, $fase_id, $urutan_pertama_fase_skrg);

                        //jika tidak dibuat pada fase sekarang, maka hasil = kotak
                        //jika dibuat pada fase sekarang, maka defaultnya hasil = silang, karena sudah ada yang disetujui
                        if ($hasil == 'kotak') $hasil = $hasil;
                        else $hasil = 'silang';
                    }
                    //dari semua draft belum ada yg disetujui
                    else {
                        $hasil = '-a';
                    }
                }
            } else {
                //jika file approval tidak kosong
                if (!$file_approval->isEmpty()) {
                    //jika yang di setujui adalah draft yang sama dengan file approval yg dipilih (diwakili oleh variable:  $id)
                    if ($file_approval_selected->latest_approval_id > $urutan_terakhir_fase_skrg) {
                        // untuk draft yang baru di create di fase pembahasan teknis/ketetapan final
                        if ($file_approval_selected->file_approval_parent_id == NULL) {
                            // $hasil = 'kotak';
                            $hasil = $this->cek_parent_dibuat_pada_fase($file_approval_selected->id, $fase_id, $urutan_pertama_fase_skrg);
                        } else {
                            // $hasil = 'centang';
                            $hasil = $this->cek_parent_dibuat_pada_fase($file_approval_selected->id, $fase_id, $urutan_pertama_fase_skrg);
                        }
                    } else {
                        //fase 2
                        if ($fase_id == '2') {
                            // untuk draft yang baru di create di fase pembahasan teknis/ketetapan final
                            $hasil = '-b';
                        } else {
                            $hasil = '-b';
                        }
                    }
                } else {
                    $hasil = '-c';
                }
            }
        }
        //fase 3
        else {
            //jika ada 2 draft
            if (count($file_approval) > 1) {
                $sudah_ada_yg_disetujui = 0;
                foreach ($file_approval_all as $fa) {
                    if ($approval_paling_akhir->id == $fa->latest_approval_id) {
                        $finished =
                            FileApproval::where('file_import_id', $fa->file_import_id)
                            ->where('latest_approval_id', $fa->latest_approval_id)
                            ->where('approval_id', $approval_paling_akhir->id)
                            ->where('file_approval_status_id', '4')
                            ->first();

                        if ($finished)
                            $sudah_ada_yg_disetujui++;
                    }
                }

                if ($approval_paling_akhir->id == $file_approval_selected->latest_approval_id) {
                    $finished =
                        FileApproval::where('file_import_id', $file_approval_selected->file_import_id)
                        ->where('latest_approval_id', $file_approval_selected->latest_approval_id)
                        ->where('approval_id', $approval_paling_akhir->id)
                        ->where('file_approval_status_id', '4')
                        ->first();

                    if ($finished)
                        $hasil = "centang";
                    else
                        $hasil = "-d";
                } else {
                    if ($sudah_ada_yg_disetujui > 0) {
                        $hasil = 'silang';
                    }
                    //dari semua draft belum ada yg disetujui
                    else {
                        $hasil = '-d';
                    }
                }
            }
            // hanya ada 1 draft 
            else {
                //jika file approval tidak kosong
                if (!$file_approval->isEmpty()) {
                    if ($approval_paling_akhir->id == $file_approval_selected->latest_approval_id) {
                        $finished =
                            FileApproval::where('file_import_id', $file_approval_selected->file_import_id)
                            ->where('latest_approval_id', $file_approval_selected->latest_approval_id)
                            ->where('approval_id', $approval_paling_akhir->id)
                            ->where('file_approval_status_id', '4')
                            ->first();

                        if ($finished)
                            $hasil = "centang";
                        else
                            $hasil = "-e";
                    } else {
                        if ($file_approval_selected->latest_approval_id == NULL) {
                            $hasil = 'silang';
                        } else {
                            //cek apakah sudah ada yang finished
                            $finished =
                                FileApproval::where('lokasi_id', $file_approval_selected->lokasi_id)
                                ->where('jenis_id', $file_approval_selected->jenis_id)
                                ->where('latest_approval_id', $approval_paling_akhir->id)
                                ->where('approval_id', $approval_paling_akhir->id)
                                ->where('file_approval_status_id', '4')
                                ->first();

                            if ($finished)
                                $hasil = "silang";
                            else
                                $hasil = "-e";
                        }
                    }
                } else {
                    $hasil = "-f";
                }
            }
        }

        return $hasil;
    }

    public function cek_parent_dibuat_pada_fase($id, $fase_id, $urutan_pertama_fase_skrg)
    {
        $current = FileApproval::where('id', $id)->first();
        if ($current->file_approval_parent_id) {
            //fase 2: cek hanya sampai parent pada fase 2, approval urutan 1
            if ($fase_id == 2) {
                if ($current->approval_id == $urutan_pertama_fase_skrg) {
                    $hasil = 'centang';
                } else {
                    $hasil = $this->cek_parent_dibuat_pada_fase($current->file_approval_parent_id, $fase_id, $urutan_pertama_fase_skrg);
                }
            }
            //fase 1: cek sampai parent pertama
            else {
                $hasil = $this->cek_parent_dibuat_pada_fase($current->file_approval_parent_id, $fase_id, $urutan_pertama_fase_skrg);
            }
        } else {
            if ($current->approval_id > $urutan_pertama_fase_skrg) {
                $hasil = 'kotak';
            } else {
                $hasil = 'centang';
            }
        }

        return $hasil;
    }

    public function faseterakhir()
    {
        return
            $this->belongsTo('App\Entities\Approval', 'latest_approval_id', 'id');
    }
}
