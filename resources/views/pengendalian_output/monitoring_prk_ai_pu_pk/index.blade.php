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
    <h3>MONITORING PRK AI Pengembangan Usaha dan Penguatan Kit</h3>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
          @if($notification_failed!= '')
              <div class="alert alert-danger alert-dismissible" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  {{ $notification_failed }}
              </div>
          @endif
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
                <input type="text" name="fase" value="{{ 'Ketetapan' }}" class="form-control col-md-7 col-xs-12" readonly="readonly">
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
              <!-- <label class="col-md-2 col-sm-3 col-xs-12"></label>
              <div class="col-md-4 col-sm-4 col-xs-12"></div> -->

              <div class="form-group">
                <label class="col-md-3 col-sm-3 col-xs-12">Form 10 Pengembangan Usaha</label>
                <div class="col-md-6 col-sm-4 col-xs-12">
                    <input type="text" name="draft_form_10_pu" value="{{ $name_draft_form_10_pu }}" class="form-control col-md-7 col-xs-12" readonly="readonly">
                </div>
              </div>
            </div>


            <div class="form-group" style="margin-top: 5px;">
              <!-- <label class="col-md-2 col-sm-3 col-xs-12"></label>
              <div class="col-md-4 col-sm-4 col-xs-12"></div> -->

              <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12">Form 10 Penguatan KIT</label>
              <div class="col-md-6 col-sm-4 col-xs-12">
                <input type="text" name="draft_form_10_pk" value="{{ $name_draft_form_10_pk }}" class="form-control col-md-7 col-xs-12" readonly="readonly">
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

    @if($input_distrik!=null)
    <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <a href="{{ Request::fullUrl() }}&download=rincian-biaya-administrasi&type=excel" id="get-excel" class="btn btn-success pull-right"><i class="fa fa-download"></i> Download Excel</a>
                    <h2 style="font-size: 18px;">MONITORING PRK AI Pengembangan Usaha dan Penguatan Kit</h2>
                    <div class="clearfix"></div>

                  </div>

                  <div style="overflow-x:auto;">
                    <table id="table-monitoring-prk-ai" class="table table-striped table-bordered" cellspacing="0" width="100%" style="height: 100px !important;font-size:11px;">
                       <thead style="background:#2A3F54;color:white;">
                         <tr>
                           <th rowspan="2" style="vertical-align:middle">No</th>
                           <!-- <th rowspan="2" style="vertical-align:middle">Nomor PRK</th> -->
                           <!-- <th rowspan="2" style="vertical-align:middle">Uraian Kegiatan</th> -->
                           <th rowspan="2" style="vertical-align:middle">Nomor PRK</th>
                           <th rowspan="2" style="vertical-align:middle">Uraian Kegiatan</th>

                           <!--RENCANA-->
                            <th colspan="2">Rencana</th>
                             <!-- End of rencena -->
                             <!--RENCANA Update-->
                            <th colspan="2">Rencana Update</th>
                               <!-- End of rencena -->

                            <th rowspan="2" style="vertical-align:middle">No PO</th>

                            <th rowspan="2" style="vertical-align:middle">Item PO</th>

                            <th rowspan="2" style="vertical-align:middle">Kode Account Code</th>

                            <th colspan="2">Realisasi</th>

                         </tr>
                         <tr>
                           <th>AI Ketetapan</th>
                           <th>AKI Ketetapan</th>

                           <th>AI Ketetapan</th>
                           <th>AKI Ketetapan</th>

                           <th>Kontrak</th>
                           <th>Disburse</th>
                         </tr>

                       </thead>
                       <body>

                  <!-- form rkau, 6, 10 -->
                  <?php $urut = 1;?>
                    @foreach($data_prk_item as $key_prk_po => $value)
                      <!-- pengelompokan berdasarkan PRK dan No PO -->
                      <tr style="background:#8EC7D1;color:black;">
                          <td>@if($value['item_po'] > 0)<a attr="{{ $key_prk_po }}" class="btn btn-primary" id="add">+@endif</td>
                          <td>{{$value['prk_kegiatan']}}</td>
                          <td>{{$value['desc_prk_kegiatan']}}</td><!-- <th>Identity PRK Kegiatan</th> -->
                          <td style="text-align: right;">{{ number_format(1000 * $value['ai_ketetapan'],0,",",".") }}</td><!-- <th>AI Ketetapan</th> -->
                          <td style="text-align: right;">{{ number_format(1000 * $value['total_year_estimate'],0,",",".") }}</td><!-- <th>AKI Ketetapan</th> -->

                          <td style="text-align: right;">{{ number_format(1000 * $value['ai_ketetapan_update'],0,",",".") }}</td><!-- <th>AI Ketetapan</th> -->
                          <td style="text-align: right;">{{ number_format(1000 * $value['total_year_estimate_update'],0,",",".") }}</td><!-- <th>AKI Ketetapan</th> -->

                          <td style="text-align: right;">
                          <!-- {{-- number_format( $value['po_no'],0,",",".") --}} -->
                          {{ $value['po_no'] }}
                          </td><!-- <th>Nomor PO</th> -->
                          <td style="text-align: right;">{{ number_format( $value['item_po'],0,",",".") }}</td><!-- <th>Item PO</th> -->

                          <td style="text-align: right;">{{ $value['account_code'] }}</td><!-- <th>Kode Account Code</th> -->

                          <td style="text-align: right;">{{ number_format((float)$value['kontrak'],0,",",".") }}</td><!-- <th>Kontrak</th> -->
                          <td style="text-align: right;">{{ number_format((float)$value['disburse'],0,",",".") }}</td><!-- <th>Disburse</th> -->
                      </tr>
                      @if($value['item_po'] > 0)
                          <?php $baris = 0; ?>
                          <!-- detail dari tiap Item PO sesuai PRK dan No PO -->
                          @foreach($value['per_item'] as $key_item => $item)
                              <?php $baris = $baris+1; $warna=($baris % 2 == 0) ? "#ffffff" : "#E8EDEF"; ?>
                              <tr style="background-color: <?= $warna?>" class="hidetrinti{{$key_prk_po}} hidetrinit">
                                  <td></td>
                                  <td>{{$item['prk_kegiatan']}}</td>
                                  <td>{{$item['desc_prk_kegiatan']}}</td><!-- <th>Identity PRK Kegiatan</th> -->
                                  <td style="text-align: right;">{{ number_format(1000 * $item['ai_ketetapan'],0,",",".") }}</td><!-- <th>AI Ketetapan</th> -->
                                  <td style="text-align: right;">{{ number_format(1000 * $item['total_year_estimate'],0,",",".") }}</td><!-- <th>AKI Ketetapan</th> -->

                                  <td style="text-align: right;">{{ number_format(1000 * $item['ai_ketetapan_update'],0,",",".") }}</td><!-- <th>AI Ketetapan</th> -->
                                  <td style="text-align: right;">{{ number_format(1000 * $item['total_year_estimate_update'],0,",",".") }}</td><!-- <th>AKI Ketetapan</th> -->

                                  <td style="text-align: right;">
                                  <!-- {{-- number_format( $item['po_no'],0,",",".") --}} -->
                                  {{ $item['po_no'] }}
                                  </td><!-- <th>Nomor PO</th> -->
                                  <td style="text-align: right;">{{ number_format( $item['item_po'],0,",",".") }}</td><!-- <th>Item PO</th> -->

                                  <td style="text-align: right;">{{ $item['account_code'] }}</td><!-- <th>Kode Account Code</th> -->

                                  <td style="text-align: right;">{{ number_format((float)$item['kontrak'],0,",",".") }}</td><!-- <th>Kontrak</th> -->
                                  <td style="text-align: right;">{{ number_format((float)$item['disburse'],0,",",".") }}</td><!-- <th>Disburse</th> -->
                              </tr>
                          @endforeach
                      @endif
                    @endforeach
                    

                       </body>
                    </table>
                    </div>
                  </div>
                </div>
            <!-- </div> -->
    </div>
    @endif
          </div>
