<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Fase extends Model
{
    protected $table = "fases";

    protected $fillable = ['id', 'name'];
}
