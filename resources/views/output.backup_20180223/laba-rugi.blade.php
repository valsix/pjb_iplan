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
    <h1> Report Laba Rugi</h1>
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
                            <form action="{{ Request::url() }}" method="get">
                              <div class="col-md-12">
                                <div class="col-md-2"><label>Tahun Anggaran</label></div>
                                <div class="col-md-4">
                                    <select class="form-control" required="true" name="tahun1">
                                       <option value="">- Pilih Tahun Anggaran -</option>
                                       @for($thn=2016;$thn<=(date('Y-m-d'))+1;$thn++)
                                          @if($thn==Request::get('tahun1'))
                                           <option selected="true" value="{{$thn}}">{{$thn}}</option>
                                          @else
                                           <option value="{{$thn}}">{{$thn}}</option>
                                          @endif
                                       @endfor
                                    </select>
                                </div>
                                <div class="col-md-2"><label>Struktur Bisnis</label></div>
                                <div class="col-md-3">
                                    <select class="form-control" required="true" name="strategi_bisnis1" id="strategi-bisnis-input">
                                       <option value="">- Pilih Struktur Bisnis -</option>
                                       @foreach ($sb as $sbs => $value)
                                          @if($value->id==Request::get('strategi_bisnis1'))
                                           <option value="{{ $value->id }}" selected="true"> {{ $value->name }} </option>
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
                                    <select class="form-control" required="true" name="distrik1" id="distrik-input">
                                      <option value="">- Pilih Distrik -</option>
                                    </select>
                                </div>
                                <div class="col-md-2"><label> Lokasi</label></div>
                                <div class="col-md-3">
                                    <select class="form-control" required="true" name="lokasi1" id="lokasi-input">
                                       <option value="">- Pilih Lokasi -</option>
                                    </select>
                                </div>
                                <br><br>
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
                                <div class="col-md-3">
                                    <select class="form-control" required="true" name="draft1" id="draft-input2">
                                       <option value="">- Pilih Draft -</option>
                                    </select>
                                </div>
                                <div>
                                  <hr>
                                </div>
                                <button style="float: right;" type="submit" class="btn btn-primary" id="cari"> 
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

        <form class="form-horizontal form-label-left" id="form1">
          <input type="hidden" name="thn-form1" id="tahun-form1" value="" class="form-control" readonly="">
          <input type="hidden" name="sb-form1" id="strategi-bisnis-form1" class="form-control" readonly="">
          <input id="distrik-form1" name="d-form1" class="form-control col-md-7 col-xs-12" type="hidden" readonly="">
          <input id="lokasi-form1" name="lok-form1" class="form-control col-md-7" type="hidden" readonly="">
          <input id="fase-form1" name="fas-form1" class="form-control col-md-7" type="hidden" readonly="">
          <input id="draft-form1" name="dr-form1" class="form-control col-md-7" type="hidden" readonly="">
        </form>
    </div>

<!-- Table LR Unit Pembangkit -->
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>Table LR Unit Pembangkit</h2>
              <div class="clearfix"></div>
            </div>

            <a href="{{ Request::fullUrl() }}&download=lr_unit_pembangkit&type=excel" id="get-excel1" class="btn btn-success pull-right"><i class="fa fa-download"></i> Download Excel</a>

            <a href="{{ Request::fullUrl() }}&download=lr_unit_pembangkit&type=pdf" id="get-pdf1" class="btn btn-primary pull-right"><i class="fa fa-download"></i> Download PDF</a>

            <div class="x_content">
              <table id="datatable" class="table table-striped table-bordered">
                 <thead style="background:#2A3F54;color:white;">
                   <tr>
                     <th class="col-md-4">Keterangan</th>
                     <th>Estimasi Real <br> <?php if($tahun1 == NULL){echo '';}else {
                       echo $tahun2;
                     } ?> </th>
                     <th>RKAP <br> {{ $tahun1 }}</th>
                   </tr>
                   <tr>
                     <th>1</th>
                     <th>2</th>
                     <th>3</th>
                   </tr>
                 </thead>
                 <tbody>
                  <?php $count=0; ?>
                  @if(isset($hasil1))
                    @foreach($hasil1 as $i=>$val)
                      @if($val->row==64)
                        <tr>
                          <td>
                            {{$val->value}}
                          </td>
                          <td colspan="2">&nbsp;</td>
                        </tr>
                      @else
                        {{-- @if($count==0) --}}
                        @if($val->kolom=='D')
                          <tr>
                            <td >
                              {{ $val->value }}
                            </td>
                          <?php $count++ ?>
                        {{-- @elseif($count==1) --}}
                        @elseif($val->kolom=='E')
                          <td style="text-align: right;">
                          @if($val->value<0)
                            {{ '('.number_format(abs(round($val->value)),0,",",".").')' }}
                          @else
                            {{ number_format(round($val->value),0,",",".") }}
                          @endif
                          </td>
                          <?php $count++ ?>
                        {{-- @elseif($count==2) --}}
                        @elseif($val->kolom=='F')
                          <td style="text-align: right;">
                          @if($val->value<0)
                          {{ '('.number_format(abs(round($val->value)),0,",",".").')' }}
                          @else
                          {{ number_format(round($val->value),0,",",".") }}
                          @endif
                          </td>
                          </tr>
                          <?php $count=0; ?>
                        @endif
                      @endif
                    @endforeach
                  @endif
                 </tbody>
              </table>
            </div>
          </div>
      </div>
    </div>
  </div>     
  