@endsection

@section('js_page')
<script type="text/javascript">
  $(document).ready(function() {
    var table = $('#table-monitoring-prk-ai').DataTable( {
        "aLengthMenu": [[10, 25, 50, 100, -1],
        [10, 25, 50, 100, "All"]],
        "scrollY": "300px",
        // "scrollX": "300px",
        // pagingType: "full_numbers",
        "pageLength": -1, //default all supaya bisa hide semua inti & kegiatan
        "fixedHeader": true,
        "scrollCollapse": true,
        // pagingType: "full_numbers",
        ordering: false
    } );

    setTimeout(function() {
        table.draw();
      }, 1000 );

    $('#menu_toggle').click(function() {
      setTimeout(function() {
          table.draw();
          }, 500 );
      } );

    $('#table-monitoring-prk-ai label input').on( 'keyup', function () {
    table
        .columns( 0 )
        .search( this.value )
        .draw();
    });

    @if($input_lokasi!=null)
    $(function() {
      @foreach($data_prk_item as $key_prk_po => $value)
          @if($value['item_po'] > 0)
              @foreach($value['per_item'] as $key_item => $item)
                  $(".hidetrinti{{ $key_prk_po }}").find("td").hide();
              @endforeach
          @endif
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
            if( $(this).html() == "+") {
              $(this).html("-");
            }
            else {
              $(this).html("+");
            }

            $('.hidetrinti'+id).find("td").slideToggle();
            console.log('else .hidetrinti'+id);

            $('.trkeg_parent'+id).find("td").slideUp();
            console.log('trkeg_parent'+id);
        });

      });

    });
    @endif
} );
</script>
@endsection
