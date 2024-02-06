<?php namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class FileApprovalStatus extends Model
{
    protected $table = "file_approval_status";

    protected $fillable = ['id', 'name'];
}
