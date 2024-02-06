@extends('layouts.app')

@section('css_page')
    <style type="text/css">
        .table-container
        {
            widtd: 100%;
            overflow-x: auto;
            overflow: auto;
            margin: 0 0 1em;
        }
        thead th{
          text-align: center;
        }

    </style>

@endsection

@section('content')
    <h1> Program Strategis </h1>
    <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">

    <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                     Pencarian
                </div>
                <div class="panel-default">
                    <br>
                    <div class="row">
                        <div class="col-lg-12">
                            <form>
                                <div class="col-md-2"><label>Tahun Anggaran</label></div>
                                <div class="col-md-4">
                                    <select name="tahun_anggaran" class="form-control">
                                       <option value="">- Pilih Tahun -</option>
                                       @for($i=2017;$i<=(date('Y')+1);$i++)
                                        <option value="{{$i}}" @isset($input_tahun) @if($input_tahun == $i) selected @endif @endisset>{{$i}}</option>
                                       @endfor
                                    </select>
                                </div>
                                <div class="col-md-2"><label>Struktur Bisnis</label></div>
                                <div class="col-md-3">
                                    <select class="form-control" name="strategi_bisnis">
                                       <option value="">- Pilih Struktur Bisnis -</option>

                                       @foreach ($sb as $sbs => $value)
                                         <option value="{{ $value->id }}" @isset($input_sb) @if($input_sb->name == $value->name) selected @endif @endisset> {{ $value->name }} </option>
                                       @endforeach
                                    </select>
                                </div>

                                <br>
                                <br>
                                <div class="col-md-2"><label>Distrik</label></div>
                                <div class="col-md-4">
                                    <select class="form-control" name="distrik">

                                      <option value="">- Pilih Distrik -</option>
                                      @isset($input_distrik) <option value="{{$idistrik}}" selected> {{$input_distrik->name}}</option> @endisset
                                    </select>
                                </div>

                                <script type="text/javascript">
                                    $(document).ready(function() {
                                        $('select[name="strategi_bisnis"]').on('change', function() {
                                            var strategi_bisnisID = $(this).val();
                                            $('select[name="distrik"]').empty();
                                            $('select[name="lokasi"]').empty();

                                            if(strategi_bisnisID) {
                                                $.ajax({
                                                    url: "{{url('/output/program-strategis/ajax/')}}"+'/'+strategi_bisnisID,
                                                    type: "GET",
                                                    dataType: "json",
                                                    success:function(data) {
                                                // console.log(data);
                                                      $('select[name="distrik"]').empty();
                                                      $('select[name="lokasi"]').append('<option value="">- Pilih Lokasi -</option>');
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


                                <div class="col-md-2"><label> Lokasi</label></div>
                                <div class="col-md-3">
                                    <select class="form-control" name="lokasi">
                                      <option value="">- Pilih Lokasi -</option>

                                      @isset($input_lokasi) <option value="{{$ilokasi}}" selected> {{$input_lokasi->name}}</option> @endisset

                                    </select>
                                </div>

                                <script type="text/javascript">
                                    $(document).ready(function() {
                                        $('select[name="distrik"]').on('change', function() {
                                            var lokasiID = $(this).val();
                                            $('select[name="lokasi"]').empty();

                                            if(lokasiID) {
                                                $.ajax({
                                                    url: "{{url('/output/program-strategis/ajax2/')}}"+'/'+lokasiID,
                                                    type: "GET",
                                                    dataType: "json",
                                                    success:function(data) {

                                                      $('select[name="lokasi"]').empty();
                                                      console.log(data);
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

                                <br>
                                <br>
                                <div class="col-md-2"><label>Fase</label></div>
                                <div class="col-md-4">
                                    <select class="form-control" name="fase">
                                      <option value="">- Pilih Fase -</option>
                                        @foreach($fases as $fs)
                                          <option value="{{$fs->id}}" @isset($input_fase) @if($input_fase->name == $fs->name) selected @endif @endisset>{{$fs->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2"><label>Draft Form 6 Reimburse</label></div>
                                <div class="col-md-3">
                                  <select class="form-control" name="draft_form_6_reimburse">
                                    <option value="">- Pilih Draft -</option>
                                    @isset($input_draft_form_6_reimburse)
                                    <option value="{{$input_draft_form_6_reimburse}}" selected> {{$versi6_reimburse->draft_versi}}</option>
                                    @endisset

                                  </select>
                                </div>

                                <script type="text/javascript">
                                  $(document).ready(function() {
                                    $('select[name="lokasi"]').on('change', function() {
                                      console.log("masuk");
                                      var id_lokasi = $(this).val();
                                      var id_tahun = $('select[name="tahun_anggaran"]').val();

                                      $('select[name="draft_form_6_reimburse"]').empty();

                                      if(id_lokasi && id_tahun) {
                                          $.ajax({
                                              url: "{{url('/output/program-strategis/ajax4/')}}"+'/'+id_lokasi+"/"+id_tahun,
                                              type: "GET",
                                              dataType: "json",
                                              success:function(data) {
                                                console.log(data);
                                                $.each(data, function(ad , value) {
                                                    $('select[name="draft_form_6_reimburse"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +'</option>');
                                                });

                                              }
                                          });
                                      }else{
                                          $('select[name="draft_form_6_reimburse"]').empty();

                                      }
                                    })
                                  })
                                </script>

                                <br>
                                <br>
                                <div class="col-md-2"><label></label></div>
                                <div class="col-md-4">

                                </div>

                                <div class="col-md-2"><label>Draft Form 6 Rutin</label></div>
                                <div class="col-md-3">
                                  <select class="form-control" name="draft_form_6_rutin">
                                    <option value="">- Pilih Draft -</option>
                                    @isset($input_draft_form_6_rutin) <option value="{{$input_draft_form_6_rutin}}" selected> {{$versi6_rutin->draft_versi}}</option> @endisset



                                  </select>
                                </div>

                                <script type="text/javascript">
                                  $(document).ready(function() {
                                    $('select[name="lokasi"]').on('change', function() {
                                      var id_lokasi = $(this).val();
                                      var id_tahun = $('select[name="tahun_anggaran"]').val();

                                      $('select[name="draft_form_6_rutin"]').empty();

                                      if(id_lokasi && id_tahun) {
                                          $.ajax({
                                              url: "{{url('/output/program-strategis/ajax5/')}}"+'/'+id_lokasi+'/'+id_tahun,
                                              type: "GET",
                                              dataType: "json",
                                              success:function(data) {

                                                $.each(data, function(ad , value) {
                                                    $('select[name="draft_form_6_rutin"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +'</option>');
                                                });

                                              }
                                          });
                                      }else{
                                          $('select[name="draft_form_6_rutin"]').empty();

                                      }
                                    })
                                  })
                                </script>

                                <br>
                                <br>
                                <div class="col-md-2"><label></label></div>
                                <div class="col-md-4">

                                </div>

                                <div class="col-md-2"><label>Draft Form 10 Pengembangan Usaha</label></div>
                                <div class="col-md-3">
                                  <select class="form-control" name="draft_form_10_pu">
                                    <option value="">- Pilih Draft -</option>
                                    @isset($input_draft_form_10_pu) <option value="{{$input_draft_form_10_pu}}" selected> {{$versi10_pu->draft_versi}}</option> @endisset


                                  </select>
                                </div>

                                <script type="text/javascript">
                                  $(document).ready(function() {
                                    $('select[name="lokasi"]').on('change', function() {
                                      var id_lokasi = $(this).val();
                                      var id_tahun = $('select[name="tahun_anggaran"]').val();

                                      $('select[name="draft_form_10_pu"]').empty();

                                      if(id_lokasi && id_tahun) {
                                          $.ajax({
                                              url: "{{url('/output/program-strategis/ajax6/')}}"+'/'+id_lokasi+"/"+id_tahun,
                                              type: "GET",
                                              dataType: "json",
                                              success:function(data) {

                                                $.each(data, function(ad , value) {
                                                    $('select[name="draft_form_10_pu"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +'</option>');
                                                });

                                              }
                                          });
                                      }else{
                                          $('select[name="draft_form_10_pu"]').empty();

                                      }
                                    })
                                  })
                                </script>

                                <br>
                                <br>
                                <div class="col-md-6"></div>
                                <div class="col-md-2"><label>Draft Form 10 Penguatan KIT</label></div>
                                <div class="col-md-3">
                                  <select class="form-control" name="draft_form_10_pk">
                                    <option value="">- Pilih Draft -</option>
                                    @isset($input_draft_form_10_pk) <option value="{{$input_draft_form_10_pk}}" selected> {{$versi10_pk->draft_versi}}</option> @endisset


                                  </select>
                                </div>

                                <script type="text/javascript">
                                  $(document).ready(function() {
                                    $('select[name="lokasi"]').on('change', function() {
                                      var id_lokasi = $(this).val();
                                      var id_tahun = $('select[name="tahun_anggaran"]').val();

                                      $('select[name="draft_form_10_pk"]').empty();

                                      if(id_lokasi && id_tahun) {
                                          $.ajax({
                                              url: "{{url('/output/program-strategis/ajax7/')}}"+'/'+id_lokasi+'/'+id_tahun,
                                              type: "GET",
                                              dataType: "json",
                                              success:function(data) {

                                                $.each(data, function(ad , value) {
                                                    $('select[name="draft_form_10_pk"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +'</option>');
                                                });

                                              }
                                          });
                                      }else{
                                          $('select[name="draft_form_10_pk"]').empty();

                                      }
                                    })
                                  })
                                </script>

                                <br>
                                <br>
                                <div class="col-md-6"></div>
                                <div class="col-md-2"><label>Draft Form 10 PLN </label></div>
                                <div class="col-md-3">
                                  <select class="form-control" name="draft_form_10_pln">
                                    <option value="">- Pilih Draft -</option>
                                    @isset($input_draft_form_10_pln) <option value="{{$input_draft_form_10_pln}}" selected> {{$versi10_pln->draft_versi}}</option> @endisset


                                  </select>
                                </div>

                                <script type="text/javascript">
                                  $(document).ready(function() {
                                    $('select[name="lokasi"]').on('change', function() {
                                      var id_lokasi = $(this).val();
                                      var id_tahun = $('select[name="tahun_anggaran"]').val();

                                      $('select[name="draft_form_10_pln"]').empty();

                                      if(id_lokasi && id_tahun) {
                                          $.ajax({
                                              url: "{{url('/output/program-strategis/ajax8/')}}"+'/'+id_lokasi+'/'+id_tahun,
                                              type: "GET",
                                              dataType: "json",
                                              success:function(data) {

                                                $.each(data, function(ad , value) {
                                                    $('select[name="draft_form_10_pln"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +'</option>');
                                                });

                                              }
                                          });
                                      }else{
                                          $('select[name="draft_form_10_pln"]').empty();

                                      }
                                    })
                                  })
                                </script>

                                <div>
                                   <button type="submit" class="btn btn-primary">
                                       <span class="glyphicon glyphicon-search"> </span> cari
                                   </button>
                                </div>
                            </form>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        </div>
    </div>

<!-- Grafik  -->

  <div class="row">
  <div class="col-md-6 col-sm-6 col-xs-6">
    <div class="x_panel">
      <div class="x_title">
          <div id="Jumlah-program-strategis">
            <script type="text/javascript">
              Highcharts.chart('Jumlah-program-strategis', {
                  chart: {
                      plotBackgroundColor: null,
                      plotBorderWidth: null,
                      plotShadow: false,
                      type: 'pie'
                  },
                    title: {
                        text: 'Jumlah Program Strategis'
                    },
                    tooltip: {
                        pointFormat: '{series.name}: {point.cursore},<b>{point.percentage:.1f}%</b>'
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>: {point.cursore},{point.percentage:.1f} %',
                                style: {
                                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                }
                            }
                        }
                    },
                    series: [{
                        name: 'Brands',
                        colorByPoint: true,
                        data: [{
                            name: 'Program Investasi',
                            y: @if(isset($percentPI)){{$percentPI}} @else 0 @endif,
                            cursore: @if(isset($countPI)){{$countPI}} @else 0 @endif
                        }, {
                            name: 'Program Proyek',
                            y: @if(isset($percentPP)){{$percentPP}} @else 0 @endif,
                            sliced: true,
                            selected: true,
                            cursore: @if(isset($countPP)){{$countPP}} @else 0 @endif
                        }, {
                            name: 'Program Overhaul',
                            y: @if(isset($percentPO)){{$percentPO}} @else 0 @endif,
                            cursore: @if(isset($countPO)){{$countPO}} @else 0 @endif
                        }]
                    }]
                });
            </script>
          </div>
      </div>
    </div>
  </div>



  <div class="col-md-6 col-sm-6 col-xs-6">
    <div class="x_panel">
      <div class="x_title">
          <div id="anggaran-program-strategis">
            <script type="text/javascript">
               Highcharts.chart('anggaran-program-strategis', {
                  chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Anggaran Program Strategis'
                    },
                    xAxis: {
                        type: 'category'
                    },

                    legend: {
                        enabled: false
                    },
                    plotOptions: {
                        series: {
                            borderWidth: 0,
                            dataLabels: {
                                enabled: true,
                                format: '{point.y:,.0f}'
                            }
                        }
                    },

                    tooltip: {
                        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:,.0f}</b> of total<br/>'
                    },

                    series: [{
                        name: 'Brands',
                        colorByPoint: true,
                        data: [{
                            name: 'Program Investasi',
                            y: @if(isset($sumPI)){{$sumPI}} @else 0 @endif,

                        }, {
                            name: 'Program Proyek',
                            y: @if(isset($sumPP)){{$sumPP}} @else 0 @endif,

                        }, {
                            name: 'Program Overhaul',
                            y: @if(isset($sumPO)){{$sumPO}} @else 0 @endif,

                        }]
                    }],

                });
            </script>
          </div>
      </div>
    </div>
  </div>
</div>
<a href="{{ Request::fullUrl() }}&download=program_strategis&type=excel" target="_blank" id="get-excel1" class="btn btn-success pull-right"><i class="fa fa-download"></i> Download Excel</a>


<a href="{{ Request::fullUrl() }}&download=program_investasi&type=pdf" target="_blank" class="btn btn-primary pull-right"><i class="fa fa-download"></i> Download PDF</a>

<!-- Table -->
    <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Program Investasi</h2>
                    <div class="clearfix"></div>
                  </div>


                  <div class="x_content">
                    <table  class="table table-striped table-bordered">
                      <thead style="background:#2A3F54;color:white;">
                        <tr>
                          <th>No</th>
                          <th>No PRK</th>
                          <th>Program</th>
                          <th>AKI</th>
                        </tr>
                      </thead>
                      <body>
                        <?php $count = 0 ?>
                        @isset($combineall)
                        @foreach($combineall as $c1)
                          <tr>
                            <td>{{$count+1}}</td>
                            <td>{{$c1["noprk"]}}</td>
                            <td>{{$c1["desprk"]}}</td>
                            <td style="text-align: right
">{{App\Http\Controllers\Controller::numberFormat($c1["aki"],0)}}</td>
                          </tr>
                          <?php $count+=1; ?>
                        @endforeach
                        @endisset
                      </body>
                    </table>
                  </div>
                </div>
            </div>
          </div>

      <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Program Proyek</h2>
                    <div class="clearfix"></div>
                  </div>

                  <div class="x_content">
                    <table  class="table table-striped table-bordered">
                      <thead style="background:#2A3F54;color:white;">
                        <tr>
                          <th>No</th>
                          <th>No PRK</th>
                          <th>Program</th>
                          <th>Anggaran</th>
                        </tr>
                      </thead>
                      <body>
                        <?php $count = 0 ?>
                        @isset($combine2)
                          @foreach($combine2 as $c2)
                          <tr>
                            <td>{{$count+1}}</td>
                            <td>{{$c2['I']}}</td>
                            <td>{{$c2['T']}}</td>
                            <td style="text-align: right
">{{App\Http\Controllers\Controller::numberFormat($c2['AN'],0)}} </td>
                          </tr>
                            <?php $count+=1; ?>
                          @endforeach
                        @endisset
                      </body>
                    </table>
                  </div>
                </div>
            </div>
          </div>


      <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Program Overhaul</h2>
                    <div class="clearfix"></div>
                  </div>


                  <div class="x_content">
                    <table  class="table table-striped table-bordered">
                      <thead style="background:#2A3F54;color:white;">
                        <tr>
                          <th>No</th>
                          <th>No PRK</th>
                          <th>Program</th>
                          <th>Anggaran</th>
                        </tr>
                      </thead>
                      <body>
                        <?php $count = 0 ?>
                        @isset($prkInti)
                          @foreach($prkInti as $pi)
                          <tr>
                            <td>{{$count+1}}</td>
                            <td>{{$pi['key']}}</td>
                            <td>{{$pi['inti']}}</td>
                            <td style="text-align: right
">{{App\Http\Controllers\Controller::numberFormat($pi['sum'],0)}}</td>
                          </tr>
                            <?php $count+=1; ?>
                          @endforeach
                        @endisset
                      </body>
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
<script type="text/javascript">
  $('#datatable').DataTable( {
  "stripeClasses": [ 'odd-row', 'even-row' ]
  } );
</script>

@endsection