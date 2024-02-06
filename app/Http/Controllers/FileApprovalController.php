<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Entities\Approval;
use App\Entities\FileApproval;
use App\Entities\FileApprovalStatus;
use App\Entities\FileImport;
use App\Entities\Template;
use App\Entities\StrategiBisnis;
use App\Entities\Distrik;
use App\Entities\Lokasi;
use App\Entities\Fase;
use App\Entities\Jenis;
use App\Entities\User;
use App\Entities\Role;
use App\Entities\ExcelData;
use App\Entities\FileImportKetetapan;
use App\Entities\PGDLFileImportRevisi;
use App\Entities\ExcelDataKetetapan;
use Illuminate\Support\Facades\Artisan;
use DB;
use Mail;

class FileApprovalController extends Controller
{
    public function assignment()
    {
        $user_id = session('user_id');
        $user = User::find($user_id);
        $role_id = session('role_id');
        $role = Role::find($role_id);

        //kantor pusat
        if ($role->is_kantor_pusat) {
            $Sb = StrategiBisnis::all();
        } else {
            $Sb = StrategiBisnis::where('id', $user->distrik->strategi_bisnis->id)->get();
        }
        // dd($Sb);

        // get filter
        $tahun_anggaran = Input::get('tahun_anggaran');
        $input_sb = Input::get('strategi_bisnis');
        $distrik_id = Input::get('distrik');
        $lokasi_id = Input::get('lokasi');
        $conditions = array();

        $tahun = Template::select('tahun')->where('jenis_id', 2)->orWhere('jenis_id', 1)->orWhere('jenis_id', 3)->distinct()->get();

        if (Input::get('strategi_bisnis') != NULL) {
            $input_sb = DB::table('strategi_bisnis')->select('name', 'id')->where('id', Input::get('strategi_bisnis'))->get()[0];
            $distrik = Distrik::select('name', 'id')->where('strategi_bisnis_id', $input_sb->id)->get();
        }
        if ($distrik_id != NULL) {
            $input_distrik = DB::table('distrik')->select('name', 'id')->where('id', $distrik_id)->get()[0];
            $lokasi = Lokasi::select('name', 'id')->where('distrik_id', $input_distrik->id)->get();
        }
        if ($lokasi_id != NULL) {
            $input_lokasi = DB::table('lokasi')->select('name', 'id')->where('id', $lokasi_id)->get()[0];
        }

        // dd($tahun_anggaran." ".$strategi_bisnis_id." ".$distrik_id." ".$lokasi_id." ".$jenis_id);
        if ($tahun_anggaran) {
            $conditions['tahun_anggaran'] = $tahun_anggaran;
        }
        if ($lokasi_id) {
            $conditions['lokasi_id'] = $lokasi_id;
        }
        // dd($conditions);
        $fileapproval_ketetapan_draft_selain_usulan_unit = NULL;

        if ($role->is_kantor_pusat) {
            $fileapproval = FileApproval::with(['jenis', 'fileImport', 'lokasi.distrik', 'faseterakhir', 'approval.fase'])
                ->select(
                    \DB::raw("DISTINCT ON (file_import_id) file_import_id"),
                    'id',
                    'lokasi_id',
                    'latest_approval_id',
                    'approval_id',
                    'tahun_anggaran',
                    'jenis_id',
                    'updated_at',
                    'file_approval_status_id'
                )
                ->whereHas('fileImport', function ($query) {
                    $query->where('status_upload_id', '3'); // berhasil upload
                })
                ->whereHas('approval', function ($query) {
                    $query->whereHas('role', function ($query2) {
                        $role_id = session('role_id');
                        $query2->where('role_id', $role_id);
                    });
                })
                ->where($conditions)
                ->groupBy(
                    'id',
                    'lokasi_id',
                    'approval_id',
                    'tahun_anggaran',
                    'jenis_id',
                    'updated_at',
                    'latest_approval_id',
                    'file_import_id'
                )
                ->orderBy('file_import_id', 'desc')
                ->orderBy('id', 'desc')
                ->orderBy('updated_at', 'desc')
                ->get();
        } else {
            //Khusus Manager Unit
            if ($role_id == 3) {
                // $conditions['manager_unit_user_id'] = $user_id;

                $fileapproval = FileApproval::with(['jenis', 'fileImport', 'lokasi.distrik', 'faseterakhir', 'approval.fase'])
                    ->select(
                        \DB::raw("DISTINCT ON (file_import_id) file_import_id"),
                        'file_approval.id',
                        'lokasi_id',
                        'latest_approval_id',
                        'approval_id',
                        'tahun_anggaran',
                        'jenis_id',
                        'file_approval.updated_at',
                        'file_approval_status_id'
                    )
                    ->whereHas('fileImport', function ($query) {
                        $query->where('status_upload_id', '3'); // berhasil upload
                    })
                    ->whereHas('approval', function ($query) {
                        $query->whereHas('role', function ($query2) {
                            $role_id = session('role_id');
                            $query2->where('role_id', $role_id);
                        });
                    })
                    ->where($conditions)
                    ->where('manager_unit_user_id', $user_id)
                    ->whereHas('lokasi', function ($query3) {
                        $query3->whereHas('distrik', function ($query4) {
                            $user_id = session('user_id');
                            $user = User::find($user_id);
                            $query4->where('id', $user->distrik_id);
                        });
                    })
                    // ->join('jenis', 'file_approval.jenis_id', '=', 'jenis.id')
                    ->groupBy('jenis_id', 'file_approval.id', 'lokasi_id', 'approval_id', 'tahun_anggaran', 'file_approval.updated_at', 'latest_approval_id')
                    ->orderBy('file_import_id', 'desc')
                    ->orderBy('file_approval.id', 'desc')
                    ->orderBy('file_approval.updated_at', 'desc')
                    ->get();

                // dd($fileapproval);
                $i = 0;
                $jenis_utk_manager_unit = array();
                foreach ($fileapproval as $key => $value) {
                    // dump($value->jenis_id);
                    $jenis_utk_manager_unit[$i] = $value->jenis_id;
                    $i++;
                }
                // dd($jenis_utk_manager_unit);
                // die();'

                $fileapproval_ketetapan_draft_selain_usulan_unit = FileApproval::select(
                    \DB::raw("DISTINCT ON (file_import_id) file_import_id"),
                    'file_approval.id',
                    'lokasi_id',
                    'latest_approval_id',
                    'approval_id',
                    'tahun_anggaran',
                    'jenis_id',
                    'file_approval.updated_at'
                )
                    ->whereHas('fileImport', function ($query) {
                        $query->where('status_upload_id', '3'); // berhasil upload
                    })
                    ->where($conditions)
                    ->where('latest_approval_id', 9)
                    ->where('file_approval_status_id', 4)
                    ->where('file_approval_parent_id', NULL)
                    ->where('approval_id', '!=', 1)
                    ->whereIn('jenis_id', $jenis_utk_manager_unit)
                    ->whereHas('lokasi', function ($query3) {
                        $query3->whereHas('distrik', function ($query4) {
                            $user_id = session('user_id');
                            $user = User::find($user_id);
                            $query4->where('id', $user->distrik_id);
                        });
                    })
                    // ->join('jenis', 'file_approval.jenis_id', '=', 'jenis.id')
                    ->groupBy('jenis_id', 'file_approval.id', 'lokasi_id', 'approval_id', 'tahun_anggaran', 'file_approval.updated_at', 'latest_approval_id')
                    ->orderBy('file_import_id', 'desc')
                    ->orderBy('file_approval.id', 'desc')
                    ->orderBy('file_approval.updated_at', 'desc')
                    ->get();
            } else {
                $fileapproval = FileApproval::with(['jenis', 'fileImport', 'lokasi.distrik', 'faseterakhir', 'approval.fase'])
                    ->select(
                        \DB::raw("DISTINCT ON (file_import_id) file_import_id"),
                        'file_approval.id',
                        'lokasi_id',
                        'latest_approval_id',
                        'approval_id',
                        'tahun_anggaran',
                        'jenis_id',
                        'file_approval.updated_at',
                        'file_approval_status_id'
                    )
                    ->whereHas('fileImport', function ($query) {
                        $query->where('status_upload_id', '3'); // berhasil upload
                    })
                    ->whereHas('approval', function ($query) {
                        $query->whereHas('role', function ($query2) {
                            $role_id = session('role_id');
                            $query2->where('role_id', $role_id);
                        });
                    })
                    ->where($conditions)
                    ->whereHas('lokasi', function ($query3) {
                        $query3->whereHas('distrik', function ($query4) {
                            $user_id = session('user_id');
                            $user = User::find($user_id);
                            $query4->where('id', $user->distrik_id);
                        });
                    })
                    // ->join('jenis', 'file_approval.jenis_id', '=', 'jenis.id')
                    ->groupBy('jenis_id', 'file_approval.id', 'lokasi_id', 'approval_id', 'tahun_anggaran', 'file_approval.updated_at', 'latest_approval_id')
                    ->orderBy('file_import_id', 'desc')
                    ->orderBy('file_approval.id', 'desc')
                    ->orderBy('file_approval.updated_at', 'desc')
                    ->get();

                $fileapproval_ketetapan_draft_selain_usulan_unit = FileApproval::with(['jenis', 'fileImport', 'lokasi.distrik', 'faseterakhir', 'approval.fase'])
                    ->select(
                        \DB::raw("DISTINCT ON (file_import_id) file_import_id"),
                        'file_approval.id',
                        'lokasi_id',
                        'latest_approval_id',
                        'approval_id',
                        'tahun_anggaran',
                        'jenis_id',
                        'file_approval.updated_at',
                        'file_approval_status_id'
                    )
                    ->whereHas('fileImport', function ($query) {
                        $query->where('status_upload_id', '3'); // berhasil upload
                    })
                    ->where($conditions)
                    ->where('latest_approval_id', 9)
                    ->where('file_approval_status_id', 4)
                    ->where('file_approval_parent_id', NULL)
                    ->where('approval_id', '!=', 1)
                    ->whereHas('lokasi', function ($query3) {
                        $query3->whereHas('distrik', function ($query4) {
                            $user_id = session('user_id');
                            $user = User::find($user_id);
                            $query4->where('id', $user->distrik_id);
                        });
                    })
                    // ->join('jenis', 'file_approval.jenis_id', '=', 'jenis.id')
                    ->groupBy('jenis_id', 'file_approval.id', 'lokasi_id', 'approval_id', 'tahun_anggaran', 'file_approval.updated_at', 'latest_approval_id')
                    ->orderBy('file_import_id', 'desc')
                    ->orderBy('file_approval.id', 'desc')
                    ->orderBy('file_approval.updated_at', 'desc')
                    ->get();
            }
            // dd($fileapproval);
            // dd($fileapproval_ketetapan_draft_selain_usulan_unit);
        }

        $fileapproval_cek_status = FileApproval::selectRaw('count(file_approval_status_id)')
            ->whereHas('fileImport', function ($query) {
                $query->where('status_upload_id', '3'); // berhasil upload
            })
            ->whereHas('approval', function ($query) {
                $query->whereHas('role', function ($query2) {
                    $role_id = session('role_id');
                    $query2->where('role_id', $role_id);
                });
            })
            ->where($conditions)
            ->groupBy('file_approval_status_id')
            ->get()
            ->count();

        $data = [
            'fileapproval' => $fileapproval,
            'fileapproval_cek_status' => $fileapproval_cek_status,
            'fileapproval_ketetapan_draft_selain_usulan_unit' => $fileapproval_ketetapan_draft_selain_usulan_unit
        ];
        // $Sb = StrategiBisnis::all();
        $approval_terakhir = Approval::where('fase_id', '3')->orderBy('urutan', 'desc')->first();

        return view('assignment', $data, compact('tahun', 'tahun_anggaran', 'Sb', 'input_sb', 'distrik', 'input_distrik', 'lokasi', 'input_lokasi', 'jenis', 'role', 'approval_terakhir'));
    }

