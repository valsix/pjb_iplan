<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FileApproval extends Model
{
    protected $table = "file_approval";

    protected $fillable = ['id', 'tahun_anggaran', 'lokasi_id', 'approval_id', 'file_import_id', 'file_approval_status', 'keterangan', 'approval_by', 'approval_at', 'jenis_id'];

}
