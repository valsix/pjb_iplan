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

        table  tbody{
           display:block;
           height:500px;
           overflow:auto;//set tbody to auto
        }
        table thead {
            display:block;
        }

        table th,table td{
            width:225px;//fixed width
        }     
    </style>

@endsection

@section('content')
    <h1> Mitigasi Risiko </h1>

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
                                  <select class="form-control" name="tahun" >
                                     <option>- Pilih Tahun -</option>
                                        @foreach($tahun as $th)
                                          <option value="{{$th->tahun}}" @if($input_tahun == $th->tahun) {{'selected'}} @endif>{{$th->tahun}}</option>
                                        @endforeach
                                  </select>
                              </div>
                              <div class="col-md-2"><label>Struktur Bisnis</label></div>
                              <div class="col-md-3">
                                  <select class="form-control" name="strategi_bisnis">
                                     <option value="">- Pilih Struktur Bisnis -</option>
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


                              <div class="col-md-2"><label> Lokasi</label></div>
                              <div class="col-md-3">
                                  <select class="form-control" name="lokasi" id="lokasi">
                                     <option>- Pilih Lokasi -</option>
                                      @if($input_sb!=null && $input_distrik!=null && $input_lokasi!=null)
                                         @foreach($lokasi as $l)
                                         <option value="{{$l->id}}" <?php echo($l->id == $input_lokasi->id ? 'selected' : '')?>>{{$l->name}}</option>
                                         @endforeach
                                      @endif 
                                  </select>
                              </div><br><br>

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

                             <div class="col-md-2"><label>Fase</label></div>
                              <div class="col-md-4">
                                  <select class="form-control" name="fase">
                                     <option>- Pilih Fase -</option>
                                     @foreach ($fs as $fases => $value)
                                          <option value="{{ $value->id }}" <?php if($input_fase!= null) echo($input_fase->id == $value->id ? 'selected' : '')?>> {{ $value->name }} </option>
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

                              <div class="col-md-2"><label>Form 6 - Reimburse</label></div>
                              <div class="col-md-3">
                                  <select class="form-control" name="reimburse">
                                     <option>- Pilih Draft -</option>
                                     @if($input_sb!=null && $input_form_6_reimburse!=null && $input_lokasi!=null)
                                          @foreach($drafts_form_6_reimburse as $d)
                                              <option value="{{$d->id}}" <?php echo($d->id == $input_form_6_reimburse->id ? 'selected' : '')?>>{{$d->draft_versi}}</option>
                                          @endforeach
                                     @endif
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

                              <div class="col-md-6"></div>
                              <div class="col-md-2"><label>Form 6 - Rutin</label></div>
                              <div class="col-md-3">
                                  <select class="form-control" name="rutin">
                                     <option>- Pilih Draft -</option>
                                     @if($input_sb!=null && $input_form_6_rutin!=null && $input_lokasi!=null)
                                        @foreach($drafts_form_6_rutin as $d)
                                            <option value="{{$d->id}}" <?php echo($d->id == $input_form_6_rutin->id ? 'selected' : '')?>>{{$d->draft_versi}}</option>
                                        @endforeach
                                     @endif  
                                  </select>
                              </div><br><br>
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

                              <div class="col-md-6"></div>
                              <div class="col-md-2"><label>form 10 - pengembangan_usaha</label></div>
                              <div class="col-md-3">
                                  <select class="form-control" name="usaha">
                                     <option>- Pilih Draft -</option>
                                     @if($input_sb!=null && $input_form_10_pu!=null && $input_lokasi!=null)
                                        @foreach($drafts_form_10_pu as $d)
                                            <option value="{{$d->id}}" <?php echo($d->id == $input_form_10_pu->id ? 'selected' : '')?>>{{$d->draft_versi}}</option>
                                        @endforeach
                                     @endif
                                  </select>
                              </div><br><br>
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

                              <div class="col-md-6"></div>
                              <div class="col-md-2"><label>form 10 - Penguatan Kit</label></div>
                              <div class="col-md-3">
                                  <select class="form-control" name="kit">
                                     <option>- Pilih Draft -</option>
                                      @if($input_sb!=null && $input_form_10_pk!=null && $input_lokasi!=null)
                                        @foreach($drafts_form_10_pk as $d)
                                            <option value="{{$d->id}}" <?php echo($d->id == $input_form_10_pk->id ? 'selected' : '')?>>{{$d->draft_versi}}</option>
                                        @endforeach
                                      @endif 
                                  </select>
                              </div><br><br>
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

                              <div class="col-md-6"></div>
                              <div class="col-md-2"><label>form 10 - PLN</label></div>
                              <div class="col-md-3">
                                  <select class="form-control" name="pln">
                                     <option>- Pilih Draft -</option>
                                     @if($input_sb!=null && $input_form_10_pln!=null && $input_lokasi!=null)
                                        @foreach($drafts_form_10_pln as $d)
                                            <option value="{{$d->id}}" <?php echo($d->id == $input_form_10_pln->id ? 'selected' : '')?>>{{$d->draft_versi}}</option>
                                        @endforeach
                                     @endif 
                                  </select>
                              </div><br><br>
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

                              <div class="col-md-6"></div>
                              <div class="col-md-2"><label>form Register</label></div>
                              <div class="col-md-3">
                                  <select class="form-control" name="register">
                                     <option>- Pilih Draft -</option>
                                     @if($input_sb!=null && $input_form_10_register!=null && $input_lokasi!=null)
                                        @foreach($drafts_form_10_register as $d)
                                            <option value="{{$d->id}}" <?php echo($d->id == $input_form_10_register->id ? 'selected' : '')?>>{{$d->draft_versi}}</option>
                                        @endforeach
                                     @endif 
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
      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>Table</h2>
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
          <table class="table table-striped table-bordered">
            <thead style="background:#2A3F54;color:white;">
              <tr>
                <th>#</th>
                <th>Risk Tag</th>
                <th>Risk Event</th>
                <th>Rencana Program Penanganan Risiko</th>
                <th>Nilai Anggaran</th>
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