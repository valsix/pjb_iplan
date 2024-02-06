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
           width: calc( 100% - 1em )
        }

    </style>

@endsection

@section('content')
    <h1> Rincian Biaya Pegawai </h1>
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
                                                    // url: '/output/rincian-biaya-har/ajax/'+strategi_bisnisID,
                                                    url: "{{ url('/output/rincian-biaya-pegawai/ajax/') }}/"+strategi_bisnisID,
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
                                                url: "{{ url('/output/rincian-biaya-pegawai/ajax2') }}"+"/"+lokasiID,
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

                                <div class="col-md-2"><label>Draft RKAU</label></div>
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
                                             url: "{{ url('/output/rincian-biaya-pegawai/ajax3') }}"+"/"+id_lokasi+"/"+id_tahun,
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
    </div>


    <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Anggaran Biaya Kepegawaian</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    <!-- <a href="/output/rincian-biaya-pegawai/downloadpdf" class="btn btn-primary pull-right"><i class="fa fa-download"></i>Download PDF</a> -->

                    <a href="{{ Request::fullUrl() }}&download=rincian-biaya-pegawai&type=excel" id="get-excel" class="btn btn-success pull-right"><i class="fa fa-download"></i> Download Excel</a>

                    <a href="{{ Request::fullUrl() }}&download=rincian-biaya-pegawai&type=pdf" id="get-pdf" class="btn btn-primary pull-right"><i class="fa fa-download"></i> Download PDF</a>

                    <!-- <button id="exportButton" class="btn btn-lg btn-danger clearfix"><span class="fa fa-file-pdf-o"></span> Export to PDF</button> -->

                    <!-- <a href="/output/rincian-biaya-pegawai/downloadexcel" class="btn btn-success pull-right"><i class="fa fa-download"></i> Download Excel</a>                   -->

                    <?php
                      $index_tabel = 1;
                    ?>

                    <table id="table-biaya-pegawai" class="table table-striped table-bordered">
                      <thead style="background:#2A3F54;color:white;">
                      <tr>
                         <th rowspan="2" class="col-md-1" style="vertical-align: middle;">NO</th>
                         <th rowspan="2" class="col-md-2" style="vertical-align: middle;">No PRK</th>
                         <th style="vertical-align: middle;" class="col-md-5">RINCIAN</th>
                         <th class="col-md-2">Prak Real <br> {{$input_tahun-1}}</th>
                         <th class="col-md-2">RKAP <br> {{$input_tahun}}</th>

                      <tr>
                         <th>1</th>
                         <th>2</th>
                         <th>3</th>
                      </tr>
                      </tr>
                      </thead>
                      <body>
                        {{-- sub jumlah (A) --}}
                        <?php
                          $subJumlahA1 = 0;
                          $subJumlahA2 = 0;
                          $subJumlahB1 = 0;
                          $subJumlahB2 = 0;
                          $subJumlahC1 = 0;
                          $subJumlahC2 = 0;



                          if ($input_tahun != NULL && $input_lokasi != NULL && $input_fase != NULL && $input_draft != NULL) {
                            for ($i = 0 ; $i < $countA ; $i++) { ?>
                              <tr>
                                <td class="col-md-1">{{ $number[$i]->value }}</td>
                                <td class="col-md-2" style="text-align: center;">{{ $noprk[$i]->value }}</td>
                                <td style="text-align: left;"  class="col-md-5">{{ $newRincian[$i] }}</td>
                                <td style="text-align: right" class="col-md-2">{{ number_format($fixEstimasi[$i],0, ",",".") }}</td>
                                <td style="text-align: right" class="col-md-2">{{ number_format($fixRkap[$i],0, ",",".") }}</td>
                              </tr>

                              <?php
                                $subJumlahA1 += $fixEstimasi[$i];
                                $subJumlahA2 += $fixRkap[$i];
                                $j = $i+1;
                              ?>
                        <?php
                            }
                            $totalEstimasi = 0;
                            $totalRkap = 0;
                            foreach ($fixEstimasi as $key => $value) {
                              $totalEstimasi += $fixEstimasi[$key];
                              $totalRkap += $fixRkap[$key];
                            }
                            ?>
                            <tr style="background:#4682B4;color:white;">
                              <td class="col-md-1"></td>
                              <td class="col-md-2" style="text-align: center;"></td>
                              <td style="text-align: center;"  class="col-md-5"><b>Sub Jumlah Dalam Bentuk Kompensasi Pegawai (A)</b></td>
                              <td style="text-align: right" ><b>{{ number_format($subJumlahA1,0, ",",".") }}</b></td>
                              <td style="text-align: right" ><b>{{ number_format($subJumlahA2,0, ",",".") }}</b></td>
                            </tr>
                            <tr>
                              <td class="col-md-1"></td>
                              <td class="col-md-2" style="text-align: center;"></td>
                              <td style="text-align: left;"  class="col-md-5"></td>
                              <td style="text-align: right" ><br/></td>
                              <td style="text-align: right" ></td>
                            </tr>

                            <?php
                              for ($j ; $j < $countB-1 ; $j++) { ?>
                                <tr>
                                  <td class="col-md-1" >{{ $number[$j]->value }}</td>
                                  <td class="col-md-2" style="text-align: center;">{{ $noprk[$j]->value }}</td>
                                  <td style="text-align: left;"  class="col-md-5">{{ $newRincian[$j] }}</td>
                                  <td style="text-align: right" >{{ number_format($fixEstimasi[$j],0, ",",".") }}</td>
                                  <td style="text-align: right" >{{ number_format($fixRkap[$j],0, ",",".") }}</td>
                                </tr>

                                <?php
                                  $subJumlahB1 += $fixEstimasi[$j];
                                  $subJumlahB2 += $fixRkap[$j];
                                  $k = $j+1;
                                ?>
                            <?php
                              }
                            ?>
                              <tr style="background:#4682B4;color:white;">
                                <td class="col-md-1"></td>
                                <td class="col-md-2" style="text-align: center;"></td>
                                <td style="text-align: center;"  class="col-md-5"><b>Sub Jumlah Dalam Bentuk Manfaat Pegawai (B)</b></td>
                                <td style="text-align: right" ><b>{{ number_format($subJumlahB1,0, ",",".") }}</b></td>
                                <td style="text-align: right" ><b>{{ number_format($subJumlahB2,0, ",",".") }}</b></td>
                              </tr>
                              <tr>
                                <td class="col-md-1"></td>
                                <td class="col-md-2" style="text-align: center;"></td>
                                <td style="text-align: left;"  class="col-md-5"></td>
                                <td style="text-align: right" ><br/></td>
                                <td style="text-align: right" ></td>
                              </tr>

                            <?php
                              for ($k ; $k < $countC-2 ; $k++) { ?>
                                <tr>
                                  <td class="col-md-1">{{ $number[$k]->value }}</td>
                                  <td class="col-md-2" style="text-align: center;">{{ $noprk[$k]->value }}</td>
                                  <td style="text-align: left;"  class="col-md-5">{{ $newRincian[$k] }}</td>
                                  <td style="text-align: right" >{{ number_format($fixEstimasi[$k],0, ",",".") }}</td>
                                  <td style="text-align: right" >{{ number_format($fixRkap[$k],0, ",",".") }}</td>
                                </tr>
                                <?php
                                  $subJumlahC1 += $fixEstimasi[$k];
                                  $subJumlahC2 += $fixRkap[$k];
                                ?>
                            <?php
                              }
                            ?>
                            <tr style="background:#4682B4;color:white;">
                              <td class="col-md-1"></td>
                              <td class="col-md-2" style="text-align: center;"></td>
                              <td style="text-align: center;"  class="col-md-5"><b>Sub Jumlah Dalam Bentuk Diklat dan Lainnya (C)</b></td>
                              <td style="text-align: right" ><b>{{ number_format($subJumlahC1,0, ",",".") }}</b></td>
                              <td style="text-align: right" ><b>{{ number_format($subJumlahC2,0, ",",".") }}</b></td>
                            </tr>

                            <tr>
                              <td class="col-md-1"></td>
                              <td class="col-md-2"></td>
                              <td style="text-align: center;"  class="col-md-5"></td>
                              <td style="text-align: right" ><br/></td>
                              <td style="text-align: right" ></td>
                            </tr>

                            <tr style="background:#2A3F54;color:white;">
                              <td class="col-md-1"></td>
                              <td class="col-md-2" style="text-align: center;"></td>
                              <td style="text-align: center"  class="col-md-5"><b>JUMLAH BIAYA</b></td>
                              <td style="text-align: right" >{{ number_format($totalEstimasi,0, ",",".") }}</td>
                              <td style="text-align: right" >{{ number_format($totalRkap,0, ",",".") }}</td>
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
        </div>


<!-- you need to include the shieldui css and js assets in order for the components to work -->
<!-- <link rel="stylesheet" type="text/css" href="http://www.shieldui.com/shared/components/latest/css/light/all.min.css" /> -->

@endsection

@section('js_page')
<script type="text/javascript">
  $(document).ready(function() {
    var table = $('#table-biaya-pegawai').DataTable( {
        "aLengthMenu": [[10, 25, 50, 100, -1],
        [10, 25, 50, 100, "All"]],
        "scrollY": "800px",
        "scrollX": "300px",
        "scrollCollapse": true,
        pagingType: "full_numbers",
        fixedHeader: true,
        ordering: false
    } );

</script>
@endsection
