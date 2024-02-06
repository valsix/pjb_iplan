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
    <h3>MONITORING PRK AO</h3>
    <div class="row">

    <!-- <div class="col-md-12 col-sm-12 col-xs-12">
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
                                                    url: "{{ url('/output/loader-ellipse/ajax/') }}/"+strategi_bisnisID,
                                                    type: "GET",
                                                    dataType: "json",
                                                    success:function(data) {
                                                console.log(data);
                                                      $('select[name="distrik"]').empty();
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
                                        @endif
                                    </select>
                                </div>

                                <script type="text/javascript">
                                  function check() {
                                        var lokasiID = $(this).val();
                                        $('select[name="lokasi"]').empty();

                                        if(lokasiID) {
                                            $.ajax({
                                                url: "{{ url('output/loader-ellipse/ajax2/') }}/"+lokasiID,
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
                                            console.log(lokasiID);

                                            if(lokasiID) {
                                                $.ajax({
                                                    url: '/output/loader-ellipse/ajax2/'+lokasiID,
                                                    type: "GET",
                                                    dataType: "json",
                                                    success:function(data) {

                                                      $('select[name="lokasi"]').empty();
                                                      console.log("waw");
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
                                          <option value="{{ $value->id }}" <?php// if($input_fase!= null) echo($input_fase->id == $value->id ? 'selected' : '')?>> {{ $value->name }} </option>
                                      @endforeach
                                  </select>
                                </div>

                                <div class="col-md-2"><label>Draft RKAU</label></div>
                                <div class="col-md-3">
                                  <select class="form-control" name="draft_rkau">
                                      <option value="" disabled="">-- Pilih Draft RKAU --</option>

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
                                              url: "{{ url('/output/loader-ellipse/ajax3/') }}/"+id_lokasi+"/"+id_tahun,
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

                                <br>
                                <br>
                                <div class="col-md-2"><label></label></div>
                                <div class="col-md-4">

                                </div>


                                <script type="text/javascript">
                                  $(document).ready(function() {
                                    $('select[name="lokasi"]').on('change', function() {
                                      var id_lokasi = $(this).val();
                                      var id_tahun = $('select[name="tahun_anggaran"]').val();

                                      $('select[name="draft_form_6_reimburse"]').empty();

                                      if(id_lokasi && id_tahun) {
                                          $.ajax({
                                              url: "{{ url('/output/loader-ellipse/ajax4/') }}/"+id_lokasi+"/"+id_tahun,
                                              type: "GET",
                                              dataType: "json",
                                              success:function(data) {

                                                $.each(data, function(ad , value) {
                                                    $('select[name="draft_form_6_reimburse"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +'</option>');
                                                });

                                              }
                                          });
                                      }else{
                                          $('select[name="draft_form_6_reimburse"]').empty();

                                      }
                                    })
                                  })
                                </script>

                                <br>
                                <br>
                                <div class="col-md-2"><label></label></div>
                                <div class="col-md-4">

                                </div>

                                <div class="col-md-2"><label>Draft Form 6 Rutin</label></div>
                                <div class="col-md-3">
                                  <select class="form-control" name="draft_form_6_rutin" >
                                    <option value="" disabled="">-- Pilih Draft Form 6 Rutin --</option>

                                  </select>
                                </div>

                                <script type="text/javascript">
                                  $(document).ready(function() {
                                    $('select[name="lokasi"]').on('change', function() {
                                      var id_lokasi = $(this).val();
                                      var id_tahun = $('select[name="tahun_anggaran"]').val();

                                      $('select[name="draft_form_6_rutin"]').empty();

                                      if(id_lokasi && id_tahun) {
                                          $.ajax({
                                              url: "{{ url('/output/loader-ellipse/ajax5/') }}/"+id_lokasi+"/"+id_tahun,
                                              type: "GET",
                                              dataType: "json",
                                              success:function(data) {

                                                $.each(data, function(ad , value) {
                                                    $('select[name="draft_form_6_rutin"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +'</option>');
                                                });

                                              }
                                          });
                                      }else{
                                          $('select[name="draft_form_6_rutin"]').empty();

                                      }
                                    })
                                  })
                                </script>

                                <br>
                                <br>
                                <div class="col-md-2"><label></label></div>
                                <div class="col-md-4">

                                </div>

                                <div class="col-md-2"><label>Draft Form 10 Pengembangan Usaha</label></div>
                                <div class="col-md-3">
                                  <select class="form-control" name="draft_form_10_pu" >
                                      <option value="" disabled="">-- Pilih Draft Form 10 Pengembangan Usaha --</option>

                                  </select>
                                </div>

                                <script type="text/javascript">
                                  $(document).ready(function() {
                                    $('select[name="lokasi"]').on('change', function() {
                                      var id_lokasi = $(this).val();
                                      var id_tahun = $('select[name="tahun_anggaran"]').val();

                                      $('select[name="draft_form_10_pu"]').empty();

                                      if(id_lokasi && id_tahun) {
                                          $.ajax({
                                              url: "{{ url('/output/loader-ellipse/ajax6/') }}/"+id_lokasi+"/"+id_tahun,
                                              type: "GET",
                                              dataType: "json",
                                              success:function(data) {

                                                $.each(data, function(ad , value) {
                                                    $('select[name="draft_form_10_pu"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +'</option>');
                                                });

                                              }
                                          });
                                      }else{
                                          $('select[name="draft_form_10_pu"]').empty();

                                      }
                                    })
                                  })
                                </script>

                                <br>
                                <br>
                                <div class="col-md-6"></div>
                                <div class="col-md-2"><label>Draft Form 10 Penguatan KIT</label></div>
                                <div class="col-md-3">
                                  <select class="form-control" name="draft_form_10_pk" >
                                      <option value="" disabled="">-- Pilih Draft Form 10 Penguatan Kit --</option>

                                  </select>
                                </div>

                                <script type="text/javascript">
                                  $(document).ready(function() {
                                    $('select[name="lokasi"]').on('change', function() {
                                      var id_lokasi = $(this).val();
                                      var id_tahun = $('select[name="tahun_anggaran"]').val();

                                      $('select[name="draft_form_10_pk"]').empty();

                                      if(id_lokasi && id_tahun) {
                                          $.ajax({
                                              url: "{{ url('/output/loader-ellipse/ajax7/') }}/"+id_lokasi+"/"+id_tahun,
                                              type: "GET",
                                              dataType: "json",
                                              success:function(data) {

                                                $.each(data, function(ad , value) {
                                                    $('select[name="draft_form_10_pk"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +'</option>');
                                                });

                                              }
                                          });
                                      }else{
                                          $('select[name="draft_form_10_pk"]').empty();

                                      }
                                    })
                                  })
                                </script>

                                <br>
                                <br>
                                <div class="col-md-6"></div>
                                <div class="col-md-2"><label>Draft Form 10 PLN </label></div>
                                <div class="col-md-3">
                                  <select class="form-control" name="draft_form_10_pln" >
                                      <option value="" disabled="">-- Pilih Draft Form 10 PLN --</option>

                                  </select>
                                </div>

                                <script type="text/javascript">
                                  $(document).ready(function() {
                                    $('select[name="lokasi"]').on('change', function() {
                                      var id_lokasi = $(this).val();
                                      var id_tahun = $('select[name="tahun_anggaran"]').val();

                                      $('select[name="draft_form_10_pln"]').empty();

                                      if(id_lokasi && id_tahun) {
                                          $.ajax({
                                              url: "{{ url('/output/loader-ellipse/ajax8/') }}/"+id_lokasi+"/"+id_tahun,
                                              type: "GET",
                                              dataType: "json",
                                              success:function(data) {

                                                $.each(data, function(ad , value) {
                                                    $('select[name="draft_form_10_pln"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +'</option>');
                                                });

                                              }
                                          });
                                      }else{
                                          $('select[name="draft_form_10_pln"]').empty();

                                      }
                                    })
                                  })
                                </script>

                                <br>
                                <br>
                                <div class="col-md-6"></div>
                                <div class="col-md-2"><label>Draft Form Bahan Bakar </label></div>
                                <div class="col-md-3">
                                  <select class="form-control" name="draft_form_bahan_bakar">
                                      <option value="" disabled="">-- Pilih Draft Form Bahan Bakar --</option>

                                  </select>
                                </div>

                                <br>
                                <br>
                                <div class="col-md-6"></div>
                                <div class="col-md-2"><label>Draft Form Penyusutan</label></div>
                                <div class="col-md-3">
                                  <select class="form-control" name="draft_form_penyusutan" >
                                    <option value="" disabled="">-- Pilih Draft Form Penyusutan --</option>

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
            <form class="form-horizontal form-label-left">

            <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12">Tahun Anggaran</label>
              <div class="col-md-4 col-sm-4 col-xs-12">
                <!-- <select class="form-control col-md-7 col-xs-12" name="tahun_anggaran">
                  <option>- Pilih Tahun -</option>
                    @foreach($tahun as $th)
                      <option value="{{$th->tahun}}" @if($input_tahun == $th->tahun) {{'selected'}} @endif>{{$th->tahun}}</option>
                    @endforeach
                </select> -->
                <input type="text" class="form-control col-md-7 col-xs-12" name="tahun_anggaran" readonly="readonly" value="{{$input_tahun}}">
              </div>

              <div class="form-group">
              <label class="col-md-2 col-sm-3 col-xs-12">Struktur Bisnis</label>
              <div class="col-md-3 col-sm-4 col-xs-12">
                <!-- <select class="form-control col-md-7 col-xs-12" name="strategi_bisnis">
                  <option>- Pilih Struktur Bisnis -</option>
                    @foreach ($sb as $sbs => $value)
                      <option value="{{ $value->id }}" <?php if($input_sb!=null) echo($input_sb->id == $value->id ? 'selected' : '')?> > {{ $value->name }} </option>
                    @endforeach
                </select> -->
                <input type="text" class="form-control col-md-7 col-xs-12" name="strategi_bisnis" readonly="readonly" value="{{($input_sb!=null) ? $input_sb->name : ''}}">
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
                <input type="text" class="form-control col-md-7 col-xs-12" name="distrik" readonly="readonly" value="{{($input_distrik!=null) ? $input_distrik->name : ''}}">
              </div>

              <script type="text/javascript">
                $(document).ready(function() {
                    $('select[name="strategi_bisnis"]').on('change', function() {
                        var strategi_bisnisID = $(this).val();
                        $('select[name="distrik"]').empty();
                        $('select[name="lokasi"]').empty();

                        if(strategi_bisnisID) {
                            $.ajax({
                                url: "{{ url('/output/loader-ellipse/ajax/') }}/"+strategi_bisnisID,
                                type: "GET",
                                dataType: "json",
                                success:function(data) {
                            console.log(data);
                                  $('select[name="distrik"]').empty();
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
              <label class="col-md-2 col-sm-3 col-xs-12">Lokasi</label>
              <div class="col-md-3 col-sm-4 col-xs-12">
                <!-- <select class="form-control col-md-7 col-xs-12" name="lokasi">
                  <option>- Pilih Lokasi -</option>
                    @if($input_sb!=null && $input_distrik!=null && $input_lokasi!=null)
                    @endif
                </select> -->
                <input type="text" class="form-control col-md-7 col-xs-12" name="lokasi" readonly="readonly" 
                @if($input_lokasi)
                  <?php $val = null; ?>
                  <?php $ival = null; ?>
                  @foreach($input_lokasi as $l)
                  <?php
                    $ival++;
                    if($ival==1)  
                      $val = $val.' '.$l->name; 
                    else
                      $val = $val.', '.$l->name; 
                  ?>
                  @endforeach
                  value="{{ $val }}"
                @else
                  value=""
                @endif
                >
              </div>
            </div>
            </div>

            <script type="text/javascript">
              function check() {
                  var lokasiID = $(this).val();
                  $('select[name="lokasi"]').empty();

                  if(lokasiID) {
                      $.ajax({
                          url: "{{ url('output/loader-ellipse/ajax2/') }}/"+lokasiID,
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
                      console.log(lokasiID);

                      if(lokasiID) {
                          $.ajax({
                              url: '/output/loader-ellipse/ajax2/'+lokasiID,
                              type: "GET",
                              dataType: "json",
                              success:function(data) {

                                $('select[name="lokasi"]').empty();
                                console.log("waw");
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

                  }

              $(document).ready(function() {
                  $('select[name="distrik"]').on('change', check);
                  $('select[name="distrik"]').on('click', check);

              });
            </script>

            <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12">Fase</label>
              <div class="col-md-4 col-sm-4 col-xs-12">
                <!-- <select class="form-control col-md-7 col-xs-12" name="fase">
                  <option>- Pilih Fase -</option>
                    @foreach ($fase as $fases => $value)
                      <option value="{{ $value->id }}" <?php if($input_fase!= null) echo($input_fase->id == $value->id ? 'selected' : '')?>> {{ $value->name }} </option>
                    @endforeach
                </select> -->
                <input type="text" name="fase" value="{{($input_fase) ? $input_fase->name : ''}}" class="form-control col-md-7 col-xs-12" readonly="readonly">
              </div>

              <div class="form-group">
                <label class="col-md-2 col-sm-3 col-xs-12">s.d Bulan</label>
                <div class="col-md-3 col-sm-4 col-xs-12">
                  <input type="text" name="fase" value="{{ $nama_bln_dipilih }}" class="form-control col-md-7 col-xs-12" readonly="readonly">
                </div>
              </div>
            </div>
            <hr>

            <div class="form-group" style="margin-top: 5px;">
              <div class="form-group">
                <label class="col-md-3 col-sm-3 col-xs-12">RKAU</label>
                <div class="col-md-6 col-sm-4 col-xs-12">
                  <input class="form-control col-md-7 col-xs-12" value = "{{ $name_draft_rkau }}" name="draft_rkau" readonly="true">
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
                          url: "{{ url('/output/loader-ellipse/ajax3/') }}/"+id_lokasi+"/"+id_tahun,
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

            <div class="form-group" style="margin-top: 5px;">
              <div class="form-group">
                <label class="col-md-3 col-sm-3 col-xs-12">Form 6 Reimburse</label>
                <div class="col-md-6 col-sm-4 col-xs-12">
                  <input class="form-control col-md-7 col-xs-12" value="{{ $name_draft_form_6_reimburse }}" readonly="true" required name="draft_form_6_reimburse">
                </div>
              </div>
            </div>

            <div class="form-group" style="margin-top: 5px;">
              <div class="form-group">
                <label class="col-md-3 col-sm-3 col-xs-12">Form 6 Rutin</label>
                <div class="col-md-6 col-sm-4 col-xs-12">
                  <input class="form-control col-md-7 col-xs-12" value="{{ $name_draft_form_6_rutin }}" readonly="true" required name="draft_form_6_rutin">
                </div>
              </div>
            </div>

            <div class="form-group" style="margin-top: 5px;">
              <div class="form-group">
                <label class="col-md-3 col-sm-3 col-xs-12">Form Bahan Bakar</label>
                <div class="col-md-6 col-sm-4 col-xs-12">
                  <input class="form-control col-md-7 col-xs-12" value="{{ $name_draft_form_bahan_bakar }}" readonly="true" required name="draft_form_bahan_bakar">
                </div>
              </div>
            </div>

            <div class="form-group" style="margin-top: 5px;">
              <div class="form-group">
                <label class="col-md-3 col-sm-3 col-xs-12">Form Penyusutan</label>
                <div class="col-md-6 col-sm-4 col-xs-12">
                  <input class="form-control col-md-7 col-xs-12" value="{{ $name_draft_form_penyusutan }}" readonly="true" required name="draft_form_penyusutan">
                </div>
              </div>
            </div>

            <script type="text/javascript">
              $(document).ready(function() {
                $('select[name="lokasi"]').on('change', function() {
                  var id_lokasi = $(this).val();
                  var id_tahun = $('select[name="tahun_anggaran"]').val();

                  $('select[name="draft_form_6_reimburse"]').empty();

                  if(id_lokasi && id_tahun) {
                      $.ajax({
                          url: "{{ url('/output/loader-ellipse/ajax4/') }}/"+id_lokasi+"/"+id_tahun,
                          type: "GET",
                          dataType: "json",
                          success:function(data) {

                            $.each(data, function(ad , value) {
                                $('select[name="draft_form_6_reimburse"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +'</option>');
                            });

                          }
                      });
                  }else{
                      $('select[name="draft_form_6_reimburse"]').empty();

                  }
                })
              })
            </script>

            <script type="text/javascript">
              $(document).ready(function() {
                $('select[name="lokasi"]').on('change', function() {
                  var id_lokasi = $(this).val();
                  var id_tahun = $('select[name="tahun_anggaran"]').val();

                  $('select[name="draft_form_6_rutin"]').empty();

                  if(id_lokasi && id_tahun) {
                      $.ajax({
                          url: "{{ url('/output/loader-ellipse/ajax5/') }}/"+id_lokasi+"/"+id_tahun,
                          type: "GET",
                          dataType: "json",
                          success:function(data) {

                            $.each(data, function(ad , value) {
                                $('select[name="draft_form_6_rutin"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +'</option>');
                            });

                          }
                      });
                  }else{
                      $('select[name="draft_form_6_rutin"]').empty();

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
              </div>   -->

            </form>
          </div>
        </div>
      </div>
    </div>

    @if($input_distrik!=null)
    <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <a href="{{ Request::fullUrl() }}&download=rincian-biaya-administrasi&type=excel" id="get-excel" class="btn btn-success pull-right"><i class="fa fa-download"></i> Download Excel</a>
                    <h2 style="font-size: 18px;">MONITORING PRK AO</h2>
                    <div class="clearfix"></div>

                  </div>

                  <div style="overflow-x:auto;">
                    <table id="table-monitoring-prk-ao" class="table table-striped table-bordered" cellspacing="0" width="100%" style="height: 100px !important;font-size:11px;">
                       <thead style="background:#2A3F54;color:white;">
                         <tr>
                           <th rowspan="2" style="vertical-align:middle">No</th>
                           <th rowspan="2" style="vertical-align:middle">Identity PRK Parent</th>
                           <th rowspan="2" style="vertical-align:middle">Identity PRK Inti</th>
                           <th rowspan="2" style="vertical-align:middle">PRK Kegiatan</th>
                           <th rowspan="2" style="vertical-align:middle">Identity PRK Kegiatan</th>
                           <!--RENCANA-->
                            <th colspan="2">
                             Rencana
                           </th>
                           <!--RENCANA Update-->
                            <th colspan="3">
                             Rencana Update
                           </th>
                           <th>Realisasi</th>

                           <th>Estimasi Realisasi</th>
                             <!-- End of rencena -->
                         </tr>
                         <tr>
                           <th>Ijin Proses</th>
                           <th>Beban</th>

                           <th>Ijin Proses</th>
                           <th>Beban </th>
                           <th>Beban s.d Bulan</th>

                           <th>Beban s.d Bulan</th>

                           <th>Beban </th>

                         </tr>
                       </thead>
                       <body>

                  <!-- form rkau, 6, 10 -->
                  <?php $baris = 0; ?>
                  <?php $urut = 1;?>

                    @foreach($dataparent as $key_form => $parent_per_form)
                        @foreach($parent_per_form as $key_parent => $parent)
                          @foreach($datainti[$key_form] as $key_inti=>$inti)
                            @if($inti['prk_parent'] == $key_parent)
                              @foreach($datakegiatan[$key_form] as $key_kegiatan => $kegiatan)
                                @if($kegiatan['prk_inti'] == $key_inti)

                              <!-- parent -->
                              @if($key_parent!= '')
                              <?php $baris = $baris+1; $warna=($baris % 2 == 0) ? "#ffffff" : "#E8EDEF"; ?>
                              <tr style="background-color: <?= $warna?>">
                                <td><?php echo $urut++;?></td>

                                <td>{{substr($parent['desc_prk_parent'],0,40)}}</td><!-- <th>Nomor Project /PRK</th> -->
                                <!-- inti -->

                                <td>{{substr($inti['desc_prk_inti'],0,40)}}</td><!-- <th>Nomor Project /PRK</th> -->
                                <!-- kegiatan -->
                                        @if(strlen($kegiatan['prk_kegiatan'])!=8)
                                        <td>{{substr($kegiatan['prk_kegiatan'],2,8)}}</td><!-- <th>PRK kegiatan</th> -->
                                        @else
                                        <td>{{$kegiatan['prk_kegiatan']}}</td>
                                        @endif
                                        <!--Rencana-->
                                        <td>{{substr($kegiatan['desc_prk_kegiatan'],0,40)}}</td><!-- <th>Identity PRK Kegiatan</th> -->
                                        <td style="text-align: right;">{{ number_format(1000 * $kegiatan['ijin_proses'],2,",",".") }}</td><!-- <th>Ijin Proses (LAB)</th> -->
                                        <td style="text-align: right;">{{ number_format(1000 * $kegiatan['beban_mat'],2,",",".") }}</td><!-- <th>Beban (MAT)</th> -->
                                        <!--Rencana Update-->
                                        <td style="text-align: right;">{{ number_format(1000 * $kegiatan['ijin_proses_update'],2,",",".") }}</td><!-- <th>Ijin Proses (LAB)</th> -->
                                        <td style="text-align: right;">{{ number_format(1000 * $kegiatan['beban_mat_update'],2,",",".") }}</td><!-- <th>Beban (MAT)</th> -->
                                        <td style="text-align: right;">{{ number_format(1000 * $kegiatan['disburse_sd_bulan'],2,",",".") }}</td><!-- <th>Beban (MAT) sd Bulan</th> -->
                                        <!--Realisasi-->
                                        <td style="text-align: right;">{{ number_format($kegiatan['disburse_sd_bulan_realisasi'],2,",",".") }}</td><!-- <th>Beban (MAT) sd Bulan</th> -->
									
										<!--202012 Monitoring PRK AO -->
										 <td style="text-align: right;">{{ number_format($kegiatan['estimate_realisasi'],2,",",".") }}</td><!-- <th>Beban (MAT)</th> -->
                                    </tr>
                                    @endif
                                    @endif
                                @endforeach <!-- end of inti -->
                                @endif
                            @endforeach <!-- end of inti -->
                        @endforeach <!-- end of parent -->
                        @endforeach
                        <!-- end of form rkau, 6 dan 10 -->

                       </body>
                    </table>
                    </div>
                  </div>
                </div>
            <!-- </div> -->
    </div>
    @endif
          </div>

<!--
<script type="text/javascript">
  $(document).ready(function() {
    $('select[name="lokasi"]').on('change', function() {
      var id_lokasi = $(this).val();
      var id_tahun = $('select[name="tahun_anggaran"]').val();

      $('select[name="draft_form_bahan_bakar"]').empty();

      if(id_lokasi && id_tahun) {
          $.ajax({
              url: "{{ url('/output/loader-ellipse/ajax9/') }}/"+id_lokasi+"/"+id_tahun,
              type: "GET",
              dataType: "json",
              success:function(data) {

                $.each(data, function(ad , value) {
                    $('select[name="draft_form_bahan_bakar"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +'</option>');
                });

              }
          });
      }else{
          $('select[name="draft_form_bahan_bakar"]').empty();

      }
    })
  })
</script>
<script type="text/javascript">
  $(document).ready(function() {
    $('select[name="lokasi"]').on('change', function() {
      var id_lokasi = $(this).val();
      var id_tahun = $('select[name="tahun_anggaran"]').val();

      $('select[name="draft_form_penyusutan"]').empty();

      if(id_lokasi && id_tahun) {
          $.ajax({
              url: "{{ url('/output/loader-ellipse/ajax10/') }}/"+id_lokasi+"/"+id_tahun,
              type: "GET",
              dataType: "json",
              success:function(data) {

                $.each(data, function(ad , value) {
                    $('select[name="draft_form_penyusutan"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +'</option>');
                });

              }
          });
      }else{
          $('select[name="draft_form_penyusutan"]').empty();

      }
    })
  })
</script>
-->
@endsection

@section('js_page')
<script type="text/javascript">
  $(document).ready(function() {
    var table = $('#table-monitoring-prk-ao').DataTable( {
        "aLengthMenu": [[10, 25, 50, 100, -1],
        [10, 25, 50, 100, "All"]],
        "scrollY": "300px",
        "scrollX": "100%",
        "scrollCollapse": true,
        "paging": true,
        // pagingType: "full_numbers",
        fixedHeader: true,
        ordering: false
    } );

    $('#menu_toggle').click(function() {
      setTimeout(function() {
          table.draw();
          }, 500 );
      } );
//     $('#table-monitoring-prk-ao_filter label input').on( 'keyup', function () {
//     table
//         .columns( 0 )
//         .search( this.value )
//         .draw();
// } );
} );
</script>
@endsection
