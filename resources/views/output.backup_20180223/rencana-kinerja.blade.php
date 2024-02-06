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

    </style>

@endsection

@section('content')
    <h1> Rencana Kinerja </h1>
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
                                       @for($i=2017;$i<=(date('Y-m-d')+1);$i++)
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

                                <div class="col-md-2"><label>Draft RKAU</label></div>
                                <div class="col-md-3">
                                  <select class="form-control" name="draft_rkau" required>
                                    <option value="">- Pilih Draft -</option>
                                    @isset($idraft)
                                    <option value="{{$idraft}}" selected> {{$versi_rkau->draft_versi}}</option>
                                    @endisset
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
                    <h2>Table Rencana Kerja</h2>
                    <div class="clearfix"></div>
                  </div>
                  <a href="{{ Request::fullUrl() }}&download=rencana_kinerja&type=excel" id="get-excel1" class="btn btn-success pull-right"><i class="fa fa-download"></i> Download Excel</a>
                  <a href="{{ Request::fullUrl() }}&download=rencana_kinerja&type=pdf"  class="btn btn-primary pull-right"><i class="fa fa-download"></i> Download PDF</a>

                  <div class="x_content">
                    <table id="datatable" class="table table-striped table-bordered">
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
                      @isset($combine)
                        @foreach($combine as $c)
                          @if(!array_key_exists('C',$c) || !array_key_exists('D',$c) || !array_key_exists('E',$c) || !array_key_exists('F',$c) || !array_key_exists('G',$c) || !array_key_exists('H',$c))
                            @break
                          @endif
                          <tr>
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
