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
    <h1>List PRK</h1>
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
                                                    url: "{{ url('/output/list-prk/ajax/') }}/"+strategi_bisnisID,
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
                                                url: "{{ url('output/list-prk/ajax2/') }}/"+lokasiID,
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
                                                    url: '/output/list-prk/ajax2/'+lokasiID,
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
                                              url: "{{ url('/output/list-prk/ajax3/') }}/"+id_lokasi+"/"+id_tahun,
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
                                              url: "{{ url('/output/list-prk/ajax4/') }}/"+id_lokasi+"/"+id_tahun,
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
                                              url: "{{ url('/output/list-prk/ajax5/') }}/"+id_lokasi+"/"+id_tahun,
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
                                              url: "{{ url('/output/list-prk/ajax6/') }}/"+id_lokasi+"/"+id_tahun,
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
                                              url: "{{ url('/output/list-prk/ajax7/') }}/"+id_lokasi+"/"+id_tahun,
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
                                              url: "{{ url('/output/list-prk/ajax8/') }}/"+id_lokasi+"/"+id_tahun,
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
                    <table id="table-list-prk" class="table table-striped table-bordered">
                       <thead style="background:#2A3F54;color:white;">
                         <tr>
                           <th>Aksi</th>
                           <th>No PRK</th>
                           <th>PRK Parent</th>
                           <th>PRK Inti</th>
                           <th>PRK Kegiatan</th>
                           <th>Anggaran</th>
                           <th>Disburse</th>
                         </tr>
                       </thead>
                       <body>

                        <!-- form rkau, 6, 10 -->
                        @foreach($dataparent as $key_form => $parent_per_form)
                        @foreach($parent_per_form as $key_parent => $parent)
                        <!-- parent -->
                            @if($key_parent!= '')
                            <tr style="color:black;">
                                <td><a attr="{{ $key_parent }}" class="btn btn-primary" id="add">+ Inti</a></td>
                                @if(strlen($key_parent)!=4)
                                <td>{{substr($key_parent,2,4)}}</td><!-- <th>Nomor Project /PRK</th> -->
                                @else
                                <td>{{$key_parent}}</td><!-- <th>Nomor Project /PRK</th> -->
                                @endif
                                <td>{{ $parent['desc_prk_parent'] }}</td>
                                <td></td>
                                <td></td>
                                <td style="text-align: right;">{{ number_format(1000 * $parent['beban_mat'],0,",",".") }}</td><!-- <th>Beban (MAT)</th> -->
                                <td style="text-align: right;">{{ number_format(1000 * $parent['cash_oth'],0,",",".") }}</td><!-- <th>Cash (OTH)</th> -->
                            </tr>

                            <!-- inti -->
                            @foreach($datainti[$key_form] as $key_inti=>$inti)
                                @if($inti['prk_parent'] == $key_parent)
                                <tr style="background:#d2e6e9;color:black;" class="hidetrinti{{$key_parent}} hidetrinit">
                                    <td><a attr="{{ $key_inti }}" class="btn btn-primary" id="add2">+ Kegiatan</a></td>
                                    @if(strlen($key_inti)!=6)
                                    <td>{{substr($key_inti,2,6)}}</td><!-- <th>Nomor Project /PRK</th> -->
                                    @else
                                    <td>{{$key_inti}}</td><!-- <th>Nomor Project /PRK</th> -->
                                    @endif
                                    <td></td>
                                    <td>{{ $inti['desc_prk_inti'] }}</td><!-- <th>Deskripsi Project /PRK [40 karakter]</th> -->
                                    <td></td>
                                    <td style="text-align: right;">{{ number_format(1000 * $inti['beban_mat'],0,",",".") }}</td><!-- <th>Beban (MAT)</th> -->
                                    <td style="text-align: right;">{{ number_format(1000 * $inti['cash_oth'],0,",",".") }}</td><!-- <th>Cash (OTH)</th> -->
                                </tr>

                                <!-- kegiatan -->
                                @foreach($datakegiatan[$key_form] as $key_kegiatan => $kegiatan)
                                    @if($kegiatan['prk_inti'] == $key_inti)
                                    <tr style="background:#8EC7D1;color:black;" class="hidetrkeg{{$key_inti}} trkeg_parent{{$key_parent}} hidetrinit2">
                                        <td></td>
                                        @if(strlen($kegiatan['prk_kegiatan'])!=8)
                                        <td>{{substr($kegiatan['prk_kegiatan'],2,8)}}</td><!-- <th>Nomor Project /PRK</th> -->
                                        @else
                                        <td>{{$kegiatan['prk_kegiatan']}}</td><!-- <th>Nomor Project /PRK</th> -->
                                        @endif
                                        <td></td>
                                        <td></td>
                                        <td>{{ $kegiatan['desc_prk_kegiatan'] }}</td>
                                        <td style="text-align: right;">{{ number_format(1000 * $kegiatan['beban_mat'],0,",",".") }}</td><!-- <th>Beban (MAT)</th> -->
                                        <td style="text-align: right;">{{ number_format(1000 * $kegiatan['cash_oth'],0,",",".") }}</td><!-- <th>Cash (OTH)</th> -->
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
              url: "{{ url('/output/list-prk/ajax9/') }}/"+id_lokasi+"/"+id_tahun,
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
              url: "{{ url('/output/list-prk/ajax10/') }}/"+id_lokasi+"/"+id_tahun,
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
    var table = $('#table-list-prk').DataTable( {
        "aLengthMenu": [[10, 25, 50, 75, 100, -1], 
        [10, 25, 50, 75, 100, "All"]],
        "pageLength": -1, //default all supaya bisa hide semua inti & kegiatan
        pagingType: "full_numbers",
        ordering: false
    } );
    $('#table-list-prk_filter label input').on( 'keyup', function () {
    table
        .columns( 0 )
        .search( this.value )
        .draw();
    });

    @if($input_lokasi!=null)
    $(function() {
      @foreach($dataparent as $key_form => $parent_per_form)
          @foreach($parent_per_form as $key_parent => $parent)
            // parent
            @if($key_parent!= '')
              // inti
              @foreach($datainti[$key_form] as $key_inti=>$inti)
                @if($inti['prk_parent'] == $key_parent)
                  
                  $(".hidetrinti{{ $key_parent }}").find("td").hide();

                  @foreach($datakegiatan[$key_form] as $key_kegiatan => $kegiatan)
                    @if($kegiatan['prk_inti'] == $key_inti)

                      $(".hidetrkeg{{ $key_inti }}").find("td").hide();

                    @endif //end of kegiatan
                  @endforeach

                @endif //end of inti
              @endforeach
            @endif //end of parent
          @endforeach
      @endforeach
      // $(".hidetrinit").find("td").hide();
      // $(".hidetrinit2").find("td").hide();
      // $("#add").click(function(event) {
      $(function() {

        //show Inti
        $('body').on('click','#add',function(event){
            event.stopPropagation();
            var $target = $(event.target);
            var id = $(this).attr("attr");
            // $(this).html("html");
            if( $(this).html() == "+ Inti") {
              $(this).html("- Inti");
            }
            else {
              $(this).html("+ Inti");
            }

            $('.hidetrinti'+id).find("td").slideToggle();
            console.log('else .hidetrinti'+id);

            $('.trkeg_parent'+id).find("td").slideUp();
            console.log('trkeg_parent'+id);
        });

        //show Kegiatan
        $('body').on('click','#add2',function(event){
            event.stopPropagation();
            var $target = $(event.target);
            var id = $(this).attr("attr");

            if( $(this).html() == "+ Kegiatan") {
              $(this).html("- Kegiatan");
            }
            else {
              $(this).html("+ Kegiatan");
            }

            $('.hidetrkeg'+id).find("td").slideToggle();
            console.log('else .hidetrkeg'+id);
        });
      });

    });
    @endif
  });
</script>
@endsection
