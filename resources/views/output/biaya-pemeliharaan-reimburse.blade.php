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

        /*update line height & font size*/
        .table thead tr th{
          line-height: 1;
        }
        .table tbody tr td{
          /*Untuk data yang deskripsi panjang*/
          /*line-height: 1; */

          /*Untuk data yang tidak ada deskripsi panjang*/
          line-height: 0.5; 
        }
        .table {
          font-size: 11px;
        }

        .form-horizontal .form-group
        {
            margin-right: 0;
            margin-left: 0;
            margin-top: -12px;
        }
    </style>

@endsection

@section('content')
    <h3> BIAYA PEMELIHARAAN REIMBURSE</h3>

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
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Tahun Anggaran</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <!-- <select class="form-control col-md-7 col-xs-12" required="true" name="tahun">
                <option value="">- Pilih Tahun -</option>
                  @for($i=2016;$i<=(date('Y')+1);$i++)
                    <option value="{{$i}}"  @isset($tahun) @if($tahun == $i) selected @endif @endisset>{{$i}}</option>
                  @endfor
              </select> -->
              <input type="text" class="form-control col-md-7 col-xs-12" name="tahun" readonly="readonly" value="{{isset($tahun) ? $tahun : ''}}">
            </div>

            <div class="form-group">
            <label class="control-label col-md-2 col-sm-3 col-xs-12">Struktur Bisnis</label>
            <div class="col-md-3 col-sm-4 col-xs-12">
              <!-- <select class="form-control col-md-7 col-xs-12" name="strategi_bisnis" required>
                <option value="">- Pilih Stuktur Bisnis -</option>
                  @foreach ($Sbisnis as $sbs => $value)
                    <option value="{{ $value->id }}" @isset($input_sb) @if($input_sb->name == $value->name) selected @endif @endisset> {{ $value->name }} </option>
                  @endforeach
              </select> -->
              <input type="text" class="form-control col-md-7 col-xs-12" name="strategi_bisnis" readonly="readonly" value="{{isset($input_sb) ? $input_sb->name : ''}}">
            </div>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" >Distrik</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <!-- <select class="form-control col-md-7 col-xs-12" name="distrik" required>
                <option value="">- Pilih Distrik -</option>
                  @isset($distrik)
                    <option value="{{$input_distrik}}" selected> {{$distrik->name}}</option>
                  @endisset
              </select> -->
              <input type="text" class="form-control col-md-7 col-xs-12" name="distrik" readonly="readonly" value="{{isset($distrik) ? $distrik->name : ''}}">
            </div>

            <script type="text/javascript">
              $(document).ready(function() {
                  $('select[name="strategi_bisnis"]').on('change', function() {
                      var strategi_bisnisID = $(this).val();
                      $('select[name="distrik"]').empty();
                      $('select[name="lokasi"]').empty();

                      if(strategi_bisnisID) {
                          $.ajax({
                          url: '{{url("/output/biaya-pemeliharaan/ajax/")}}'+'/'+strategi_bisnisID,
                          type: "GET",
                          dataType: "json",
                          success:function(data) {
                          // console.log(data);
                            var t = "";
                            $.each(data, function(sb, value) {
                                t += '<option value="'+ value["id"] +'">'+ value["name"] +'</option>';
                            });

                              $('select[name="distrik"]').append("<option value=''>Pilih Distrik</option>"+t);

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
            <div class="col-md-3 col-sm-4 col-xs-12">
              <!-- <select class="form-control col-md-7 col-xs-12" name="lokasi" id="lokasi" required>
                  <option value="">- Pilih Lokasi -</option>
                    @isset($input_lokasi)
                      <option value="{{$lokasi}}" selected> {{$input_lokasi->name}}</option>
                    @endisset
              </select> -->
              <input type="text" class="form-control col-md-7 col-xs-12" name="lokasi" readonly="readonly" value="{{isset($input_lokasi) ? $input_lokasi->name : ''}}">
            </div>
          </div>
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
                              var l = "";
                            $.each(data, function(ad , value) {
                              l += "'<option value='"+ value["id"] +"'>"+ value["name"] +"</option>";
                            });
                              $('select[name="lokasi"]').append('<option value="">Pilih Lokasi</option>'+l);
                          }
                      });
                    }else{
                       $('select[name="lokasi"]').empty();

                  }
               });
            });
          </script>

          <div class="form-group">
            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Fase</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <!-- <select class="form-control col-md-7 col-xs-12"  name="fase" required>
                <option value="">- Pilih Fase -</option>
                  @foreach($fs as $f)
                    <option value="{{$f->id}}" @isset($input_fase) @if($input_fase->name == $f->name) selected @endif @endisset>{{$f->name}}</option>
                  @endforeach
              </select> -->
              <input type="text" name="fase" value="{{isset($input_fase) ? $input_fase->name : ''}}" class="form-control col-md-7 col-xs-12" readonly="readonly">
            </div>

             <script type="text/javascript">
                $(document).ready(function() {
                  $('select[name="lokasi"]').on('change', function() {
                    var id_lokasi = $(this).val();
                    var id_tahun = $('select[name="tahun"]').val();

                    $('select[name="reimburse"]').empty();

                    if(id_lokasi && id_tahun) {
                        $.ajax({
                            url: "{{url('/output/biaya-pemeliharaan/ajax3/')}}"+'/'+id_lokasi+"/"+id_tahun,
                            type: "GET",
                            dataType: "json",
                            success:function(data) {
                              console.log(data);
                              $.each(data, function(ad , value) {
                                  $('select[name="reimburse"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +'</option>');
                              });

                            }
                        });
                    }else{
                        $('select[name="reimburse"]').empty();

                    }
                  })
                })
             </script>

            <div class="form-group">
              <label class="col-md-2 col-sm-3 col-xs-12"></label>
              <div class="col-md-3 col-sm-4 col-xs-12"></div>
            </div>
          </div>
          <hr>

          <div class="form-group" style="margin-top: 5px;">
            <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Form 6 - Reimburse</label>
             <div class="col-md-6 col-sm-4 col-xs-12">
                <!-- <select class="form-control col-md-7 col-xs-12" name="reimburse">
                 <option value="">- Pilih Form6 - Reimburse -</option>
                  @isset($input_reimburse)
                    <option value="{{$reimburse}}" selected> {{$input_reimburse->draft_versi}}</option>
                  @endisset
                </select> -->
                <input type="text" name="reimburse" value="{{ isset($input_reimburse) ? $input_reimburse->draft_versi.' - '.$input_reimburse->name : '' }}" class="form-control col-md-7 col-xs-12" readonly="readonly">
             </div>
            </div>
          </div>

          <script type="text/javascript">
            $(document).ready(function() {
                $('select[name="lokasi"]').on('change', function() {
                  console.log("masuk");
                  var id_lokasi = $(this).val();
                  var id_tahun = $('select[name="tahun"]').val();

                  $('select[name="rutin"]').empty();

                  if(id_lokasi && id_tahun) {
                                          $.ajax({
                        url: "{{url('/output/biaya-pemeliharaan/ajax4/')}}"+'/'+id_lokasi+"/"+id_tahun,
                        type: "GET",
                        dataType: "json",
                        success:function(data) {
                          console.log(data);
                          $.each(data, function(ad , value) {
                              $('select[name="rutin"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +'</option>');
                          });

                        }
                    });
                }else{
                    $('select[name="rutin"]').empty();

                }
              })
            })
          </script>

          <!-- <div class="form-group" style="margin-top: 5px;">
            <div class="form-group">
              <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Form 6 - Rutin</label>
                <div class="col-md-6 col-sm-4 col-xs-12">
                  <select class="form-control col-md-7 col-xs-12" name="rutin">
                    <option value="">- Pilih Form6 - Rutin -</option>
                      @isset($input_rutin)
                       <option value="{{$rutin}}" selected>{{$input_rutin->draft_versi}}</option>
                      @endisset
                  </select>
                  <input type="text" name="rutin" value="{{ isset($input_rutin) ? $input_rutin->draft_versi.' - '.$input_rutin->name : '' }}" class="form-control col-md-7 col-xs-12" readonly="readonly">
                </div>
            </div>
          </div> -->
<!--
            <div class="ln_solid"></div>

            <div class="form-group">
              <div >
                <button type="submit" class="btn btn-primary pull-right">
                    <span class="glyphicon glyphicon-search"> </span> cari
                </button>
              </div>
            </div>         -->

          </form>
        </div>
      </div>
    </div>
  </div>

<!-- Grafik  -->
<?php
  if ($lokasi) {

      $nl = $overhaul_nilai;

      $en = $engineering_nilai;

      $tr = $total - $nl - $en;
      if ($nl=="0" && $en=="0") {
        echo "";
      }else{
        $nl_persen = $nl/$total*100;
        $en_persen = $en/$total*100;
        $tr_persen = $tr/$total*100;
      }
    }
 ?>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
          <div id="rincian-aktifitas-pemeliharaan">
            <script type="text/javascript">
              Highcharts.chart('rincian-aktifitas-pemeliharaan', {
                  chart: {
                      plotBackgroundColor: null,
                      plotBorderWidth: null,
                      plotShadow: false,
                      type: 'pie'
                  },
                    title: {
                        text: 'Rincian Aktivitas Pemeliharaan Reimburse'
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.percentage:.1f}</b>'
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                                style: {
                                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                }
                            }
                        }
                    },
                    series: [{
                        name: 'Brands',
                        color: true,
                        data:[@if($lokasi ){
                               name: '<?php echo $overhaul.', '.Round($nl/1000000).'<br>'; ?>',
                               y: <?php if($nl == '0' && $en== '0'){echo "0";}else{echo $nl_persen;} ?>
                              },
                              {
                                name: '<?php echo $engineering.', '.Round($en/1000000).'<br>'; ?>',
                                y: <?php if($nl == '0' && $en== '0'){echo "0";}else{echo $en_persen;} ?>
                              },
                              {
                                name: '<?php echo 'Rutin Pemeliharaan'.', '.Round($tr/1000000).'<br>'; ?>',
                                y: <?php if($nl == '0' && $en== '0'){echo "0";}else{echo $tr_persen;} ?>
                              }
                              @endif]

                    }]
                });

            </script>
          </div>
      </div>
    </div>
  </div>
