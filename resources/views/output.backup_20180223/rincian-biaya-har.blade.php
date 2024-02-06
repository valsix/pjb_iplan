@extends('layouts.app')

@section('css_page')
   
@endsection

@section('content')

    {{-- <link media="all" type="text/css" rel="stylesheet" href="{{url('DataTables/DataTables-1.10.16/css/jquery.dataTables.min.css')}}"> --}}
    <link media="all" type="text/css" rel="stylesheet" href="{{url('DataTables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css')}}">
    <link media="all" type="text/css" rel="stylesheet" href="{{url('DataTables/datatables.min.css')}}">
    <script src="{{url('DataTables/datatables.min.js')}}"></script>

    <h1> Rincian Biaya Har </h1>
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
                                    <select name="tahun_anggaran" required class="form-control">
                                      <option value="">- Pilih Tahun -</option>
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
                                    <select class="form-control" name="strategi_bisnis" required>
                                      <option value="">- Pilih Struktur Bisnis -</option>
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
                                    <select class="form-control" name="distrik">
                                      <option value="">- Pilih Distrik -</option>
                                    </select>
                                </div>
                                <div class="col-md-2"><label> Lokasi</label></div>
                                <div class="col-md-4">
                                    <select class="form-control" name="lokasi">
                                       <option value="">- Pilih Lokasi -</option>
                                    </select>
                                </div>
                                <br>
                                <br>
                                <div class="col-md-2"><label> Fase</label></div>
                                <div class="col-md-4">
                                    <select class="form-control" required="true" name="fase1" id="fase-input1">
                                       <option value="">- Pilih Fase -</option>
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
                                        <option value="">- Pilih Draft -</option>
                                       {{-- <option value=""></option> --}}
                                    </select>
                                </div>
                                <br>
                                <br>
                                <div class="col-md-2"><label> Kode Aktivitas</label></div>
                                <div class="col-md-4">
                                    <select class="form-control" name="kode_ak">
                                    <option value="">- Pilih Kode Aktifitas -</option>
                                    </select>
                                </div>

                                <div class="col-md-2"><label> Kode PRK</label></div>
                                <div class="col-md-4">
                                    <select class="form-control" name="kode_prk">
                                      <option value="">- Pilih Kode Prk -</option>
                                    </select>
                                </div>
                                <br>
                                <br>

                                <div class="col-md-2"><label>Deskripsi PRK kegiatan</label></div>
                                <div class="col-md-4">
                                    <select class="form-control" name="kegiatan">
                                    <option value="">- Pilih Deskripsi PRK kegiatan -</option>
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
@if($input_tahun != NULL && $input_sb != NULL && $input_distrik != NULL && $input_lokasi != NULL && $fs != NULL)
<div class="row">
 <div class="col-md-12 col-sm-12 col-xs-12">
  <div class="x_panel table-responsive">
    <div class="x_title">
      <h2>Rincian Program Pemeliharaan Per Aktivitas Tahun 
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
      <div class="clearfix"></div>
    </div>
   
    <a href="{{Request::fullUrl()}}&download=excel&type=excel" id="down-excel" class="btn btn-success pull-right"><i class="fa fa-download"></i> Download Excel</a>

    <a href="{{Request::fullUrl()}}&download=pdf" id="down-pdf" class="btn btn-primary pull-right"><i class="fa fa-download"></i> Download PDF</a>
    <style>
      table {
          border-collapse: collapse;
      }

      table, th {
          border: 1px solid white;
      }
    </style>
    {{-- <div class="table-responsive"> --}}
      <table cellspacing="0" border="1" width="100%" style="height: 100px !important;" id="myTable">
        <thead style="background:#2A3F54;color:white;">
          <tr>
            <td rowspan="3">Kode Aktifitas</td>
            <td rowspan="3">Kode PRK</td>
            <td rowspan="3">Deskripsi PRK Kegiatan</td>
            <td colspan="4">TOTAL PEMAKAIAN (LABA RUGI)</td>
            <td colspan="7">TOTAL PEMAKAIAN (CASH FLOW)</td>
            <td rowspan="3">ALOKASI<br>(UP/UBJOM, UPHAR/STOCKIST, UPHB, PJAC, PJB2)</td>
            <td rowspan="3">Persetujuan Proses Kontrak Pengadaan</td>
            <td rowspan="3">Disburse</td>
          </tr>
          <tr>
            <td colspan="2">Material</td>
            <td rowspan="2">Jasa</td>
            <td rowspan="2">Total</td>
            <td colspan="2">Pembayaran Hutang</td>
            <td colspan="2">Material</td>
            <td rowspan="2">Jumlah Material</td>
            <td rowspan="2">Jumlah Jasa</td>
            <td rowspan="2">Total</td>
          </tr>
          <tr>
            <td>Persediaan</td>
            <td>Pengadaan Langsung Pakai</td>
            <td>Material</td>
            <td>Jasa</td>
            <td>Pengadaan Langsung Pakai</td>
            <td>Persediaan</td>
          </tr>
          <tr>
            <td >1</td>
            <td >2</td>
            <td >3</td>
            <td >4</td>
            <td >5</td>
            <td >6</td>
            <td >7=4+5+6</td>
            <td >8</td>
            <td >9</td>
            <td >10</td>
            <td >11</td>
            <td >12</td>
            <td >13</td>
            <td >14=12+13</td>
            <td >15</td>
            <td >16</td>
            <td >17</td>
          </tr>
        </thead>
        <tbody>

          <?php $baris = 0; ?>
          <!-- form 6 -->
          @foreach($dataparent as $key_form => $parent_per_form)
          @foreach($parent_per_form as $key_parent => $parent)
          <!-- parent -->
              @if($key_parent!= '')
              <?php $baris = $baris+1; $warna=($baris % 2 == 0) ? "#ffffff" : "#E8EDEF"; ?>
              <tr style="background-color: <?= $warna?>">
                  <td>{{$parent['kode_aktivitas']}}</td><!-- Kode Aktifitas -->
                  <td>{{$key_parent}}</td><!-- Kode PRK Kegiatan -->
                  <td>{{$parent['desc_prk_parent']}}</td><!-- Deskripsi PRK Kegiatan -->
                  <td style="text-align: right;">{{ number_format($parent['persediaan_lr'],0,",",".") }}</td>
                  <td style="text-align: right;">{{ number_format($parent['pengadaan_lr'],0,",",".") }}</td>
                  <td style="text-align: right;">{{ number_format($parent['jasa_lr'],0,",",".") }}</td>
                  <td style="text-align: right;">{{ number_format($parent['total_lr'],0,",",".") }}</td>
                  <td style="text-align: right;">{{ number_format($parent['material_hutang_cf'],0,",",".") }}</td>
                  <td style="text-align: right;">{{ number_format($parent['jasa_hutang_cf'],0,",",".") }}</td>
                  <td style="text-align: right;">{{ number_format($parent['pengadaan_cf'],0,",",".") }}</td>
                  <td style="text-align: right;">{{ number_format($parent['persediaan_cf'],0,",",".") }}</td>
                  <td style="text-align: right;">{{ number_format($parent['jml_material_cf'],0,",",".") }}</td>
                  <td style="text-align: right;">{{ number_format($parent['jml_jasa_cf'],0,",",".") }}</td>
                  <td style="text-align: right;">{{ number_format($parent['total_cf'],0,",",".") }}</td>
                  <td></td>
                  <td style="text-align: right;">{{ number_format($parent['persetujuan'],0,",",".") }}</td>
                  <td></td>
              </tr>

              <!-- inti -->
              @foreach($datainti[$key_form] as $key_inti=>$inti)
                  @if($inti['prk_parent'] == $key_parent)
                  <?php $baris = $baris+1; $warna=($baris % 2 == 0) ? "#ffffff" : "#E8EDEF"; ?>
                  <tr style="background-color: <?= $warna?>">
                      <td>{{$inti['kode_aktivitas']}}</td><!-- Kode Aktifitas -->
                      <td>{{$key_inti}}</td><!-- Kode PRK -->
                      <td>{{$inti['desc_prk_inti']}}</td><!-- Deskripsi PRK Kegiatan -->
                      <td style="text-align: right;">{{ number_format($inti['persediaan_lr'],0,",",".") }}</td>
                      <td style="text-align: right;">{{ number_format($inti['pengadaan_lr'],0,",",".") }}</td>
                      <td style="text-align: right;">{{ number_format($inti['jasa_lr'],0,",",".") }}</td>
                      <td style="text-align: right;">{{ number_format($inti['total_lr'],0,",",".") }}</td>
                      <td style="text-align: right;">{{ number_format($inti['material_hutang_cf'],0,",",".") }}</td>
                      <td style="text-align: right;">{{ number_format($inti['jasa_hutang_cf'],0,",",".") }}</td>
                      <td style="text-align: right;">{{ number_format($inti['pengadaan_cf'],0,",",".") }}</td>
                      <td style="text-align: right;">{{ number_format($inti['persediaan_cf'],0,",",".") }}</td>
                      <td style="text-align: right;">{{ number_format($inti['jml_material_cf'],0,",",".") }}</td>
                      <td style="text-align: right;">{{ number_format($inti['jml_jasa_cf'],0,",",".") }}</td>
                      <td style="text-align: right;">{{ number_format($inti['total_cf'],0,",",".") }}</td>
                      <td></td>
                      <td style="text-align: right;">{{ number_format($inti['persetujuan'],0,",",".") }}</td>
                      <td></td>
                  </tr>

                  <!-- kegiatan -->
                  @foreach($datakegiatan[$key_form] as $key_kegiatan => $kegiatan)
                      @if($kegiatan['prk_inti'] == $key_inti)
                      <?php $baris = $baris+1; $warna=($baris % 2 == 0) ? "#ffffff" : "#E8EDEF"; ?>
                      <tr style="background-color: <?= $warna?>">
                          <td>{{$kegiatan['kode_aktivitas']}}</td><!-- Kode Aktifitas -->
                          <td>{{$kegiatan['prk_kegiatan']}}</td><!-- Kode PRK -->
                          <td>{{$kegiatan['desc_prk_kegiatan']}}</td><!-- Deskripsi PRK Kegiatan -->
                          <td style="text-align: right;">{{ number_format($kegiatan['persediaan_lr'],0,",",".") }}</td>
                          <td style="text-align: right;">{{ number_format($kegiatan['pengadaan_lr'],0,",",".") }}</td>
                          <td style="text-align: right;">{{ number_format($kegiatan['jasa_lr'],0,",",".") }}</td>
                          <td style="text-align: right;">{{ number_format($kegiatan['total_lr'],0,",",".") }}</td>
                          <td style="text-align: right;">{{ number_format($kegiatan['material_hutang_cf'],0,",",".") }}</td>
                          <td style="text-align: right;">{{ number_format($kegiatan['jasa_hutang_cf'],0,",",".") }}</td>
                          <td style="text-align: right;">{{ number_format($kegiatan['pengadaan_cf'],0,",",".") }}</td>
                          <td style="text-align: right;">{{ number_format($kegiatan['persediaan_cf'],0,",",".") }}</td>
                          <td style="text-align: right;">{{ number_format($kegiatan['jml_material_cf'],0,",",".") }}</td>
                          <td style="text-align: right;">{{ number_format($kegiatan['jml_jasa_cf'],0,",",".") }}</td>
                          <td style="text-align: right;">{{ number_format($kegiatan['total_cf'],0,",",".") }}</td>
                          <td>{{ $kegiatan['alokasi'] }}</td>
                          <td style="text-align: right;">{{ number_format($kegiatan['persetujuan'],0,",",".") }}</td>
                          <td>{{ $kegiatan['disburse'] }}</td>
                      </tr>
                      @endif
                  @endforeach <!-- end of kegiatan -->
                  @endif
              @endforeach <!-- end of inti -->
              @endif
          @endforeach <!-- end of parent -->
          @endforeach
          <!-- end of form 6 -->
        </tbody>
      </table>
    {{-- </div> --}}
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
                    $('select[name="distrik"]').append('<option value="">- Pilih Lokasi -</option>');
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
                url: "{{ url('/output/rincian-biaya-har/ajax3/') }}/"+DraftID+"/"+lokasiID+"/"+tahun,
                type: "GET",
                dataType: "json",
                success:function(data) {
                  $('select[name="draft1"]').empty();
                  console.log(data);
                  $('select[name="draft1"]').append('<option value="">- Silahkan Pilih Draft -</option>');
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