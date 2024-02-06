@extends('layouts.app')

@section('css_page')
    <style type="text/css">
        .table-container
        {
            width: 100%;
            overflow-x: auto;
            overflow: auto;
            margin: 0 0 1em;
        }
        thead th{
            text-align: center;
        }

        /Update line height & font-size/
        .table thead tr th{
          line-height: 1;
        }
        .table tbody tr td{
          /Untuk data yang deskripsi panjang/
          line-height: 1; 

          /Untuk data yang tidak ada deskripsi panjang/
          /*line-height: 0.5; */
        }
        .table {
          font-size: 11px;
        }

        .form-horizontal .form-group
        {
            margin-right: 0;
            margin-left: 0;
            margin-top: -13px;
        }

    </style>

@endsection
@section('content')
<h3>MONITORING REVISI LABA RUGI KOMPARATIF</h3>
<div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel collapse">
            <!-- <div class="panel-heading"> -->
            <div class="x_title">
              <h2 style="font-size: 18px;">PENCARIAN</h2>
              <ul class="nav navbar-right panel_toolbox">
                <li>
                   <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
                <li>
                   <a class="close-link"><i class="fa fa-close"></i></a>
                </li>
              </ul>
              <div class="clearfix"></div>
            </div>
            <!-- <div class="panel-default"> -->
             <div class="x_content" style="display: none;">
            <br/>
            <form class="form-horizontal form-label-left" action="{{ url('/output/pengendalian/rekap_lr') }}">

            <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12">Tahun Anggaran</label>
              <div class="col-md-4 col-sm-4 col-xs-12">
                <input type="text" class="form-control col-md-7 col-xs-12" name="tahun_anggaran" readonly="readonly" value="{{!empty($input_tahun) ? $input_tahun : ''}}">
              </div>

              <div class="form-group">
              <label class="col-md-2 col-sm-3 col-xs-12">Struktur Bisnis</label>
              <div class="col-md-3 col-sm-4 col-xs-12">
                <input type="text" class="form-control col-md-7 col-xs-12" name="strategi_bisnis" readonly="readonly" value="{{!empty($strategi_bisnis) ? $strategi_bisnis->name : ''}}">
              </div>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12" >Distrik</label>
              <div class="col-md-4 col-sm-4 col-xs-12">
                <input type="text" class="form-control col-md-7 col-xs-12" name="distrik" readonly="readonly" value="{{!empty($distrik) ? $distrik->name : ''}}">
              </div>

              <div class="form-group">
              <label class="col-md-2 col-sm-3 col-xs-12">Lokasi</label>
              <div class="col-md-3 col-sm-4 col-xs-12">
                @if($distrik->name == 'UBJOM LUAR JAWA -1' || $distrik->name == 'UBJOM LUAR JAWA -2')
                    <?php $val = ''; ?>
                    @if($lokasi)
                        <?php $ival = null; ?>
                        @foreach($lokasi as $l)
                        <?php
                            $ival++;
                            if($ival==1)  
                                $val = $val.' '.$l->name; 
                            else
                                $val = $val.', '.$l->name; 
                        ?>
                        @endforeach
                    @endif
                    <input type="text" class="form-control col-md-7 col-xs-12" name="lokasi" readonly="readonly" value="{{ $val }}">
                @else
                    <select class="form-control col-md-7 col-xs-12" name="lokasi" required>
                        @foreach($lokasi as $l)
                            <option value="{{$l->name}}" <?php echo($l->id == $input_lokasi->id ? 'selected' : '')?>>{{ $l->name }}</option>
                        @endforeach
                    </select>
                @endif
              </div>
            </div>
            </div>

          

            <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12">Fase</label>
              <div class="col-md-4 col-sm-4 col-xs-12">
                <input type="text" name="fase" value="Ketetapan" class="form-control col-md-7 col-xs-12" readonly="readonly">
              </div>

              <div class="form-group">
                <label class="col-md-2 col-sm-3 col-xs-12">Bulan Anggaran</label>
                <div class="col-md-3 col-sm-4 col-xs-12">
                  <input type="text" name="bulan" value="Desember" class="form-control col-md-7 col-xs-12" readonly="readonly">
                </div>
              </div>
            </div>
            <hr>

            @if($distrik->name != 'UBJOM LUAR JAWA -1' && $distrik->name != 'UBJOM LUAR JAWA -2')
                <div class="ln_solid"></div>
                
                <div class="form-group">
                    <div >
                        <button type="submit" class="btn btn-primary pull-right">
                            <span class="glyphicon glyphicon-search"> </span> cari
                        </button>
                    </div>
                </div> 
            @endif 

            </form>
          </div>
        </div>
      </div>
    </div>

    @if(!empty($notification_failed))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ $notification_failed }}
        </div>
    @endif
