<?php

namespace App\Http\Controllers;

use App\Entities\Jenis;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use View;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct() {
        $jenis = Jenis::all();
        $jenis_pengendalian = Jenis::where('name', '!=', 'Risk Profile')->get();

        View::share('nav_jenis', $jenis);
        View::share('nav_jenis_pengendalian', $jenis_pengendalian);
    }

    public static function numberFormat($number,$decimal = 2)
    {
        return number_format($number,$decimal,",",".");
    }
}
