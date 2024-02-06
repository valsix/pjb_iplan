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
                                       <option></option>
                                       <option value="2017">2017</option>
                                       <option value="2018">2018</option>
                                    </select>
                                </div>
                                <div class="col-md-2"><label>Struktur Bisnis</label></div>
                                <div class="col-md-3">
                                    <select class="form-control" name="strategi_bisnis">
                                       <option></option>
                                       @foreach ($sb as $sbs => $value)
                                         <option value="{{ $value->id }}"> {{ $value->name }} </option>
                                       @endforeach
                                    </select>
                                </div>
                               
                                <br>
                                <br>
                                <div class="col-md-2"><label>Distrik</label></div>
                                <div class="col-md-4">
                                    <select class="form-control" name="distrik">
                                       
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
                                                    url: '/output/rincian-biaya-har/ajax/'+strategi_bisnisID,
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
                                       
                                    </select>
                                </div>

                                <script type="text/javascript">
                                    $(document).ready(function() {
                                        $('select[name="distrik"]').on('change', function() {
                                            var lokasiID = $(this).val();
                                            $('select[name="lokasi"]').empty();

                                            if(lokasiID) {
                                                $.ajax({
                                                    url: '/output/rincian-biaya-har/ajax2/'+lokasiID,
                                                    type: "GET",
                                                    dataType: "json",
                                                    success:function(data) {
                        
                                                      $('select[name="lokasi"]').empty();
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
                                        });
                                    });
                                </script>

                                <br>
                                <br>
                                <div class="col-md-2"><label>Fase</label></div>
                                <div class="col-md-4">
                                  <select class="form-control" name="fase">
                                     <option></option>
                                     @foreach ($fase as $fases => $value)
                                      <option value="{{ $value->id }}"> {{ $value->name }} </option>
                                     @endforeach
                                  </select>
                                </div>

                                <div class="col-md-2"><label>Draft RKAU</label></div>
                                <div class="col-md-3">
                                  <select class="form-control" name="draft_rkau">
                                    <option></option>

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
                                              url: '/output/list-prk/ajax3/'+id_lokasi+'/'+id_tahun,
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
                                    <option></option>

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
                                              url: '/output/list-prk/ajax4/'+id_lokasi+'/'+id_tahun,
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
                                    <option></option>

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
                                              url: '/output/list-prk/ajax5/'+id_lokasi+'/'+id_tahun,
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
                                    <option></option>

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
                                              url: '/output/list-prk/ajax6/'+id_lokasi+'/'+id_tahun,
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
                                    <option></option>

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
                                              url: '/output/list-prk/ajax7/'+id_lokasi+'/'+id_tahun,
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
                                    <option></option>

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
                                              url: '/output/list-prk/ajax8/'+id_lokasi+'/'+id_tahun,
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

        <div class="x_panel">
            <div class="x_title">
                <h2>Form </h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br />
                <form class="form-horizontal form-label-left">

                    <div class="form-group "> 
                        <label class="col-md-2 col-md-4">Fase</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <?php  
                            // dd($input_fase);
                            if ($input_fase != NULL) { ?>
                              <input value="{{ $input_fase->name }}" id="fase" class="form-control col-md-7 " type="text" readonly="">
                          <?php
                            }
                            else { ?>
                              <input value="" id="fase" class="form-control col-md-7 " type="text" readonly="">
                          <?php
                            }
                          ?> 
                        </div>
                    </div>

                    <div class="form-group "> 
                      <label class="col-md-2 col-md-4">Draft RKAU</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          <?php  
                            if ($input_draft_rkau != NULL) { ?>
                              <input value="{{ $input_draft_rkau->draft_versi }}" id="draft_rkau" class="form-control col-md-7 " type="text" readonly="">
                          <?php
                            }
                            else { ?>
                              <input value="" id="draft_rkau" class="form-control col-md-7 " type="text" readonly="">
                          <?php
                            }
                          ?>
                      </div>
                    </div>

                    <div class="form-group "> 
                      <label class="col-md-2 col-md-4">Draft Form 6 Reimburse</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          <?php  
                            if ($input_draft_rkau != NULL) { ?>
                              <input value="{{ $input_draft_form_6_reimburse->draft_versi }}" id="draft_form_6_reimburse" class="form-control col-md-7 " type="text" readonly="">
                          <?php
                            }
                            else { ?>
                              <input value="" id="draft_form_6_reimburse" class="form-control col-md-7 " type="text" readonly="">
                          <?php
                            }
                          ?>
                      </div>
                    </div>

                    <div class="form-group "> 
                      <label class="col-md-2 col-md-4">Draft Form 6 Rutin</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          <?php  
                            if ($input_draft_rkau != NULL) { ?>
                              <input value="{{ $input_draft_form_6_rutin->draft_versi }}" id="draft_form_6_rutin" class="form-control col-md-7 " type="text" readonly="">
                          <?php
                            }
                            else { ?>
                              <input value="" id="draft_form_6_rutin" class="form-control col-md-7 " type="text" readonly="">
                          <?php
                            }
                          ?>
                      </div>
                    </div>

                    <div class="form-group "> 
                      <label class="col-md-2 col-md-4">Draft Form 10 Pengembangan Usaha</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          <?php  
                            if ($input_draft_rkau != NULL) { ?>
                              <input value="{{ $input_draft_form_10_pu->draft_versi }}" id="draft_form_10_pu" class="form-control col-md-7 " type="text" readonly="">
                          <?php
                            }
                            else { ?>
                              <input value="" id="draft_form_10_pu" class="form-control col-md-7 " type="text" readonly="">
                          <?php
                            }
                          ?>
                      </div>
                    </div>

                    <div class="form-group "> 
                      <label class="col-md-2 col-md-4">Draft Form 10 Penguatan KIT</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          <?php  
                            if ($input_draft_rkau != NULL) { ?>
                              <input value="{{ $input_draft_form_10_pk->draft_versi }}" id="draft_form_10_pk" class="form-control col-md-7 " type="text" readonly="">
                          <?php
                            }
                            else { ?>
                              <input value="" id="draft_form_10_pk" class="form-control col-md-7 " type="text" readonly="">
                          <?php
                            }
                          ?>
                      </div>
                    </div>

                    <div class="form-group "> 
                      <label class="col-md-2 col-md-4">Draft Form 10 PLN</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          <?php  
                            if ($input_draft_rkau != NULL) { ?>
                              <input value="{{ $input_draft_form_10_pln->draft_versi }}" id="draft_form_10_pln" class="form-control col-md-7 " type="text" readonly="">
                          <?php
                            }
                            else { ?>
                              <input value="" id="draft_form_10_pln" class="form-control col-md-7 " type="text" readonly="">
                          <?php
                            }
                          ?>
                      </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 col-md-4" " >Tahun Anggaran</label>
                        <div class="col-md-6 col-sm-6 col-xs-12 ">
                          <?php  
                            if ($input_tahun != NULL) { ?>
                          <input value="{{ $input_tahun }}" type="text " id="tahun "  class="form-control" readonly="">
                          <?php
                            }
                            else { ?>
                              <input type="text " id="tahun "  class="form-control" readonly="">
                          <?php
                            }
                          ?>
                        </div>
                      </div>

                      <div class="form-group ">
                        <label class="col-md-2 col-md-4 " >Struktur Bisnis</label>
                        <div class="col-md-6 col-sm-6 col-xs-12 ">
                          <?php  
                            if ($input_sb != NULL) { ?>
                              <input value="{{ $input_sb->name }}" type="text " id="struktur-bisnis "  class="form-control col-md-7" readonly="" >
                          <?php
                            }
                            else { ?>
                              <input value="" type="text " id="struktur-bisnis "  class="form-control col-md-7" readonly="" >
                          <?php
                            }
                          ?>
                        </div>
                      </div>

                      <div class="form-group ">
                        <label class="col-md-2 col-md-4">Distrik</label>
                        <div class="col-md-6 col-sm-6 col-xs-12 ">
                          <?php  
                            if ($input_distrik != NULL) { ?>
                              <input value="{{ $input_distrik->name }}" id="distrik " class="form-control col-md-7 col-xs-12 " type="text" readonly="">
                          <?php
                            }
                            else { ?>
                              <input value="" id="distrik " class="form-control col-md-7 col-xs-12 " type="text" readonly="">
                          <?php
                            }
                          ?>
                        </div>
                      </div>
                
                      <div class="form-group "> 
                        <label class="col-md-2 col-md-4">Lokasi</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php  
                            if ($input_lokasi != NULL) { ?>
                              <input value="{{ $input_lokasi->name }}" id="lokasi" class="form-control col-md-7 " type="text" readonly="">
                          <?php
                            }
                            else { ?>
                              <input value="" id="lokasi" class="form-control col-md-7 " type="text" readonly="">
                          <?php
                            }
                          ?>
                        </div>
                      </div>

                </form>
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

                  <button type="button" class="btn btn-primary pull-right"><i class="fa fa-download"></i> Download PDF</button>
                  <button type="button" class="btn btn-success pull-right"><i class="fa fa-download"></i> Download Excel</button>
                  
                  <div class="x_content">
                       <table class="table table-striped table-bordered">
                         <thead>
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
                              <tr class="clickable" data-toggle="collapse" id="row1" data-target=".row1">
                                  <td></td>
                                  <td>172L01</td>
                                  <td>
                                    <button type="button" class="btn btn-info btn-xs" data-toggle="collapse"> PRK Inti <i class="fa fa-chevron-down"></i></button>
                                    Tools/Material consumable/sparepart umum/jasa umum
                                  </td>
                                  <td></td>  
                                  <td></td>
                                  <td></td>
                                  <td></td>
                              </tr>
                              <tr class="collapse row1">
                                  <td></td>
                                  <td></td>
                                  <td>PRK Intilkdjas</td>  
                                  <td></td>
                                  <td></td>
                                  <td></td>                              
                              </tr>
                              <tr class="collapse row1">
                                  <td></td>
                                  <td></td>
                                  <td>PRK Inti</td>  
                                  <td></td>
                                  <td></td>
                                 <td>
                                   <button type="button" class="btn btn-info btn-xs ">
                                     PRK Kegiatan
                                   </button>
                                 </td>
                              </tr>
                                         
                              <tr id="collapseme" class="collapse out">
                                <td>a</td>
                                <td>b</td>
                                <td>a</td>
                                <td>b</td>
                                <td></td>
                                <td></td>
                              </tr> 

                         </tbody>
           
                           <script type="text/javascript">
                             $(".btn").click(function() {
                                if($("#collapseme").hasClass("out")) {
                                    $("#collapseme").addClass("in");
                                    $("#collapseme").removeClass("out");
                                } else {
                                    $("#collapseme").addClass("out");
                                    $("#collapseme").removeClass("in");
                                }
                             });
                           </script>

                      </table>
                  </div>
                </div>
              </div>
        </div>
     

    @endsection