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
        td {
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
    <h3> RENCANA KERJA</h3>
    <div class="row">

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
                  <option value="">- Pilih Tahun -</option>
                    @for($i=2017;$i<=(date('Y-m-d')+1);$i++)
                      <option value="{{$i}}" @isset($input_tahun) @if($input_tahun == $i) selected @endif @endisset>{{$i}}</option>
                    @endfor
              </select> -->
              <input type="text" name="tahun_anggaran" class="form-control col-md-7 col-xs-12" value="{{$input_tahun}}" readonly="readonly" />
            </div>

            <div class="form-group">
            <label class="control-label col-md-2 col-sm-3 col-xs-12">Struktur Bisnis</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
             <!--  <select class="form-control col-md-7 col-xs-12" name="strategi_bisnis">
                <option value="">- Pilih Struktur Bisnis -</option>
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
              <!-- <select class="form-control col-md-7 col-xs-12" name="distrik">
                <option value="">- Pilih Distrik -</option>
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
                        $('select[name="lokasi"]').append('<option value="">- Pilih Lokasi -</option>');

                        if(strategi_bisnisID) {
                            $.ajax({
                            url: "{{url('/output/rencana-kinerja/ajax/')}}/"+strategi_bisnisID,
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
              <!-- <select class="form-control col-md-7 col-xs-12"  name="lokasi">
                  <option value="">- Pilih Lokasi -</option>
                    @isset($input_lokasi)
                      <option value="{{$ilokasi}}" selected> {{$input_lokasi->name}}</option>
                    @endisset
              </select> -->
              <input type="text" name="lokasi" class="form-control col-md-7 col-xs-12" value="{{($input_lokasi)? $input_lokasi->name : ''}}" readonly="readonly" />
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
                            url: "{{url('/output/rincian-biaya-har/ajax2/')}}/"+lokasiID,
                            type: "GET",
                            dataType: "json",
                            success:function(data) {

                              $('select[name="lokasi"]').empty();
                              console.log(data);
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

          <div class="form-group">
            <label for="middle-name" class="control-label col-md-2 col-sm-3 col-xs-12">Fase</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <!-- <select class="form-control col-md-7 col-xs-12" name="fase">
                 <option value="">- Pilih Fase -</option>
                    @foreach($fases as $fs)
                      <option value="{{$fs->id}}" @isset($input_fase) @if($input_fase->name == $fs->name) selected @endif @endisset>{{$fs->name}}</option>
                    @endforeach
              </select> -->
              <input type="text" name="fase" class="form-control col-md-7 col-xs-12" value="{{isset($input_fase) ? $input_fase->name : '' }}" readonly="readonly" />
            </div>

            <div class="form-group">
            <label class="control-label col-md-2 col-sm-3 col-xs-12">Draft RKAU</label>
             <div class="col-md-4 col-sm-4 col-xs-12">
              <!-- <select class="form-control col-md-7 col-xs-12" name="draft_rkau" required>
                <option value="">- Pilih Draft -</option>
                  @isset($idraft)
                    <option value="{{$idraft}}" selected> {{$versi_rkau->draft_versi}}</option>
                  @endisset
              </select> -->
              <input type="text" name="draft_rkau" class="form-control col-md-12 col-xs-12" value="{{isset($idraft) ? $versi_rkau->draft_versi.' - '.$versi_rkau->name : ''}}" readonly="readonly" />
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
                          url: "{{ url('/output/rencana-kinerja/ajax3/') }}/"+id_lokasi+"/"+id_tahun,
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

       <!--    <div class="ln_solid"></div>

          <div class="form-group">
            <div >
              <button type="submit" class="btn btn-primary pull-right">
                  <span class="glyphicon glyphicon-search"> </span> cari
              </button>
            </div>
          </div>   -->

            </form>
          </div>
        </div>
      </div>
    </div>


    <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2 style="font-size: 18px;">RENCANA KERJA</h2>
                    <div class="clearfix"></div>
                  </div>
                  <a href="{{ Request::fullUrl() }}&download=rencana_kinerja&type=excel" id="get-excel1" class="btn btn-success pull-right"><i class="fa fa-download"></i> Download Excel</a>
                  <a href="{{ Request::fullUrl() }}&download=rencana_kinerja&type=pdf"  class="btn btn-primary pull-right"><i class="fa fa-download"></i> Download PDF</a>

                  <div class="x_content">
                    <table id="datatable" class="table table-striped table-bordered" style="font-size:11px;">
                      <thead style="background:#2A3F54;color:white;">
                      <tr>
                          <td colspan="2" rowspan="2" style="vertical-align: middle;">Unit Existing</td>
                          <td>RKAP</td>
                          <td>Prak Real</td>
                          <td>RKAP</td>
                          <td>% RKAP @if(isset($input_tahun)){{$input_tahun}}@else n @endif</td>
                          <td>% RKAP @if(isset($input_tahun)){{$input_tahun}}@else n @endif</td>
                      </tr>
                      <tr>
                          <td>@if(isset($input_tahun)){{$input_tahun-1}}@else n-1 @endif</td>
                          <td>@if(isset($input_tahun)){{$input_tahun-1}}@else n-1 @endif</td>
                          <td>@if(isset($input_tahun)){{$input_tahun}}@else n @endif</td>
                          <td>THD RKAP @if(isset($input_tahun)){{$input_tahun-1}}@else n-1 @endif</td>
                          <td>THD Prak Real @if(isset($input_tahun)){{$input_tahun-1}}@else n-1 @endif</td>
                      </tr>
                      <tr>
                          <td colspan="2">1</td>
                          <td>2</td>
                          <td>3</td>
                          <td>4</td>
                          <td>5=4/2</td>
                          <td>6=4/3</td>
                      </tr>
                      </thead>
                      <?php $baris = 0; ?>
                      @isset($combine)
                        @foreach($combine as $c)
                          <?php $baris = $baris+1; $warna=($baris % 2 == 0) ? "#ffffff" : "#E8EDEF"; ?>
                          @if(!array_key_exists('C',$c) || !array_key_exists('D',$c) || !array_key_exists('E',$c) || !array_key_exists('F',$c) || !array_key_exists('G',$c) || !array_key_exists('H',$c))
                            @break
                          @endif
                          <tr style="background-color: <?= $warna?>">
                            <td>{{$c['B']}}</td>
                            <td>{{$c['C']}}</td>
                            <td>
                            @isset($c['D'])
                            @if($c['D']==NULL)
                            {{ '' }}
                            @else
                            {{number_format((float)$c['D'],2,',','.')}}
                            @endif
                            @endisset
                            </td>
                            <td>
                            @isset($c['E'])
                            @if($c['E']==NULL)
                            {{ '' }}
                            @else
                            {{number_format((float)$c['E'],2,',','.')}}
                            @endif
                            @endisset
                            </td>
                            <td>
                            @isset($c['F'])
                            @if($c['F']==NULL)
                            {{ '' }}
                            @else
                            {{number_format((float)$c['F'],2,',','.')}}
                            @endif
                            @endisset
                            </td>
                            <td>
                            @isset($c['G'])
                            @if($c['G']==NULL)
                            {{ '' }}
                            @else
                            {{round($c['G']*100)}}%
                            @endif
                            @endisset
                            </td>
                            <td>@isset($c['H'])
                            @if($c['H']==NULL)
                            {{ '' }}
                            @else
                            {{round($c['H']*100)}}%
                            @endif
                            @endisset</td>

                          </tr>
                        @endforeach
                      @endisset
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>



@endsection