    public function index($jenis_id)
    {
        // $fileapproval = FileApproval::all();

        // select fa.lokasi_id, fa.tahun_anggaran, fa.approval_id, fa.jenis_id
        // --select fa.*
        // from file_approval fa
        // join approval a on a.id = fa.approval_id
        // where a.role_id = 1 and fa.lokasi_id = 22
        // group by fa.lokasi_id, fa.jenis_id, fa.tahun_anggaran, fa.approval_id
        $jenis = Jenis::find($jenis_id);

        // get session user_id dan role_id
        $user_id = session('user_id');
        $user = User::find($user_id);
        $role_id = session('role_id');
        $role = Role::find($role_id);
        // dd($role_id);

        // get filter
        $tahun_anggaran = Input::get('tahun_anggaran');
        $input_sb = Input::get('strategi_bisnis');
        $distrik_id = Input::get('distrik');
        $lokasi_id = Input::get('lokasi');
        $conditions = array();

        $tahun = Template::select('tahun')->where('jenis_id', 2)->orWhere('jenis_id', 1)->orWhere('jenis_id', 3)->distinct()->get();

        if (Input::get('strategi_bisnis') != NULL) {
            $input_sb = DB::table('strategi_bisnis')->select('name', 'id')->where('id', Input::get('strategi_bisnis'))->get()[0];
            $distrik = Distrik::select('name', 'id')->where('strategi_bisnis_id', $input_sb->id)->get();
        }
        if ($distrik_id != NULL) {
            $input_distrik = DB::table('distrik')->select('name', 'id')->where('id', $distrik_id)->get()[0];
            $lokasi = Lokasi::select('name', 'id')->where('distrik_id', $input_distrik->id)->get();
        }
        if ($lokasi_id != NULL) {
            $input_lokasi = DB::table('lokasi')->select('name', 'id')->where('id', $lokasi_id)->get()[0];
        }

        // dd($tahun_anggaran." ".$strategi_bisnis_id." ".$distrik_id." ".$lokasi_id." ".$jenis_id);
        if ($tahun_anggaran) {
            $conditions['tahun_anggaran'] = $tahun_anggaran;
        }
        if ($lokasi_id) {
            $conditions['lokasi_id'] = $lokasi_id;
        }

        $fileapproval_ketetapan_draft_selain_usulan_unit = NULL;

        //Jenis
        $conditions['jenis_id'] = $jenis_id;
        // dd($conditions);

        if ($role->is_kantor_pusat) {
            $fileapproval = FileApproval::select(
                \DB::raw("DISTINCT ON (file_import_id) file_import_id"),
                'id',
                'lokasi_id',
                'latest_approval_id',
                'approval_id',
                'tahun_anggaran',
                'jenis_id',
                'updated_at'
            )
                ->whereHas('fileImport', function ($query) {
                    $query->where('status_upload_id', '3'); // berhasil upload
                })
                ->whereHas('approval', function ($query) {
                    $query->whereHas('role', function ($query2) {
                        $role_id = session('role_id');
                        $query2->where('role_id', $role_id);
                    });
                })
                ->where($conditions)
                ->groupBy('id', 'lokasi_id', 'approval_id', 'tahun_anggaran', 'jenis_id', 'updated_at', 'latest_approval_id')
                ->orderBy('file_import_id', 'desc')
                ->orderBy('id', 'desc')
                ->orderBy('updated_at', 'desc')
                ->get();
        } else {
            //Khusus Manager Unit
            if ($role_id == 3) {
                $fileapproval = FileApproval::select(
                    \DB::raw("DISTINCT ON (file_import_id) file_import_id"),
                    'file_approval.id',
                    'lokasi_id',
                    'latest_approval_id',
                    'approval_id',
                    'tahun_anggaran',
                    'jenis_id',
                    'file_approval.updated_at'
                )
                    ->whereHas('fileImport', function ($query) {
                        $query->where('status_upload_id', '3'); // berhasil upload
                    })
                    ->whereHas('approval', function ($query) {
                        $query->whereHas('role', function ($query2) {
                            $role_id = session('role_id');
                            $query2->where('role_id', $role_id);
                        });
                    })
                    ->where($conditions)
                    ->where('manager_unit_user_id', $user_id)
                    ->whereHas('lokasi', function ($query3) {
                        $query3->whereHas('distrik', function ($query4) {
                            $user_id = session('user_id');
                            $user = User::find($user_id);
                            $query4->where('id', $user->distrik_id);
                        });
                    })
                    // ->join('jenis', 'file_approval.jenis_id', '=', 'jenis.id')
                    ->groupBy('jenis_id', 'file_approval.id', 'lokasi_id', 'approval_id', 'tahun_anggaran', 'file_approval.updated_at', 'latest_approval_id')
                    ->orderBy('file_import_id', 'desc')
                    ->orderBy('file_approval.id', 'desc')
                    ->orderBy('file_approval.updated_at', 'desc')
                    ->get();

                $i = 0;
                $jenis_utk_manager_unit = array();
                foreach ($fileapproval as $key => $value) {
                    // dump($value->jenis_id);
                    $jenis_utk_manager_unit[$i] = $value->jenis_id;
                    $i++;
                }
                // dd($jenis_utk_manager_unit);
                // die();'

                $fileapproval_ketetapan_draft_selain_usulan_unit = FileApproval::select(
                    \DB::raw("DISTINCT ON (file_import_id) file_import_id"),
                    'file_approval.id',
                    'lokasi_id',
                    'latest_approval_id',
                    'approval_id',
                    'tahun_anggaran',
                    'jenis_id',
                    'file_approval.updated_at'
                )
                    ->whereHas('fileImport', function ($query) {
                        $query->where('status_upload_id', '3'); // berhasil upload
                    })
                    ->where($conditions)
                    ->where('latest_approval_id', 9)
                    ->where('file_approval_status_id', 4)
                    ->where('file_approval_parent_id', NULL)
                    ->where('approval_id', '!=', 1)
                    ->whereIn('jenis_id', $jenis_utk_manager_unit)
                    ->whereHas('lokasi', function ($query3) {
                        $query3->whereHas('distrik', function ($query4) {
                            $user_id = session('user_id');
                            $user = User::find($user_id);
                            $query4->where('id', $user->distrik_id);
                        });
                    })
                    // ->join('jenis', 'file_approval.jenis_id', '=', 'jenis.id')
                    ->groupBy('jenis_id', 'file_approval.id', 'lokasi_id', 'approval_id', 'tahun_anggaran', 'file_approval.updated_at', 'latest_approval_id')
                    ->orderBy('file_import_id', 'desc')
                    ->orderBy('file_approval.id', 'desc')
                    ->orderBy('file_approval.updated_at', 'desc')
                    ->get();
            } else {
                $fileapproval = FileApproval::select(
                    \DB::raw("DISTINCT ON (file_import_id) file_import_id"),
                    'file_approval.id',
                    'lokasi_id',
                    'latest_approval_id',
                    'approval_id',
                    'tahun_anggaran',
                    'jenis_id',
                    'file_approval.updated_at'
                )
                    ->whereHas('fileImport', function ($query) {
                        $query->where('status_upload_id', '3'); // berhasil upload
                    })
                    ->whereHas('approval', function ($query) {
                        $query->whereHas('role', function ($query2) {
                            $role_id = session('role_id');
                            $query2->where('role_id', $role_id);
                        });
                    })
                    ->where($conditions)
                    ->whereHas('lokasi', function ($query3) {
                        $query3->whereHas('distrik', function ($query4) {
                            $user_id = session('user_id');
                            $user = User::find($user_id);
                            $query4->where('id', $user->distrik_id);
                        });
                    })
                    // ->join('jenis', 'file_approval.jenis_id', '=', 'jenis.id')
                    ->groupBy('jenis_id', 'file_approval.id', 'lokasi_id', 'approval_id', 'tahun_anggaran', 'file_approval.updated_at', 'latest_approval_id')
                    ->orderBy('file_import_id', 'desc')
                    ->orderBy('file_approval.id', 'desc')
                    ->orderBy('file_approval.updated_at', 'desc')
                    ->get();

                $fileapproval_ketetapan_draft_selain_usulan_unit = FileApproval::select(
                    \DB::raw("DISTINCT ON (file_import_id) file_import_id"),
                    'file_approval.id',
                    'lokasi_id',
                    'latest_approval_id',
                    'approval_id',
                    'tahun_anggaran',
                    'jenis_id',
                    'file_approval.updated_at'
                )
                    ->whereHas('fileImport', function ($query) {
                        $query->where('status_upload_id', '3'); // berhasil upload
                    })
                    ->where($conditions)
                    ->where('latest_approval_id', 9)
                    ->where('file_approval_status_id', 4)
                    ->where('file_approval_parent_id', NULL)
                    ->where('approval_id', '!=', 1)
                    ->whereHas('lokasi', function ($query3) {
                        $query3->whereHas('distrik', function ($query4) {
                            $user_id = session('user_id');
                            $user = User::find($user_id);
                            $query4->where('id', $user->distrik_id);
                        });
                    })
                    // ->join('jenis', 'file_approval.jenis_id', '=', 'jenis.id')
                    ->groupBy('jenis_id', 'file_approval.id', 'lokasi_id', 'approval_id', 'tahun_anggaran', 'file_approval.updated_at', 'latest_approval_id')
                    ->orderBy('file_import_id', 'desc')
                    ->orderBy('file_approval.id', 'desc')
                    ->orderBy('file_approval.updated_at', 'desc')
                    ->get();
            }
            // dd($fileapproval);
        }

        $fileapproval_cek_status = FileApproval::selectRaw('count(file_approval_status_id)')
            ->whereHas('fileImport', function ($query) {
                $query->where('status_upload_id', '3'); // berhasil upload
            })
            ->whereHas('approval', function ($query) {
                $query->whereHas('role', function ($query2) {
                    $role_id = session('role_id');
                    $query2->where('role_id', $role_id);
                });
            })
            ->where($conditions)
            ->groupBy('file_approval_status_id')
            ->get()
            ->count();

        $data = ['fileapproval' => $fileapproval, 'fileapproval_cek_status' => $fileapproval_cek_status, 'fileapproval_ketetapan_draft_selain_usulan_unit' => $fileapproval_ketetapan_draft_selain_usulan_unit];
        $Sb = StrategiBisnis::all();

        return view('daftar_approval', $data, compact('tahun', 'tahun_anggaran', 'Sb', 'input_sb', 'distrik', 'input_distrik', 'lokasi', 'input_lokasi', 'jenis'));
    }

