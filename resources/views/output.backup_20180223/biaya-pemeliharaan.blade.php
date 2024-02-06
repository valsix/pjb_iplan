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
    <h1> Biaya Pemeliharaan </h1>
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
                          <form method>
                              <div class="col-md-2"><label>Tahun Anggaran</label></div>
                              <div class="col-md-4">
                                    <select class="form-control" required="true" name="tahun">
                                      <option value="">- Pilih Tahun -</option>
                                       @for($i=2016;$i<=(date('Y')+1);$i++)
                                        <option value="{{$i}}"  @isset($tahun) @if($tahun == $i) selected @endif @endisset>{{$i}}</option>
                                       @endfor
                                    </select>
                                </div>
                              <div class="col-md-2"><label>Struktur Bisnis</label></div>
                              <div class="col-md-3">
                                  <select class="form-control" name="strategi_bisnis" required>
                                     <option value="">- Pilih Stuktur Bisnis -</option>
                                     @foreach ($Sbisnis as $sbs => $value)
                                      <option value="{{ $value->id }}" @isset($input_sb) @if($input_sb->name == $value->name) selected @endif @endisset> {{ $value->name }} </option>
                                     @endforeach
                                  </select>
                              </div>
                             
                              <br>
                              <br>
                              <div class="col-md-2"><label>Distrik</label></div>
                              <div class="col-md-4">
                                  <select class="form-control" name="distrik" required>
                                     <option value="">- Pilih Distrik -</option>
                                       @isset($distrik) <option value="{{$input_distrik}}" selected> {{$distrik->name}}</option> @endisset
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


                              <div class="col-md-2"><label> Lokasi</label></div>
                              <div class="col-md-3">
                                  <select class="form-control" name="lokasi" id="lokasi" required>
                                     <option value="">- Pilih Lokasi -</option>
                                       @isset($input_lokasi) <option value="{{$lokasi}}" selected> {{$input_lokasi->name}}</option> @endisset
                                     
                                  </select>
                              </div><br><br>

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
                             <div class="col-md-2"><label>Fase</label></div>
                              <div class="col-md-4">
                                  <select class="form-control" name="fase" required>
                                      <option value="">- Pilih Fase -</option>
                                        @foreach($fs as $f)
                                          <option value="{{$f->id}}" @isset($input_fase) @if($input_fase->name == $f->name) selected @endif @endisset>{{$f->name}}</option>
                                        @endforeach
                                  </select>
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

                              <div class="col-md-2"><label>Form 6 - Reimburse</label></div>
                              <div class="col-md-3">
                                  <select class="form-control" name="reimburse">
                                     <option value="">- Pilih Form6 - Reimburse -</option>
                                     @isset($input_reimburse) <option value="{{$reimburse}}" selected> {{$input_reimburse->draft_versi}}</option> @endisset
                                  </select>
                              </div><br><br>
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
                              <div class="col-md-6"></div>
                              <div class="col-md-2"><label>Form 6 - Rutin</label></div>
                              <div class="col-md-3">
                                  <select class="form-control" name="rutin">
                                     <option value="">- Pilih Form6 - Rutin -</option>
                                    @isset($input_rutin)<option value="{{$rutin}}" selected>{{$input_rutin->draft_versi}}</option>@endisset
                                  </select>
                              </div>
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
<?php 
  if ($lokasi) {
      foreach ($overhaul_nilai as $key) {
        $nl = $key->value;
      }
      foreach ($engineering_nilai as $engin) {
        $en = $engin->value;
      }

      $tr = $subtotal - $nl - $en;
      if ($nl=="0" && $en=="0") {
        echo "";
      }else{
        $nl_persen = $nl/$subtotal*100;
        $en_persen = $en/$subtotal*100;
        $tr_persen = $tr/$subtotal*100;
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
                        text: 'Rincian Aktivitas Pemeliharaan'
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
                    <h2>Program Investasi</h2>
                    <div class="clearfix"></div>
                  </div>

                  <a href="{{Request::fullUrl()}}&download=excel" class="btn btn-success pull-right"><i class="fa fa-download"></i> Download Excel</a>
                  <a href="{{Request::fullUrl()}}&download=pdf" type="button" class="btn btn-primary pull-right" target="_blank"><i class="fa fa-download"></i> Download PDF</a>

                  <div class="x_content">
                    <table class="table table-striped table-bordered">
                      <thead style="background:#2A3F54;color:white;">
                        <tr>
                          <th>Rincian Aktivitas</th>
                          <th>RKAP <br>{{$input_tahun}}</th>
                        </tr>
                        <tr>
                          <th>2</th>
                          <th>4</th>
                        </tr>
                      </thead>
                      <body>
                        @if($lokasi)
                          @foreach($overhaul_nilai as $n)
                            <tr>
                              <td style="text-align: left; background: #E8EDEF;"><?php echo $overhaul.' / '.$inspection; ?></td>
                              <td style="text-align: right; background: #E8EDEF;">
                                <?php $isi = $n->value;?>
                                {{number_format($isi, 0,',','.')}}
                              </td>
                            </tr> 
                            <tr>
                              <td style="text-align: left"><?php echo $engineering.' / '.$project.' / '.$modifikasi; ?></td>
                              <td style="text-align: right">
                                @foreach($engineering_nilai as $r)
                                  <?php $isi1 = $r->value ?>
                                  {{number_format($isi1, 0,',','.')}}
                                @endforeach
                              </td>
                            </tr>
                            <tr>
                              <td style="text-align: left; background: #E8EDEF;"><?php echo $non.' / '.$tu.' / '.$sarana; ?></td>
                              <td style="text-align: right; background: #E8EDEF;">
                                @foreach($nilai_non as $non)
                                  <?php $isi2 = $non->value ?>
                                  {{number_format($isi2, 0,',','.')}} 
                                @endforeach
                              </td>
                            </tr>
                            <tr>
                              <td style="text-align: left;">{{$operasi}}</td>
                              <td style="text-align: right">
                                @foreach($nilai_operasi as $kop)
                                  <?php $isi3 = $kop->value; ?>
                                  {{number_format($isi3, 0,',','.')}}
                                @endforeach
                              </td>
                            </tr>
                            <tr>
                              <td style="text-align: left; background: #E8EDEF;"><?php echo $kimia .' & '.$lab; ?></td>
                              <td style="text-align: right; background: #E8EDEF;">
                                @foreach($nilai_kimia as $kim)
                                  <?php $isi4 = Round($kim->value); ?>
                                  {{number_format($isi4,0,',','.')}}
                                @endforeach
                              </td>
                            </tr>
                            <tr>
                              <td style="text-align: left">{{$k3}}</td>
                              <td style="text-align: right">
                                @foreach($nilai_k3 as $n)
                                  <?php $isi5 = Round($n->value); ?>
                                  {{number_format($isi5,0,',','.')}}
                                @endforeach
                              </td>
                            </tr>
                            <tr>
                              <td style="text-align: left; background: #E8EDEF;">{{$lingkungan}}</td>
                              <td style="text-align: right; background: #E8EDEF;">
                                @foreach($nilai_lingkungan as $ni) 
                                  <?php $isi6 = Round($ni->value); ?>
                                  {{number_format($isi6, 0,',','.')}} 
                                @endforeach
                              </td>
                            </tr>
                            <tr>
                              <td style="text-align: left">{{$preventive}}</td>
                              <td style="text-align: right">
                                @foreach($nilai_preventive as $prev)
                                  <?php $isi7 = Round($prev->value); ?>
                                  {{number_format($isi7, 0,',','.')}}
                                @endforeach
                              </td>
                            </tr>
                            <tr>
                              <td style="text-align: left; background: #E8EDEF;">{{$predictive}}</td>
                              <td style="text-align: right; background: #E8EDEF;">
                                @foreach($nilai_predictive as $pred)
                                  <?php $isi8 = Round($pred->value); ?>
                                  {{number_format($isi8, 0,',','.')}}
                                @endforeach
                              </td>
                            </tr>
                             <tr>
                              <td style="text-align: left">{{$corective}}</td>
                              <td style="text-align: right">
                                @foreach($nilai_corective as $ped)
                                  <?php $isi9 = Round($ped->value); ?>
                                  {{number_format($isi9,0, ',','.')}}
                                @endforeach</td>
                            </tr>
                            <tr>
                              <td style="background:#0F6F5B; color: white; text-align: left;">Total Pemeliharaan</td>
                              <td style="background:#0F6F5B; color: white; text-align: right;">
                                <?php $totalS = Round($subtotal); ?>
                                {{number_format($totalS, 0,',','.')}}
                              </td>
                            </tr>
                          @endforeach
                        @endif
                      </body>
                    </table>
                  </div>
                </div>
            </div>
          </div>
  

@endsection

