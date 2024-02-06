<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    protected $table = "approval";

    protected $fillable = ['id', 'fase_id', 'group_id', 'urutan', 'enabled'];
}
