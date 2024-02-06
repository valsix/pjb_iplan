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
    <h3>REPORT RENCANA ANGGARAN UNTUK LOADER ELLIPSE</h3>
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
                                          <option value="{{ $value->id }}" <?php if($input_fase!= null) echo($input_fase->id == $value->id ? 'selected' : '')?>> {{ $value->name }} </option>
                                      @endforeach
                                  </select>
                                </div>

                                <div class="col-md-2"><label>Draft RKAU</label></div>
                                <div class="col-md-3">
                                  <select class="form-control" name="draft_rkau">
                                      <option value="" disabled="">-- Pilih Draft RKAU --</option>
                                      @if($input_draft_rkau!= null)
                                          @foreach($draft_form_rkau as $draft)
                                              <option value="{{$draft->id}}" {{($input_draft_rkau->id == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}}</option>
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

                                <div class="col-md-2"><label>Draft Form 6 Reimburse</label></div>
                                <div class="col-md-3">
                                  <select class="form-control" name="draft_form_6_reimburse" >
                                      <option value="" disabled="">-- Pilih Draft Form 6 Reimburse --</option>
                                      @if($input_draft_form_6_reimburse!= null)
                                          @foreach($draft_form_6_reimburse as $draft)
                                              <option value="{{$draft->id}}" {{($input_draft_form_6_reimburse->id == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}}</option>
                                          @endforeach
                                      @endif
                                  </select>
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
                                      @if($input_draft_form_6_rutin!= null)
                                          @foreach($draft_form_6_rutin as $draft)
                                              <option value="{{$draft->id}}" {{($input_draft_form_6_rutin->id == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}}</option>
                                          @endforeach
                                      @endif
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
                                      @if($input_draft_form_10_pu!= null)
                                          @foreach($draft_form_10_pu as $draft)
                                              <option value="{{$draft->id}}" {{($input_draft_form_10_pu->id == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}}</option>
                                          @endforeach
                                      @endif
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
                                      @if($input_draft_form_10_pk!= null)
                                          @foreach($draft_form_10_pk as $draft)
                                              <option value="{{$draft->id}}" {{($input_draft_form_10_pk->id == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}}</option>
                                          @endforeach
                                      @endif
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
                                      @if($input_draft_form_10_pln!= null)
                                          @foreach($draft_form_10_pln as $draft)
                                              <option value="{{$draft->id}}" {{($input_draft_form_10_pln->id == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}}</option>
                                          @endforeach
                                      @endif
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
                                      @if($input_draft_form_bahan_bakar!= null)
                                          @foreach($draft_form_bahan_bakar as $draft)
                                              <option value="{{$draft->id}}" {{($input_draft_form_bahan_bakar->id == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}}</option>
                                          @endforeach
                                      @endif
                                  </select>
                                </div>

                                <br>
                                <br>
                                <div class="col-md-6"></div>
                                <div class="col-md-2"><label>Draft Form Penyusutan</label></div>
                                <div class="col-md-3">
                                  <select class="form-control" name="draft_form_penyusutan" >
                                    <option value="" disabled="">-- Pilih Draft Form Penyusutan --</option>
                                      @if($input_draft_form_penyusutan!= null)
                                          @foreach($draft_form_penyusutan as $draft)
                                              <option value="{{$draft->id}}" {{($input_draft_form_penyusutan->id == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}}</option>
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
                      @foreach($lokasi as $l)
                        <option value="{{$l->id}}" <?php echo($l->id == $input_lokasi->id ? 'selected' : '')?>>{{$l->name}}</option>
                      @endforeach
                    @endif
                </select> -->
                <input type="text" class="form-control col-md-7 col-xs-12" name="lokasi" readonly="readonly" value="{{($input_lokasi) ? $input_lokasi->name : ''}}">
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
                <label class="col-md-2 col-sm-3 col-xs-12"></label>
                <div class="col-md-3 col-sm-4 col-xs-12"></div>
              </div>
            </div>
            <hr>

            <div class="form-group" style="margin-top: 5px;">
              <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12">Draft RKAU</label>
              <div class="col-md-6 col-sm-4 col-xs-12">
                <!-- <select class="form-control col-md-7 col-xs-12" name="draft_rkau">
                  <option value="" disabled="">-- Pilih Draft RKAU --</option>
                    @if($input_draft_rkau!= null)
                        @foreach($draft_form_rkau as $draft)
                          <option value="{{$draft->id}}" {{($input_draft_rkau->id == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}}</option>
                        @endforeach
                    @endif
                </select> -->
                <input type="text" name="draft_rkau" value="{{ ($input_draft_rkau!= null) ? $input_draft_rkau->draft_versi.' - '.$input_draft_rkau->name : '' }}" class="form-control col-md-7 col-xs-12" readonly="readonly">
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
              <!-- <label class="col-md-2 col-sm-3 col-xs-12"></label>
              <div class="col-md-4 col-sm-4 col-xs-12"></div> -->

              <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12">Draft Form 6 Reimburse</label>
              <div class="col-md-6 col-sm-4 col-xs-12">
                <!-- <select class="form-control col-md-7 col-xs-12" name="draft_form_6_reimburse">
                  <option value="" disabled="">-- Pilih Draft Form 6 Reimburse --</option>
                    @if($input_draft_form_6_reimburse!= null)
                      @foreach($draft_form_6_reimburse as $draft)
                        <option value="{{$draft->id}}" {{($input_draft_form_6_reimburse->id == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}}</option>
                      @endforeach
                    @endif
                </select> -->
                <input type="text" name="draft_form_6_reimburse" value="{{ ($input_draft_form_6_reimburse!= null) ? $input_draft_form_6_reimburse->draft_versi.' - '.$input_draft_form_6_reimburse->name : '' }}" class="form-control col-md-7 col-xs-12" readonly="readonly">
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

            <div class="form-group" style="margin-top: 5px;">
              <!-- <label class="col-md-2 col-sm-3 col-xs-12"></label>
              <div class="col-md-4 col-sm-4 col-xs-12"></div> -->

              <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12">Draft Form 6 Rutin</label>
              <div class="col-md-6 col-sm-4 col-xs-12">
                <!-- <select class="form-control col-md-7 col-xs-12" name="draft_form_6_rutin">
                  <option value="" disabled="">-- Pilih Draft Form 6 Rutin --</option>
                    @if($input_draft_form_6_rutin!= null)
                      @foreach($draft_form_6_rutin as $draft)
                        <option value="{{$draft->id}}" {{($input_draft_form_6_rutin->id == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}}</option>
                      @endforeach
                    @endif
                </select> -->
                <input type="text" name="draft_form_6_rutin" value="{{ ($input_draft_form_6_rutin!= null) ? $input_draft_form_6_rutin->draft_versi.' - '.$input_draft_form_6_rutin->name : '' }}" class="form-control col-md-7 col-xs-12" readonly="readonly">
              </div>
              </div>
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

            <div class="form-group" style="margin-top: 5px;">
              <!-- <label class="col-md-2 col-sm-3 col-xs-12"></label>
              <div class="col-md-4 col-sm-4 col-xs-12"></div> -->

              <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12">Draft Form 10 Pengembangan Usaha</label>
              <div class="col-md-6 col-sm-4 col-xs-12">
                <!-- <select class="form-control col-md-7 col-xs-12"  name="draft_form_10_pu">
                  <option value="" disabled="">-- Pilih Draft Form 10 Pengembangan Usaha --</option>
                    @if($input_draft_form_10_pu!= null)
                      @foreach($draft_form_10_pu as $draft)
                        <option value="{{$draft->id}}" {{($input_draft_form_10_pu->id == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}}</option>
                      @endforeach
                    @endif
                </select> -->
                <input type="text" name="draft_form_10_pu" value="{{ ($input_draft_form_10_pu!= null) ? $input_draft_form_10_pu->draft_versi.' - '.$input_draft_form_10_pu->name : '' }}" class="form-control col-md-7 col-xs-12" readonly="readonly">
              </div>
              </div>
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

            <div class="form-group" style="margin-top: 5px;">
              <!-- <label class="col-md-2 col-sm-3 col-xs-12"></label>
              <div class="col-md-4 col-sm-4 col-xs-12"></div> -->

              <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12">Draft Form 10 Penguatan KIT</label>
              <div class="col-md-6 col-sm-4 col-xs-12">
                <!-- <select class="form-control col-md-7 col-xs-12" name="draft_form_10_pk">
                  <option value="" disabled="">-- Pilih Draft Form 10 Penguatan Kit --</option>
                    @if($input_draft_form_10_pk!= null)
                        @foreach($draft_form_10_pk as $draft)
                          <option value="{{$draft->id}}" {{($input_draft_form_10_pk->id == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}}</option>
                        @endforeach
                    @endif
                </select> -->
                <input type="text" name="draft_form_10_pk" value="{{ ($input_draft_form_10_pk!= null) ? $input_draft_form_10_pk->draft_versi.' - '.$input_draft_form_10_pk->name : '' }}" class="form-control col-md-7 col-xs-12" readonly="readonly">
              </div>
              </div>
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

            <div class="form-group" style="margin-top: 5px;">
              <!-- <label class="col-md-2 col-sm-3 col-xs-12"></label>
              <div class="col-md-4 col-sm-4 col-xs-12"></div> -->

              <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12">Draft Form 10 PLN</label>
              <div class="col-md-6 col-sm-4 col-xs-12">
                <!-- <select class="form-control col-md-7 col-xs-12" name="draft_form_10_pln">
                  <option value="" disabled="">-- Pilih Draft Form 10 PLN --</option>
                    @if($input_draft_form_10_pln!= null)
                      @foreach($draft_form_10_pln as $draft)
                        <option value="{{$draft->id}}" {{($input_draft_form_10_pln->id == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}}</option>
                      @endforeach
                    @endif
                </select> -->
                <input type="text" name="draft_form_10_pln" value="{{ ($input_draft_form_10_pln!= null) ? $input_draft_form_10_pln->draft_versi.' - '.$input_draft_form_10_pln->name : '' }}" class="form-control col-md-7 col-xs-12" readonly="readonly">
              </div>
              </div>
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

            <div class="form-group" style="margin-top: 5px;">
              <!-- <label class="col-md-2 col-sm-3 col-xs-12"></label>
              <div class="col-md-4 col-sm-4 col-xs-12"></div> -->

              <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12">Draft Form Bahan Bakar</label>
              <div class="col-md-6 col-sm-4 col-xs-12">
                <!-- <select class="form-control col-md-7 col-xs-12" name="draft_form_bahan_bakar">
                  <option value="" disabled="">-- Pilih Draft Form Bahan Bakar --</option>
                    @if($input_draft_form_bahan_bakar!= null)
                      @foreach($draft_form_bahan_bakar as $draft)
                        <option value="{{$draft->id}}" {{($input_draft_form_bahan_bakar->id == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}}</option>
                      @endforeach
                    @endif
                </select> -->
                <input type="text" name="draft_form_bahan_bakar" value="{{ ($input_draft_form_bahan_bakar!= null) ? $input_draft_form_bahan_bakar->draft_versi.' - '.$input_draft_form_bahan_bakar->name : '' }}" class="form-control col-md-7 col-xs-12" readonly="readonly">
              </div>
              </div>
            </div>

            <div class="form-group" style="margin-top: 5px;">
              <!-- <label class="col-md-2 col-sm-3 col-xs-12"></label>
              <div class="col-md-4 col-sm-4 col-xs-12"></div> -->

              <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12">Draft Form Penyusutan</label>
              <div class="col-md-6 col-sm-4 col-xs-12">
                <!-- <select class="form-control col-md-7 col-xs-12" name="draft_form_penyusutan">
                  <option value="" disabled="">-- Pilih Draft Form Penyusutan --</option>
                    @if($input_draft_form_penyusutan!= null)
                      @foreach($draft_form_penyusutan as $draft)
                        <option value="{{$draft->id}}" {{($input_draft_form_penyusutan->id == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}}</option>
                      @endforeach
                    @endif
                </select> -->
                <input type="text" name="draft_form_penyusutan" value="{{ ($input_draft_form_penyusutan!= null) ? $input_draft_form_penyusutan->draft_versi.' - '.$input_draft_form_penyusutan->name : '' }}" class="form-control col-md-7 col-xs-12" readonly="readonly">
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
              </div>   -->

            </form>
          </div>
        </div>
      </div>
    </div>

    @if($input_lokasi!=null)
    <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <a href="{{ Request::fullUrl() }}&download=rincian-biaya-administrasi&type=excel" id="get-excel" class="btn btn-success pull-right"><i class="fa fa-download"></i> Download Excel</a>
                    <h2 style="font-size: 18px;">REPORT RENCANA ANGGARAN UNTUK LOADER ELLIPSE</h2>
                    <div class="clearfix"></div>

                  </div>


                  <div style="overflow-x:auto;">
                    <table id="table-loader-ellipse" class="table table-striped table-bordered" style="font-size:11px;">
                       <thead style="background:#2A3F54;color:white;">
                         <tr>
                           <th>Nomor Project /PRK</th>
                           <th>Deskripsi Project /PRK [40 karakter]</th>
                           <th>Ext.Description Line 1 [60 Karakter]</th>
                           <th>Ext.Description Line 2 [60 Karakter]</th>
                           <th>Parent Project</th>
                           <th>Raised Date (yyyymmdd)</th>
                           <th>Originator</th>
                           <th>Account Code</th>
                           <th>Authorize Employee</th>
                           <th>Authorize Date (yyyymmdd)</th>
                           <th>Nomer Rumah PRK</th>
                           <th>Years</th>
                           <th>Version</th>
                           <th>PRK Type</th>
                           <th>Plan Start Date (yyyymmdd)</th>
                           <th>Plan Finish Date (yyyymmdd)</th>
                           <th>Schedule Start Date</th>
                           <th>Schedule Finish Date</th>
                           <th>Actual Start Date</th>
                           <th>Actual Finish Date</th>
                           <th>Build Method (T/B)</th>
                           <th>Budget Code</th>
                           <th>Direct Est Cost/Revenue</th>
                           <th>Category Code</th>
                           <th>Category Value</th>
                           <th>Classification</th>
                           <th>Estimator</th>
                           <th>Year Estimate</th>
                           <th>Total Year Estimate/Revenue</th>
                           <th>Jan</th>
                           <th>Feb</th>
                           <th>Mar</th>
                           <th>Apr</th>
                           <th>Mei</th>
                           <th>Jun</th>
                           <th>Jul</th>
                           <th>Agt</th>
                           <th>Sep</th>
                           <th>Okt</th>
                           <th>Nov</th>
                           <th>Des</th>
                           <th>UPLOAD STATUS</th>
                         </tr>
                       </thead>
                       <body>

                        <!-- form rkau, 6, 10 -->
                        <?php $baris = 0; ?>
                        @foreach($dataparent as $key_form => $parent_per_form)
                        @foreach($parent_per_form as $key_parent => $parent)
                        <!-- parent -->
                            @if($key_parent!= '')
                            <?php $baris = $baris+1; $warna=($baris % 2 == 0) ? "#ffffff" : "#E8EDEF"; ?>
                            <tr style="background-color: <?= $warna?>">
                                @if(strlen($key_parent)!=4)
                                  <td>{{substr($key_parent,2,4)}}</td><!-- <th>Nomor Project /PRK</th> -->
                                  @else
                                  <td>{{$key_parent}}</td><!-- <th>Nomor Project /PRK</th> -->
                                  @endif
                                  <td>{{substr($parent['desc_prk_parent'],0,40)}}</td><!-- <th>Deskripsi Project /PRK</th> -->
                                  <td>{{substr($parent['desc_prk_parent'],40,60)}}</td><!-- <th>Deskripsi Project /PRK [40 karakter]</th> -->
                                  <td>{{substr($parent['desc_prk_parent'],100,60)}}</td><!-- <th>Ext.Description Line 1 [60 Karakter]</th> -->
                                  <td></td><!-- <th>Parent Project</th> -->
                                  <td></td><!-- <th>Raised Date (yyyymmdd)</th> -->
                                  <td></td><!-- <th>Originator</th> -->
                                  <td>K00000100</td><!-- <th>Account Code</th> -->
                                  <td></td><!-- <th>Authorize Employee</th> -->
                                  <td></td><!-- <th>Authorize Date (yyyymmdd)</th> -->
                                  <td></td><!-- <th>Nomor RUmah PRK</th> -->
                                  <td>{{ $input_tahun }}</td><!-- <th>Years</th> -->
                                  <td>001</td><!-- <th>Version</th> -->
                                  <td>PP</td><!-- <th>PRK Type</th> -->
                                  <td>{{$input_tahun}}0101</td><!-- <th>Plan Start Date (yyyymmdd)</th> -->
                                  <td>{{$input_tahun}}1231</td><!-- <th>Plan Finish Date (yyyymmdd)</th> -->
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td>{{ number_format(1000 * $parent['direct_est_cost'] ,0,",",".")}}</td> <!-- <th>Direct Est Cost/Revenue</th> -->
                                  <td>{{ $parent['category_code'] }}</td><!-- <th>Category Code</th> -->
                                  <td>{{ number_format(1000 * $parent['category_value'],0,",",".") }}</td><!-- <th>Category Value</th> -->
                                  <td></td><!-- <th>Classification</th> -->
                                  <td></td><!-- <th>Estimator</th> -->
                                  <td></td><!-- <th>Years Estimate</th> -->
                                  <td style="text-align: right;">{{ number_format(1000 * $parent['total_year_estimate'],0,",",".") }}</td><!-- <th>Total Year Estimate</th> -->
                                  @for($bulan=1; $bulan<=12; $bulan++)
                                      <td style="text-align: right;">{{ number_format(1000 * $parent['disburse'][$bulan],0,",",".") }}</td><!-- <th>disburse bulan ke {{$bulan}}</th> -->
                                  @endfor
                                  <td></td><!-- <th>UPLOAD STATUS</th> -->
                            </tr>

                            <!-- inti -->
                            @foreach($datainti[$key_form] as $key_inti=>$inti)
                                @if($inti['prk_parent'] == $key_parent)
                                <?php $baris = $baris+1; $warna=($baris % 2 == 0) ? "#ffffff" : "#E8EDEF"; ?>
                                <tr style="background-color: <?= $warna?>">
                                    @if(strlen($key_inti)!=6)
                                    <td>{{substr($key_inti,2,6)}}</td><!-- <th>Nomor Project /PRK</th> -->
                                    @else
                                    <td>{{$key_inti}}</td><!-- <th>Nomor Project /PRK</th> -->
                                    @endif
                                    <td>{{substr($inti['desc_prk_inti'],0,40)}}</td><!-- <th>Deskripsi Project /PRK</th> -->
                                    <td>{{substr($inti['desc_prk_inti'],40,60)}}</td><!-- <th>Deskripsi Project /PRK [40 karakter]</th> -->
                                    <td>{{substr($inti['desc_prk_inti'],100,60)}}</td><!-- <th>Ext.Description Line 1 [60 Karakter]</th> -->
                                    @if(strlen($inti['prk_parent'])!=4)
                                    <td>{{ substr($inti['prk_parent'],2,4) }}</td><!-- <th>Parent Project</th> -->
                                    @else
                                    <td>{{ $inti['prk_parent'] }}</td><!-- <th>Parent Project</th> -->
                                    @endif
                                    <td></td><!-- <th>Raised Date (yyyymmdd)</th> -->
                                    <td></td><!-- <th>Originator</th> -->
                                    <td>K00000100</td><!-- <th>Account Code</th> -->
                                    <td></td><!-- <th>Authorize Employee</th> -->
                                    <td></td><!-- <th>Authorize Date (yyyymmdd)</th> -->
                                    <td></td><!-- <th>Nomer Rumah PRK</th> -->
                                    <td>{{ $input_tahun }}</td><!-- <th>Years</th> -->
                                    <td>001</td><!-- <th>Version</th> -->
                                    <td>PI</td><!-- <th>PRK Type</th> -->
                                    <td>{{$input_tahun}}0101</td><!-- <th>Plan Start Date (yyyymmdd)</th> -->
                                    <td>{{$input_tahun}}1231</td><!-- <th>Plan Finish Date (yyyymmdd)</th> -->
                                    <td></td><!-- <th>Schedule Start Date (yyyymmdd)</th> -->
                                    <td></td><!-- <th>Schedule Finish Date (yyyymmdd)</th> -->
                                    <td></td><!-- <th>Actual Start Date (yyyymmdd)</th> -->
                                    <td></td><!-- <th>Actual Finish Date (yyyymmdd)</th> -->
                                    <td></td><!-- <th>Build Method (T/B)</th> -->
                                    <td></td><!-- <th>Budget Code</th> -->
                                    <td>{{ number_format(1000 * $inti['direct_est_cost'],0,",",".")}}</td><!-- <th>Direct East Code/Revenue</th> -->
                                    <td>{{ $inti['category_code'] }}</td><!-- <th>Category Code</th> -->
                                    <td>{{ number_format(1000 * $inti['category_value'],0,",",".") }}</td><!-- <th>Category Value</th> -->
                                    <td></td><!-- <th>Classification</th> -->
                                    <td></td><!-- <th>Estimator</th> -->
                                    <td></td><!-- <th>Years Estimate</th> -->
                                    <td style="text-align: right;">{{ number_format(1000 * $inti['total_year_estimate'],0,",",".") }}</td><!-- <th>Total Year Estimate</th> -->
                                    @for($bulan=1; $bulan<=12; $bulan++)
                                        <td style="text-align: right;">{{ number_format(1000 * $inti['disburse'][$bulan],0,",",".") }}</td><!-- <th>disburse bulan ke {{$bulan}}</th> -->
                                    @endfor
                                    <td></td><!-- <th>UPLOAD STATUS</th> -->
                                </tr>

                                <!-- kegiatan -->
                                @foreach($datakegiatan[$key_form] as $key_kegiatan => $kegiatan)
                                    @if($kegiatan['prk_inti'] == $key_inti)
                                    <?php $baris = $baris+1; $warna=($baris % 2 == 0) ? "#ffffff" : "#E8EDEF"; ?>
                                    <tr style="background-color: <?= $warna?>">
                                        @if(strlen($kegiatan['prk_kegiatan'])!=8)
                                      <td>{{substr($kegiatan['prk_kegiatan'],2,8)}}</td><!-- <th>Nomor Project /PRK</th> -->
                                      @else
                                      <td>{{$kegiatan['prk_kegiatan']}}</td><!-- <th>Nomor Project /PRK</th> -->
                                      @endif
                                      <td>{{substr($kegiatan['desc_prk_kegiatan'],0,40)}}</td><!-- <th>Deskripsi Project /PRK</th> -->
                                      <td>{{substr($kegiatan['desc_prk_kegiatan'],40,60)}}</td><!-- <th>Deskripsi Project /PRK [40 karakter]</th> -->
                                      <td>{{substr($kegiatan['desc_prk_kegiatan'],100,60)}}</td><!-- <th>Ext.Description Line 1 [60 Karakter]</th> -->
                                      @if(strlen($kegiatan['prk_inti'])!=6)
                                      <td>{{ substr($kegiatan['prk_inti'],2,6) }}</td><!-- <th>Parent Project</th> -->
                                      @else
                                      <td>{{ $kegiatan['prk_inti'] }}</td><!-- <th>Parent Project</th> -->
                                      @endif
                                      <td></td><!-- <th>Raised Date (yyyymmdd)</th> -->
                                      <td></td><!-- <th>Originator</th> -->
                                      <td>K00000100</td><!-- <th>Account Code</th> -->
                                      <td></td><!-- <th>Authorize Employee</th> -->
                                      <td></td><!-- <th>Authorize Date (yyyymmdd)</th> -->
                                      <td></td><!-- <th>Nomer Rumah PRK</th> -->
                                      <td>{{ $input_tahun }}</td><!-- <th>Years</th> -->
                                      <td>001</td><!-- <th>Version</th> -->
                                      <td>PK</td><!-- <th>PRK Type</th> -->
                                      <td>{{$input_tahun}}0101</td><!-- <th>Plan Start Date (yyyymmdd)</th> -->
                                      <td>{{$input_tahun}}1231</td><!-- <th>Plan Finish Date (yyyymmdd)</th> -->
                                      <td></td><!-- <th>Schedule Start Date (yyyymmdd)</th> -->
                                      <td></td><!-- <th>Schedule Finish Date (yyyymmdd)</th> -->
                                      <td></td><!-- <th>Actual Start Date (yyyymmdd)</th> -->
                                      <td></td><!-- <th>Actual Finish Date (yyyymmdd)</th> -->
                                      <td></td><!-- <th>Build Methor (T/B)</th> -->
                                      <td></td><!-- <th>Budget Code</th> -->
                                      <td>{{ number_format(1000 *  $kegiatan['direct_est_cost'],0,",",".")}}</td><!-- <th>Direct Est Cost/Revenue</th> -->
                                      <td>{{ $kegiatan['category_code'] }}</td><!-- <th>Category Code</th> -->
                                      <td>{{ number_format(1000 * $kegiatan['category_value'],0,",",".") }}</td><!-- <th>Category Value</th> -->
                                      <td></td><!-- <th>Classification</th> -->
                                      <td></td><!-- <th>Estimator</th> -->
                                      <td></td><!-- <th>Year Estimate</th> -->
                                      <td style="text-align: right;">{{ number_format(1000 * $kegiatan['total_year_estimate'],0,",",".") }}</td><!-- <th>Total Year Estimate</th> -->
                                      @for($bulan=1; $bulan<=12; $bulan++)
                                          <td style="text-align: right;">{{ number_format(1000 * $kegiatan['disburse'][$bulan],0,",",".") }}</td><!-- <th>disburse bulan ke {{$bulan}}</th> -->
                                      @endfor
                                      <td></td><!-- <th>UPLOAD STATUS</th> -->
                                    </tr>
                                    @endif
                                @endforeach <!-- end of inti -->
                                @endif
                            @endforeach <!-- end of inti -->
                            @endif
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
@endsection

@section('js_page')
<script type="text/javascript">
  $(document).ready(function() {
    var table = $('#table-loader-ellipse').DataTable( {
        "aLengthMenu": [[10, 25, 50, 100, -1],
        [10, 25, 50, 100, "All"]],
        "scrollY": "800px",
        "scrollX": "300px",
        "scrollCollapse": true,
        "paging": true,
        // pagingType: "full_numbers",
        fixedHeader: true,
        ordering: false
    } );
    $('#table-loader-ellipse_filter label input').on( 'keyup', function () {
    table
        .columns( 0 )
        .search( this.value )
        .draw();
} );
} );
</script>
@endsection