</div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <a href="{{ Request::fullUrl() }}&type=excel" id="get-excel" class="btn btn-success pull-right"><i class="fa fa-download"></i> Download Excel</a>
                    <h2 style="font-size: 18px;"><label>REKAPITULASI LABA RUGI KOMPARATIF {{$input_tahun}} </label></h2><h2 style="font-size: 18px;">&nbsp;<label>{{ !empty($strategi_bisnis) ? ($strategi_bisnis->name == 'UP' ? 'Unit Pembangkit' : 'Jasa OM') : ''}}</label></h2>&nbsp;
                     <h2 style="font-size: 18px;">(Dalam Ribuan Rupiah)</h2>
                    <div class="clearfix"></div>

            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead style="background:#2A3F54;color:white;">
                        <tr role="row">
                            <th>Keterangan</th>
                            <th>RKAP N</th>
                            <th>RKAP N Update</th>
                            <th>RKAP Jan</th>
                            <th>RKAP Feb</th>
                            <th>RKAP Mar</th>
                            <th>RKAP Apr</th>
                            <th>RKAP May</th>
                            <th>RKAP Jun</th>
                            <th>RKAP Jul</th>
                            <th>RKAP Aug</th>
                            <th>RKAP Sep</th>
                            <th>RKAP Oct</th>
                            <th>RKAP Nov</th>
                            <th>RKAP Dec</th>
                        </tr>
                        <tr role="row">
                            <th>1</th>
                            <th>2</th>
                            <th>3</th>
                            <th>4</th>
                            <th>5</th>
                            <th>6</th>
                            <th>7</th>
                            <th>8</th>
                            <th>9</th>
                            <th>10</th>
                            <th>11</th>
                            <th>12</th>
                            <th>13</th>
                            <th>14</th>
                            <th>15</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($lr_result))
                            <?php $baris = $count = 0; ?>
                            <!-- khusus UP, menampilkan produksi penjualan di atas -->
                            @foreach($lr_result as $i => $val)
                                <!-- OM yg tidak ditampilkan penjualan tenaga listrik & produksi & penjualan -->
                                <!-- UP yg tidak ditampilkan Pendapatan Jasa OM & detail pemeliharaan -->
                                @if($distrik->strategi_bisnis->name == 'UP' && $i>=65 )
                                <?php $baris = $baris+1; $warna=($baris % 2 == 0) ? "#ffffff" : "#E8EDEF"; ?>
                                <tr style="background-color: <?= $warna?>">
                                    @foreach($settings as $key => $column_setting)
                                        @if( is_numeric( $val[$column_setting->judul_kolom] ))
                                            <!-- untuk angka dibuat rata kanan -->
                                            <td style="text-align: right;">
                                                <!-- untuk baris produksi & penjualan tidak ditampilkan jumlahnya -->
                                                @if($i>65)
                                                    <!-- jika desimal -->
                                                    @if( floor( $val[$column_setting->judul_kolom] ) != $val[$column_setting->judul_kolom] )
                                                        {{ number_format($val[$column_setting->judul_kolom], 2,',','.') }}
                                                    <!-- jika bukan desimal -->
                                                    @else
                                                        {{ number_format($val[$column_setting->judul_kolom], 0,',','.') }}
                                                    @endif
                                                @endif
                                            </td>
                                        @else
                                            <!-- untuk yg teks rata kiri-->
                                            <td>{{isset($val[$column_setting->judul_kolom]) ? $val[$column_setting->judul_kolom] : ''}}</td>
                                        @endif
                                    @endforeach
                                </tr>
                                @endif
                            @endforeach
                            <!-- menampilkan selain produksi & penjualan -->
                            @foreach($lr_result as $i => $val)
                                <!-- OM yg tidak ditampilkan penjualan tenaga listrik & produksi & penjualan -->
                                <!-- UP yg tidak ditampilkan Pendapatan Jasa OM & detail pemeliharaan -->
                                @if(($distrik->strategi_bisnis->name == 'OM' && ($i<15 || ($i>20 && $i<53) || ($i>57 && $i<65))) || ($distrik->strategi_bisnis->name == 'UP' && ($i<21 || ($i>23 && $i<39) || ($i>44 && $i<53) || ($i>57 && $i<65))) )
                                <?php $baris = $baris+1; $warna=($baris % 2 == 0) ? "#ffffff" : "#E8EDEF"; ?>
                                <tr style="background-color: <?= $warna?>">
                                    @foreach($settings as $key => $column_setting)
                                        @if( is_numeric( $val[$column_setting->judul_kolom] ))
                                            <!-- untuk angka dibuat rata kanan -->
                                            <td style="text-align: right;">
                                                <!-- jika desimal -->
                                                @if( floor( $val[$column_setting->judul_kolom] ) != $val[$column_setting->judul_kolom] )
                                                    {{ number_format($val[$column_setting->judul_kolom], 2,',','.') }}
                                                <!-- jika bukan desimal -->
                                                @else
                                                    {{ number_format($val[$column_setting->judul_kolom], 0,',','.') }}
                                                @endif
                                            </td>
                                        @else
                                            <!-- untuk teks dibuat rata kiri -->
                                            <td>{{isset($val[$column_setting->judul_kolom]) ? $val[$column_setting->judul_kolom] : ''}}</td>
                                        @endif
                                    @endforeach
                                </tr>
                                @endif
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


@endsection