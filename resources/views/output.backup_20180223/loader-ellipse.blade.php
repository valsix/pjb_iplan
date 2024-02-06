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

    </style>

@endsection

@section('content')
    <h1>Report Rencana Anggaran Untuk Loader Ellipse</h1>
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
    </div>

    @if($input_lokasi!=null)
    <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <a href="{{ Request::fullUrl() }}&download=rincian-biaya-administrasi&type=excel" id="get-excel" class="btn btn-success pull-right"><i class="fa fa-download"></i> Download Excel</a>
                    <h2>Table</h2>
                    <div class="clearfix"></div>

                  </div>
                  
                 
                  <div style="overflow-x:auto;">
                    <table id="table-loader-ellipse" class="table table-striped table-bordered">
                       <thead style="background:#2A3F54;color:white;">
                         <tr>
                           <th>Nomor Project /PRK</th>
                           <th>Deskripsi Project /PRK [40 karakter]</th>
                           <th>Ext.Description Line 1 [60 Karakter]</th>
                           <th>Ext.Description Line 2 [60 Karakter]</th>
                           <th>Parent Project</th>
                           <th>Beban (MAT)</th>
                           <th>Cash (OTH)</th>
                           <th>Ijin Proses (LAB)</th>
                           <th>Spread Code</th>
                           <th>Raised Date (yyyymmdd)</th>
                           <th>Originator</th>
                           <th>Account Code</th>
                           <th>Authorize Employee</th>
                           <th>Authorize Date (yyyymmdd)</th>
                           <th>Rumah PRK Number</th>
                           <th>Years</th>
                           <th>Version</th>
                           <th>PRK Type</th>
                           <th>Plan Start Date (yyyymmdd)</th>
                           <th>Plan Finish Date (yyyymmdd)</th>
                           <th>Category Code</th>
                           <th>Total Year Estimate</th>
                           <th>Classification</th>
                           <th>Estimator</th>
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
                           <th>Tahun Disburse</th>
                           <th>UPLOAD STATUS PROJECT</th>
                           <th>UPLOAD STATUS PROJECT ESTIMATE</th>
                           <th>UPLOAD STATUS PERIOD PROJECT ESTIMATE</th>
                           <th>JUMLAH SUBMIT (KALI)</th>
                         </tr>
                       </thead>
                       <body>

                        <!-- form rkau, 6, 10 -->
                        @foreach($dataparent as $key_form => $parent_per_form)
                        @foreach($parent_per_form as $key_parent => $parent)
                        <!-- parent -->
                            @if($key_parent!= '')
                            <tr>
                                @if(strlen($key_parent)!=4)
                                <td>{{substr($key_parent,2,4)}}</td><!-- <th>Nomor Project /PRK</th> -->
                                @else
                                <td>{{$key_parent}}</td><!-- <th>Nomor Project /PRK</th> -->
                                @endif
                                <td>{{substr($parent['desc_prk_parent'],0,40)}}</td><!-- <th>Nomor Project /PRK</th> -->
                                <td>{{substr($parent['desc_prk_parent'],40,60)}}</td><!-- <th>Deskripsi Project /PRK [40 karakter]</th> -->
                                <td>{{substr($parent['desc_prk_parent'],100,60)}}</td><!-- <th>Ext.Description Line 1 [60 Karakter]</th> -->
                                <td></td><!-- <th>Parent Project</th> -->
                                <td style="text-align: right;">{{ number_format(1000 * $parent['beban_mat'],0,",",".") }}</td><!-- <th>Beban (MAT)</th> -->
                                <td style="text-align: right;">{{ number_format(1000 * $parent['cash_oth'],0,",",".") }}</td><!-- <th>Cash (OTH)</th> -->
                                <td style="text-align: right;">{{ number_format(1000 * $parent['ijin_proses'],0,",",".") }}</td><!-- <th>Ijin Proses (LAB)</th> -->
                                <td></td><!-- <th>Spread Code</th> -->
                                <td></td><!-- <th>Raised Date (yyyymmdd)</th> -->
                                <td></td><!-- <th>Originator</th> -->
                                <td>K00000100</td><!-- <th>Account Code</th> -->
                                <td></td><!-- <th>Authorize Employee</th> -->
                                <td></td><!-- <th>Authorize Date (yyyymmdd)</th> -->
                                <td></td><!-- <th>Rumah PRK Number</th> -->
                                <td>{{ $input_tahun }}</td><!-- <th>Years</th> -->
                                <td>001</td><!-- <th>Version</th> -->
                                <td>PP</td><!-- <th>PRK Type</th> -->
                                <td>{{$input_tahun}}0101</td><!-- <th>Plan Start Date (yyyymmdd)</th> -->
                                <td>{{$input_tahun}}1231</td><!-- <th>Plan Finish Date (yyyymmdd)</th> -->
                                <td></td><!-- <th>Category Code</th> -->
                                <td style="text-align: right;">{{ number_format(1000 * $parent['total_year_estimate'],0,",",".") }}</td><!-- <th>Total Year Estimate</th> -->
                                <td></td><!-- <th>Classification</th> -->
                                <td></td><!-- <th>Estimator</th> -->
                                @for($bulan=1; $bulan<=12; $bulan++)
                                    <td style="text-align: right;">{{ number_format(1000 * $parent['disburse'][$bulan],0,",",".") }}</td><!-- <th>disburse bulan ke {{$bulan}}</th> -->
                                @endfor
                                <td>{{ $input_tahun }}</td><!-- <th>Tahun Disburse</th> -->
                                <td></td><!-- <th>UPLOAD STATUS PROJECT</th> -->
                                <td></td><!-- <th>UPLOAD STATUS PROJECT ESTIMATE</th> -->
                                <td></td><!-- <th>UPLOAD STATUS PERIOD PROJECT ESTIMATE</th> -->
                                <td></td><!-- <th>JUMLAH SUBMIT (KALI)</th> -->
                            </tr>

                            <!-- inti -->
                            @foreach($datainti[$key_form] as $key_inti=>$inti)
                                @if($inti['prk_parent'] == $key_parent)
                                <tr>
                                    @if(strlen($key_inti)!=6)
                                    <td>{{substr($key_inti,2,6)}}</td><!-- <th>Nomor Project /PRK</th> -->
                                    @else
                                    <td>{{$key_inti}}</td><!-- <th>Nomor Project /PRK</th> -->
                                    @endif
                                    <td>{{substr($inti['desc_prk_inti'],0,40)}}</td><!-- <th>Nomor Project /PRK</th> -->
                                    <td>{{substr($inti['desc_prk_inti'],40,60)}}</td><!-- <th>Deskripsi Project /PRK [40 karakter]</th> -->
                                    <td>{{substr($inti['desc_prk_inti'],100,60)}}</td><!-- <th>Ext.Description Line 1 [60 Karakter]</th> -->
                                    @if(strlen($inti['prk_parent'])!=4)
                                    <td>{{ substr($inti['prk_parent'],2,4) }}</td><!-- <th>Parent Project</th> -->
                                    @else
                                    <td>{{ $inti['prk_parent'] }}</td><!-- <th>Parent Project</th> -->
                                    @endif
                                    <td style="text-align: right;">{{ number_format(1000 * $inti['beban_mat'],0,",",".") }}</td><!-- <th>Beban (MAT)</th> -->
                                    <td style="text-align: right;">{{ number_format(1000 * $inti['cash_oth'],0,",",".") }}</td><!-- <th>Cash (OTH)</th> -->
                                    <td style="text-align: right;">{{ number_format(1000 * $inti['ijin_proses'],0,",",".") }}</td><!-- <th>Ijin Proses (LAB)</th> -->
                                    <td></td><!-- <th>Spread Code</th> -->
                                    <td></td><!-- <th>Raised Date (yyyymmdd)</th> -->
                                    <td></td><!-- <th>Originator</th> -->
                                    <td>K00000100</td><!-- <th>Account Code</th> -->
                                    <td></td><!-- <th>Authorize Employee</th> -->
                                    <td></td><!-- <th>Authorize Date (yyyymmdd)</th> -->
                                    <td></td><!-- <th>Rumah PRK Number</th> -->
                                    <td>{{ $input_tahun }}</td><!-- <th>Years</th> -->
                                    <td>001</td><!-- <th>Version</th> -->
                                    <td>PI</td><!-- <th>PRK Type</th> -->
                                    <td>{{$input_tahun}}0101</td><!-- <th>Plan Start Date (yyyymmdd)</th> -->
                                    <td>{{$input_tahun}}1231</td><!-- <th>Plan Finish Date (yyyymmdd)</th> -->
                                    <td></td><!-- <th>Category Code</th> -->
                                    <td style="text-align: right;">{{ number_format(1000 * $inti['total_year_estimate'],0,",",".") }}</td><!-- <th>Total Year Estimate</th> -->
                                    <td></td><!-- <th>Classification</th> -->
                                    <td></td><!-- <th>Estimator</th> -->
                                    @for($bulan=1; $bulan<=12; $bulan++)
                                        <td style="text-align: right;">{{ number_format(1000 * $inti['disburse'][$bulan],0,",",".") }}</td><!-- <th>disburse bulan ke {{$bulan}}</th> -->
                                    @endfor
                                    <td>{{ $input_tahun }}</td><!-- <th>Tahun Disburse</th> -->
                                    <td></td><!-- <th>UPLOAD STATUS PROJECT</th> -->
                                    <td></td><!-- <th>UPLOAD STATUS PROJECT ESTIMATE</th> -->
                                    <td></td><!-- <th>UPLOAD STATUS PERIOD PROJECT ESTIMATE</th> -->
                                    <td></td><!-- <th>JUMLAH SUBMIT (KALI)</th> -->
                                </tr>

                                <!-- kegiatan -->
                                @foreach($datakegiatan[$key_form] as $key_kegiatan => $kegiatan)
                                    @if($kegiatan['prk_inti'] == $key_inti)
                                    <tr>
                                        @if(strlen($kegiatan['prk_kegiatan'])!=8)
                                        <td>{{substr($kegiatan['prk_kegiatan'],2,8)}}</td><!-- <th>Nomor Project /PRK</th> -->
                                        @else
                                        <td>{{$kegiatan['prk_kegiatan']}}</td><!-- <th>Nomor Project /PRK</th> -->
                                        @endif
                                        <td>{{substr($kegiatan['desc_prk_kegiatan'],0,40)}}</td><!-- <th>Nomor Project /PRK</th> -->
                                        <td>{{substr($kegiatan['desc_prk_kegiatan'],40,60)}}</td><!-- <th>Deskripsi Project /PRK [40 karakter]</th> -->
                                        <td>{{substr($kegiatan['desc_prk_kegiatan'],100,60)}}</td><!-- <th>Ext.Description Line 1 [60 Karakter]</th> -->
                                        @if(strlen($kegiatan['prk_inti'])!=6)
                                        <td>{{ substr($kegiatan['prk_inti'],2,6) }}</td><!-- <th>Parent Project</th> -->
                                        @else
                                        <td>{{ $kegiatan['prk_inti'] }}</td><!-- <th>Parent Project</th> -->
                                        @endif
                                        <td style="text-align: right;">{{ number_format(1000 * $kegiatan['beban_mat'],0,",",".") }}</td><!-- <th>Beban (MAT)</th> -->
                                        <td style="text-align: right;">{{ number_format(1000 * $kegiatan['cash_oth'],0,",",".") }}</td><!-- <th>Cash (OTH)</th> -->
                                        <td style="text-align: right;">{{ number_format(1000 * $kegiatan['ijin_proses'],0,",",".") }}</td><!-- <th>Ijin Proses (LAB)</th> -->
                                        <td></td><!-- <th>Spread Code</th> -->
                                        <td></td><!-- <th>Raised Date (yyyymmdd)</th> -->
                                        <td></td><!-- <th>Originator</th> -->
                                        <td>K00000100</td><!-- <th>Account Code</th> -->
                                        <td></td><!-- <th>Authorize Employee</th> -->
                                        <td></td><!-- <th>Authorize Date (yyyymmdd)</th> -->
                                        <td></td><!-- <th>Rumah PRK Number</th> -->
                                        <td>{{ $input_tahun }}</td><!-- <th>Years</th> -->
                                        <td>001</td><!-- <th>Version</th> -->
                                        <td>PK</td><!-- <th>PRK Type</th> -->
                                        <td>{{$input_tahun}}0101</td><!-- <th>Plan Start Date (yyyymmdd)</th> -->
                                        <td>{{$input_tahun}}1231</td><!-- <th>Plan Finish Date (yyyymmdd)</th> -->
                                        <td></td><!-- <th>Category Code</th> -->
                                        <td style="text-align: right;">{{ number_format(1000 * $kegiatan['total_year_estimate'],0,",",".") }}</td><!-- <th>Total Year Estimate</th> -->
                                        <td></td><!-- <th>Classification</th> -->
                                        <td></td><!-- <th>Estimator</th> -->
                                        @for($bulan=1; $bulan<=12; $bulan++)
                                            <td style="text-align: right;">{{ number_format(1000 * $kegiatan['disburse'][$bulan],0,",",".") }}</td><!-- <th>disburse bulan ke {{$bulan}}</th> -->
                                        @endfor
                                        <td>{{ $input_tahun }}</td><!-- <th>Tahun Disburse</th> -->
                                        <td></td><!-- <th>UPLOAD STATUS PROJECT</th> -->
                                        <td></td><!-- <th>UPLOAD STATUS PROJECT ESTIMATE</th> -->
                                        <td></td><!-- <th>UPLOAD STATUS PERIOD PROJECT ESTIMATE</th> -->
                                        <td></td><!-- <th>JUMLAH SUBMIT (KALI)</th> -->
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
        pagingType: "full_numbers",
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
