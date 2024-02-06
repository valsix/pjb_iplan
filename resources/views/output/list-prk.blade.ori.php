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
  font-size: 12px;
}

</style>

@endsection

@section('content')
<h1>List PRK </h1>
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
                    url: "{{ url('/output/list-prk/ajax') }}"+"/"+strategi_bisnisID,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {
                                                // console.log(data);
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
             <option></option>
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
                url: "{{ url('/output/list-prk/ajax2') }}"+"/"+lokasiID,
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
          <option>- Pilih Draft RKAU -</option>
            @if($input_sb!=null && $input_draft_rkau!=null && $input_lokasi!=null)
                @foreach($drafts as $d)
                    <option value="{{$d->id}}" <?php echo($d->id == $input_draft_rkau->id ? 'selected' : '')?>>{{$d->draft_versi}}</option>
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
                url: "{{ url('/output/list-prk/ajax3') }}"+"/"+id_lokasi+"/"+id_tahun,
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
        <select class="form-control" name="draft_form_6_reimburse">
          <option>- Pilih Draft Form 6 Reimburse -</option>
            @if($input_sb!=null && $input_draft_form_6_reimburse!=null && $input_lokasi!=null)
                @foreach($draft_form_6_reimburse2 as $d)
                    <option value="{{$d->id}}" <?php echo($d->id == $input_draft_form_6_reimburse->id ? 'selected' : '')?>>{{$d->draft_versi}}</option>
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
                url: "{{ url('/output/list-prk/ajax4') }}"+"/"+id_lokasi+"/"+id_tahun,
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
        <select class="form-control" name="draft_form_6_rutin">
          <option>- Pilih Draft Form 6 Rutin -</option>
            @if($input_sb!=null && $input_draft_form_6_rutin!=null && $input_lokasi!=null)
                @foreach($draft_form_6_rutin2 as $d)
                    <option value="{{$d->id}}" <?php echo($d->id == $input_draft_form_6_rutin->id ? 'selected' : '')?>>{{$d->draft_versi}}</option>
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
                url: "{{ url('/output/list-prk/ajax5') }}"+"/"+id_lokasi+"/"+id_tahun,
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
        <select class="form-control" name="draft_form_10_pu">
          <option>- Pilih Draft Form 10 Pengembangan Usaha -</option>
            @if($input_sb!=null && $input_draft_form_10_pu!=null && $input_lokasi!=null)
                @foreach($draft_form_10_pengembangan_usaha2 as $d)
                    <option value="{{$d->id}}" <?php echo($d->id == $input_draft_form_10_pu->id ? 'selected' : '')?>>{{$d->draft_versi}}</option>
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
                url: "{{ url('/output/list-prk/ajax6') }}"+"/"+id_lokasi+"/"+id_tahun,
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
        <select class="form-control" name="draft_form_10_pk">
          <option>- Pilih Draft Form 10 Penguatan KIT -</option>
            @if($input_sb!=null && $input_draft_form_10_pk!=null && $input_lokasi!=null)
                @foreach($draft_form_10_penguatan_kit2 as $d)
                    <option value="{{$d->id}}" <?php echo($d->id == $input_draft_form_10_pk->id ? 'selected' : '')?>>{{$d->draft_versi}}</option>
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
                url: "{{ url('/output/list-prk/ajax7') }}"+"/"+id_lokasi+"/"+id_tahun,
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
        <select class="form-control" name="draft_form_10_pln">
          <option>- Pilih Draft Form 10 PLN -</option>
            @if($input_sb!=null && $input_draft_form_10_pln!=null && $input_lokasi!=null)
                @foreach($draft_form_10_pln2 as $d)
                    <option value="{{$d->id}}" <?php echo($d->id == $input_draft_form_10_pln->id ? 'selected' : '')?>>{{$d->draft_versi}}</option>
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
                url: "{{ url('/output/list-prk/ajax8') }}"+"/"+id_lokasi+"/"+id_tahun,
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
        <a href="{{ Request::fullUrl() }}&download=rincian-biaya-pegawai&type=excel" id="get-excel" class="btn btn-success pull-right"><i class="fa fa-download"></i> Download Excel</a>
        
        <a href="{{ Request::fullUrl() }}&download=rincian-biaya-pegawai&type=pdf" id="get-pdf" class="btn btn-primary pull-right"><i class="fa fa-download"></i> Download PDF</a>

        <h2>Table</h2>
        <ul class="nav navbar-right panel_toolbox">
          <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
          </li>
          <li><a class="close-link"><i class="fa fa-close"></i></a>
          </li>
        </ul>
        <div class="clearfix"></div>
      </div>



                  <!-- <button type="button" class="btn btn-primary pull-right"><i class="fa fa-download"></i> Download PDF</button>
                    <button type="button" class="btn btn-success pull-right"><i class="fa fa-download"></i> Download Excel</button> -->

                    <div class="x_content">
                     <table id="table-list-prk" class="table table-striped table-bordered">
                       <thead style="background:#2A3F54;color:white;">
                         <tr>
                           <th>Action</th>
                           <th>No PRK</th>
                           <th>PRK Parent</th>
                           <th>PRK Inti</th>
                           <th>PRK Kegiatan</th>
                           <th>Anggaran</th>
                           <th>Disburse</th>
                         </tr>
                       </thead>
                       <tbody>
                        <!-- rutin -->
                        <?php
                        
                        

                        if ($input_lokasi != NULL && $new_rutin != NULL) {
                          for ($i = 0 ; $i<count($new_rutin) ; $i++) { ?>
                            <tr style="background:white;color:black">
                              <p>
                                <td><a attr="{{$new_rutin[$i]}}" class="btn btn-primary" id="add">Show Inti</td>
                                <td>{{ $new_rutin[$i] }}</td>
                                <td>{{ $cellR_rutin[$i]->value }}</td>
                                <td></td>
                                <td></td>
                                {{-- <td>{{ $totalA }}</td> --}}
                                <td>
                                  @foreach($totalParentAnggaranRutin as $key)
                                  <?php 
                                    if($key[0] == $new_rutin[$i]){
                                      echo $key[1];
                                    } 
                                  ?>
                                  @endforeach
                                </td>
                                <td>
                                  @foreach($totalParentDisburseRutin as $key)
                                    <?php
                                      if ($key[0] == $new_rutin[$i]) {
                                        echo $key[1];
                                      }
                                    ?>
                                  @endforeach
                                </td>
                            </tr>
                            </p>

                             @foreach($new2_rutin as $key)
                             <?php
                             $totalAnggaran = 0;
                             $totalDisburse = 0;
                             $aa = 0;
                             foreach ($cellI_rutin as $cellI_key => $value) {
                               if (substr($value->value, 2,6) == $key) {
                                $totalAnggaran += $cellAN_rutin[$cellI_key]->value;
                                $totalDisburse += $cellAV_rutin[$cellI_key]->value;
                               }
                               // $aa = $totalAnggaran + $totalDisburse;
                             }

                             if (substr($key, 2, 2) == $new_rutin[$i]) { ?>
                               <tr style="background:#CCFFFF;color:black;" class="hidetr{{$new_rutin[$i]}} hidetrinit">
                                 <td><a attr={{$key}} class="btn btn-primary" id="add2">Show Kegiatan</a></td>
                                 <td>{{ $key }}</td>
                                 <td></td>
                                 <td>{{ $cellS_rutin[$i]->value }}</td>
                                 <td></td>
                                 <td>{{ $totalAnggaran }}</td>
                                 <td>{{ $totalDisburse }}</td>
                               </tr>

                                @foreach($cellI_rutin as $rutin => $value)                              
                                 <?php
                                 if (substr($value->value, 2, 6 ) == $key ) { ?>
                                   <tr style="background:#33CCFF;color:black;" class="hide2{{$key}} hidetrinit2">
                                     <td></td>
                                     <td>{{ $cellI_rutin[$rutin]->value }}</td>
                                     <td></td>
                                     <td></td>
                                     <td>{{ $cellS_rutin[$rutin]->value }}</td>
                                     <td>{{ $cellAN_rutin[$rutin]->value }}</td>
                                     <td>{{ $cellAV_rutin[$rutin]->value }}</td>
                                   </tr>
                                 <?php
                                  }
                                  ?>
                            
                                @endforeach
                              <?php
                              }
                              ?>
                            @endforeach
                            <?php

                          //end of for pertama
                          }

                        // end of if pertama
                        }
                        ?>
                 
                        <!-- reimburse -->
                        <?php
                        if ($input_lokasi != NULL && $new != NULL) {
                          for ($i = 0 ; $i<count($new) ; $i++) { ?>
                            <tr style="background:white;color:black">
                              <p>
                                <td><a attr="{{$new[$i]}}" class="btn btn-primary" id="add3">Show Inti</td>
                                <td>{{ $new[$i] }}</td>
                                <td>{{ $cellR_reimburse[$i]->value }}</td>
                                <td></td>
                                <td></td>
                                <td>
                                  @foreach($totalParentAnggaranReimburse as $key)
                                  <?php 
                                    if($key[0] == $new[$i]){
                                      echo $key[1];
                                    } 
                                  ?>
                                  @endforeach
                                </td>
                                <td>
                                  @foreach($totalParentDisburseReimburse as $key)
                                    <?php
                                      if ($key[0] == $new[$i]) {
                                        echo $key[1];
                                      }
                                    ?>
                                  @endforeach
                                </td>
                            </tr>
                            </p>

                             @foreach($new2 as $key)
                             <?php
                             $totalAnggaran = 0;
                             $totalDisburse = 0;
                             foreach ($cellI_reimburse as $cellI_key => $value) {
                               if (substr($value->value, 2,6) == $key) {
                                $totalAnggaran += $cellAN_reimburse[$cellI_key]->value;
                                $totalDisburse += $cellAV_reimburse[$cellI_key]->value;
                               }
                             }
                             if (substr($key, 2, 2) == $new[$i]) { ?>
                               <tr style="background:#CCFFFF;color:black;" class="hidetr3{{$new[$i]}} hidetrinit3">
                                 <td><a attr={{$key}} class="btn btn-primary" id="add4">Show Kegiatan</a></td>
                                 <td>{{ $key }}</td>
                                 <td></td>
                                 <td>{{ $cellS_reimburse[$i]->value }}</td>
                                 <td></td>
                                 <td>{{ $totalAnggaran }}</td>
                                 <td>{{ $totalDisburse }}</td>
                               </tr>

                                @foreach($cellI_reimburse as $reimburse => $value)                              
                                 <?php
                                 if (substr($value->value, 2, 6 ) == $key ) { ?>
                                   <tr style="background:#33CCFF;color:black;" class="hide4{{$key}} hidetrinit4">
                                     <td></td>
                                     <td>{{ $cellI_reimburse[$reimburse]->value }}</td>
                                     <td></td>
                                     <td></td>
                                     <td>{{ $cellS_reimburse[$reimburse]->value }}</td>
                                     <td>{{ $cellAN_reimburse[$reimburse]->value }}</td>
                                     <td>{{ $cellAV_reimburse[$reimburse]->value }}</td>
                                   </tr>
                                 <?php
                                  }
                                  ?>
                            
                                @endforeach
                              <?php
                              }
                              ?>
                            @endforeach
                            <?php

                          //end of for pertama
                          }

                        // end of if pertama
                        }
                        ?>

                        <!-- Pengembangan Usaha -->
                        <?php
                        if ($input_lokasi != NULL && $new_pu != NULL) {
                          for ($i = 0 ; $i<count($new_pu) ; $i++) { ?>
                            <tr style="background:white;color:black">
                              <p>
                                <td><a attr="{{$new_pu[$i]}}" class="btn btn-primary" id="add3">Show Inti</td>
                                <td>{{ $new_pu[$i] }}</td>
                                <td>{{ $cellS_PU[$i]->value }}</td>
                                <td></td>
                                <td></td>
                                <td>
                                  @foreach($totalParentAnggaranPU as $key)
                                  <?php 
                                    if($key[0] == $new_pu[$i]){
                                      echo $key[1];
                                    } 
                                  ?>
                                  @endforeach
                                </td>
                                <td>
                                  @foreach($totalParentDisbursePU as $key)
                                    <?php
                                      if ($key[0] == $new_pu[$i]) {
                                        echo $key[1];
                                      }
                                    ?>
                                  @endforeach
                                </td>
                            </tr>
                            </p>

                             @foreach($new2_pu as $key)
                             <?php
                             $totalAnggaran = 0;
                             $totalDisburse = 0;
                             foreach ($cellJ_PU as $cellJ_key => $value) {
                               if (substr($value->value, 2,6) == $key) {
                                $totalAnggaran += $cellAJ_PU[$cellJ_key]->value;
                                $totalDisburse += $cellAU_PU[$cellJ_key]->value;
                               }
                             }
                             if (substr($key, 2, 2) == $new_pu[$i]) { ?>
                               <tr style="background:#CCFFFF;color:black;" class="hidetr3{{$new_pu[$i]}} hidetrinit3">
                                 <td><a attr={{$key}} class="btn btn-primary" id="add4">Show Kegiatan</a></td>
                                 <td>{{ $key }}</td>
                                 <td></td>
                                 <td>{{ $cellT_PU[$i]->value }}</td>
                                 <td></td>
                                 <td>{{ $totalAnggaran }}</td>
                                 <td>{{ $totalDisburse }}</td>
                               </tr>

                                @foreach($cellJ_PU as $pu => $value)                              
                                 <?php
                                 if (substr($value->value, 2, 6 ) == $key ) { ?>
                                   <tr style="background:#33CCFF;color:black;" class="hide4{{$key}} hidetrinit4">
                                     <td></td>
                                     <td>{{ $cellJ_PU[$pu]->value }}</td>
                                     <td></td>
                                     <td></td>
                                     <td>{{ $cellU_PU[$pu]->value }}</td>
                                     <td>{{ $cellAJ_PU[$pu]->value }}</td>
                                     <td>{{ $cellAU_PU[$pu]->value }}</td>
                                   </tr>
                                 <?php
                                  }
                                  ?>
                            
                                @endforeach
                              <?php
                              }
                              ?>
                            @endforeach
                            <?php

                          //end of for pertama
                          }

                        // end of if pertama
                        }
                        ?>

                        <!-- Penguatan KIT -->
                        <?php
                        if ($input_lokasi != NULL && $new_pk != NULL) {
                          for ($i = 0 ; $i<count($new_pk) ; $i++) { ?>
                            <tr style="background:white;color:black">
                              <p>
                                <td><a attr="{{$new_pk[$i]}}" class="btn btn-primary" id="add3">Show Inti</td>
                                <td>{{ $new_pk[$i] }}</td>
                                <td>{{ $cellR_KIT[$i]->value }}</td>
                                <td></td>
                                <td></td>
                                <td>
                                  @foreach($totalParentAnggaranPK as $key)
                                  <?php 
                                    if($key[0] == $new_pk[$i]){
                                      echo $key[1];
                                    } 
                                  ?>
                                  @endforeach
                                </td>
                                <td>
                                  @foreach($totalParentDisbursePK as $key)
                                    <?php
                                      if ($key[0] == $new_pk[$i]) {
                                        echo $key[1];
                                      }
                                    ?>
                                  @endforeach
                                </td>
                            </tr>
                            </p>

                             @foreach($new2_pk as $key)
                             <?php
                             $totalAnggaran = 0;
                             $totalDisburse = 0;
                             foreach ($cellI_KIT as $cellI_key => $value) {
                               if (substr($value->value, 2,6) == $key) {
                                $totalAnggaran += $cellAI_KIT[$cellI_key]->value;
                                $totalDisburse += $cellAT_KIT[$cellI_key]->value;
                               }
                             }
                             if (substr($key, 2, 2) == $new_pk[$i]) { ?>
                               <tr style="background:#CCFFFF;color:black;" class="hidetr3{{$new_pk[$i]}} hidetrinit3">
                                 <td><a attr={{$key}} class="btn btn-primary" id="add4">Show Kegiatan</a></td>
                                 <td>{{ $key }}</td>
                                 <td></td>
                                 <td>{{ $cellS_KIT[$i]->value }}</td>
                                 <td></td>
                                 <td>{{ $totalAnggaran }}</td>
                                 <td>{{ $totalDisburse }}</td>
                               </tr>

                                @foreach($cellI_KIT as $pk => $value)                              
                                 <?php
                                 if (substr($value->value, 2, 6 ) == $key ) { ?>
                                   <tr style="background:#33CCFF;color:black;" class="hide4{{$key}} hidetrinit4">
                                     <td></td>
                                     <td>{{ $cellI_KIT[$pk]->value }}</td>
                                     <td></td>
                                     <td></td>
                                     <td>{{ $cellT_KIT[$pk]->value }}</td>
                                     <td>{{ $cellAI_KIT[$pk]->value }}</td>
                                     <td>{{ $cellAT_KIT[$pk]->value }}</td>
                                   </tr>
                                 <?php
                                  }
                                  ?>
                            
                                @endforeach
                              <?php
                              }
                              ?>
                            @endforeach
                            <?php

                          //end of for pertama
                          }

                        // end of if pertama
                        }
                        ?>

                        <!-- IPEG -->
                        <?php
                        if ($input_lokasi != NULL && $int_count_I_PEG != NULL) {
                          for ($i = 0 ; $i<$int_count_I_PEG ; $i++) { ?>
                            <tr >
                              <p>
                                <td></td>
                                <td>{{ $cellE_I_PEG[$i]->value }}</td>
                                <td>{{ $cellF_I_PEG[$i]->value }}</td>
                                <td></td>
                                <td></td>
                                <td>{{ $cellH_I_PEG[$i]->value }}</td>
                                <td>{{ $cellJK_I_PEG[$i] }}</td>
                            </tr>
                            </p>
                            <?php
                          }
                        }
                        ?>

                        <!-- IPADM -->
                        <?php
                        if ($input_lokasi != NULL && $int_count_I_ADM != NULL) {
                          for ($i = 0 ; $i<$int_count_I_ADM ; $i++) { ?>
                            <tr>
                              <p>
                                <td></td>
                                <td>{{ $cellE_I_ADM[$i]->value }}</td>
                                <td>{{ $cellF_I_ADM[$i]->value }}</td>
                                <td></td>
                                <td></td>
                                <td>{{ $cellH_I_ADM[$i]->value }}</td>
                                <td>{{ $cellJK_I_ADM[$i] }}</td>
                            </tr>
                            </p>
                            <?php
                          }
                        }
                        ?>

                        <!-- I Biaya -->
                        <?php
                        if ($input_lokasi != NULL && $int_count_I_BIAYA != NULL) {
                          for ($i = 0 ; $i<$int_count_I_BIAYA ; $i++) { ?>
                            <tr>
                              <p>
                                <td></td>
                                <td>{{ $cellE_I_BIAYA[$i]->value }}</td>
                                <td>{{ $cellF_I_BIAYA[$i]->value }}</td>
                                <td></td>
                                <td></td>
                                <td>{{ $cellH_I_BIAYA[$i]->value }}</td>
                                <td>{{ $cellJK_I_BIAYA[$i] }}</td>
                            </tr>
                            </p>
                            <?php
                          }
                        }
                        ?>

                        <!-- I Diluar Usaha -->
                        <?php
                        if ($input_lokasi != NULL && $int_count_I_DILUAR != NULL) {
                          for ($i = 0 ; $i<$int_count_I_DILUAR ; $i++) { ?>
                            <tr>
                              <p>
                                <td></td>
                                <td>{{ $cellE_I_DILUAR[$i]->value }}</td>
                                <td>{{ $cellF_I_DILUAR[$i]->value }}</td>
                                <td></td>
                                <td></td>
                                <td>{{ $cellH_I_DILUAR[$i]->value }}</td>
                                <td>{{ $cellJK_I_DILUAR[$i] }}</td>
                            </tr>
                            </p>
                            <?php
                          }
                        }
                        ?>

                        <!-- I Diluar Usaha -->
                        <?php
                        if ($input_lokasi != NULL && $int_count_I_PENDUKUNG != NULL) {
                          for ($i = 0 ; $i<$int_count_I_PENDUKUNG ; $i++) { ?>
                            <tr>
                              <p>
                                <td></td>
                                <td>{{ $cellC_I_PENDUKUNG[$i]->value }}</td>
                                <td>{{ $cellD_I_PENDUKUNG[$i]->value }}</td>
                                <td></td>
                                <td></td>
                                <td>{{ $cellE_I_PENDUKUNG[$i]->value }}</td>
                                <td>{{ $cellF_I_PENDUKUNG[$i]->value }}</td>
                            </tr>
                            </p>
                            <?php
                          }
                        }
                        ?>

                        

             </tbody>
               </table>
             </div>
           </div>
         </div>
       </div>
      </div>



       @endsection

@section('js_page')
<script type="text/javascript">

$(document).ready(function() {
var table = $('#table-list-prk').DataTable( {
  pagingType: "full_numbers",
  ordering: false,
  "lengthMenu": [[10, 25, 50, 75, 100, -1], [10, 25, 50, 75, 100, "All"]]

});
$('#table-list-prk_filter label input').on( 'keyup', function () {
  table
  .columns( 0 )
  .search( this.value )
  .draw();
});
        

  // JS RUTIN
  $(function() {
      $(".hidetrinit").find("td").hide();
      // $("#add").click(function(event) {
      $('body').on('click','#add',function(event){
          event.stopPropagation();
          var $target = $(event.target);
          var id = $(this).attr("attr")
          if ( $target.closest("td").attr("colspan") > 1 ) {
              $target.slideUp();
              $('.hidetr'+id).find("td").slideUp();
              $('.hide2'+id).find("td").slideUp();
          } else {
              
              $('.hidetr'+id).find("td").slideToggle();
              
          }                    
      });
  });


  $(function() {
      $(".hidetrinit2").find("td").hide();
      $('body').on('click','#add2',function(event){
          event.stopPropagation();
          var $target = $(event.target);
          var id = $(this).attr("attr")
          if ( $target.closest("td").attr("colspan") > 1 ) {
              $target.slideUp();
          } else {
            $('.hide2'+id).find("td").slideToggle();
          }                    
      });
  });
  // END OF JS RUTIN


  // JS REIMBURSE
  $(function() {
      $(".hidetrinit3").find("td").hide();
      // $("#add").click(function(event) {
      $('body').on('click','#add3',function(event){
          event.stopPropagation();
          var $target = $(event.target);
          var id = $(this).attr("attr")
          if ( $target.closest("td").attr("colspan") > 1 ) {
              $target.slideUp();
              $('.hidetr3'+id).find("td").slideUp();
              $('.hide4'+id).find("td").slideUp();
          } else {
              
              $('.hidetr3'+id).find("td").slideToggle();
              
          }                    
      });
  });

  $(function() {
      $(".hidetrinit4").find("td").hide();
      $('body').on('click','#add4',function(event){
          event.stopPropagation();
          var $target = $(event.target);
          var id = $(this).attr("attr")
          if ( $target.closest("td").attr("colspan") > 1 ) {
              $target.slideUp();
          } else {
            $('.hide4'+id).find("td").slideToggle();
          }                    
      });
  });
});
</script>

@endsection