    public function Ajax($id)
    {
        $user_id = session('user_id');
        $user = User::find($user_id);
        $role_id = session('role_id');
        $role = Role::find($role_id);

        //kantor pusat
        if ($role->is_kantor_pusat) {
            $ds = Distrik::where('strategi_bisnis_id', $id)->select("name", "id")->get();
        } else {
            $ds = Distrik::where('id', $user->distrik_id)->select("name", "id")->get();
        }
        // $ds = Distrik::where('strategi_bisnis_id', $id)->select("name","id")->get();

        return json_encode($ds);
    }

    public function myformAjax2($id)
    {
        $lokasi = Lokasi::where('distrik_id', $id)->select("name", "id")->get();

        return json_encode($lokasi);
    }

    public function detail(Request $request, $tahun_anggaran, $lokasi_id, $jenis_id, $fase_id, $id)
    {
        if ($request->isMethod('get')) {
            // jika fase interchange approval diatur secara terpisah dengan alur normal
            if ($fase_id == 4) {
                return $this->showInterchangeApproval($request, $tahun_anggaran, $lokasi_id, $jenis_id, $fase_id, $id);
            } else {
                return $this->showNormalApproval($request, $tahun_anggaran, $lokasi_id, $jenis_id, $fase_id, $id);
            }
        } elseif ($request->isMethod('post')) {
            $this->validate($request, [
                // 'keterangan' => 'required',
            ]);

            $fase_id = Input::post('fase_id');
            $input = [
                'user_id' => session('user_id'),
                'role_id' => session('role_id'),
                'jenis_id' => Input::post('jenis_id'),
                'lokasi_id' => Input::post('lokasi_id'),
                'tahun_anggaran' => Input::post('tahun_anggaran'),
                'file_approval_selected_id' => Input::get('file_approval_selected_id'),
                'file_approval_status_id' => Input::get('file_approval_status_id'),

                'manager_unit_user_id' => Input::post('manager_unit_user_id'),
            ];

            // jika fase interchange approval diatur secara terpisah dengan alur normal
            if ($fase_id == 4) {
                $this->interchangeApproval($fase_id, $id, $input);
            } else {
                $this->normalApproval($fase_id, $id, $input);
            }

            $request->session()->flash('success', 'Data berhasil diubah');

            return redirect('assignment');
        }
    }