<script type="text/javascript">
  function gantiDistrik1(strategi_bisnisID){
    if(strategi_bisnisID) {
        $.ajax({
            url: "{{url('/output/laba-rugi/ajax/')}}/"+strategi_bisnisID,
            type: "GET",
            dataType: "json",
            success:function(data) {
        // console.log(data);
              $('select[name="distrik1"]').empty();
              $.each(data, function(sb, value) {
                if(value['id']=={{ Request::get('distrik1')+0 }}){
                  $('select[name="distrik1"]').append('<option selected="true" value="'+ value["id"] +'">'+ value["name"] +'</option>');
                  gantiLokasi1(value['id']);
                  $("#distrik-form1").val(value["name"]);
                }else{
                  gantiLokasi1(value['id']);
                  $('select[name="distrik1"]').append('<option value="'+ value["id"] +'">'+ value["name"] +'</option>');
                }
              });

            }
        });
    }else{
        $('select[name="distrik1"]').empty();
    }
  }
  function gantiLokasi1(lokasiID){
    if(lokasiID) {
        $.ajax({
            url: "{{ url('/output/laba-rugi/ajax2/') }}/"+lokasiID,
            type: "GET",
            dataType: "json",
            success:function(data) {

              $('select[name="lokasi1"]').empty();
              console.log(data);
              $('select[name="lokasi1"]').append('<option value=""></option>');
              $.each(data, function(ad , value) {
                console.log(ad);
                if(value['id']=={{ Request::get('lokasi1')+0 }}){
                  $('select[name="lokasi1"]').append('<option selected="true" value="'+ value["id"] +'">'+ value["name"] +'</option>');
                    $("#lokasi-form1").val(value["name"]);
                    gantiDraft1($('select[name="fase1"]').val(), value['id'], $('select[name="tahun1"]').val());
                }else{
                  $('select[name="lokasi1"]').append('<option value="'+ value["id"] +'">'+ value["name"] +'</option>');
                }
              });

              // $('select[name="lokasi1"]').empty();
              // console.log(data);
              // $('select[name="lokasi1"]').append('<option value=""></option>');
              // $.each(data, function(ad, value){
              //   console.log(ad);
              //   $('select[name="lokasi1"]').append('<option selected="true" value="'+ value["id"] +'">'+ value["name"] +'</option>');
              // });
            }
        });
    }else{
        $('select[name="lokasi1"]').empty();
    }
  }
  function gantiDraft1(DraftID, lokasiID, tahun){
    if(DraftID && lokasiID && tahun) {
        $.ajax({
            url: "{{ url('/output/laba-rugi/ajax3/') }}/"+DraftID+"/"+lokasiID+"/"+tahun,
            type: "GET",
            dataType: "json",
            success:function(data) {

              $('select[name="draft1"]').empty();
              console.log(data);
              $('select[name="draft"]').append('<option value="">==Silahkan Pilih draft==</option>');
              $.each(data, function(ad , value) {
                console.log(ad);
                if(value['id']=={{ Request::get('draft1')+0 }}){
                  $('select[name="draft1"]').append('<option selected="true" value="'+ value["id"] +'">'+ value["draft_versi"] +'</option>');
                    $("#draft-form1").val(value["draft_versi"]);
                }else{
                  $('select[name="draft1"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +'</option>');
                }
              });
            }
        });
    }else{
        $('select[name="draft1"]').empty();
    }
  }
  $('select[name="strategi_bisnis1"]').on('change', function() {
    var strategi_bisnisID = $(this).val();
    $('select[name="distrik1"]').empty();
    $('select[name="lokasi1"]').empty();
    gantiDistrik1(strategi_bisnisID);
  });

  $('select[name="distrik1"]').on('change', function() {
    var lokasiID = $(this).val();
    $('select[name="lokasi1"]').empty();
    gantiLokasi1(lokasiID);
  });

  $('select[name="fase1"]').on('change', function() {
    gantiDraft1($(this).val(), $('select[name="lokasi1"]').val(), $('select[name="tahun1"]').val());
  });

  @if(Request::input('strategi_bisnis1'))
      gantiDistrik1({{Request::get('strategi_bisnis1')}});
        $("#tahun-form1").val($("select[name=tahun1]").val());
        $("#strategi-bisnis-form1").val($("select[name=strategi_bisnis1] option:selected").text());
        $("#tahun-form2").val($("select[name=tahun2]").val());
        $("#strategi-bisnis-form2").val($("select[name=strategi_bisnis2] option:selected").text());
        $("#fase-form1").val($("select[name=fase1] option:selected").text());
        $("#fase-form2").val($("select[name=fase2] option:selected").text());
        $("#draft-form1").val($("select[name=draft1] option:selected").text());
        $("#draft-form2").val($("select[name=draft2] option:selected").text());
  @endif

  $("#get-pdf1").click(function(event) {
    var link=$(this).attr('href');
    var param=$("#form1").serialize();
    $(this).attr('href', link+'&'+param);
  });
  $("#get-excel1").click(function(event) {
    var link=$(this).attr('href');
    var param=$("#form1").serialize();
    $(this).attr('href', link+'&'+param);
  });
  $("#get-pdf2").click(function(event) {
    var link=$(this).attr('href');
    var param=$("#form2").serialize();
    $(this).attr('href', link+'&'+param);
  });
  $("#get-excel2").click(function(event) {
    var link=$(this).attr('href');
    var param=$("#form2").serialize();
    $(this).attr('href', link+'&'+param);
  });
</script>

<script type="text/javascript">
  function gantiDistrik2(strategi_bisnisID){
    if(strategi_bisnisID) {
        $.ajax({
            url : "{{url('/output/laba-rugi/ajax/')}}/"+strategi_bisnisID,
            type : "GET",
            dataType : "json",
            success:function(data) {
        // console.log(data);
              $('select[name="distrik2"]').empty();
              $.each(data, function(sb, value) {
                if(value['id']=={{ Request::get('distrik2')+0 }}){
                  $('select[name="distrik2"]').append('<option selected="true" value="'+ value["id"] +'">'+ value["name"] +'</option>');
                    gantiLokasi2(value['id']);
                    $("#distrik-form2").val($("select[name=distrik2] option:selected").text());
                }else{
                  $('select[name="distrik2"]').append('<option value="'+ value["id"] +'">'+ value["name"] +'</option>');
                }
              });

            }
        });
    }else{
        $('select[name="distrik2"]').empty();
    }
  }
  function gantiLokasi2(lokasiID){
    if(lokasiID) {
        $.ajax({
            url : "{{ url('/output/laba-rugi/ajax2/') }}/"+lokasiID,
            type : "GET",
            dataType : "json",
            success : function(data) {

              $('select[name="lokasi2"]').empty();
              console.log(data);
              $.each(data, function(ad , value) {
                console.log(ad);
                if(value['id']=={{ Request::get('lokasi2')+0 }}){
                  $('select[name="lokasi2"]').append('<option selected="true" value="'+ value["id"] +'">'+ value["name"] +'</option>');
                    $("#lokasi-form2").val(value["name"]);
                    // $("#lokasi-form2").val($("select[name=lokasi2] option:selected").text());
                    // setTimeout(function(){
                      gantiDraft2($('select[name="fase2"]').val(), value['id'], $('select[name="tahun2"]').val());
                    // }, 1000);
                }else{
                  $('select[name="lokasi2"]').append('<option value="'+ value["id"] +'">'+ value["name"] +'</option>');
                }
              });
            }
        });
    }else{
        $('select[name="lokasi2"]').empty();
    }
  }
  function gantiDraft2(DraftID, lokasiID, tahun){
    if(DraftID && lokasiID && tahun) {
        $.ajax({
            url: "{{ url('/output/laba-rugi/ajax3/') }}/"+DraftID+"/"+lokasiID+"/"+tahun,
            type: "GET",
            dataType: "json",
            success:function(data) {

              $('select[name="draft2"]').empty();
              console.log(data);
              $('select[name="draft"]').append('<option value="">==Silahkan Pilih draft==</option>');
              $.each(data, function(ad , value) {
                console.log(ad);
                if(value['id']=={{ Request::get('draft2')+0 }}){
                  $('select[name="draft2"]').append('<option selected="true" value="'+ value["id"] +'">'+ value["draft_versi"] +'</option>');
                    $("#draft-form2").val(value["draft_versi"]);
                }else{
                  $('select[name="draft2"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +'</option>');
                }
              });
            }
        });
    }else{
        $('select[name="draft2"]').empty();
    }
  }
  $('select[name="strategi_bisnis2"]').on('change', function() {
    var strategi_bisnisID = $(this).val();
    $('select[name="distrik2"]').empty();
    $('select[name="lokasi2"]').empty();
    gantiDistrik2(strategi_bisnisID);
  });
  $('select[name="distrik2"]').on('change', function() {
    var lokasiID = $(this).val();
    $('select[name="lokasi2"]').empty();
    gantiLokasi2(lokasiID);
  });

  $('select[name="fase2"]').on('change', function() {
    gantiDraft2($(this).val(), $('select[name="lokasi2"]').val(), $('select[name="tahun2"]').val());
  });

  @if(Request::input('strategi_bisnis2'))
    gantiDistrik2({{Request::get('strategi_bisnis2')}});
  @endif

  $("#cari").click(function () {
    var inputStrategi = $('#strategi-bisnis-input option:selected').text();
    // alert(inputStrategi);
    $('#strategi-bisnis-form').val(inputStrategi);
    // document.getElementByid("#strategi_bisnis-form").innerHTML = inputStrategi;

    var inputDistrik = $('#distrik-input option:selected').text();
    $('#distrik-form').val(inputDistrik);

    var inputLokasi = $('#lokasi-input option:selected').text();
    $('#lokasi-form').val(inputLokasi);
  });
</script>

@endsection
