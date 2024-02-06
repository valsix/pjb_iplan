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
         table .collapse.in {
         display:table-row;
        }
        th{
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

        table th,table td{
          /*fixed width*/
            width:240px; 
        }

        /*Update line height & font-size*/
        .table thead tr th{
          line-height: 1;
        }
        .table tbody tr td{
          /*Untuk data yang deskripsi panjang*/
          line-height: 1; 

          /*Untuk data yang tidak ada deskripsi panjang*/
          /*line-height: 0.5; */
        }
        .table {
          font-size: 11px;
        }

        .form-horizontal .form-group
        {
            margin-right: 0;
            margin-left: 0;
            margin-top: -13px;
        }
    </style>
@endsection

@section('content')
    <h3> Mitigasi Resiko </h3>

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
                <label class="col-md-3 col-sm-3 col-xs-12">Tahun Anggaran</label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <!-- <select class="form-control col-md-7 col-xs-12" name="tahun" >
                      <option>- Pilih Tahun -</option>
                        @foreach($tahun as $th)
                          <option value="{{$th->tahun}}" @if($input_tahun == $th->tahun) {{'selected'}} @endif>{{$th->tahun}}</option>
                        @endforeach
                  </select> -->
                  <input type="text" name="tahun_anggaran" class="form-control col-md-7 col-xs-12" value="{{$input_tahun}}" readonly="readonly" />
                </div>

                <div class="form-group">
                <label class="col-md-2 col-sm-3 col-xs-12">Struktur Bisnis</label>
                <div class="col-md-3 col-sm-4 col-xs-12">
                  <!-- <select class="form-control col-md-7 col-xs-12" name="strategi_bisnis">
                      <option value="">- Pilih Struktur Bisnis -</option>
                        @foreach ($sb as $sbs => $value)
                          <option value="{{ $value->id }}" <?php if($input_sb!=null) echo($input_sb->id == $value->id ? 'selected' : '')?> > {{ $value->name }} </option>
                        @endforeach
                  </select> -->
                  <input type="text" name="strategi_bisnis" class="form-control col-md-7 col-xs-12" value="{{($input_sb!=null)? $input_sb->name : '' }}" readonly="readonly" />
                </div>
                </div>
              </div>

              <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12" >Distrik</label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <!-- <select class="form-control col-md-7 col-xs-12" name="distrik">
                    <option>- Pilih Distrik -</option>
                      @if($input_sb!=null && $input_distrik!=null)
                        @foreach($distrik as $d)
                          <option value="{{$d->id}}" <?php echo($d->id == $input_distrik->id ? 'selected' : '')?>>{{$d->name}}</option>
                        @endforeach
                      @endif
                  </select> -->
                  <input type="text" name="distrik" class="form-control col-md-7 col-xs-12" value="{{($input_distrik!=null)? $input_distrik->name : '' }}" readonly="readonly" />
                </div>

                <script type="text/javascript">
                  $(document).ready(function() {
                      $('select[name="strategi_bisnis"]').on('change', function() {
                          var strategi_bisnisID = $(this).val();
                          $('select[name="distrik"]').empty();
                          $('select[name="lokasi"]').empty();

                          if(strategi_bisnisID) {
                              $.ajax({
                                  url: '{{Url("/output/mitigasi-risiko/ajax/")}}'+'/'+strategi_bisnisID,
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
               <label class="col-md-2 col-sm-3 col-xs-12">Lokasi</label>
                <div class="col-md-3 col-sm-4 col-xs-12">
                  <!-- <select class="form-control col-md-7 col-xs-12" name="lokasi" id="lokasi">
                    <option>- Pilih Lokasi -</option>
                      @if($input_sb!=null && $input_distrik!=null && $input_lokasi!=null)
                        @foreach($lokasi as $l)
                          <option value="{{$l->id}}" <?php echo($l->id == $input_lokasi->id ? 'selected' : '')?>>{{$l->name}}</option>
                        @endforeach
                      @endif
                  </select> -->
                  <input type="text" name="lokasi" class="form-control col-md-7 col-xs-12" value="{{($input_lokasi!=null)? $input_lokasi->name : ''}}" readonly="readonly" />
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
                                url: "{{url('/output/mitigasi-risiko/ajax2/')}}"+'/'+lokasiID,
                                type: "GET",
                                dataType: "json",
                                success:function(data) {
                                  var m = "";
                                  $.each(data, function(ad , value) {
                                      m += '<option value="'+value["id"]+'">'+value["name"]+'</option>';
                                  });
                                      $('select[name="lokasi"]').append('<option value="">Pilih Lokasi</option>'+m);

                                }
                            });
                        }else{
                            $('select[name="lokasi"]').empty();

                        }
                    });
                });
              </script>

              <div class="form-group">
                <label class="col-md-3 col-sm-3 col-xs-12">Fase</label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <!-- <select class="form-control col-md-7 col-xs-12" name="fase">
                    <option>- Pilih Fase -</option>
                      @foreach ($fs as $fases => $value)
                        <option value="{{ $value->id }}" <?php if($input_fase!= null) echo($input_fase->id == $value->id ? 'selected' : '')?>> {{ $value->name }} </option>
                      @endforeach
                  </select> -->
                  <input type="text" name="fase" class="form-control col-md-7 col-xs-12" value="{{($input_fase!= null) ? $input_fase->name : '' }}" readonly="readonly" />
                </div>

                <script type="text/javascript">
                  $(document).ready(function() {
                    $('select[name="lokasi"]').on('change', function() {
                      var id_lokasi = $(this).val();
                      var id_tahun = $('select[name="tahun"]').val();

                      $('select[name="reimburse"]').empty();

                      if(id_lokasi && id_tahun) {
                          $.ajax({
                              url: "{{url('/output/mitigasi-risiko/ajax3/')}}"+'/'+id_lokasi+"/"+id_tahun,
                              type: "GET",
                              dataType: "json",
                              success:function(data) {
                                //console.log(data);
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
                <label class="col-md-3 col-sm-3 col-xs-12">Form 6 - Reimburse</label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <!-- <select class="form-control col-md-7 col-xs-12" name="reimburse">
                    <option>- Pilih Draft -</option>
                      @if($input_sb!=null && $input_form_6_reimburse!=null && $input_lokasi!=null)
                        @foreach($drafts_form_6_reimburse as $d)
                          <option value="{{$d->id}}" <?php echo($d->id == $input_form_6_reimburse->id ? 'selected' : '')?>>{{$d->draft_versi}}</option>
                        @endforeach
                      @endif
                  </select> -->
                  <input type="text" name="reimburse" value="{{ ($input_form_6_reimburse!=null) ? $input_form_6_reimburse->draft_versi.' - '.$input_form_6_reimburse->name : '' }}" class="form-control col-md-7 col-xs-12" readonly="readonly">
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
                            url: "{{url('/output/mitigasi-risiko/ajax4/')}}"+'/'+id_lokasi+"/"+id_tahun,
                            type: "GET",
                            dataType: "json",
                            success:function(data) {
                              //console.log(data);
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

              <div class="form-group" style="margin-top: 5px;">
                <!-- <label class="col-md-2 col-sm-3 col-xs-12"></label>
                <div class="col-md-4 col-sm-4 col-xs-12"></div> -->

                <div class="form-group">
                <label class="col-md-3 col-sm-3 col-xs-12">Form 6 - Rutin</label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <!-- <select class="form-control col-md-7 col-xs-12" name="rutin">
                    <option>- Pilih Draft -</option>
                      @if($input_sb!=null && $input_form_6_rutin!=null && $input_lokasi!=null)
                        @foreach($drafts_form_6_rutin as $d)
                          <option value="{{$d->id}}" <?php echo($d->id == $input_form_6_rutin->id ? 'selected' : '')?>>{{$d->draft_versi}}</option>
                        @endforeach
                      @endif
                  </select> -->
                  <input type="text" name="rutin" value="{{ ($input_form_6_rutin!= null) ? $input_form_6_rutin->draft_versi.' - '.$input_form_6_rutin->name : '' }}" class="form-control col-md-7 col-xs-12" readonly="readonly">
                </div>
                </div>
              </div>

              <script type="text/javascript">
                $(document).ready(function() {
                  $('select[name="lokasi"]').on('change', function() {
                    console.log("masuk");
                    var id_lokasi = $(this).val();
                    var id_tahun = $('select[name="tahun"]').val();

                    $('select[name="usaha"]').empty();

                    if(id_lokasi && id_tahun) {
                        $.ajax({
                            url: "{{url('/output/mitigasi-risiko/ajax5/')}}"+'/'+id_lokasi+"/"+id_tahun,
                            type: "GET",
                            dataType: "json",
                            success:function(data) {
                              console.log(data);
                              $.each(data, function(ad , value) {
                                  $('select[name="usaha"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +'</option>');
                              });

                            }
                        });
                    }else{
                        $('select[name="usaha"]').empty();

                    }
                  })
                })
              </script>

              <div class="form-group" style="margin-top: 5px;">
                <!-- <label class="col-md-2 col-sm-3 col-xs-12"></label>
                <div class="col-md-4 col-sm-4 col-xs-12"></div> -->

                <div class="form-group">
                <label class="col-md-3 col-sm-3 col-xs-12">Form 10 - Pengembangan Usaha</label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <!-- <select class="form-control col-md-7 col-xs-12" name="usaha">
                    <option>- Pilih Draft -</option>
                      @if($input_sb!=null && $input_form_10_pu!=null && $input_lokasi!=null)
                        @foreach($drafts_form_10_pu as $d)
                          <option value="{{$d->id}}" <?php echo($d->id == $input_form_10_pu->id ? 'selected' : '')?>>{{$d->draft_versi}}</option>
                        @endforeach
                      @endif
                  </select> -->
                  <input type="text" name="usaha" value="{{ ($input_form_10_pu!= null) ? $input_form_10_pu->draft_versi.' - '.$input_form_10_pu->name : '' }}" class="form-control col-md-7 col-xs-12" readonly="readonly">
                </div>
               </div>
              </div>

              <script type="text/javascript">
                $(document).ready(function() {
                  $('select[name="lokasi"]').on('change', function() {
                    console.log("masuk");
                    var id_lokasi = $(this).val();
                    var id_tahun = $('select[name="tahun"]').val();

                    $('select[name="kit"]').empty();

                    if(id_lokasi && id_tahun) {
                        $.ajax({
                            url: "{{url('/output/mitigasi-risiko/ajax6/')}}"+'/'+id_lokasi+"/"+id_tahun,
                            type: "GET",
                            dataType: "json",
                            success:function(data) {
                            console.log(data);
                              $.each(data, function(ad , value) {
                                  $('select[name="kit"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +'</option>');
                              });

                            }
                        });
                    }else{
                        $('select[name="kit"]').empty();

                    }
                  })
                })
              </script>

              <div class="form-group" style="margin-top: 5px;">
                <!-- <label class="col-md-2 col-sm-3 col-xs-12"></label>
                <div class="col-md-4 col-sm-4 col-xs-12"></div> -->

                <div class="form-group">
                <label class="col-md-3 col-sm-3 col-xs-12">Form 10 - Penguatan Kit</label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <!-- <select class="form-control col-md-7 col-xs-12" name="kit">
                    <option>- Pilih Draft -</option>
                      @if($input_sb!=null && $input_form_10_pk!=null && $input_lokasi!=null)
                        @foreach($drafts_form_10_pk as $d)
                          <option value="{{$d->id}}" <?php echo($d->id == $input_form_10_pk->id ? 'selected' : '')?>>{{$d->draft_versi}}</option>
                        @endforeach
                      @endif
                  </select> -->
                  <input type="text" name="kit" value="{{ ($input_form_10_pk!= null) ? $input_form_10_pk->draft_versi.' - '.$input_form_10_pk->name : '' }}" class="form-control col-md-7 col-xs-12" readonly="readonly">
                 </div>
                </div>
              </div>

              <script type="text/javascript">
                $("select[name='lokasi']").on('change', function(){
                  var id_lokasi = $(this).val();
                  var id_tahun  = $('select[name="tahun"]').val();
                  $('select[name="pln"]').empty();

                  if (id_lokasi && id_tahun) {
                    $.ajax({
                      url: "{{url('/output/mitigasi-risiko/ajax7/')}}"+'/'+id_lokasi+'/'+id_tahun,
                      type: 'GET',
                      dataType: 'json',
                      success: function(dfjk){
                        $.each(dfjk, function(i, l) {
                          $('select[name="pln"]').append('<option value="'+l.id+'">'+l.draft_versi+'</option>');
                        });
                      }
                    })
                  }else{
                    $("select[name='pln']").empty();
                  }

                });
              </script>

              <div class="form-group" style="margin-top: 5px;">
                <!-- <label class="col-md-2 col-sm-3 col-xs-12"></label>
                <div class="col-md-4 col-sm-4 col-xs-12"></div> -->

                <div class="form-group">
                <label class="col-md-3 col-sm-3 col-xs-12">Form 10 - PLN</label>
                 <div class="col-md-4 col-sm-4 col-xs-12">
                  <!-- <select class="form-control col-md-7 col-xs-12" name="pln">
                    <option>- Pilih Draft -</option>
                      @if($input_sb!=null && $input_form_10_pln!=null && $input_lokasi!=null)
                        @foreach($drafts_form_10_pln as $d)
                          <option value="{{$d->id}}" <?php echo($d->id == $input_form_10_pln->id ? 'selected' : '')?>>{{$d->draft_versi}}</option>
                        @endforeach
                      @endif
                    </select> -->
                    <input type="text" name="pln" value="{{ ($input_form_10_pln!= null) ? $input_form_10_pln->draft_versi.' - '.$input_form_10_pln->name : '' }}" class="form-control col-md-7 col-xs-12" readonly="readonly">
                 </div>
                </div>
              </div>

              <script type="text/javascript">
                  $("select[name='lokasi']").on('change', function() {
                    var id_lokasi = $(this).val();
                    var id_tahun  = $("select[name='tahun']").val();
                    $("select[name='register']").empty();
                    if (id_lokasi && id_tahun) {
                      $.ajax({
                        url: '{{url("/output/mitigasi-risiko/ajax8/")}}'+"/"+id_lokasi+"/"+id_tahun,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data){
                          $.each(data, function(a, b) {
                            $("select[name='register']").append('<option value="'+b.id+'">'+b.draft_versi+'</option>');
                          });
                        }
                      })
                    }else{
                      $("select[name='register']").empty();
                    }
                  });
              </script>

              <div class="form-group" style="margin-top: 5px;">
                <!-- <label class="col-md-2 col-sm-3 col-xs-12"></label>
                <div class="col-md-4 col-sm-4 col-xs-12"></div> -->

                <div class="form-group">
                <label class="col-md-3 col-sm-3 col-xs-12">Form Register</label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <!-- <select class="form-control col-md-7 col-xs-12" name="register">
                    <option>- Pilih Draft -</option>
                      @if($input_sb!=null && $input_form_10_register!=null && $input_lokasi!=null)
                        @foreach($drafts_form_10_register as $d)
                          <option value="{{$d->id}}" <?php echo($d->id == $input_form_10_register->id ? 'selected' : '')?>>{{$d->draft_versi}}</option>
                        @endforeach
                      @endif
                  </select> -->
                  <input type="text" name="register" value="{{ ($input_form_10_register!= null) ? $input_form_10_register->draft_versi.' - '.$input_form_10_register->name : '' }}" class="form-control col-md-7 col-xs-12" readonly="readonly">
                </div>
                </div>
              </div>


              <!-- <div class="ln_solid"></div>

                <div class="form-group">
                  <div >
                    <button type="submit" class="btn btn-primary pull-right">
                        <span class="glyphicon glyphicon-search"> </span> cari
                    </button>
                  </div>
                </div>     -->

              </form>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2 style="font-size: 18px;">MITIGASI RESIKO</h2>
              <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>
              <li><a class="close-link"><i class="fa fa-close"></i></a>
            </li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <a class="btn btn-success pull-right" href="{{Request::fullUrl()}}&unduh=excel" target="_blank"><i class="fa fa-download"></i> Download Excel</a>
        <a href="{{Request::fullUrl()}}&unduh=pdf" class="btn btn-primary pull-right" target="_blank"><i class="fa fa-download"></i> Download PDF</a>
        <div class="x_content">
          <table class="table table-striped table-bordered" style="font-size:11px;">
            <thead style="background:#2A3F54;color:white;">
              <tr>
                <th style="vertical-align: middle;">#</th>
                <th style="vertical-align: middle;">Risk Tag</th>
                <th style="vertical-align: middle;">Risk Event</th>
                <th style="vertical-align: middle;">Rencana Program Penanganan Risiko</th>
                <th style="vertical-align: middle;">Nilai Anggaran</th>
              </tr>
            </thead>
            <tbody>
              @if($input_tahun && $input_sb && $input_distrik && $input_lokasi)
              <?php $i = 0; ?>
              @foreach($queryB as $qr)
               <tr class="clickable" data-toggle="collapse" id="row1" data-target=".row1<?php echo $qr->row; ?>">
                <td>
                  <button type="button" class="btn btn-info btn-xs" data-toggle="collapse">Detail Total  <i class="fa fa-chevron-down"></i></button>
                </td>
                <td>@if($qr->kolom == "B"){{$qr->value}} @endif</td>
                <td>@foreach($queryC as $qrc) @if($qrc->row == $qr->row){{$qrc->value}}@endif @endforeach</td>
                <td>
                  <label>Total PRK: </label>&nbsp;<span class="badge bg-green">{{ $total_prk_tiap_risk_profile[$i] }}</span><br>

                </td>
                <td style="text-align: right;">
                  <?php
                    $total1 = Round($totalB[$i]);
                    $t      = number_format($total1,0,',','.');
                    echo $t;
                   ?>
                </td>
              </tr>
              <?php $d = 1; ?>
              @foreach($detail_anggaran_tiap_risk_profile[$i] as $detail_risk_profile)
              @if($d<=5)
              <tr class="collapse row1<?php echo $qr->row; ?>">
                <td></td>
                <td></td>
                <td></td>
                <td style="text-align: left;">{{ $detail_risk_profile['deskripsi'] }}</td>
                <td style="text-align: right;">{{ number_format(round($detail_risk_profile['jumlah']),0,',','.') }}</td>
              </tr>
              @endif
              <?php $d++; ?>
              @endforeach
              <?php $i++; ?>
              @endforeach
              @endif
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