    // form normal approval
    public function showNormalApproval(Request $request, $tahun_anggaran, $lokasi_id, $jenis_id, $fase_id, $id)
    {
        //untuk ambil semua draft pada jenis yang sama
        $fileapproval_all = FileApproval::select(
            \DB::raw("DISTINCT ON (file_import_id) file_import_id"),
            'id',
            'lokasi_id',
            'latest_approval_id',
            'approval_id',
            'tahun_anggaran',
            'approval_by',
            'file_approval_status_id',
            'jenis_id',
            'created_by',
            'file_import_id',
            'file_approval_parent_id'
        )
            ->whereHas('fileImport', function ($query) {
                $query->where('status_upload_id', '3'); // berhasil upload
            })
            ->whereHas('approval', function ($query) use ($fase_id) {
                $query->where('fase_id', $fase_id);
                $query->whereHas('role', function ($query2) {
                    $role_id = session('role_id');
                    $query2->where('role_id', $role_id);
                });
            })
            ->where('tahun_anggaran', $tahun_anggaran)
            ->where('lokasi_id', $lokasi_id)
            ->where('jenis_id', $jenis_id)
            ->orderBy('file_import_id', 'desc')
            ->orderBy('id', 'desc')
            ->orderBy('updated_at', 'desc')
            ->get();

        //file approval selected (where id = $id)
        $fileapproval = $fileapproval_all->where('id', $id);
        $approval_id_skrg = $fileapproval[0]->approval_id;

        $role_id = session('role_id');
        $item['fileapproval'] = $fileapproval;
        $item['tahun_anggaran'] = $tahun_anggaran;
        $item['lokasi'] = Lokasi::find($lokasi_id);
        $item['fase'] = Fase::find($fase_id);
        $item['approval'] = Approval::all();
        $item['file_approval'] = FileApproval::all();
        $item['jenis_id'] = $jenis_id;
        $item['jenis'] = Jenis::find($jenis_id);
        $item['show_button'] = false; // default sembunyikan button

        //fase ketetapan dengan urutan terakhir
        // $approval_paling_akhir = Approval::where('fase_id', '3')->orderBy('urutan', 'desc')->first();

        if ($fase_id == '1' || $fase_id == '2' || $fase_id == '3') {
            // jika ada 2 draft 
            // 20200825 FFR menambahkan $fase_id=='3' karena pd fase ketetapan terdapat 2 draft yg berbeda. laporan ica.
            if (count($fileapproval_all) > 1) {
                $sudah_ada_yg_disetujui = 0;
                foreach ($fileapproval_all as $fa) {
                    if ($fa->latest_approval_id != NULL && ($fa->file_approval_status_id != 1 || $fa->file_approval_status_id != 2)) {
                        //jika sudah ada yang ganti approval id
                        if ($fa->latest_approval_id > $approval_id_skrg) {
                            $sudah_ada_yg_disetujui++;
                        }
                    }
                }
                if ($sudah_ada_yg_disetujui > 0) {
                    $item['show_button'] = false;
                } else {
                    // ketika awal2 posisi drafted/returned/queue maka munculkan
                    if (
                        $fileapproval->first()->file_approval_status_id == 1 ||
                        $fileapproval->first()->file_approval_status_id == 2 ||
                        $fileapproval->first()->file_approval_status_id == 5
                    ) {

                        if (
                            $fileapproval->first()->file_approval_status_id == 1 ||
                            $fileapproval->first()->file_approval_status_id == 5
                        ) {
                            $item['show_button'] = true;
                        } else {
                            $fase_skrg = Approval::where('id', $fileapproval->first()->latest_approval_id)->first()->fase_id;

                            $urutan_pertama_fase_skrg =
                                Approval::where('fase_id', $fase_skrg)->orderBy('urutan', 'asc')->limit('1')->first();

                            //jika role_id yg sedang login = role_id di urutan pertama fase skrg, maka true
                            if ($role_id == $urutan_pertama_fase_skrg->role_id) {
                                $item['show_button'] = true;
                            } else {
                                $item['show_button'] = false;
                            }
                        }
                    } else {
                        // # jika submited/approved maka hiden button
                        if (
                            $fileapproval->first()->file_approval_status_id == 3 ||
                            $fileapproval->first()->file_approval_status_id == 4
                        ) {
                            $item['show_button'] = false;

                            if ($fileapproval->first()->latest_approval_id == '') $la_id = 1;
                            else $la_id = $fileapproval->first()->latest_approval_id;

                            $fase = Approval::where('id', $la_id)->first()->fase_id;
                            $approval = Approval::where('role_id', $role_id)
                                ->where('fase_id', $fase)
                                ->first();

                            if ($approval) {
                                $approval_id_current_role = $approval->id;
                                // posisi sekarang = session grup yang sedang login maka true
                                if ($fileapproval->first()->latest_approval_id == $approval_id_current_role) {
                                    $item['show_button'] = true;
                                }
                            }

                            //urutan terakhir di approval
                            // $urutan_terakhir = Approval::where('fase_id', '3')->orderBy('urutan', 'desc')->limit('1')->first();

                            //jika urutan terakhir
                            // if($fileapproval->first()->latest_approval_id == $urutan_terakhir->id) {
                            //     $item['show_button'] = false;
                            // }
                        }
                    }
                }
            }
            //hanya ada 1 draft
            else {
                // ketika awal2 posisi drafterd/returned maka munculkan
                if (
                    $fileapproval->first()->file_approval_status_id == 1 ||
                    $fileapproval->first()->file_approval_status_id == 2 ||
                    $fileapproval->first()->file_approval_status_id == 5
                ) {

                    if (
                        $fileapproval->first()->file_approval_status_id == 1 ||
                        $fileapproval->first()->file_approval_status_id == 5
                    ) {
                        $item['show_button'] = true;
                    } else {
                        $fase_skrg = Approval::where('id', $fileapproval->first()->latest_approval_id)->first()->fase_id;

                        $urutan_pertama_fase_skrg =
                            Approval::where('fase_id', $fase_skrg)->orderBy('urutan', 'asc')->limit('1')->first();

                        //jika role_id yg sedang login = role_id di urutan pertama fase skrg, maka true
                        if ($role_id == $urutan_pertama_fase_skrg->role_id) {
                            $item['show_button'] = true;
                        } else {
                            $item['show_button'] = false;
                        }
                    }
                } else {
                    // # jika submited/approved maka hiden button
                    if (
                        $fileapproval->first()->file_approval_status_id == 3 ||
                        $fileapproval->first()->file_approval_status_id == 4
                    ) {
                        $item['show_button'] = false;

                        if ($fileapproval->first()->latest_approval_id == '') $la_id = 1;
                        else $la_id = $fileapproval->first()->latest_approval_id;

                        $fase = Approval::where('id', $la_id)->first()->fase_id;
                        $approval = Approval::where('role_id', $role_id)
                            ->where('fase_id', $fase)
                            ->first();

                        if ($approval) {
                            $approval_id_current_role = $approval->id;
                            // posisi sekarang = session grup yang sedang login maka true
                            if ($fileapproval->first()->latest_approval_id == $approval_id_current_role) {
                                $item['show_button'] = true;
                            }
                        }

                        //urutan terakhir di approval
                        // $urutan_terakhir = Approval::where('fase_id', '3')->orderBy('urutan', 'desc')->limit('1')->first();

                        //jika urutan terakhir
                        // if($fileapproval->first()->latest_approval_id == $urutan_terakhir->id) {
                        //     $item['show_button'] = false;
                        // }
                    }
                }
            }
        }
        //fase 3
        else {
            if (count($fileapproval_all) > 1) {
                $sudah_ada_yg_disetujui = 0;
                foreach ($fileapproval_all as $fa) {
                    if ($fa->latest_approval_id != NULL && ($fa->file_approval_status_id != 1 || $fa->file_approval_status_id != 2)) {
                        //jika sudah ada yang ganti approval id
                        if ($fa->latest_approval_id > $approval_id_skrg) {
                            $sudah_ada_yg_disetujui++;
                        }
                    }
                }

                if ($sudah_ada_yg_disetujui > 0) {
                    $item['show_button'] = false;
                } else {
                    // ketika awal2 posisi drafterd/returned maka munculkan
                    if (
                        $fileapproval->first()->file_approval_status_id == 1 ||
                        $fileapproval->first()->file_approval_status_id == 2 ||
                        $fileapproval->first()->file_approval_status_id == 5
                    ) {

                        if ($fileapproval->first()->file_approval_status_id == 1) {
                            $item['show_button'] = true;
                        } else {
                            $fase_skrg = Approval::where('id', $fileapproval->first()->latest_approval_id)->first()->fase_id;

                            $urutan_pertama_fase_skrg =
                                Approval::where('fase_id', $fase_skrg)->orderBy('urutan', 'asc')->limit('1')->first();

                            //jika role_id yg sedang login = role_id di urutan pertama fase skrg, maka true
                            if ($role_id == $urutan_pertama_fase_skrg->role_id) {
                                $item['show_button'] = true;
                            } else {
                                $item['show_button'] = false;
                            }
                        }
                    } else {
                        // # jika submited/approved maka hiden button
                        if (
                            $fileapproval->first()->file_approval_status_id == 3 ||
                            $fileapproval->first()->file_approval_status_id == 4
                        ) {
                            $item['show_button'] = false;

                            if ($fileapproval->first()->latest_approval_id == '') $la_id = 1;
                            else $la_id = $fileapproval->first()->latest_approval_id;

                            $fase = Approval::where('id', $la_id)->first()->fase_id;
                            $approval = Approval::where('role_id', $role_id)
                                ->where('fase_id', $fase)
                                ->first();

                            if ($approval) {
                                $approval_id_current_role = $approval->id;
                                // posisi sekarang = session grup yang sedang login maka true
                                if ($fileapproval->first()->latest_approval_id == $approval_id_current_role) {
                                    $item['show_button'] = true;
                                }
                            }

                            //urutan terakhir di approval
                            // $urutan_terakhir = Approval::where('fase_id', '3')->orderBy('urutan', 'desc')->limit('1')->first();

                            //jika urutan terakhir
                            // if($fileapproval->first()->latest_approval_id == $urutan_terakhir->id) {
                            //     $item['show_button'] = false;
                            // }

                        }
                    }
                }
            }
            //hanya ada 1 draft
            else {
                // ketika awal2 posisi drafterd/returned/queue maka munculkan
                if (
                    $fileapproval->first()->file_approval_status_id == 1 ||
                    $fileapproval->first()->file_approval_status_id == 2 ||
                    $fileapproval->first()->file_approval_status_id == 5
                ) {

                    if ($fileapproval->first()->file_approval_status_id == 1 || $fileapproval->first()->file_approval_status_id == 5) {
                        $item['show_button'] = true;
                    } else {
                        $fase_skrg = Approval::where('id', $fileapproval->first()->latest_approval_id)->first()->fase_id;

                        $urutan_pertama_fase_skrg =
                            Approval::where('fase_id', $fase_skrg)->orderBy('urutan', 'asc')->limit('1')->first();

                        //jika role_id yg sedang login = role_id di urutan pertama fase skrg, maka true
                        if ($role_id == $urutan_pertama_fase_skrg->role_id) {
                            $item['show_button'] = true;
                        } else {
                            $item['show_button'] = false;
                        }
                    }
                } else {
                    // # jika submited/approved maka hiden button
                    if (
                        $fileapproval->first()->file_approval_status_id == 3 ||
                        $fileapproval->first()->file_approval_status_id == 4
                    ) {
                        $item['show_button'] = false;

                        if ($fileapproval->first()->latest_approval_id == '') $la_id = 1;
                        else $la_id = $fileapproval->first()->latest_approval_id;

                        $fase = Approval::where('id', $la_id)->first()->fase_id;
                        $approval = Approval::where('role_id', $role_id)
                            ->where('fase_id', $fase)
                            ->first();

                        if ($approval) {
                            $approval_id_current_role = $approval->id;
                            // posisi sekarang = session grup yang sedang login maka true
                            if ($fileapproval->first()->latest_approval_id == $approval_id_current_role) {
                                $item['show_button'] = true;
                            }
                        }

                        //urutan terakhir di approval
                        $urutan_terakhir = Approval::where('fase_id', '3')->orderBy('urutan', 'desc')->limit('1')->first();

                        //jika urutan terakhir
                        if ($fileapproval->first()->latest_approval_id == $urutan_terakhir->id) {
                            $item['show_button'] = false;
                        }
                    }
                }
            }
        }

        // 2 => staff unit // 5 => Staff Anggaran
        if ($role_id == 2 || $role_id == 5) {
            $item['file_approval_status'] = FileApprovalStatus::where('id', '3')->get();
        }
        // 3 => manager
        else {
            $item['file_approval_status'] = FileApprovalStatus::whereIn('id', ['2', '4'])->get();
        }

        $role_id = session('role_id');
        $role = Role::where('id', $role_id)->first();
        $item['role_name'] = $role->name;

        // Kebutuhan khusus Staff Unit untuk submit ke Manager Unit yang dipilih
        $user_id = session('user_id');
        $user = User::find($user_id);
        $distrik_id = $user->distrik_id;
        $item['manager_unit'] = User::where('distrik_id', $distrik_id)
            ->whereHas('roles', function ($q) {
                $q->where('id', 3);
            })
            ->where('enabled', 1)
            ->get();

        $item['approval_terakhir'] = Approval::where('fase_id', '3')->orderBy('urutan', 'desc')->first();

        return view('detail_approval', $item);
    }

