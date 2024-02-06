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
        thead th {
            text-align: center;
        }

        /*Update line height & font-size*/
        .table thead tr th{
          line-height: 1;
        }
        .table tbody tr td{
          /*Untuk data yang deskripsi panjang*/
          line-height: 1; 

          /*Untuk data yang tidak ada deskripsi panjang*/
          /*line-height: 0.5; */
        }
        .table {
          font-size: 11px;
        }

        .form-horizontal .form-group
        {
           margin-right: 0;
           margin-left: 0;
           margin-top: -10px;
       }
    </style>

@endsection

@section('content')
    <h3> RINCIAN AI PENGEMBANGAN USAHA</h3>

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
            <form class="form-horizontal form-label-left">

                <div class="form-group">
                  <label class="control-label col-md-2 col-sm-3 col-xs-12">Tahun Anggaran</label>
                  <div class="col-md-4 col-sm-4 col-xs-12">
                   <!--  <select name="tahun_anggaran" class="form-control col-md-7 col-xs-12">
                      <option>- Pilih Tahun -</option>
                        @for($i=2017;$i<=(date('Y-m-d')+1);$i++)
                          <option value="{{$i}}" @isset($input_tahun) @if($input_tahun == $i) selected @endif @endisset>{{$i}}</option>
                        @endfor
                    </select> -->
                    <input type="text" name="tahun_anggaran" class="form-control col-md-7 col-xs-12" value="{{$input_tahun}}" readonly="readonly" />
                  </div>

                  <div class="form-group">
                  <label class="control-label col-md-2 col-sm-3 col-xs-12">Struktur Bisnis</label>
                  <div class="col-md-4 col-sm-4 col-xs-12">
                    <!-- <select name="strategi_bisnis" class="form-control col-md-7 col-xs-12">
                      <option>- Pilih Struktur Bisnis -</option>
                        @foreach ($sb as $sbs => $value)
                          <option value="{{ $value->id }}" @isset($input_sb) @if($input_sb->name == $value->name) selected @endif @endisset> {{ $value->name }} </option>
                        @endforeach
                    </select> -->
                    <input type="text" name="strategi_bisnis" class="form-control col-md-7 col-xs-12" value="{{($input_sb)? $input_sb->name : '' }}" readonly="readonly" />
                  </div>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-2 col-sm-3 col-xs-12" >Distrik</label>
                  <div class="col-md-4 col-sm-4 col-xs-12">
                    <!-- <select name="distrik" class="form-control col-md-7 col-xs-12">
                      <option>- Pilih Distrik -</option>
                        @isset($input_distrik) <option value="{{$idistrik}}" selected> {{$input_distrik->name}}</option> @endisset
                    </select> -->
                    <input type="text" name="distrik" class="form-control col-md-7 col-xs-12" value="{{($input_distrik)? $input_distrik->name : '' }}" readonly="readonly" />
                  </div>

                  <script type="text/javascript">
                      $(document).ready(function() {
                          $('select[name="strategi_bisnis"]').on('change', function() {
                              var strategi_bisnisID = $(this).val();
                              $('select[name="distrik"]').empty();
                              $('select[name="lokasi"]').empty();

                              if(strategi_bisnisID) {
                                  $.ajax({
                                      url: "{{url('/output/rincian-pengembangan-usaha/ajax/')}}/"+strategi_bisnisID,
                                      type: "GET",
                                      dataType: "json",
                                      success:function(data) {
                                        $('select[name="distrik"]').empty();
                                        $('select[name="distrik"]').append('<option value="">- Pilih Distrik -</option>');
                                        $.each(data, function(sb, value) {
                                          $('select[name="distrik"]').append('<option value="'+ value["id"] +'">'+ value["name"] +'</option>');
                                        });
                                        }
                                  });
                              }else{
                                  $('select[name="distrik"]').empty();
                              }
                          });
                      });
                  </script>

                  <div class="form-group">
                  <label class="control-label col-md-2 col-sm-3 col-xs-12">Lokasi</label>
                  <div class="col-md-4 col-sm-4 col-xs-12">
                    <!-- <select name="lokasi" class="form-control col-md-7 col-xs-12">
                      <option>- Pilih Lokasi -</option>
                          @isset($input_lokasi)
                            <option value="{{$ilokasi}}" selected> {{$input_lokasi->name}}</option>
                          @endisset
                      </select> -->
                    <input type="text" name="lokasi" class="form-control col-md-7 col-xs-12" value="{{($ilokasi)? $input_lokasi->name : ''}}" readonly="readonly" />
                  </div>
                </div>

                <script type="text/javascript">
                    $(document).ready(function() {
                        $('select[name="distrik"]').on('change', function() {
                            var lokasiID = $(this).val();
                            $('select[name="lokasi"]').empty();

                            //console.log(lokasiID);

                            if(lokasiID) {
                                $.ajax({
                                    url: "{{url('/output/rincian-pengembangan-usaha/ajax2/')}}/"+lokasiID,
                                    type: "GET",
                                    dataType: "json",
                                    success:function(data) {

                                      $('select[name="lokasi"]').empty();
                                      //console.log("waw");
                                      //console.log(data);
                                      // $('select[name="lokasi"]').append('<option value="">==Silahkan Pilih Lokasi==</option>');
                                      $('select[name="lokasi"]').append('<option value="">- Pilih Lokasi -</option>');
                                      $.each(data, function(ad , value) {
                                      console.log(ad);
                                          $('select[name="lokasi"]').append('<option value="'+ value["id"] +'">'+ value["name"] +'</option>');
                                      });

                                    }
                                });
                            }else{
                                $('select[name="lokasi"]').empty();
                            }
                        });
                    });
                </script>

                </div>

                <div class="form-group">
                  <label for="middle-name" class="control-label col-md-2 col-sm-3 col-xs-12">Fase</label>
                  <div class="col-md-4 col-sm-4 col-xs-12">
                    <!-- <select required="true" name="fase" class="form-control col-md-7 col-xs-12">
                      <option>- Pilih Fase -</option>
                        @foreach($fases as $fs)
                          <option value="{{$fs->id}}" @isset($input_fase) @if($input_fase->name == $fs->name) selected @endif @endisset>{{$fs->name}}</option>
                        @endforeach
                    </select> -->
                    <input type="text" name="fase" class="form-control col-md-7 col-xs-12" value="{{isset($input_fase) ? $input_fase->name : '' }}" readonly="readonly" />
                  </div>

                  <div class="form-group">
                  <label class="control-label col-md-2 col-sm-3 col-xs-12">Draft</label>
                  <div class="col-md-4 col-sm-4 col-xs-12">
                   <!--  <select required="true" name="draft1" class="form-control col-md-7 col-xs-12">
                      <option>- Pilih Draft -</option>
                        @isset($input_draft)
                          <option value="{{$input_draft}}" selected>
                            {{$draft->draft_versi}}
                          </option>
                        @endisset
                    </select> -->

                    <input type="text" name="draft1" class="form-control col-md-12 col-xs-12" value="{{isset($input_draft) ? $draft->draft_versi.' - '.$draft->name : ''}}" readonly="readonly" />
                  </div>
                  </div>
                </div>

                <script type="text/javascript">
                  $(document).ready(function() {
                    $('select[name="lokasi"]').on('change', function() {
                      var id_lokasi = $(this).val();
                      var id_tahun = $('select[name="tahun_anggaran"]').val();

                      // console.log(id_lokasi);
                      // console.log(id_tahun);

                      $('select[name="draft1"]').empty();

                      if(id_lokasi && id_tahun) {
                          $.ajax({
                              url: "{{url('/output/rincian-pengembangan-usaha/ajax3/')}}/"+id_lokasi+"/"+id_tahun,
                              type: "GET",
                              dataType: "json",
                              success:function(data) {

                                $('select[name="draft1"]').empty();
                                console.log(data);
                                //console.log("woy");

                                $.each(data, function(ad , value) {
                                  console.log(ad);
                                    $('select[name="draft1"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +'</option>');
                                });

                              }
                          });
                      }else{
                          $('select[name="draft1"]').empty();

                      }
                    })
                  })
                </script>


               <!--  <div class="ln_solid"></div>

                <div class="form-group">
                  <div>
                    <button type="submit" class="btn btn-primary pull-right">
                        <span class="glyphicon glyphicon-search"> </span> cari
                    </button>
                  </div>
                </div>     -->

            </form>
          </div>
        </div>
      </div>
    </div>


<!-- Grafik Rencana Disburse -->

<div class="row">
  <div class="col-md-6 col-sm-6 col-xs-6">
    <div class="x_panel">
      <div class="x_title">

          <div id="rencana-disburse">
             <script type="text/javascript">
                Highcharts.setOptions({
                  lang: {
                    thousandsSep: '.'
                  }
                });

               Highcharts.chart('rencana-disburse', {

                  title: {
                      text: 'Rencana Disburse'
                  },

                  yAxis: {
                      title: {
                          text: 'Dalam Milyar Rp'
                      }
                  },

                  legend: {
                      layout: 'vertical',
                      align: 'right',
                      verticalAlign: 'middle'
                  },

                  xAxis: {
                        categories: [
                        <?php
                        if ($count != NULL) {
                          foreach ($totalSumByMonth1 as $key => $value) {
                            echo "'".$value["bulan"]."',";
                          }
                        }
                        ?>
                        ]
                        //categories: ['Mar', 'Mar', 'Apr', 'Jul', 'Dec', 'Dec', 'Nov', 'Sep', 'Jun', 'Mar']
                    },

                  series: [{
                            name: 'Disburse',

                        data: [
                        <?php
                        if ($count != NULL) {
                          $keys = array_keys($totalSumByMonth1);
                          foreach ($totalSumByMonth1 as $key => $value) {
                            echo round($value["value"]).',';
                          }
                        }
                        ?>
                        ]
                      //data: [250, 617, 3467, 3467, 3542, 8327, 8327, 9042, 10632, 10757 ]
                  },],

                  responsive: {
                      rules: [{
                          condition: {
                              maxWidth: 500
                          },

                  chartOptions: {
                      legend: {
                          layout: 'horizontal',
                          align: 'center',
                          verticalAlign: 'bottom'
                        }
                    }
                  }]
                }
            });

           </script>
         </div>
      </div>
    </div>
  </div>

  <!-- Grafik Target Terkontrak -->

  <div class="col-md-6 col-sm-6 col-xs-6">
    <div class="x_panel">
      <div class="x_title">
          <div id="target-terkontrak">
              <script type="text/javascript">
                Highcharts.chart('target-terkontrak', {

                  title: {
                      text: 'Target Terkontrak'
                  },

                  yAxis: {
                      title: {
                          text: 'Dalam Milyar Rp'
                      }
                  },

                  legend: {
                      layout: 'vertical',
                      align: 'right',
                      verticalAlign: 'middle'
                  },

                  xAxis: {
                      categories: [
                        <?php
                        if ($count != NULL) {
                          foreach ($totalSumByMonth2 as $key => $value) {
                            echo "'".$value["bulan"]."',";
                          }
                        }
                        ?>
                        ]
                        //categories: ['Mar', 'Mar', 'Apr', 'Jul', 'Dec', 'Dec', 'Nov', 'Sep', 'Jun', 'Mar']
                    },

                  series: [{
                            name: 'AI',

                        data: [
                        <?php
                        if ($count != NULL) {
                          $keys = array_keys($totalSumByMonth2);
                          foreach ($totalSumByMonth2 as $key => $value) {
                            // dd($value["value"]);
                            echo round($value["value"]).',';
                          }
                        }
                        ?>
                        ]
                  },],

                  responsive: {
                      rules: [{
                          condition: {
                              maxWidth: 500
                          },
                  chartOptions: {
                      legend: {
                          layout: 'horizontal',
                          align: 'center',
                          verticalAlign: 'bottom'
                        }
                      }
                    }]
                  }

               });

              </script>
          </div>
      </div>
    </div>
  </div>

</div>

<!-- Table Rincian Penetapan Anggaran pengembangan Usaha -->

        <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2 style="font-size: 18px;">RINCIAN PENETAPAN ANGGARAN PENGEMBANGAN USAHA PJB</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>

                  <a href="{{ Request::fullUrl() }}&download=rincian-pengembangan-usaha&type=excel" target="_blank" id="get-excel" class="btn btn-success pull-right"><i class="fa fa-download"></i> Download Excel</a>

                  <a href="{{ Request::fullUrl() }}&download=rincian-pengembangan-usaha&type=pdf" target="_blank" id="get-pdf" class="btn btn-primary pull-right"><i class="fa fa-download"></i> Download PDF</a>

                  <div class="x_content" >
                       <table class="table table-striped table-bordered" id="table-usaha" >
                        <thead style="background:#2A3F54;color:white;">
                         <tr>
                            <th rowspan="2">Divisi / Bidang</th>
                            <th rowspan="2">Kode PRK</th>
                            <th rowspan="2">Deskripsi PRK Kegiatan</th>
                            <th rowspan="2">Anggaran Investasi Luncuran</th>
                            <th rowspan="2">Anggaran Investasi Murni</th>
                            <th rowspan="2">Total Anggaran Investasi</th>
                            <th colspan="2">COD</th>
                            <th rowspan="2">Pengadaan Pusat/Unit</th>
                            <th colspan="2">Disburse tahun ke- @if(isset($input_tahun)){{$input_tahun}} @else n @endif</th>
                          </tr>
                          <tr>
                            <th>Bulan</th>
                            <th>Tahun</th>
                            <th>Bulan</th>
                            <th>Nilai</th>
                          </tr>
                          <tr>
                            <th>1</th>
                            <th>1</th>
                            <th>2</th>
                            <th>3</th>
                            <th>4</th>
                            <th>5=3+4</th>
                            <th>6</th>
                            <th>7</th>
                            <th>8</th>
                            <th>9</th>
                            <th>10</th>
                          </tr>
                          </thead>
                          <tbody style="font-size:11px;">
                          <?php
                            if( $count != NULL ) {
                              $baris = 0;
                              for ($i = 0 ; $i < $count; $i++) {
                                  ?>
                                <?php $baris = $baris+1; $warna=($baris % 2 == 0) ? "#ffffff" : "#E8EDEF"; ?>
                                <tr style="background-color: <?= $warna?>">
                                  <td>{{$divisi[$i]->value}}</td>
                                  <td>{{$kodePRK[$i]->value}}</td>
                                  <td>{{$deskPRK[$i]->value}}</td>
                                  <td style="text-align: right">{{App\Http\Controllers\Controller::numberFormat(round($anggaranIL[$i]->value),0)}}</td>
                                  <td style="text-align: right">{{App\Http\Controllers\Controller::numberFormat(round($anggaranIM[$i]->value),0)}}</td>
                                  <td style="text-align: right">{{App\Http\Controllers\Controller::numberFormat(round($totalAnggaranInvest[$i]->value),0)}}</td>
                                  <td>{{$codBulan[$i]->value}}</td>
                                  <td>{{$codTahun[$i]->value}}</td>
                                  <td>{{$pengadaanPusat[$i]->value}}</td>
                                  <td>{{$disburseBulan[$i]->value}}</td>
                                  <td style="text-align: right">{{App\Http\Controllers\Controller::numberFormat(round($disburseNilai[$i]->value),0)}}</td>
                                </tr>
                              <?php
                              }
                            }
                            ?>
                          </tbody>
                      </table>
                  </div>
                </div>
              </div>
          </div>


<style media="screen">
   .odd-row {
     background: #FFFFFF;
   }
   .even-row{
     background: #E8EDEF;
   }
</style>

@endsection

@section('js_page')
<script type="text/javascript">
  $(document).ready(function() {
    var table = $('#table-usaha').DataTable( {
        "scrollY": "800px",
        "scrollX": "300px",
        "scrollCollapse": true,
        pagingType: "full_numbers",
        ordering: false,
        searching: false,
        aLengthMenu: [
        [10, 25, 50, 100, -1],
        [10, 25, 50, 100, "All"]],
        iDisplayLength: 10
    } );
} );
</script>
@endsection
