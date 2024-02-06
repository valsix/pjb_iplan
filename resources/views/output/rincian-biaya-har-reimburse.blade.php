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
          font-size: 12px;
        }
        th{
          text-align: center;
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

    {{-- <link media="all" type="text/css" rel="stylesheet" href="{{url('DataTables/DataTables-1.10.16/css/jquery.dataTables.min.css')}}"> --}}
    <link media="all" type="text/css" rel="stylesheet" href="{{url('DataTables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css')}}">
    <link media="all" type="text/css" rel="stylesheet" href="{{url('DataTables/datatables.min.css')}}">
    <script src="{{url('DataTables/datatables.min.js')}}"></script>
    <h3> RINCIAN BIAYA HAR REIMBURSE</h3>
    <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">

   <!--    <div class="col-lg-12">
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
                                      @for($i=2017;$i<=(date('Y-m-d')+1);$i++)
                                        @if($i==Request::get('tahun_anggaran'))
                                         <option value="{{$i}}" selected="true">{{$i}}</option>
                                        @else
                                         <option value="{{$i}}">{{$i}}</option>
                                        @endif
                                      @endfor
                                    </select>
                                </div>
                                <div class="col-md-2"><label>Struktur Bisnis</label></div>
                                <div class="col-md-4">
                                    <select class="form-control" name="strategi_bisnis">
                                      <option>- Pilih Struktur Bisnis -</option>
                                       @foreach ($sb as $sbs => $value)
                                          @if($value->id==Request::get('strategi_bisnis'))
                                           <option value="{{ $value->id }}" selected="true" > {{ $value->name }} </option>
                                          @else
                                           <option value="{{ $value->id }}"> {{ $value->name }} </option>
                                          @endif
                                       @endforeach
                                    </select>
                                </div>

                                <br>
                                <br>
                                <div class="col-md-2"><label>Distrik</label></div>
                                <div class="col-md-4">
                                    <select class="form-control" name="distrik" required>
                                      <option value="">- Pilih Distrik -</option>
                                    </select>
                                </div>
                                <div class="col-md-2"><label> Lokasi</label></div>
                                <div class="col-md-4">
                                    <select class="form-control" name="lokasi" required>
                                       <option value="">- Pilih Lokasi -</option>
                                    </select>
                                </div>
                                <br>
                                <br>
                                <div class="col-md-2"><label> Fase</label></div>
                                <div class="col-md-4">
                                    <select class="form-control" required="true" name="fase1" id="fase-input1">
                                      <option value="">- pilih fase -</option>
                                       @foreach($fs as $i=>$row)
                                          @if(Request::get('fase1')==$row['id'])
                                         <option value="{{$row['id']}}" selected="true">{{$row['name']}}</option>
                                          @else
                                         <option value="{{$row['id']}}">{{$row['name']}}</option>
                                          @endif
                                       @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2"><label> Draft</label></div>
                                <div class="col-md-4">
                                    <select class="form-control" required="true" name="draft1" id="draft-input2">
                                       {{-- <option>- Pilih Draft -</option> --}}
                                       <option value="">- Pilih Draft -</option>
                                    </select>
                                </div>
                                <br>
                                <br>
                                <div class="col-md-2"><label> Kode Aktivitas</label></div>
                                <div class="col-md-4">
                                    <select class="form-control" name="kode_ak">
                                      <option value="">- Pilih Kode Aktivitas -</option>
                                    </select>
                                </div>

                                <div class="col-md-2"><label> Kode PRK</label></div>
                                <div class="col-md-4">
                                    <select class="form-control" name="kode_prk">
                                      <option value="">- Pilih Kode PRK -</option>
                                    </select>
                                </div>
                                <br>
                                <br>

                                <div class="col-md-2"><label>Deskripsi PRK kegiatan</label></div>
                                <div class="col-md-4">
                                    <select class="form-control" name="kegiatan">
                                    <option value="">- Pilih PRK kegiatan -</option>
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
      </div> -->

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel collapse">
        <!-- <div class="panel-heading"> -->
        <div class="x_title">
          <h2 style="font-size: 18px;">PENCARIAN</h2>
          <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </li>
            <li><a class="close-link"><i class="fa fa-close"></i></a>
            </li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <!-- <div class="panel-default"> -->
        <div class="x_content" style="display: none;">
            <br/>
            <form class="form-horizontal form-label-left">

            <div class="form-group">
              <label class="col-md-2 col-sm-3 col-xs-12">Tahun Anggaran</label>
              <div class="col-md-4 col-sm-4 col-xs-12">
                <!-- <select class="form-control col-md-7 col-xs-12" name="tahun_anggaran">
                  <option>- Pilih Tahun -</option>
                    @for($i=2017;$i<=(date('Y-m-d')+1);$i++)
                      @if($i==Request::get('tahun_anggaran'))
                        <option value="{{$i}}" selected="true">{{$i}}</option>
                          @else
                        <option value="{{$i}}">{{$i}}</option>
                      @endif
                    @endfor
                </select> -->
                <input type="text" class="form-control col-md-7 col-xs-12" name="tahun_anggaran" readonly="readonly" value="{{$input_tahun}}">
              </div>

              <div class="form-group">
              <label class="col-md-2 col-sm-3 col-xs-12">Struktur Bisnis</label>
              <div class="col-md-4 col-sm-4 col-xs-12">
                <!-- <select class="form-control col-md-7 col-xs-12" name="strategi_bisnis">
                  <option>- Pilih Struktur Bisnis -</option>
                    @foreach ($sb as $sbs => $value)
                      @if($value->id==Request::get('strategi_bisnis'))
                        <option value="{{ $value->id }}" selected="true" > {{ $value->name }} </option>
                          @else
                        <option value="{{ $value->id }}"> {{ $value->name }} </option>
                      @endif
                    @endforeach
                </select> -->
                <input type="text" class="form-control col-md-7 col-xs-12" name="strategi_bisnis" readonly="readonly" value="{{isset($input_sb) ? $input_sb->name : ''}}">
              </div>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-2 col-sm-3 col-xs-12" >Distrik</label>
              <div class="col-md-4 col-sm-4 col-xs-12">
                <!-- <select class="form-control col-md-7 col-xs-12" name="distrik" required>
                    <option value="">- Pilih Distrik -</option>
                </select> -->
                <input type="text" class="form-control col-md-7 col-xs-12" name="distrik" readonly="readonly" value="{{isset($input_distrik) ? $input_distrik->name : ''}}">
              </div>

              <div class="form-group">
              <label class="col-md-2 col-sm-3 col-xs-12">Lokasi</label>
              <div class="col-md-4 col-sm-4 col-xs-12">
                <!-- <select class="form-control col-md-7 col-xs-12" name="lokasi" required>
                    <option value="">- Pilih Lokasi -</option>
                </select> -->
                <input type="text" class="form-control col-md-7 col-xs-12" name="lokasi" readonly="readonly" value="{{isset($input_lokasi) ? $input_lokasi->name : ''}}">
              </div>
            </div>
            </div>

            <div class="form-group">
              <label class="col-md-2 col-sm-3 col-xs-12">Fase</label>
              <div class="col-md-4 col-sm-4 col-xs-12">
                <!-- <select class="form-control col-md-7 col-xs-12" required="true" name="fase1" id="fase-input1">
                  <option value="">- pilih fase -</option>
                    @foreach($fs as $i=>$row)
                      @if(Request::get('fase1')==$row['id'])
                        <option value="{{$row['id']}}" selected="true">{{$row['name']}}</option>
                          @else
                        <option value="{{$row['id']}}">{{$row['name']}}</option>
                      @endif
                    @endforeach
                </select> -->
                <input type="text" name="fase" value="{{isset($input_fase) ? $input_fase->name : ''}}" class="form-control col-md-7 col-xs-12" readonly="readonly">
              </div>

              <div class="form-group">
              <label class="col-md-2 col-sm-3 col-xs-12"></label>
              <div class="col-md-3 col-sm-4 col-xs-12"></div>
              </div>
            </div>
            <hr>
            <div class="form-group" style="margin-top: 5px;">
              <div class="form-group">
              <label class="col-md-2 col-sm-3 col-xs-12">Form 6 - Reimburse</label>
              <div class="col-md-6 col-sm-4 col-xs-12">
                <!-- <select class="form-control col-md-7 col-xs-12" required="true" name="draft1" id="draft-input2">
                  {{-- <option>- Pilih Draft -</option> --}}
                  <option value="">- Pilih Draft -</option>
                </select> -->
                <input type="text" name="draft1" value="{{ isset($input_form_6_reimburse) ? $input_form_6_reimburse->draft_versi.' - '.$input_form_6_reimburse->name : '' }}" class="form-control col-md-7 col-xs-12" readonly="readonly">
              </div>
              </div>
            </div>

            <!-- <div class="form-group">
              <label class="col-md-2 col-sm-3 col-xs-12">Kode Aktivitas</label>
              <div class="col-md-4 col-sm-4 col-xs-12">
                <select class="form-control col-md-7 col-xs-12" name="kode_ak">
                    <option value="">- Pilih Kode Aktivitas -</option>
                </select>
              </div>

              <div class="form-group">
              <label class="col-md-2 col-sm-3 col-xs-12">Kode PRK</label>
              <div class="col-md-3 col-sm-4 col-xs-12">
                <select class="form-control col-md-7 col-xs-12" name="kode_prk">
                    <option value="">- Pilih Kode PRK -</option>
                </select>
              </div>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-2 col-sm-3 col-xs-12">Deskripsi PRK Kegiatan</label>
              <div class="col-md-4 col-sm-4 col-xs-12">
                <select class="form-control col-md-7 col-xs-12" name="kegiatan">
                    <option value="">- Pilih PRK kegiatan -</option>
                </select>
              </div>
            </div>


            <div class="ln_solid"></div>

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

          <form class="form-horizontal form-label-left" id="form-hidden">
            <input value="" name="tahun-fm" type="hidden" id="tahun"  class="form-control" readonly="">
            <input value="" name="sb-fm" type="hidden" id="struktur-bisnis"  class="form-control col-md-7" readonly="" >
            <input value="" name="distrik-fm" id="distrik" class="form-control col-md-7 col-xs-12 " type="hidden" readonly="">
            <input value="" name="lokasi-fm" id="lokasi" class="form-control col-md-7 " type="hidden" readonly="">
            <input value="" name="fase-fm" id="fase" class="form-control col-md-7 col-xs-12 " type="hidden" readonly="">
            <input value="" name="draft-fm" id="draft" class="form-control col-md-7 col-xs-12 " type="hidden" readonly="">
            <input value="" name="kode_ak-fm" id="kode_aktivitas" class="form-control col-md-7 col-xs-12 " type="hidden" readonly="">
            <input value="" name="kode_prk-fm" id="kode_prk" class="form-control col-md-7 col-xs-12 " type="hidden" readonly="">
            <input value="" name="desk_prk-fm" id="desk_prk" class="form-control col-md-7 col-xs-12 " type="hidden" readonly="">
          </form>
       </div>
    </div>
@if($sb != NULL && $input_tahun != NULL && $input_sb != NULL && $input_distrik != NULL && $fs != NULL && $input_lokasi != NULL)
<div class="row">
 <div class="col-md-12 col-sm-12 col-xs-12">
  <div class="x_panel">
    <div class="x_title">
      <h2 style="font-size: 18px; margin-right: 10px;">RINCIAN PROGRAM PEMELIHARAAN REMIMBURSE PER AKTIVITAS TAHUN 
        <?php
          if ($input_tahun != NULL) { ?>
        {{ $input_tahun }}
        <?php
          }
          else { ?>
            XXXX
        <?php
          }
        ?>
      </h2>
      <h5 style="margin-top: 8px;"> (Dalam Ribuan Rupiah)</h5>
      <div class="clearfix"></div>
    </div>

    <a href="{{Request::fullUrl()}}&download=excel" id="down-excel" class="btn btn-success pull-right"><i class="fa fa-download"></i> Download Excel</a>

    <a href="{{Request::fullUrl()}}&download=pdf" target="blank" id="down-pdf" class="btn btn-primary pull-right"><i class="fa fa-download"></i> Download PDF</a>
    <style>
      table {
          border-collapse: collapse;
      }

      table, th {
          border: 1;
          border-color: white;
      }

      table thead tr th{
        line-height: 1;
      }

      table tbody tr td{
        /*Untuk data yang deskripsi panjang*/
        line-height: 1; 

        /*Untuk data yang tidak ada deskripsi panjang*/
        /*line-height: 0.5; */
      }

    </style>
      <table cellspacing="0" border="1" width="100%" style="height: 100px !important;font-size:11px;" id="myTable">
        <thead style="background:#2A3F54;color:white;">
          <tr>
            <th rowspan="3">Kode Aktifitas</th>
            <th rowspan="3">Kode PRK</th>
            <th rowspan="3">Deskripsi PRK Kegiatan</th>
            <th colspan="4">TOTAL PEMAKAIAN (LABA RUGI)</th>
            <th colspan="7">TOTAL PEMAKAIAN (CASH FLOW)</th>
            <th rowspan="3">ALOKASI<br>(UP/UBJOM, UPHAR/STOCKIST, UPHB, PJAC, PJB2)</th>
            <th rowspan="3">Persetujuan Proses Kontrak Pengadaan</th>
            <th rowspan="3">Disburse</th>
          </tr>
          <tr>
            <th colspan="2">Material</th>
            <th rowspan="2">Jasa</th>
            <th rowspan="2">Total</th>
            <th colspan="2">Pembayaran Hutang</th>
            <th colspan="2">Material</th>
            <th rowspan="2">Jumlah Material</th>
            <th rowspan="2">Jumlah Jasa</th>
            <th rowspan="2">Total</th>
          </tr>
          <tr>
            <th>Persediaan</th>
            <th>Pengadaan Langsung Pakai</th>
            <th>Material</th>
            <th>Jasa</th>
            <th>Pengadaan Langsung Pakai</th>
            <th>Persediaan</th>
          </tr>
          <tr>
            <th >1</th>
            <th >2</th>
            <th >3</th>
            <th >4</th>
            <th >5</th>
            <th >6</th>
            <th >7=4+5+6</th>
            <th >8</th>
            <th >9</th>
            <th >10</th>
            <th >11</th>
            <th >12</th>
            <th >13</th>
            <th >14=12+13</th>
            <th >15</th>
            <th >16</th>
            <th >17</th>
          </tr>
        </thead>
        <tbody style="height: 100px; overflow-y: true;">
          <?php $count=0; ?>
          @if($hasil)
            <?php
              $baris = 1;
              foreach($hasil as $i=>$val){
              $warna=($baris % 2 == 0) ? "#ffffff" : "#E8EDEF";
            ?>
            <tr style="background-color: <?= $warna?>; ">
              <?php $kolom = 1; ?>
              @foreach($val as $riw)
                @if(is_numeric($riw))
                  @if($kolom==1 || $kolom==15 || $kolom==17)
                  <td style="text-align: center;">{{ number_format($riw, 0,'.','.')}}</td>
                  @else
                  <td style="text-align: right;">{{ number_format($riw, 0,'.','.')}}</td>
                  @endif
                @else
                  @if($kolom==1 || $kolom==15 || $kolom==17)
                  <td style="text-align: center;">{{$riw}}</td>
                  @else
                  <td style="text-align: left;">{{$riw}}</td>
                  @endif
                @endif
              <?php $kolom++; ?>
              @endforeach
            </tr>
            <?php $baris++;} ?>
          @endif
        </tbody>
      </table>
    {{-- </div> --}}
  </div>
</div>


</div>
@endif
<script type="text/javascript">
  $(document).ready(function() {
      function gantiDistrik(strategi_bisnisID){
          if(strategi_bisnisID) {
              $.ajax({
                  // url: '/output/rincian-biaya-har/ajax/'+strategi_bisnisID,
                  url: "{{ url('/output/rincian-biaya-har/ajax/') }}/"+strategi_bisnisID,
                  type: "GET",
                  dataType: "json",
                  success:function(data) {
                  // console.log(data);
                    $('select[name="distrik"]').append('<option value="">- Silahkan Pilih Distrik -</option>');
                    $.each(data, function(sb, value) {
                        if(value["id"]=={{Request::get('distrik')}}+0){
                          $('select[name="distrik"]').append('<option selected="true" value="'+ value["id"] +'">'+ value["name"] +'</option>');
                          gantiLokasi(value['id']);
                          $("#distrik").val(value['name']);
                        }else{
                          $('select[name="distrik"]').append('<option value="'+ value["id"] +'">'+ value["name"] +'</option>');
                        }
                    });

                  }
              });
          }else{
              $('select[name="distrik"]').empty();
          }
      }
      function gantiLokasi(lokasiID){
        if(lokasiID) {
            $.ajax({
                // url: '/output/rincian-biaya-har/ajax2/'+lokasiID,
                url: "{{ url('/output/rincian-biaya-har/ajax2/') }}/"+lokasiID,
                type: "GET",
                dataType: "json",
                success:function(data) {

                  $('select[name="lokasi"]').empty();
                  console.log(data);
                  $('select[name="lokasi"]').append('<option value="">- Pilih Lokasi -</option>');
                  $.each(data, function(ad , value) {
                  console.log(ad);
                    if(value["id"]=={{Request::get('lokasi')}}+0){
                      $('select[name="lokasi"]').append('<option selected="true" value="'+ value["id"] +'">'+ value["name"] +'</option>');
                      gantiDraft($('select[name="fase1"]').val(), $('select[name="lokasi"]').val(), $('select[name="tahun_anggaran"]').val());
                      $("#lokasi").val(value['name']);
                      get3field(value['id'], $("select[name=lokasi]").val());
                    }else{
                      $('select[name="lokasi"]').append('<option value="'+ value["id"] +'">'+ value["name"] +'</option>');
                    }
                  });

                }
            });
        }else{
          $('select[name="lokasi"]').empty();
        }
      }
      function gantiDraft(DraftID, lokasiID, tahun){
        if(DraftID && lokasiID && tahun) {
            $.ajax({
                url: "{{ url('/output/rincian-biaya-har-reimburse/ajax3/') }}/"+DraftID+"/"+lokasiID+"/"+tahun,
                type: "GET",
                dataType: "json",
                success:function(data) {
                  $('select[name="draft1"]').empty();
                  console.log(data);
                  $('select[name="draft1"]').append('<option value="">- Pilih Draft -</option>');
                  $.each(data, function(ad , value) {
                    console.log(ad);
                    if(value['id']=={{ Request::get('draft1')+0 }}){
                      $('select[name="draft1"]').append('<option selected="true" value="'+ value["id"] +'">'+ value["draft_versi"] +'</option>');
                        $("#draft").val(value["draft_versi"]);
                        get3field(value['id'], $("select[name=lokasi]").val());
                    }else{
                      $('select[name="draft1"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +'</option>');
                        get3field(value['id'], $("select[name=lokasi]").val());
                    }
                  });
                }
            });
        }else{
            $('select[name="draft1"]').empty();
        }
      }
      function get3field(draft, lokasiID){
        if(draft && lokasiID) {
            $.ajax({
                url: "{{ url('/output/rincian-biaya-har/ajax4/') }}/"+draft+"/"+lokasiID,
                type: "GET",
                dataType: "json",
                success:function(data) {

                  $('select[name="kode_ak"]').empty();
                  $('select[name="kode_prk"]').empty();
                  $('select[name="kegiatan"]').empty();
                  console.log(data);
                  $('select[name="kode_ak"]').append('<option value="">- Pilih Kode Aktifitas -</option>');
                  $('select[name="kode_prk"]').append('<option value="">- Pilih Kode PRK -</option>');
                  $('select[name="kegiatan"]').append('<option value="">- Pilih PRK Kegiatan -</option>');

                  /*Kode Aktifitas*/
                    $.each(data.kode_ak, function(ad , value) {
                      console.log(ad);
                      if(value['value']=={{ Request::get('kode_ak')+'' }}){
                        $('select[name="kode_ak"]').append('<option selected="true" value="'+ value["value"] +'">'+ value["value"] +'</option>');
                          $("#kode_ak-form").val(value["value"]);
                      }else{
                        $('select[name="kode_ak"]').append('<option value="'+ value["value"] +'">'+ value["value"] +'</option>');
                      }
                    });
                  /*Kode Aktifitas*/
                  /*Kode PRK*/
                    $.each(data.kode_prk, function(ad , value) {
                      console.log(ad);
                      if(value['value']=={{ Request::get('kode_prk')+'' }}){
                        $('select[name="kode_prk"]').append('<option selected="true" value="'+ value["value"] +'">'+ value["value"] +'</option>');
                          $("#kode_prk-form").val(value["value"]);
                      }else{
                        $('select[name="kode_prk"]').append('<option value="'+ value["value"] +'">'+ value["value"] +'</option>');
                      }
                    });
                  /*Kode PRK*/
                  /*Kegiatan*/
                    $.each(data.kegiatan, function(ad , value) {
                      console.log(ad);
                      if(value['value']=={{ Request::get('kegiatan')+'' }}){
                        $('select[name="kegiatan"]').append('<option selected="true" value="'+ value["value"] +'">'+ value["value"] +'</option>');
                          $("#kegiatan-form").val(value["value"]);
                      }else{
                        $('select[name="kegiatan"]').append('<option value="'+ value["value"] +'">'+ value["value"] +'</option>');
                      }
                    });
                  /*Kegiatan*/
                }
            });
        }else{
            $('select[name="kode_ak"]').empty();
            $('select[name="kode_prk"]').empty();
            $('select[name="kegiatan"]').empty();
        }
      }
      $('select[name="distrik"]').on('change', function() {
        var lokasiID = $(this).val();
        $('select[name="lokasi"]').empty();
        gantiLokasi(lokasiID);
      });
      $('select[name="fase1"]').on('change', function() {
        var DraftID = $(this).val();
        $('select[name="draft"]').empty();
        gantiDraft($(this).val(), $('select[name="lokasi"]').val(), $('select[name="tahun_anggaran"]').val());
      });
      $('select[name="strategi_bisnis"]').on('change', function() {
          var strategi_bisnisID = $(this).val();
          $('select[name="distrik"]').empty();
          $('select[name="lokasi"]').empty();
          gantiDistrik(strategi_bisnisID);
      });
      @if(Request::get('strategi_bisnis'))
        gantiDistrik($("select[name=strategi_bisnis]").val());
        $("#fase").val($("select[name=fase1] option:selected").text());
        $("#tahun").val($("select[name=tahun_anggaran]").val());
        $("#struktur-bisnis").val($("select[name=strategi_bisnis] option:selected").text());

        if($("select[name=kode_ak] option:selected").val()!=''){
          $("#kode_aktivitas").val($("select[name=kode_ak] option:selected").text());
        }
        if($("select[name=kode_prk] option:selected").val()!=''){
          $("#kode_prk").val($("select[name=kode_prk] option:selected").text());
        }
        if($("select[name=kegiatan] option:selected").val()!=''){
          $("#desk_prk").val($("select[name=kegiatan] option:selected").text());
        }
      @endif
      $("#down-excel").click(function(event) {
        var link=$(this).attr('href');
        var param=$("#form-hidden").serialize();
        $(this).attr('href', link+'&'+param);
      });
      $("#down-pdf").click(function(event) {
        var link=$(this).attr('href');
        var param=$("#form-hidden").serialize();
        $(this).attr('href', link+'&'+param);
      });
      /**/
  });
  var table = $("#myTable").DataTable({
         // fixedHeader: true,
        "scrollY": "800px",
        "scrollX": "300px",
        "scrollCollapse": true,
        "paging" : false,
        "ordering" : false,
        "paging": true,
        ordering: false,
        searching: true,
        aLengthMenu: [
        [10, 25, 50, 100, -1],
        [10, 25, 50, 100, "All"]],
        iDisplayLength: 10
  });
</script>
@endsection