    // form interchange approval
    private function showInterchangeApproval(Request $request, $tahun_anggaran, $lokasi_id, $jenis_id, $fase_id, $id)
    {
        $role_id = session('role_id');

        //untuk ambil semua draft pada jenis yang sama
        $fileapproval_all = FileApproval::select(
            \DB::raw("DISTINCT ON (file_import_id) file_import_id"),
            'id',
            'lokasi_id',
            'latest_approval_id',
            'approval_id',
            'tahun_anggaran',
            'approval_by',
            'file_approval_status_id',
            'jenis_id',
            'created_by',
            'file_import_id',
            'file_approval_parent_id'
        )
            ->whereHas('fileImport', function ($query) {
                $query->where('status_upload_id', '3'); // berhasil upload
            })
            ->whereHas('approval', function ($query) use ($fase_id, $role_id) {
                $query->where('fase_id', $fase_id);
                $query->where('role_id', $role_id);
            })
            ->where('tahun_anggaran', $tahun_anggaran)
            ->where('lokasi_id', $lokasi_id)
            ->where('jenis_id', $jenis_id)
            ->orderBy('file_import_id', 'desc')
            ->orderBy('id', 'desc')
            ->orderBy('updated_at', 'desc')
            ->get();

        //file approval selected (where id = $id)
        $fileapproval = $fileapproval_all->where('id', $id);
        $fileapproval_first = $fileapproval_all->where('id', $id)->first();

        $item['fileapproval'] = $fileapproval;
        $item['tahun_anggaran'] = $tahun_anggaran;
        $item['lokasi'] = Lokasi::find($lokasi_id);
        $item['fase'] = Fase::find($fase_id);
        $item['approval'] = Approval::all();
        $item['file_approval'] = FileApproval::all();
        $item['jenis_id'] = $jenis_id;
        $item['jenis'] = Jenis::find($jenis_id);

        $item['show_button'] = false; // default sembunyikan button

        if (empty($fileapproval_first)) {
            abort(404);
        }

        // ---------------------------------------------------------------------
        // NOTE:
        // 1. Ada beberapa draft/versi: hanya 1 draft bisa disubmit, jika sudah approve manager, bisa submit draft lagi
        // 2. show button jika berstatus: draft, return, queue
        // ---------------------------------------------------------------------

        $approval_id_skrg = $fileapproval_first->approval_id;

        // cari draft yang yg masih dalam proses approval manager (latest_approval_id = 11)
        $inprogress_approval = null;
        foreach ($fileapproval_all as $fa) {
            // latest approval = manager & approval status = submited by
            if ($fa->latest_approval_id == 11 && $fa->file_approval_status_id == 3) {
                $inprogress_approval = $fa;
            }
        }

        if (empty($inprogress_approval)) {
            $approval_skrg = Approval::where('id', $approval_id_skrg)->first();
            $approval_role = $approval_skrg->role_id;

            // jika role aktif sesuai sequence approval saat ini
            if ($role_id == $approval_role) {
                // ketika awal2 posisi drafted/returned maka munculkan
                if (in_array($fileapproval_first->file_approval_status_id, [1, 2, 5])) {
                    $item['show_button'] = true;
                }
            }
        }

        // 2 => staff unit // 5 => Staff Anggaran
        if ($role_id == 2 || $role_id == 5) {
            $item['file_approval_status'] = FileApprovalStatus::where('id', '3')->get();
        }
        // 3 => manager
        else {
            $item['file_approval_status'] = FileApprovalStatus::whereIn('id', ['2', '4'])->get();
        }

        $role = Role::where('id', $role_id)->first();
        $item['role_name'] = $role->name;

        // Kebutuhan khusus Staff Unit untuk submit ke Manager Unit yang dipilih
        $user_id = session('user_id');
        $user = User::find($user_id);
        $distrik_id = $user->distrik_id;
        $item['manager_unit'] = User::where('distrik_id', $distrik_id)
            ->whereHas('roles', function ($q) {
                $q->where('id', 3);
            })
            ->where('enabled', 1)
            ->get();

        $item['approval_terakhir'] = Approval::where('fase_id', 4)->orderBy('urutan', 'desc')->first();

        return view('detail_approval', $item);
    }

    // approval untuk alur normal
    private function normalApproval($fase_id, $id, $input)
    {
        DB::beginTransaction();
        $user_id = $input['user_id'];
        $role_id = $input['role_id'];

        $jenis_id = $input['jenis_id'];
        $lokasi_id = $input['lokasi_id'];
        $tahun_anggaran = $input['tahun_anggaran'];

        //khusus Staff Unit
        if ($role_id == 2) {
            $manager_unit_user_id = $input['manager_unit_user_id'];
        }

        $file_approval_selected_id = $input['file_approval_selected_id'];
        $item = FileApproval::find($file_approval_selected_id);

        $file_approval_status_id = $input['file_approval_status_id'];
        $item->file_approval_status_id = $file_approval_status_id;
        $item->save();
        // returned
        if ($file_approval_status_id == '2') {

            $fase_id = $item->approval->fase_id;
            $returned_approval_id = Approval::where('fase_id', $fase_id)
                ->where('urutan', 1)
                ->first();

            $latest_approval_id = $returned_approval_id->id;
            $this->updateParent($item, $file_approval_status_id, $latest_approval_id);
        }
        // submited approved
        if ($file_approval_status_id == '3' || $file_approval_status_id == '4') {
            $approval_id = $item->approval_id;
            $new_item = new FileApproval;
            $new_item->tahun_anggaran = $item->tahun_anggaran;
            $new_item->distrik_id = $item->distrik_id;
            $new_item->lokasi_id = $item->lokasi_id;
            $new_item->approval_id = $item->approval_id;
            $new_item->file_import_id = $item->file_import_id;
            $new_item->file_approval_status_id = '5'; //queue
            //khusus Staff Unit
            if ($role_id == 2) {
                $new_item->manager_unit_user_id = $manager_unit_user_id;
            }

            $fase = Approval::find($item->approval_id);
            $next_approval_id = $item->approval_id;

            // cek apakah ada next sequence untuk fase ini

            $next_approval = Approval::where('fase_id', $fase->fase_id)
                ->where('urutan', '>', $fase->urutan)
                ->orderBy('urutan', 'asc')
                ->first();

            if ($next_approval) {
                # jika approval masih ada lagi next urutan
                $next_approval_id = $next_approval->id;
            } else {
                // jika tidak, maka go to next fase
                $next_fase_id = $fase->fase_id + 1;
                // CR Oktober
                // Dilakukan pengecekan apakah dia Umro atau tidak,
                // Karena kalau distrik Umro, tidak bisa + 1

                $approval = Approval::where('fase_id', $next_fase_id)
                    ->orderBy('urutan', 'asc')
                    ->first();
                if ($approval)
                    $next_approval_id = $approval->id;
            }
            $new_item->approval_id = $next_approval_id;
            $new_item->latest_approval_id = $next_approval_id;

            $new_item->approval_by = $role_id;
            $new_item->created_by = $item->created_by; //konseptor
            $new_item->file_approval_parent_id = $file_approval_selected_id;
            $new_item->jenis_id = $item->jenis_id;

            // khusus fase 3
            if ($fase->fase_id == '3') {
                // cek apakah ada next sequence untuk fase ini (untuk pengecekan fase ketetapan paling akhir)
                if ($next_approval) {
                    if ($new_item->save()) {
                        $this->updateParent($new_item, $file_approval_status_id, $next_approval_id);
                    }
                } else {

                    // Mengambil id file_imports melalui file_import_id di file_approval
                    $file_import_fk = FileApproval::where('id', $id)->get();
                    foreach ($file_import_fk as $fifk) {
                        $data_fifk = $fifk->file_import_id;
                    }

                    // Mengambil data file_imports
                    $file_import_ketetapan = FileImport::where('id', $data_fifk)->get();
                    foreach ($file_import_ketetapan as $fik) {
                        $data_fik = $fik;
                    }
                    $run = Artisan::call('duplicate:data', [
                        'data_fifk' => $data_fifk
                    ]);

                    //fase ketetapan dengan urutan terakhir
                    $approval_paling_akhir = Approval::where('fase_id', '3')->orderBy('urutan', 'desc')->first();
                    $latest_approval_id = $approval_paling_akhir->id;

                    $this->updateParent($item, $file_approval_status_id, $latest_approval_id);
                }
            } else {
                if ($new_item->save()) {
                    $this->updateParent($new_item, $file_approval_status_id, $next_approval_id);
                }
            }
        }

        DB::commit();
    }

    // approval untuk alur interchange
    private function interchangeApproval($fase_id, $id, $input)
    {
        DB::beginTransaction();
        $role_id = $input['role_id'];

        $jenis_id = $input['jenis_id'];
        $lokasi_id = $input['lokasi_id'];
        $tahun_anggaran = $input['tahun_anggaran'];

        $file_approval_selected_id = $input['file_approval_selected_id'];
        $item = FileApproval::find($file_approval_selected_id);

        $file_approval_status_id = $input['file_approval_status_id'];
        $item->file_approval_status_id = $file_approval_status_id;
        $item->save();
        // returned
        if ($file_approval_status_id == '2') {

            $fase_id = $item->approval->fase_id;
            $returned_approval_id = Approval::where('fase_id', $fase_id)
                ->where('urutan', 1)
                ->first();

            $latest_approval_id = $returned_approval_id->id;
            $this->updateParent($item, $file_approval_status_id, $latest_approval_id);
        }
        // submited, approved
        if ($file_approval_status_id == '3' || $file_approval_status_id == '4') {
            $fase = Approval::find($item->approval_id);

            // cek apakah ada next sequence untuk fase ini
            // untuk alur interchange nilai fase = 4
            $next_approval = Approval::where('fase_id', 4)
                ->where('urutan', '>', $fase->urutan)
                ->orderBy('urutan', 'asc')
                ->first();

            if ($next_approval) {
                //// approval berikutnya

                $new_item = new FileApproval;
                $new_item->tahun_anggaran = $item->tahun_anggaran;
                $new_item->distrik_id = $item->distrik_id;
                $new_item->lokasi_id = $item->lokasi_id;
                $new_item->approval_id = $item->approval_id;
                $new_item->file_import_id = $item->file_import_id;
                $new_item->file_approval_status_id = '5'; //queue

                $next_approval_id = $next_approval->id;
                $next_fase_id = $next_approval->fase_id;

                $new_item->approval_id = $next_approval_id;
                $new_item->latest_approval_id = $next_approval_id;

                $new_item->approval_by = $role_id;
                $new_item->created_by = $item->created_by; //konseptor
                $new_item->file_approval_parent_id = $file_approval_selected_id;
                $new_item->jenis_id = $item->jenis_id;

                if ($new_item->save()) {
                    $this->updateParent($new_item, $file_approval_status_id, $next_approval_id);
                }
            } else {
                //// approval terakhir pada alur

                // Mengambil id file_imports melalui file_import_id di file_approval
                $file_import_fk = FileApproval::where('id', $id)->get();
                foreach ($file_import_fk as $fifk) {
                    $data_fifk = $fifk->file_import_id;
                }

                // Mengambil data file_imports
                $file_import_ketetapan = FileImport::where('id', $data_fifk)->get();
                foreach ($file_import_ketetapan as $fik) {
                    $data_fik = $fik;
                }
                $run = Artisan::call('duplicate:data', [
                    'data_fifk' => $data_fifk
                ]);

                //fase ketetapan dengan urutan terakhir
                $approval_paling_akhir = Approval::where('fase_id', '4')->orderBy('urutan', 'desc')->first();
                $latest_approval_id = $approval_paling_akhir->id;

                $this->updateParent($item, $file_approval_status_id, $latest_approval_id);
            }
        }

        DB::commit();
    }