</div>


<!-- Table -->
    <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2 style="font-size: 18px; margin-right: 10px;">ANGGARAN BIAYA PEMELIHARAAN REIMBURSE PER AKTIVITAS</h2>
                    <h5 style="margin-top: 8px;"> (Dalam Ribuan Rupiah)</h5>
                    <div class="clearfix"></div>
                  </div>

                  <a href="{{Request::fullUrl()}}&download=excel" class="btn btn-success pull-right"><i class="fa fa-download"></i> Download Excel</a>
                  <a href="{{Request::fullUrl()}}&download=pdf" type="button" class="btn btn-primary pull-right" target="_blank"><i class="fa fa-download"></i> Download PDF</a>

                  <div class="x_content">
                    <table class="table table-striped table-bordered" style="font-size:11px;">
                      <thead style="background:#2A3F54;color:white;">
                        <tr>
                          <th style="vertical-align: middle;">Rincian Aktivitas</th>
                          <th>RKAP<br>{{$input_tahun}}</th>
                        </tr>
                        <tr>
                          <th>1</th>
                          <th>2</th>
                        </tr>
                      </thead>
                      <body>
                        @if($lokasi)
                            <tr>
                              <td style="text-align: left;">{{$oj}}</td>
                              <td style="text-align: right">
                                  {{number_format(round($oj_nilai), 0,',','.')}}
                              </td>
                            </tr>
                            <tr>
                              <td style="text-align: left;">{{$operasi}}</td>
                              <td style="text-align: right">
                                  {{number_format(round($nilai_operasi), 0,',','.')}}
                              </td>
                            </tr>
                            <tr>
                              <td style="text-align: left; background: #E8EDEF;"><?php echo $kimia .' & '.$lab; ?></td>
                              <td style="text-align: right; background: #E8EDEF;">
                                  {{number_format(round($nilai_kimia),0,',','.')}}
                              </td>
                            </tr>
                            <tr>
                              <td style="text-align: left">{{$k3}}</td>
                              <td style="text-align: right">
                                  {{number_format(round($nilai_k3),0,',','.')}}
                              </td>
                            </tr>
                            <tr>
                              <td style="text-align: left; background: #E8EDEF;">{{$lingkungan}}</td>
                              <td style="text-align: right; background: #E8EDEF;">
                                  {{number_format(round($nilai_lingkungan), 0,',','.')}}
                              </td>
                            </tr>
                            <tr>
                              <td style="text-align: left">{{$preventive}}</td>
                              <td style="text-align: right">
                                  {{number_format(round($nilai_preventive), 0,',','.')}}
                              </td>
                            </tr>
                            <tr>
                              <td style="text-align: left; background: #E8EDEF;">{{$predictive}}</td>
                              <td style="text-align: right; background: #E8EDEF;">
                                  {{number_format(round($nilai_predictive), 0,',','.')}}
                              </td>
                            </tr>
                            <tr>
                              <td style="text-align: left">{{$corective}}</td>
                              <td style="text-align: right">
                                  {{number_format(round($nilai_corective),0, ',','.')}}</td>
                            </tr>
                            <tr>
                              <td style="text-align: left">{{$emergency}}</td>
                              <td style="text-align: right">
                                  {{number_format(round($nilai_emergency),0, ',','.')}}</td>
                            </tr>
                            <tr>
                              <td style="text-align: left; background: #E8EDEF;"><?php echo $overhaul.' / '.$inspection; ?></td>
                              <td style="text-align: right; background: #E8EDEF;">
                                {{number_format(round($overhaul_nilai), 0,',','.')}}
                              </td>
                            </tr>
                            <tr>
                              <td style="text-align: left">{{$breakdown}}</td>
                              <td style="text-align: right">
                                  {{number_format(round($nilai_breakdown),0, ',','.')}}</td>
                            </tr>
                            <tr>
                              <td style="text-align: left"><?php echo $engineering.' / '.$project.' / '.$modifikasi; ?></td>
                              <td style="text-align: right">
                                  {{number_format(round($engineering_nilai), 0,',','.')}}
                              </td>
                            </tr>
                            <tr>
                              <td style="text-align: left; background: #E8EDEF;"><?php echo $non.' / '.$tu.' / '.$sarana; ?></td>
                              <td style="text-align: right; background: #E8EDEF;">
                                  {{number_format(round($nilai_non), 0,',','.')}}
                              </td>
                            </tr>
                            <tr>
                              <td style="background:#0F6F5B; color: white; text-align: left;">Total Pemeliharaan</td>
                              <td style="background:#0F6F5B; color: white; text-align: right;">
                                {{number_format(round($total),0, ',','.')}}
                              </td>
                            </tr>
                        @endif
                      </body>
                    </table>
                  </div>
                </div>
            </div>
          </div>


@endsection
