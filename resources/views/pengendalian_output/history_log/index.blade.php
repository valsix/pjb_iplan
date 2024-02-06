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
                <input type="text" class="form-control col-md-7 col-xs-12" name="tahun_anggaran" readonly="readonly" value="{{$input_tahun}}">
                <!-- <input type="text" class="form-control col-md-7 col-xs-12" name="tahun_anggaran" readonly="readonly" value=""> -->
              </div>

              <div class="form-group">
              <label class="col-md-2 col-sm-3 col-xs-12">Struktur Bisnis</label>
              <div class="col-md-3 col-sm-4 col-xs-12">
                <!-- <input type="text" class="form-control col-md-7 col-xs-12" name="strategi_bisnis" readonly="readonly" value=""> -->
                <input type="text" class="form-control col-md-7 col-xs-12" name="strategi_bisnis" readonly="readonly" value="{{($input_sb!=null) ? $input_sb->name : ''}}">
              </div>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12" >Distrik</label>
              <div class="col-md-4 col-sm-4 col-xs-12">
                <!-- <input type="text" class="form-control col-md-7 col-xs-12" name="distrik" readonly="readonly" value=""> -->
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
                <!-- <input type="text" class="form-control col-md-7 col-xs-12" name="lokasi" readonly="readonly"> -->
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
                <!-- <input type="text" name="fase" value="" class="form-control col-md-7 col-xs-12" readonly="readonly"> -->
                <input type="text" name="fase" value="{{ 'Ketetapan' }}" class="form-control col-md-7 col-xs-12" readonly="readonly">

              </div>

              <div class="form-group">
                <label class="col-md-2 col-sm-3 col-xs-12">s.d Bulan</label>
                <div class="col-md-3 col-sm-4 col-xs-12">
                  <!-- <input type="text" name="fase" value="" class="form-control col-md-7 col-xs-12" readonly="readonly"> -->
                  <input type="text" name="fase" value="{{ $nama_bln_dipilih }}" class="form-control col-md-7 col-xs-12" readonly="readonly">

                </div>
              </div>
            </div></form></div></div></div></div>


    <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                <a href="{{ Request::fullUrl() }}&type=excel" id="get-excel" class="btn btn-success pull-right"><i class="fa fa-download"></i> Download Excel</a>
                    <h2 style="font-size: 18px;">HISTORY LOG <?php echo $judul; ?> </h2>
                    <div class="clearfix"></div>

                  </div>

    <div style="overflow-x:auto;">
                    <table id="table-history-log" class="table table-striped table-bordered" cellspacing="0" width="100%" style="height: 100px !important;font-size:11px;">
                       <thead style="background:#2A3F54;color:white;">
                         <tr>
                           <th rowspan="2" style="vertical-align:middle">Tanggal</th>
                           <!-- <th rowspan="2" style="vertical-align:middle">Nomor PRK</th> -->
                           <!-- <th rowspan="2" style="vertical-align:middle">Uraian Kegiatan</th> -->
                           <th rowspan="2" style="vertical-align:middle">Nomor PRK</th>
                           <th rowspan="2" style="vertical-align:middle">Identity PRK</th>

                           <th colspan="2">Deskripsi PRK</th>

                           
                                <th colspan="2">{{ $judul == 'AI' ? 'Anggaran Proyek' : 'Beban' }}  (Dalam Ribuan)</th>

                                <th colspan="2">{{ $judul == 'AI' ? 'Anggaran Investasi' : 'Cash Flow' }}  (Dalam Ribuan)</th>

                                <th colspan="2">{{ $judul == 'AI' ? 'Anggaran Kas Investasi' : 'Ijin Proses' }} (Dalam Ribuan)</th>
                    


                            <!--<th rowspan="2" style="vertical-align:middle">PIC</th> -->

                         </tr>
                         <tr>
                           <th>Awal</th>
                           <th>Revisi</th>

                           <th>Awal</th>
                           <th>Revisi</th>

                           <th>Awal</th>
                           <th>Revisi</th>

                           <th>Awal</th>
                           <th>Revisi</th>
                         </tr>

                       </thead>

                      <body>
                        <?php $baris = 0; ?>
                        <?php $urut = 1;?>
                        @foreach($pgdl_history_log as $phl)

                         <tr>

                            <td>{{ \Carbon\Carbon::parse($phl->created_at)->format('d M Y') }}</td>
                            <!-- <td>{{ \Carbon\Carbon::parse($phl->created_at)->format('d M Y') }}</td> -->
                            <td>{{ $phl->prk }}</td>
                            @if($phl->identity_prk == null)
                              <td>{{ '-' }}</td>
                              @else
                                <td>{{ $phl->identity_prk }}</td>
                            @endif
                            @if($phl->deskripsi_prk_awal == null)
                              <td>{{ '-' }}</td>
                              @else
                              <td>{{ $phl->deskripsi_prk_awal }}</td>
                            @endif
                            @if($phl->deskripsi_prk_akhir == null)
                              <td>{{ '-' }}</td>
                              @else
                              <td>{{ $phl->deskripsi_prk_akhir }}</td>
                            @endif
                            @if($phl->beban_awal == null)
                              <td>{{ '-' }}</td>
                              @else
                                @if( is_numeric( $phl->beban_awal ))
                                  <td style="text-align: right;">
                                    <!-- jika desimal -->
                                    @if( floor( $phl->beban_awal ) != $phl->beban_awal )
                                       {{ number_format($phl->beban_awal, 2,',','.') }}
                                    <!-- jika bukan desimal -->
                                    @else
                                      {{ number_format($phl->beban_awal, 0,',','.') }}
                                    @endif
                                  </td>
                                @else
                                  <td>
                                    {{ $phl->beban_awal }}
                                  </td>
                                @endif
                              <!-- <td>{{ $phl->beban_awal }}</td> -->
                            @endif
                            @if($phl->beban_akhir == null)
                              <td>{{ '-' }}</td>
                              @else
                                @if( is_numeric( $phl->beban_akhir ))
                                  <td style="text-align: right;">
                                    <!-- jika desimal -->
                                    @if( floor( $phl->beban_akhir ) != $phl->beban_akhir )
                                       {{ number_format($phl->beban_akhir, 2,',','.') }}
                                    <!-- jika bukan desimal -->
                                    @else
                                      {{ number_format($phl->beban_akhir, 0,',','.') }}
                                    @endif
                                  </td>
                                @else
                                  <td>
                                    {{ $phl->beban_akhir }}
                                  </td>
                                @endif
                              <!-- <td>{{ $phl->beban_akhir }}</td> -->
                            @endif
                            @if($phl->cashflow_awal == null)
                              <td>{{ '-' }}</td>
                              @else
                                @if( is_numeric( $phl->cashflow_awal ))
                                  <td style="text-align: right;">
                                    <!-- jika desimal -->
                                    @if( floor( $phl->cashflow_awal ) != $phl->cashflow_awal )
                                       {{ number_format($phl->cashflow_awal, 2,',','.') }}
                                    <!-- jika bukan desimal -->
                                    @else
                                      {{ number_format($phl->cashflow_awal, 0,',','.') }}
                                    @endif
                                  </td>
                                @else
                                  <td>
                                    {{ $phl->cashflow_awal }}
                                  </td>
                                @endif
                              <!-- <td>{{ $phl->cashflow_awal }}</td> -->
                            @endif
                            @if($phl->cashflow_akhir == null)
                              <td>{{ '-' }}</td>
                              @else
                                @if( is_numeric( $phl->cashflow_akhir ))
                                  <td style="text-align: right;">
                                    <!-- jika desimal -->
                                    @if( floor( $phl->cashflow_akhir ) != $phl->cashflow_akhir )
                                       {{ number_format($phl->cashflow_akhir, 2,',','.') }}
                                    <!-- jika bukan desimal -->
                                    @else
                                      {{ number_format($phl->cashflow_akhir, 0,',','.') }}
                                    @endif
                                  </td>
                                @else
                                  <td>
                                    {{ $phl->cashflow_akhir }}
                                  </td>
                                @endif
                              <!-- <td>{{ $phl->cashflow_akhir }}</td> -->
                            @endif
                            @if($phl->ijin_proses_awal == null)
                              <td>{{ '-' }}</td>
                              @else
                                @if( is_numeric( $phl->ijin_proses_awal ))
                                  <td style="text-align: right;">
                                    <!-- jika desimal -->
                                    @if( floor( $phl->ijin_proses_awal ) != $phl->ijin_proses_awal )
                                       {{ number_format($phl->ijin_proses_awal, 2,',','.') }}
                                    <!-- jika bukan desimal -->
                                    @else
                                      {{ number_format($phl->ijin_proses_awal, 0,',','.') }}
                                    @endif
                                  </td>
                                @else
                                  <td>
                                    {{ $phl->ijin_proses_awal }}
                                  </td>
                                @endif
                              <!-- <td>{{ $phl->ijin_proses_awal }}</td> -->
                            @endif
                            @if($phl->ijin_proses_akhir == null)
                              <td>{{ '-' }}</td>
                              @else
                                @if( is_numeric( $phl->ijin_proses_akhir ))
                                  <td style="text-align: right;">
                                    <!-- jika desimal -->
                                    @if( floor( $phl->ijin_proses_akhir ) != $phl->ijin_proses_akhir )
                                       {{ number_format($phl->ijin_proses_akhir, 2,',','.') }}
                                    <!-- jika bukan desimal -->
                                    @else
                                      {{ number_format($phl->ijin_proses_akhir, 0,',','.') }}
                                    @endif
                                  </td>
                                @else
                                  <td>
                                    {{ $phl->ijin_proses_akhir }}
                                  </td>
                                @endif
                              <!-- <td>{{ $phl->ijin_proses_akhir }}</td> -->
                            @endif
                            <!--<td>{{ $phl->name }}</td>-->
                        </tr>
                        @endforeach
                      </body>




                     </table></div></div></div></div>
@endsection
@section('js_page')
<script type="text/javascript">
  $(document).ready(function() {
    var table = $('#table-history-log').DataTable( {
        "aLengthMenu": [[10, 25, 50, 100, -1],
        [10, 25, 50, 100, "All"]],
        "scrollY": "300px",
        // "scrollX": "300px",
        "scrollCollapse": true,
        "paging": true,
        // pagingType: "full_numbers",
        fixedHeader: true,
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

//     $('#table-monitoring-prk-ai_filter label input').on( 'keyup', function () {
//     table
//         .columns( 0 )
//         .search( this.value )
//         .draw();
// } );
} );
</script>

@endsection