    public function detail_ketetapan_selain_usulan_unit(Request $request, $tahun_anggaran, $lokasi_id, $jenis_id, $fase_id, $id)
    {
        if ($request->isMethod('get')) {
            //untuk ambil semua draft pada jenis yang sama
            $fileapproval_all = FileApproval::select(
                \DB::raw("DISTINCT ON (file_import_id) file_import_id"),
                'id',
                'lokasi_id',
                'latest_approval_id',
                'approval_id',
                'tahun_anggaran',
                'approval_by',
                'file_approval_status_id',
                'jenis_id',
                'created_by',
                'file_import_id',
                'file_approval_parent_id'
            )
                ->whereHas('fileImport', function ($query) {
                    $query->where('status_upload_id', '3'); // berhasil upload
                })
                ->whereHas('approval', function ($query) use ($fase_id) {
                    $query->where('fase_id', $fase_id);
                    $query->whereHas('role', function ($query2) {
                        $role_id = session('role_id');
                        $query2->where('role_id', $role_id);
                    });
                })
                ->where('tahun_anggaran', $tahun_anggaran)
                ->where('lokasi_id', $lokasi_id)
                ->where('jenis_id', $jenis_id)
                ->orderBy('file_import_id', 'desc')
                ->orderBy('id', 'desc')
                ->orderBy('updated_at', 'desc')
                ->get();

            //file approval selected (where id = $id)
            $fileapproval = FileApproval::select(
                \DB::raw("DISTINCT ON (file_import_id) file_import_id"),
                'id',
                'lokasi_id',
                'latest_approval_id',
                'approval_id',
                'tahun_anggaran',
                'approval_by',
                'file_approval_status_id',
                'jenis_id',
                'created_by',
                'file_import_id',
                'file_approval_parent_id'
            )
                ->whereHas('fileImport', function ($query) {
                    $query->where('status_upload_id', '3'); // berhasil upload
                })
                ->where('tahun_anggaran', $tahun_anggaran)
                ->where('lokasi_id', $lokasi_id)
                ->where('jenis_id', $jenis_id)
                ->where('id', $id)
                ->orderBy('file_import_id', 'desc')
                ->orderBy('id', 'desc')
                ->orderBy('updated_at', 'desc')
                ->get();

            $approval_id_skrg = $fileapproval[0]->approval_id;

            $role_id = session('role_id');
            $item['fileapproval'] = $fileapproval;
            $item['tahun_anggaran'] = $tahun_anggaran;
            $item['lokasi'] = Lokasi::find($lokasi_id);
            $item['fase'] = Fase::find($fase_id);
            $item['approval'] = Approval::all();
            $item['file_approval'] = FileApproval::all();
            $item['jenis_id'] = $jenis_id;
            $item['jenis'] = Jenis::find($jenis_id);

            // dd($fileapproval, $fileapproval->first());

            //fase ketetapan dengan urutan terakhir
            $approval_paling_akhir = Approval::where('fase_id', '3')->orderBy('urutan', 'desc')->first();

            // dd($fileapproval_all);
            if ($fase_id == '1' || $fase_id == '2') {
                // jika ada 2 draft
                if (count($fileapproval_all) > 1) {
                    $sudah_ada_yg_disetujui = 0;
                    foreach ($fileapproval_all as $fa) {
                        if ($fa->latest_approval_id != NULL && ($fa->file_approval_status_id != 1 || $fa->file_approval_status_id != 2)) {
                            //jika sudah ada yang ganti approval id
                            if ($fa->latest_approval_id > $approval_id_skrg) {
                                $sudah_ada_yg_disetujui++;
                            }
                        }
                    }
                    if ($sudah_ada_yg_disetujui > 0) {
                        $item['show_button'] = false;
                    } else {
                        // ketika awal2 posisi drafted/returned/queue maka munculkan
                        if (
                            $fileapproval->first()->file_approval_status_id == 1 ||
                            $fileapproval->first()->file_approval_status_id == 2 ||
                            $fileapproval->first()->file_approval_status_id == 5
                        ) {

                            if ($fileapproval->first()->file_approval_status_id == 1) {
                                $item['show_button'] = true;
                            } else {
                                // dd('if 1');
                                $fase_skrg = Approval::where('id', $fileapproval->first()->latest_approval_id)->first()->fase_id;

                                $urutan_pertama_fase_skrg =
                                    Approval::where('fase_id', $fase_skrg)->orderBy('urutan', 'asc')->limit('1')->first();

                                // dd($urutan_pertama_fase_skrg);

                                //jika role_id yg sedang login = role_id di urutan pertama fase skrg, maka true
                                // dd($urutan_pertama_fase_skrg->role_id, $role_id);
                                if ($role_id == $urutan_pertama_fase_skrg->role_id) {
                                    // dd('if 1');
                                    $item['show_button'] = true;
                                } else {
                                    // dd('else 1');
                                    $item['show_button'] = false;
                                }
                            }
                        } else {
                            // # jika submited/approved maka hiden button
                            if (
                                $fileapproval->first()->file_approval_status_id == 3 ||
                                $fileapproval->first()->file_approval_status_id == 4
                            ) {
                                $item['show_button'] = false;
                                // }

                                // kalau kondisi sekarang sudah diaprove tapi bukan dia, maka on kan
                                // if ($fileapproval->first()->approval_by <= $role_id ) {
                                // dd($fileapproval->first()->latest_approval_id, $role_id);


                                if ($fileapproval->first()->latest_approval_id == '') $la_id = 1;
                                else $la_id = $fileapproval->first()->latest_approval_id;

                                $fase = Approval::where('id', $la_id)->first()->fase_id;
                                $approval = Approval::where('role_id', $role_id)
                                    ->where('fase_id', $fase)
                                    ->first();

                                if ($approval) {
                                    $approval_id_current_role = $approval->id;
                                    // posisi sekarang = session grup yang sedang login maka true
                                    if ($fileapproval->first()->latest_approval_id == $approval_id_current_role) {
                                        $item['show_button'] = true;
                                    }
                                }

                                //urutan terakhir di approval
                                $urutan_terakhir = Approval::where('fase_id', '3')->orderBy('urutan', 'desc')->limit('1')->first();

                                //jika urutan terakhir
                                if ($fileapproval->first()->latest_approval_id == $urutan_terakhir->id) {
                                    // $item['show_button'] = false;
                                }
                                // dd($fileapproval->first()->latest_approval_id, $approval_id_current_role);
                                // if ($fileapproval->first()->latest_approval_id <= $role_id ) {
                                // if ($fileapproval->first()->latest_approval_id <= $approval_id_current_role ) {
                                //         $item['show_button'] = true;

                                // }
                                // if ($fileapproval->first()->approval_by != 2 && $role_id == 2 ) {
                                //   // khusus staff unit
                                //       // jika status submited tapi approvalnya bukan staff unit, ada indikasi dia kereturn
                                //       $item['show_button'] = true;
                                //       echo "true ";

                                //   }

                            }
                        }
                    }
                }
                //hanya ada 1 draft
                else {
                    // ketika awal2 posisi drafterd/returned maka munculkan
                    if (
                        $fileapproval->first()->file_approval_status_id == 1 ||
                        $fileapproval->first()->file_approval_status_id == 2 ||
                        $fileapproval->first()->file_approval_status_id == 5
                    ) {

                        if (
                            $fileapproval->first()->file_approval_status_id == 1 ||
                            $fileapproval->first()->file_approval_status_id == 5
                        ) {
                            $item['show_button'] = true;
                        } else {
                            // dd('else 1');
                            $fase_skrg = Approval::where('id', $fileapproval->first()->latest_approval_id)->first()->fase_id;

                            $urutan_pertama_fase_skrg =
                                Approval::where('fase_id', $fase_skrg)->orderBy('urutan', 'asc')->limit('1')->first();

                            // dd($urutan_pertama_fase_skrg);

                            //jika role_id yg sedang login = role_id di urutan pertama fase skrg, maka true
                            // dd($urutan_pertama_fase_skrg->role_id, $role_id);
                            if ($role_id == $urutan_pertama_fase_skrg->role_id) {
                                // dd('if 1');
                                $item['show_button'] = true;
                            } else {
                                // dd('else 1');
                                $item['show_button'] = false;
                            }
                        }
                    } else {
                        // # jika submited/approved maka hiden button
                        if (
                            $fileapproval->first()->file_approval_status_id == 3 ||
                            $fileapproval->first()->file_approval_status_id == 4
                        ) {
                            $item['show_button'] = false;
                            // }

                            // kalau kondisi sekarang sudah diaprove tapi bukan dia, maka on kan
                            // if ($fileapproval->first()->approval_by <= $role_id ) {
                            // dd($fileapproval->first()->latest_approval_id, $role_id);


                            if ($fileapproval->first()->latest_approval_id == '') $la_id = 1;
                            else $la_id = $fileapproval->first()->latest_approval_id;

                            $fase = Approval::where('id', $la_id)->first()->fase_id;
                            $approval = Approval::where('role_id', $role_id)
                                ->where('fase_id', $fase)
                                ->first();

                            if ($approval) {
                                $approval_id_current_role = $approval->id;
                                // posisi sekarang = session grup yang sedang login maka true
                                if ($fileapproval->first()->latest_approval_id == $approval_id_current_role) {
                                    $item['show_button'] = true;
                                }
                            }

                            //urutan terakhir di approval
                            $urutan_terakhir = Approval::where('fase_id', '3')->orderBy('urutan', 'desc')->limit('1')->first();

                            //jika urutan terakhir
                            if ($fileapproval->first()->latest_approval_id == $urutan_terakhir->id) {
                                // $item['show_button'] = false;
                            }
                            // dd($fileapproval->first()->latest_approval_id, $approval_id_current_role);
                            // if ($fileapproval->first()->latest_approval_id <= $role_id ) {
                            // if ($fileapproval->first()->latest_approval_id <= $approval_id_current_role ) {
                            //         $item['show_button'] = true;

                            // }
                            // if ($fileapproval->first()->approval_by != 2 && $role_id == 2 ) {
                            //   // khusus staff unit
                            //       // jika status submited tapi approvalnya bukan staff unit, ada indikasi dia kereturn
                            //       $item['show_button'] = true;
                            //       echo "true ";

                            //   }

                        }
                    }
                }
            }
            //fase 3
            else {
                if (count($fileapproval_all) > 1) {
                    $sudah_ada_yg_disetujui = 0;
                    foreach ($fileapproval_all as $fa) {
                        if ($fa->latest_approval_id != NULL && ($fa->file_approval_status_id != 1 || $fa->file_approval_status_id != 2)) {
                            //jika sudah ada yang ganti approval id
                            if ($fa->latest_approval_id > $approval_id_skrg) {
                                $sudah_ada_yg_disetujui++;
                            }
                        }
                    }

                    if ($sudah_ada_yg_disetujui > 0) {
                        $item['show_button'] = false;
                    } else {
                        // ketika awal2 posisi drafterd/returned maka munculkan
                        if (
                            $fileapproval->first()->file_approval_status_id == 1 ||
                            $fileapproval->first()->file_approval_status_id == 2 ||
                            $fileapproval->first()->file_approval_status_id == 5
                        ) {

                            if ($fileapproval->first()->file_approval_status_id == 1) {
                                $item['show_button'] = true;
                            } else {
                                // dd('if 1');
                                $fase_skrg = Approval::where('id', $fileapproval->first()->latest_approval_id)->first()->fase_id;

                                $urutan_pertama_fase_skrg =
                                    Approval::where('fase_id', $fase_skrg)->orderBy('urutan', 'asc')->limit('1')->first();

                                // dd($urutan_pertama_fase_skrg);

                                //jika role_id yg sedang login = role_id di urutan pertama fase skrg, maka true
                                // dd($urutan_pertama_fase_skrg->role_id, $role_id);
                                if ($role_id == $urutan_pertama_fase_skrg->role_id) {
                                    // dd('if 1');
                                    $item['show_button'] = true;
                                } else {
                                    // dd('else 1');
                                    $item['show_button'] = false;
                                }
                            }
                        } else {
                            // # jika submited/approved maka hiden button
                            if (
                                $fileapproval->first()->file_approval_status_id == 3 ||
                                $fileapproval->first()->file_approval_status_id == 4
                            ) {
                                $item['show_button'] = false;
                                // }

                                // kalau kondisi sekarang sudah diaprove tapi bukan dia, maka on kan
                                // if ($fileapproval->first()->approval_by <= $role_id ) {
                                // dd($fileapproval->first()->latest_approval_id, $role_id);


                                if ($fileapproval->first()->latest_approval_id == '') $la_id = 1;
                                else $la_id = $fileapproval->first()->latest_approval_id;

                                $fase = Approval::where('id', $la_id)->first()->fase_id;
                                $approval = Approval::where('role_id', $role_id)
                                    ->where('fase_id', $fase)
                                    ->first();

                                if ($approval) {
                                    $approval_id_current_role = $approval->id;
                                    // posisi sekarang = session grup yang sedang login maka true
                                    if ($fileapproval->first()->latest_approval_id == $approval_id_current_role) {
                                        $item['show_button'] = true;
                                    }
                                }

                                //urutan terakhir di approval
                                $urutan_terakhir = Approval::where('fase_id', '3')->orderBy('urutan', 'desc')->limit('1')->first();

                                //jika urutan terakhir
                                if ($fileapproval->first()->latest_approval_id == $urutan_terakhir->id) {
                                    // $item['show_button'] = false;
                                }
                                // dd($fileapproval->first()->latest_approval_id, $approval_id_current_role);
                                // if ($fileapproval->first()->latest_approval_id <= $role_id ) {
                                // if ($fileapproval->first()->latest_approval_id <= $approval_id_current_role ) {
                                //         $item['show_button'] = true;

                                // }
                                // if ($fileapproval->first()->approval_by != 2 && $role_id == 2 ) {
                                //   // khusus staff unit
                                //       // jika status submited tapi approvalnya bukan staff unit, ada indikasi dia kereturn
                                //       $item['show_button'] = true;
                                //       echo "true ";

                                //   }

                            }
                        }
                    }
                }
                //hanya ada 1 draft
                else {
                    // ketika awal2 posisi drafterd/returned/queue maka munculkan
                    if (
                        $fileapproval->first()->file_approval_status_id == 1 ||
                        $fileapproval->first()->file_approval_status_id == 2 ||
                        $fileapproval->first()->file_approval_status_id == 5
                    ) {

                        if ($fileapproval->first()->file_approval_status_id == 1 || $fileapproval->first()->file_approval_status_id == 5) {
                            $item['show_button'] = true;
                        } else {
                            // dd('if 1');
                            $fase_skrg = Approval::where('id', $fileapproval->first()->latest_approval_id)->first()->fase_id;

                            $urutan_pertama_fase_skrg =
                                Approval::where('fase_id', $fase_skrg)->orderBy('urutan', 'asc')->limit('1')->first();

                            // dd($urutan_pertama_fase_skrg);

                            //jika role_id yg sedang login = role_id di urutan pertama fase skrg, maka true
                            // dd($urutan_pertama_fase_skrg->role_id, $role_id);
                            if ($role_id == $urutan_pertama_fase_skrg->role_id) {
                                // dd('if 1');
                                $item['show_button'] = true;
                            } else {
                                // dd('else 1');
                                $item['show_button'] = false;
                            }
                        }
                    } else {
                        // # jika submited/approved maka hiden button
                        if (
                            $fileapproval->first()->file_approval_status_id == 3 ||
                            $fileapproval->first()->file_approval_status_id == 4
                        ) {
                            $item['show_button'] = false;
                            // }

                            // kalau kondisi sekarang sudah diaprove tapi bukan dia, maka on kan
                            // if ($fileapproval->first()->approval_by <= $role_id ) {
                            // dd($fileapproval->first()->latest_approval_id, $role_id);


                            if ($fileapproval->first()->latest_approval_id == '') $la_id = 1;
                            else $la_id = $fileapproval->first()->latest_approval_id;

                            $fase = Approval::where('id', $la_id)->first()->fase_id;
                            $approval = Approval::where('role_id', $role_id)
                                ->where('fase_id', $fase)
                                ->first();

                            if ($approval) {
                                $approval_id_current_role = $approval->id;
                                // posisi sekarang = session grup yang sedang login maka true
                                if ($fileapproval->first()->latest_approval_id == $approval_id_current_role) {
                                    $item['show_button'] = true;
                                }
                            }

                            //urutan terakhir di approval
                            $urutan_terakhir = Approval::where('fase_id', '3')->orderBy('urutan', 'desc')->limit('1')->first();

                            //jika urutan terakhir
                            if ($fileapproval->first()->latest_approval_id == $urutan_terakhir->id) {
                                $item['show_button'] = false;
                            }
                            // dd($fileapproval->first()->latest_approval_id, $approval_id_current_role);
                            // if ($fileapproval->first()->latest_approval_id <= $role_id ) {
                            // if ($fileapproval->first()->latest_approval_id <= $approval_id_current_role ) {
                            //         $item['show_button'] = true;

                            // }
                            // if ($fileapproval->first()->approval_by != 2 && $role_id == 2 ) {
                            //   // khusus staff unit
                            //       // jika status submited tapi approvalnya bukan staff unit, ada indikasi dia kereturn
                            //       $item['show_button'] = true;
                            //       echo "true ";

                            //   }

                        }
                    }
                }
            }

            //dd("status");
            // 2 => staff unit // 5 => Staff Anggaran
            if ($role_id == 2 || $role_id == 5) {
                $item['file_approval_status'] = FileApprovalStatus::where('id', '3')->get();
            }
            // 3 => manager
            else {
                $item['file_approval_status'] = FileApprovalStatus::whereIn('id', ['2', '4'])->get();
            }

            $role_id = session('role_id');
            $role = Role::where('id', $role_id)->first();
            $item['role_name'] = $role->name;

            // Kebutuhan khusus Staff Unit untuk submit ke Manager Unit yang dipilih
            // $distrik_id = session('distrik_id');
            $user_id = session('user_id');
            $user = User::find($user_id);
            $distrik_id = $user->distrik_id;
            $item['manager_unit'] = User::where('distrik_id', $distrik_id)
                ->whereHas('roles', function ($q) {
                    $q->where('id', 3);
                })
                ->get();

            return view('detail_approval', $item);
        }
    }

