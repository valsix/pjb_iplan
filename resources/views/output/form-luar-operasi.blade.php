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

        tbody {
           display:block;
           height:500px;
           overflow:auto;
        }

        thead, tbody tr {
           display:table;
           width:100%;
           table-layout:fixed;
        }

        thead {
           width: calc( 100% - 1.3em )
        }

        /*Update line height & font-size*/
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
    <h3> RINCIAN BIAYA LUAR OPERASI </h3>
    <div class="row">
<!--     <div class="col-md-12 col-sm-12 col-xs-12">

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
                                       <option>- Pilih Tahun -</option>
                                              @foreach($tahun as $th)
                                                  <option value="{{$th->tahun}}" @if($input_tahun == $th->tahun) {{'selected'}} @endif>{{$th->tahun}}</option>
                                              @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2"><label>Struktur Bisnis</label></div>
                                <div class="col-md-3">
                                    <select class="form-control" name="strategi_bisnis">
                                       <option>- Pilih Struktur Bisnis -</option>
                                            @foreach ($sb as $sbs => $value)
                                                <option value="{{ $value->id }}" <?php if($input_sb!=null) echo($input_sb->id == $value->id ? 'selected' : '')?> > {{ $value->name }} </option>
                                            @endforeach
                                    </select>
                                </div>

                                <br>
                                <br>
                                <div class="col-md-2"><label>Distrik</label></div>
                                <div class="col-md-4">
                                    <select class="form-control" name="distrik">
                                      <option>- Pilih Distrik -</option>
                                            @if($input_sb!=null && $input_distrik!=null)
                                                @foreach($distrik as $d)
                                                <option value="{{$d->id}}" <?php echo($d->id == $input_distrik->id ? 'selected' : '')?>>{{$d->name}}</option>
                                                @endforeach
                                            @endif
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
                                                    url: "{{ url('output/form-luar-operasi/ajax/') }}/"+strategi_bisnisID,
                                                    type: "GET",
                                                    dataType: "json",
                                                    success:function(data) {
                                                // console.log(data);
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


                                <div class="col-md-2"><label> Lokasi</label></div>
                                <div class="col-md-3">
                                    <select class="form-control" name="lokasi">
                                       <option>- Pilih Lokasi -</option>
                                           @if($input_sb!=null && $input_distrik!=null && $input_lokasi!=null)
                                                @foreach($lokasi as $l)
                                                <option value="{{$l->id}}" <?php echo($l->id == $input_lokasi->id ? 'selected' : '')?>>{{$l->name}}</option>
                                                @endforeach
                                            @endif
                                    </select>
                                </div>

                                <script type="text/javascript">
                                  function check() {
                                        var lokasiID = $(this).val();
                                        $('select[name="lokasi"]').empty();

                                        if(lokasiID) {
                                            $.ajax({
                                                url: "{{ url('/output/form-luar-operasi/ajax2') }}"+"/"+lokasiID,
                                                type: "GET",
                                                dataType: "json",
                                                success:function(data) {

                                                  $('select[name="lokasi"]').empty();
                                                  $('select[name="lokasi"]').append('<option selected="" value="" disabled="">Pilih</option>');
                                                  console.log(data);
                                                  // $('select[name="lokasi"]').append('<option value="">==Silahkan Pilih Lokasi==</option>');
                                                  $.each(data, function(ad , value) {
                                                  console.log(ad);
                                                      $('select[name="lokasi"]').append('<option value="'+ value["id"] +'">'+ value["name"] +'</option>');
                                                  });

                                                }
                                            });
                                        }else{
                                            $('select[name="lokasi"]').empty();

                                        }
                                    }
                                    $(document).ready(function() {
                                        $('select[name="distrik"]').on('change', check);
                                        $('select[name="distrik"]').on('click', check);
                                    });
                                </script>

                                <br>
                                <br>
                                <div class="col-md-2"><label>Fase</label></div>
                                <div class="col-md-4">
                                    <select class="form-control" name="fase">
                                       <option>- Pilih Fase -</option>
                                          @foreach ($fase as $fases => $value)
                                              <option value="{{ $value->id }}" <?php if($input_fase!= null) echo($input_fase->id == $value->id ? 'selected' : '')?>> {{ $value->name }} </option>
                                          @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2"><label>Draft</label></div>
                                <div class="col-md-3">
                                    <select class="form-control" name="draft_rkau">
                                      <option>- Pilih Draft -</option>
                                            @if($input_sb!=null && $input_draft!=null && $input_lokasi!=null)
                                                @foreach($drafts as $d)
                                                    <option value="{{$d->id}}" <?php echo($d->id == $input_draft->id ? 'selected' : '')?>>{{$d->draft_versi}}</option>
                                                @endforeach
                                            @endif
                                    </select>
                                </div>

                                <script type="text/javascript">
                                  $(document).ready(function() {
                                    $('select[name="lokasi"]').on('change', function() {
                                      var id_lokasi = $(this).val();
                                      var id_tahun = $('select[name="tahun_anggaran"]').val();

                                      $('select[name="draft_rkau"]').empty();

                                      if(id_lokasi && id_tahun) {
                                          $.ajax({
                                             url: "{{ url('/output/form-luar-operasi/ajax3') }}"+"/"+id_lokasi+"/"+id_tahun,
                                              type: "GET",
                                              dataType: "json",
                                              success:function(data) {

                                                $.each(data, function(ad , value) {
                                                    $('select[name="draft_rkau"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +'</option>');
                                                });

                                              }
                                          });
                                      }else{
                                          $('select[name="draft_rkau"]').empty();

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
    </div> -->

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
        <form  class="form-horizontal form-label-left">

          <div class="form-group">
            <label class="control-label col-md-2 col-sm-3 col-xs-12">Tahun Anggaran</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <!-- <select class="form-control col-md-7 col-xs-12" name="tahun_anggaran">
                <option>- Pilih Tahun -</option>
                  @foreach($tahun as $th)
                    <option value="{{$th->tahun}}" @if($input_tahun == $th->tahun) {{'selected'}} @endif>{{$th->tahun}}</option>
                  @endforeach
              </select> -->
              <input type="text" name="tahun_anggaran" class="form-control col-md-7 col-xs-12" value="{{$input_tahun}}" readonly="readonly" />
            </div>

            <div class="form-group">
            <label class="control-label col-md-2 col-sm-3 col-xs-12">Struktur Bisnis</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <!-- <select class="form-control col-md-7 col-xs-12" name="strategi_bisnis">
                <option>- Pilih Struktur Bisnis -</option>
                  @foreach ($sb as $sbs => $value)
                    <option value="{{ $value->id }}" <?php if($input_sb!=null) echo($input_sb->id == $value->id ? 'selected' : '')?> > {{ $value->name }} </option>
                  @endforeach
              </select> -->
              <input type="text" name="strategi_bisnis" class="form-control col-md-7 col-xs-12" value="{{($input_sb)? $input_sb->name : '' }}" readonly="readonly" />
            </div>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-2 col-sm-3 col-xs-12" >Distrik</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <!-- <select class="form-control col-md-7 col-xs-12"  name="distrik">
                <option>- Pilih Distrik -</option>
                  @if($input_sb!=null && $input_distrik!=null)
                    @foreach($distrik as $d)
                      <option value="{{$d->id}}" <?php echo($d->id == $input_distrik->id ? 'selected' : '')?>>{{$d->name}}</option>
                    @endforeach
                  @endif
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
                                url: "{{ url('output/form-luar-operasi/ajax/') }}/"+strategi_bisnisID,
                                type: "GET",
                                dataType: "json",
                                success:function(data) {
                            // console.log(data);
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
              <!-- <select class="form-control col-md-7 col-xs-12" name="lokasi">
                <option>- Pilih Lokasi -</option>
                  @if($input_sb!=null && $input_distrik!=null && $input_lokasi!=null)
                      @foreach($lokasi as $l)
                        <option value="{{$l->id}}" <?php echo($l->id == $input_lokasi->id ? 'selected' : '')?>>{{$l->name}}</option>
                      @endforeach
                  @endif
              </select> -->
              <input type="text" name="lokasi" class="form-control col-md-7 col-xs-12" value="{{($input_lokasi)? $input_lokasi->name : ''}}" readonly="readonly" />
            </div>
          </div>
          </div>

          <script type="text/javascript">
            function check() {
                  var lokasiID = $(this).val();
                  $('select[name="lokasi"]').empty();

                  if(lokasiID) {
                      $.ajax({
                          url: "{{ url('/output/form-luar-operasi/ajax2') }}"+"/"+lokasiID,
                          type: "GET",
                          dataType: "json",
                          success:function(data) {

                            $('select[name="lokasi"]').empty();
                            $('select[name="lokasi"]').append('<option selected="" value="" disabled="">Pilih</option>');
                            console.log(data);
                            // $('select[name="lokasi"]').append('<option value="">==Silahkan Pilih Lokasi==</option>');
                            $.each(data, function(ad , value) {
                            console.log(ad);
                                $('select[name="lokasi"]').append('<option value="'+ value["id"] +'">'+ value["name"] +'</option>');
                            });

                          }
                      });
                  }else{
                      $('select[name="lokasi"]').empty();

                  }
              }
              $(document).ready(function() {
                  $('select[name="distrik"]').on('change', check);
                  $('select[name="distrik"]').on('click', check);
              });
          </script>

          <div class="form-group">
            <label for="middle-name" class="control-label col-md-2 col-sm-3 col-xs-12">Fase</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <!-- <select class="form-control col-md-7 col-xs-12" name="fase">
                <option>- Pilih Fase -</option>
                  @foreach ($fase as $fases => $value)
                    <option value="{{ $value->id }}" <?php if($input_fase!= null) echo($input_fase->id == $value->id ? 'selected' : '')?>> {{ $value->name }} </option>
                  @endforeach
              </select> -->
              <input type="text" name="fase" class="form-control col-md-7 col-xs-12" value="{{isset($input_fase) ? $input_fase->name : '' }}" readonly="readonly" />
            </div>

            <div class="form-group">
            <label class="control-label col-md-2 col-sm-3 col-xs-12">Draft RKAU</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <!-- <select class="form-control col-md-7 col-xs-12" name="draft_rkau">
                <option>- Pilih Draft -</option>
                  @if($input_sb!=null && $input_draft!=null && $input_lokasi!=null)
                    @foreach($drafts as $d)
                      <option value="{{$d->id}}" <?php echo($d->id == $input_draft->id ? 'selected' : '')?>>{{$d->draft_versi}}</option>
                    @endforeach
                  @endif
              </select> -->
              <input type="text" name="draft_rkau" class="form-control col-md-12 col-xs-12" value="{{($input_draft!=null) ? $input_draft->draft_versi.' - '.$input_draft->name : ''}}" readonly="readonly" />
            </div>
            </div>
          </div>

          <script type="text/javascript">
            $(document).ready(function() {
              $('select[name="lokasi"]').on('change', function() {
                var id_lokasi = $(this).val();
                var id_tahun = $('select[name="tahun_anggaran"]').val();

                $('select[name="draft_rkau"]').empty();

                if(id_lokasi && id_tahun) {
                    $.ajax({
                        url: "{{ url('/output/form-luar-operasi/ajax3') }}"+"/"+id_lokasi+"/"+id_tahun,
                        type: "GET",
                        dataType: "json",
                        success:function(data) {

                          $.each(data, function(ad , value) {
                              $('select[name="draft_rkau"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +'</option>');
                          });

                        }
                    });
                }else{
                    $('select[name="draft_rkau"]').empty();

                }
              })
            })
          </script>

          <!-- <div class="ln_solid"></div>

          <div class="form-group">
            <div >
              <button type="submit" class="btn btn-primary pull-right">
                  <span class="glyphicon glyphicon-search"> </span> cari
              </button>
            </div>
          </div> -->

            </form>
          </div>
        </div>
      </div>
    </div>


    <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2 style="font-size: 18px; margin-right: 10px;">PENDAPATAN DAN BEBAN DILUAR OPERASI</h2>
                    <h5 style="margin-top: 8px;"> (Dalam Ribuan Rupiah)</h5>
                    <div class="clearfix"></div>
                  </div>

                  <a href="{{ Request::fullUrl() }}&download=rincian-biaya-administrasi&type=excel" id="get-excel" class="btn btn-success pull-right"><i class="fa fa-download"></i> Download Excel</a>

                  <a href="{{ Request::fullUrl() }}&download=rincian-biaya-administrasi&type=pdf" id="get-pdf" class="btn btn-primary pull-right"><i class="fa fa-download"></i> Download PDF</a>

                  <!-- <a href="/output/form-luar-operasi/downloadpdf" class="btn btn-primary pull-right"><i class="fa fa-download"></i>Download PDF</a>

                    <a href="/output/form-luar-operasi/downloadexcel" class="btn btn-success pull-right"><i class="fa fa-download"></i> Download Excel</a>                   -->

                  <div class="x_content">
                    <table id="datatable-from-luar-operasi" class="table table-striped table-bordered" style="font-size:11px;">
                      <thead style="background:#2A3F54;color:white;">
                      <tr>
                         <th rowspan="4" class="col-md-1" style="vertical-align: middle;">NO</th>
                         <th rowspan="4" class="col-md-2" style="vertical-align: middle;">No PRK</th>
                         <th rowspan="3">RINCIAN</th>
                         <th rowspan="3" class="col-md-2">RKAP <br> {{ $input_tahun }}</th>
                      </tr>
                      <tr>
                      </tr>
                      <tr>
                      </tr>
                      <tr>
                         <th>1</th>
                         <th>2</th>
                      </tr>
                      </thead>
                      <body>
                        <tr>
                          <td class="col-md-1"></td>
                          <td class="col-md-2"></td>
                          <td>PENDAPATAN</td>
                          <td class="col-md-2"></td>
                        </tr>
                        {{-- pendapatan --}}
                        <?php
                          if ($input_tahun != NULL && $input_lokasi != NULL && $input_fase != NULL && $input_draft != NULL) {
                            $baris = 0;
                            $totalRkapPendapatan = 0;
                            for ($i = 0 ; $i < $countPendapatan-1 ; $i++) { ?>
                              <?php $baris = $baris+1; $warna=($baris % 2 == 0) ? "#ffffff" : "#E8EDEF"; ?>
                              <tr style="background-color: <?= $warna?>">
                                <td class="col-md-1">{{ $number[$i]->value }}</td>
                                <td class="col-md-2" style="text-align: center;">{{ $noprk[$i]->value }}</td>
                                <td>{{ $newRincian[$i] }}</td>
                                <td style="text-align: right;" class="col-md-2">{{ number_format(round($newRkap[$i]),0, ",",".") }}</td>
                              </tr>

                              <?php
                                // $totalRkapPendapatan = 0;
                                // foreach ($newRkap as $key => $value) 
								// 20190304 Edit by FFR
								if($i >= 5) {
                                  $totalRkapPendapatan += $newRkap[$i];
								 }
                                // }
                              ?>
                        <?php
                            $tempI = $i;
                            }
                            ?>
                            <tr style="background:#79c1a9;color:black;">
                              <td class="col-md-1"></td>
                              <td class="col-md-2"></td>
                              <td>JUMLAH PENDAPATAN</td>
                              <td style="text-align: right;" class="col-md-2">{{ number_format($totalRkapPendapatan,0, ",",".") }}</td>
                            </tr>
                            <?php $baris = $baris+1; $warna=($baris % 2 == 0) ? "#ffffff" : "#E8EDEF"; ?>
                            <tr style="background-color: <?= $warna?>">
                              <td class="col-md-1"></td>
                              <td class="col-md-2"></td>
                              <td><br/></td>
                              <td class="col-md-2"></td>
                            </tr>
                            <?php $baris = $baris+1; $warna=($baris % 2 == 0) ? "#ffffff" : "#E8EDEF"; ?>
                            <tr style="background-color: <?= $warna?>">
                              <td class="col-md-1"></td>
                              <td class="col-md-2"></td>
                              <td>BEBAN</td>
                              <td class="col-md-2"></td>
                            </tr>
                            <?php
                              for ($j = $tempI+1 ; $j < count($newRincian) ; $j++) { ?>
                                <?php $baris = $baris+1; $warna=($baris % 2 == 0) ? "#ffffff" : "#E8EDEF"; ?>
                                <tr style="background-color: <?= $warna?>">
                                  <td class="col-md-1">{{ $number[$j]->value }}</td>
                                  <td class="col-md-2" style="text-align: center;">{{ $noprk[$j]->value }}</td>
                                  <td>{{ $newRincian[$j] }}</td>
                                  <td style="text-align: right;" class="col-md-2">
                                  @if(empty($newRkap[$j]))
                                  {{ '0' }}
                                  @elseif ($newRkap[$j]<'0')
                                  {{ '('.number_format(abs(round($newRkap[$j])),0, ",",".").')' }}
                                  @else
                                  {{ number_format(round($newRkap[$j]),0, ",",".") }}
                                  @endif
                                  </td>
                                </tr>

                            <?php
                              }

                                  $totalRkapBeban = 0;
                                  for ($j = $tempI+1 ; $j < count($newRincian) ; $j++) {
                                    $totalRkapBeban += $newRkap[$j];
                                  }

                            ?>
                            <tr style="background:#79c1a9;color:black;">
                              <td class="col-md-1"></td>
                              <td class="col-md-2"></td>
                              <td>JUMLAH BEBAN</td>
                              <td style="text-align: right;">
                                @if(empty($totalRkapBeban))
                                  {{ '0' }}
                                @elseif ($totalRkapBeban<'0')
                                  {{ '('.number_format(abs(round($totalRkapBeban)),0, ",",".").')' }}
                                @else
                                  {{ number_format(round($totalRkapBeban),0, ",",".") }}
                                @endif
                              </td>
                            </tr>
                            <?php $baris = $baris+1; $warna=($baris % 2 == 0) ? "#ffffff" : "#E8EDEF"; ?>
                            <tr style="background-color: <?= $warna?>">
                              <td class="col-md-1"></td>
                              <td class="col-md-2"></td>
                              <td><br/></td>
                              <td class="col-md-2"></td>
                            </tr>
                            <?php
                              $totalSemua = $totalRkapPendapatan + $totalRkapBeban;
                            ?>
                            <tr style="background:#0f6f5b;color:white;">
                              <td class="col-md-1"></td>
                              <td class="col-md-2"></td>
                              <td>TOTAL</td>
                              <td style="text-align: right;" class="col-md-2">
                                @if(empty($totalSemua))
                                  {{ '0' }}
                                @elseif ($totalSemua<'0')
                                  {{ '('.number_format(abs(round($totalSemua)),0, ",",".").')' }}
                                @else
                                  {{ number_format(round($totalSemua),0, ",",".") }}
                                @endif
                              </td>
                            </tr>
                        <?php
                          }
                        ?>

                      </body>


                    </table>
                  </div>
                </div>
            </div>
          </div>
        </div>

@endsection