    public function duplicateData($data_fifk, $data_fik)
    {
        // dd('view');
        // Awal Proses Duplicate Data

        // Insert data file_import ke tabel file_imports_ketetapan
        DB::table('file_imports_ketetapan')->insert(
            [
                'file_import_id' => $data_fik->id,
                'template_id' => $data_fik->template_id,
                'version_id' => $data_fik->version_id,
                'fase_id' => $data_fik->fase_id,
                'tahun' => $data_fik->tahun,
                'file' => $data_fik->file,
                'status_upload_id' => $data_fik->status_upload_id,
                'error' => $data_fik->error,
                'draft_versi' => $data_fik->draft_versi,
                'form6_rutin_file_import_id' => $data_fik->form6_rutin_file_import_id,
                'form6_reimburse_file_import_id' => $data_fik->form6_reimburse_file_import_id,
                'form10_pln_file_import_id' => $data_fik->form10_pln_file_import_id,
                'form10_pu_file_import_id' => $data_fik->form10_pu_file_import_id,
                'form10_penguatankit_file_import_id' => $data_fik->form10_penguatankit_file_import_id,
                'form_bahan_bakar_file_import_id' => $data_fik->form_bahan_bakar_file_import_id,
                'form_penyusutan_file_import_id' => $data_fik->form_penyusutan_file_import_id,
                'created_at' => $data_fik->created_at,
                'updated_at' => $data_fik->updated_at,
                'distrik_id' => $data_fik->distrik_id,
                'lokasi_id' => $data_fik->lokasi_id,
                'name' => $data_fik->name,
                'created_by' => $data_fik->created_by,
                'updated_by' => $data_fik->updated_by,
                'uploaded_by' => $data_fik->uploaded_by,
            ]
        );

        // Mengambil data file_import_ketetapan untuk di insert ke tabel pgdl_file_imports_revisi
        $file_import_ketetapan = FileImportKetetapan::where('file_import_id', $data_fifk)->get();
        foreach ($file_import_ketetapan as $fiki) {
            $data_fiki = $fiki;
            // dump($data_fiki);
        }
        // die();

        // Insert data ke tabel pgdl_file_imports_revisi (copy dari file_import_keteatapan)
        DB::table('pgdl_file_imports_revisi')->insert(
            [
                'file_import_ketetapan_id' => $data_fiki->id,
                'template_id' => $data_fiki->template_id,
                'version_id' => $data_fiki->version_id,
                'fase_id' => $data_fiki->fase_id,
                'tahun' => $data_fiki->tahun,
                'file' => $data_fiki->file,
                'status_upload_id' => $data_fiki->status_upload_id,
                'error' => $data_fiki->error,
                'draft_versi' => $data_fiki->draft_versi,
                'form6_rutin_file_import_id' => $data_fiki->form6_rutin_file_import_id,
                'form6_reimburse_file_import_id' => $data_fiki->form6_reimburse_file_import_id,
                'form10_pln_file_import_id' => $data_fiki->form10_pln_file_import_id,
                'form10_pu_file_import_id' => $data_fiki->form10_pu_file_import_id,
                'form10_penguatankit_file_import_id' => $data_fiki->form10_penguatankit_file_import_id,
                'form_bahan_bakar_file_import_id' => $data_fiki->form_bahan_bakar_file_import_id,
                'form_penyusutan_file_import_id' => $data_fiki->form_penyusutan_file_import_id,
                'created_at' => $data_fiki->created_at,
                'updated_at' => $data_fiki->updated_at,
                'distrik_id' => $data_fiki->distrik_id,
                'lokasi_id' => $data_fiki->lokasi_id,
                'name' => $data_fiki->name,
                'created_by' => $data_fiki->created_by,
                'updated_by' => $data_fiki->updated_by,
                'uploaded_by' => $data_fiki->uploaded_by,
            ]
        );

        // Mengambil data dari tabel excel_datas dan di insert ke tabel excel_datas_ketetapan, di tambah id dari file_import_ketetapan
        $excel_data_ketetapan = ExcelData::where('file_import_id', $data_fifk)->get();
        foreach ($excel_data_ketetapan as $edk) {
            $data_edk = $edk;
            // dump($data_edk);
            DB::table('excel_datas_ketetapan')->insert(
                [
                    'file_import_ketetapan_id' => $data_fiki->id,
                    'sheet_id' => $data_edk->sheet_id,
                    'lokasi_id' => $data_edk->lokasi_id,
                    'kolom' => $data_edk->kolom,
                    'row' => $data_edk->row,
                    'value' => $data_edk->value,
                    'created_by' => $data_edk->created_by,
                    'updated_by' => $data_edk->updated_by,
                    'created_at' => $data_edk->created_at,
                    'updated_at' => $data_edk->updated_at,
                ]
            );
        }

        $pgdl_file_imports_revisi_id = PGDLFileImportRevisi::where('file_import_ketetapan_id', $data_fiki->id)->get();
        foreach ($pgdl_file_imports_revisi_id as $pdiri) {
            $data_pdiri = $pdiri;
            // dump ($data_pdiri->id);
        }
        // die();
        // dd($pgdl_file_imports_revisi_id);

        $excel_data_ketetapan = ExcelData::where('file_import_id', $data_fifk)->get();
        foreach ($excel_data_ketetapan as $edk) {
            $data_edk = $edk;
            // dump($data_edk);
            DB::table('pgdl_excel_datas_revisi')->insert(
                [
                    'pgdl_file_import_revisi_id' => $data_pdiri->id,
                    'sheet_id' => $data_edk->sheet_id,
                    'lokasi_id' => $data_edk->lokasi_id,
                    'kolom' => $data_edk->kolom,
                    'row' => $data_edk->row,
                    'value' => $data_edk->value,
                    'created_by' => $data_edk->created_by,
                    'updated_by' => $data_edk->updated_by,
                    'created_at' => $data_edk->created_at,
                    'updated_at' => $data_edk->updated_at,
                ]
            );
        }
        // die();

        // End Proccess

    }

    public function updateParent($item, $file_approval_status_id, $next_approval_id)
    {
        $ada_parent = $item->file_approval_parent_id;
        if ($ada_parent) {
            # jika returned cek apakah masih satu fase
            $parent = FileApproval::find($item->file_approval_parent_id);

            if ($file_approval_status_id == 2) {
                // jika parent punya fase yang sama dengan item, maka update status parent
                // if ( $parent->approval->fase_id  == $item->approval->fase_id ){

                // echo "samafase ".$next_approval_id;
                $parent->file_approval_status_id = $file_approval_status_id;
                $parent->latest_approval_id = $next_approval_id;
                $parent->save();
                $this->updateParent($parent, $file_approval_status_id, $next_approval_id);
                // }
            } else {
                # updatea parent sampai ke atas...
                $parent->file_approval_status_id = $file_approval_status_id;
                $parent->latest_approval_id = $next_approval_id;
                $parent->save();
                $this->updateParent($parent, $file_approval_status_id, $next_approval_id);
            }
        }
        $role_id = session('role_id');
        $item->approval_by = $role_id;

        $item->latest_approval_id = $next_approval_id;
        $item->save();
        // dd("save ".$next_approval_id);
    }

    public function send_email($email, $tahun_anggaran, $lokasi_id, $jenis_id, $fase_id, $draft_name)
    {
        $data['email'] = $email;
        $data['tahun_anggaran'] = $tahun_anggaran;
        $data['lokasi'] = Lokasi::find($lokasi_id);
        $data['fase'] = Fase::find($fase_id);
        $data['jenis'] = Jenis::find($jenis_id);
        $data['draft_name'] = $draft_name;

        Mail::send('approval.send', ['jenis' => $data['jenis'], 'tahun_anggaran' => $tahun_anggaran, 'lokasi' => $data['lokasi'], 'fase' => $data['fase'], 'draft_name' => $data['draft_name']], function ($message) use ($data) {
            $message->from('iplan.no-replay@ptpjb.com', 'IPLAN');
            $message->subject('Pemberitahuan Approval Baru');
            $message->to($data['email']);
        });

        return response()->json(['message' => 'Request completed']);
    }

    public function send_email_beda_fase($email, $tahun_anggaran, $lokasi_id, $fase_id, $detail_file_approved_on_selected_fase)
    {
        $data['email'] = $email;
        $data['tahun_anggaran'] = $tahun_anggaran;
        $data['detail_file_approved_on_selected_fase'] = $detail_file_approved_on_selected_fase;
        $data['lokasi'] = Lokasi::find($lokasi_id);
        $data['fase'] = Fase::find($fase_id);

        Mail::send('approval.send_beda_fase', ['tahun_anggaran' => $tahun_anggaran, 'lokasi' => $data['lokasi'], 'fase' => $data['fase'], 'detail_file_approved_on_selected_fase' => $data['detail_file_approved_on_selected_fase']], function ($message) use ($data) {
            $message->from('iplan.no-replay@ptpjb.com', 'IPLAN');
            $message->subject('Pemberitahuan Approval Baru');
            $message->to($data['email']);
        });

        return response()->json(['message' => 'Request completed']);
    }
}
