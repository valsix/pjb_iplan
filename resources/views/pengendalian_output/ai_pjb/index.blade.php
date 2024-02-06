@extends('layouts.app')

@section('css_page')

    <!-- searching -->
    <!-- <script src="{{ asset('js/jquery-1.11.2.min.js') }}" type="text/javascript"></script> -->

    <!-- Datatables -->
    <link href="{{ asset('vendors/datatables.net-bs/css/dataTables-khusus-summary.bootstrap.min.css') }}" rel="stylesheet">
    <!-- <link href="{{ asset('vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet"> -->
    <!-- <link href="{{ asset('vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css') }}" rel="stylesheet"> -->
    <!-- <link href="{{ asset('vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet"> -->
    <!-- <link href="{{ asset('vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}" rel="stylesheet"> -->

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

@section('js_page')

    <script type="text/javascript">
        $('#datatable').dataTable( {
            "searching": true,
            "aLengthMenu": [[10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"]],
            "scrollY": "800px",
            "scrollX": "300px",
            "scrollCollapse": true,
            "paging": true,
            // "ordering": false,
            // pagingType: "full_numbers",
            // fixedHeader: true,
        } );
    </script>


@endsection

@section('content')
     <div role="main">
          <div class="row">
            <div class="page-title">
              <div>
                <h3> DASHBOARD MONITOR AI PJB</h3>
              </div>
              <br>
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
              </div>

              <div class="form-group">
              <label class="col-md-2 col-sm-3 col-xs-12">Struktur Bisnis</label>
              <div class="col-md-3 col-sm-4 col-xs-12">
                <input type="text" class="form-control col-md-7 col-xs-12" name="strategi_bisnis" readonly="readonly" value="{{$distrik->strategi_bisnis->name}}">
              </div>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12" >Distrik</label>
              <div class="col-md-4 col-sm-4 col-xs-12">
                <input type="text" class="form-control col-md-7 col-xs-12" name="distrik" readonly="readonly" value="{{$distrik->name}}">
              </div>

              <div class="form-group">
              <label class="col-md-2 col-sm-3 col-xs-12">Lokasi</label>
              <div class="col-md-3 col-sm-4 col-xs-12">
                <input type="text" class="form-control col-md-7 col-xs-12" name="lokasi" readonly="readonly"
                  value="{{(!empty($lokasi) ? $lokasi->name : '')}}">
              </div>
            </div>
            </div>

            <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12">Fase</label>
              <div class="col-md-4 col-sm-4 col-xs-12">
                <input type="text" name="fase" value="{{ 'Ketetapan' }}" class="form-control col-md-7 col-xs-12" readonly="readonly">
              </div>

              <div class="form-group">
                <label class="col-md-2 col-sm-3 col-xs-12">s.d Bulan</label>
                <div class="col-md-3 col-sm-4 col-xs-12">
                  <input type="text" name="fase" value="{{$nama_bln_dipilih}}" class="form-control col-md-7 col-xs-12" readonly="readonly">
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
                    <input type="text" name="draft_form_10_pu" value="" class="form-control col-md-7 col-xs-12" readonly="readonly">
                </div>
              </div>
            </div>


            <div class="form-group" style="margin-top: 5px;">
              <!-- <label class="col-md-2 col-sm-3 col-xs-12"></label>
              <div class="col-md-4 col-sm-4 col-xs-12"></div> -->

              <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12">Form 10 Penguatan KIT</label>
              <div class="col-md-6 col-sm-4 col-xs-12">
                <input type="text" name="draft_form_10_pk" value="" class="form-control col-md-7 col-xs-12" readonly="readonly">
              </div>
              </div>
            </div>

            <div class="form-group" style="margin-top: 5px;">
              <!-- <label class="col-md-2 col-sm-3 col-xs-12"></label>
              <div class="col-md-4 col-sm-4 col-xs-12"></div> -->

              <div class="form-group">
                <label class="col-md-3 col-sm-3 col-xs-12">Form 10 PLN</label>
                <div class="col-md-6 col-sm-4 col-xs-12">
                <input type="text" name="draft_form_10_pln" value="" class="form-control col-md-7 col-xs-12" readonly="readonly">
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
              <div class="x_panel">
                <div class="x_title">
                  @if(count($ai_pjb_result) > 0)
                  <a href="{{ Request::fullUrl() }}&type=excel" id="get-excel" class="btn btn-success pull-right"><i class="fa fa-download"></i> Download Excel</a>
                  @endif
                  <h2 style="font-size: 18px;">MONITORING AI PJB</h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <table id="datatable" class="table table-striped table-bordered table-hover">
                    <thead  style="background:#282865;color: white">
                        <tr>
                          <th rowspan="2" style="vertical-align: middle;text-align: center;">No.</th>
                          <th rowspan="2" style="vertical-align: middle;text-align: center;">PRK</th>
                          <th rowspan="2" style="vertical-align: middle;text-align: center;">Program</th>
                          <th rowspan="2" style="vertical-align: middle;text-align: center;">AI Awal</th>
                          <th rowspan="2" style="vertical-align: middle;text-align: center;">AI Update</th>
                          <th rowspan="2" style="vertical-align: middle;text-align: center;">Target Terkontrak</th>
                          <th rowspan="2" style="vertical-align: middle;text-align: center;">AKI Awal</th>
                          <th rowspan="2" style="vertical-align: middle;text-align: center;">AKI Update</th>
                          <th rowspan="2" style="vertical-align: middle;text-align: center;">Target Bulan Disburse</th>
                          <th colspan="2" style="vertical-align: middle;text-align: center;">Total Program</th>

                          <th rowspan="2" style="vertical-align: middle;text-align: center;">Realisasi Bulan Kontrak</th>

                          <th rowspan="2" style="vertical-align: middle;text-align: center;">Realisasi Bulan Disburse</th>
                          <th rowspan="2" style="vertical-align: middle;text-align: center;">Nomor PO</th>
                          <th colspan="2" style="vertical-align: middle;text-align: center;">Realisasi(Rp)</th>
                          <th colspan="2" style="vertical-align: middle;text-align: center;">Status</th>
                          <th colspan="2" style="vertical-align: middle;text-align: center;">Realisasi(Program)</th>
                          <th colspan="2" style="vertical-align: middle;text-align: center;">Pencapaian Program(%)</th>
                          <th colspan="1" style="vertical-align: middle;text-align: center;">Pencapaian Kontrak Rp(%)</th>
                          <th colspan="1" style="vertical-align: middle;text-align: center;">Pencapaian Disburse Rp(%)</th>
                        </tr>
                        <tr>
                          <th>Kontrak</th>
                          <th>Disburse</th>
                          <th>Kontrak</th>
                          <th>Disburse</th>
                          <th>Kontrak</th>
                          <th>Disburse</th>
                          <th>"Selesai" Kontrak</th>
                          <th>"Selesai" Disburse</th>
                          <th>Kontrak</th>
                          <th>Disburse</th>
                          <th>Thd AI Update sd TA</th>
                          <!-- <th>Thd AI Update sd Bulan</th> -->
                          <th>Thd AKI Update TA ke N</th>
                          <!-- <th>Thd AKI Update TA ke Bulan</th> -->
                        </tr>
                        <tr>
                          <th>1</th>
                          <th>2</th>
                          <th>3</th>
                          <th>4</th>
                          <th>5</th>
                          <th>6</th>
                          <th>7</th>
                          <th>8</th>
                          <th>9</th>
                          <th>10</th>
                          <th>11</th>
                          <th>12</th>
                          <th>13</th>
                          <th>14</th>
                          <th>15</th>
                          <th>16</th>
                          <th>17</th>
                          <th>18</th>
                          <th>19</th>
                          <th>20</th>
                          <th>21=19/10</th>
                          <th>22=20/11</th>
                          <th>23=15/5</th>
                          <!-- <th>24=11/5</th> -->
                          <th>24=16/8</th>
                          <!-- <th>26=12/7</th> -->
                        </tr>
                    </thead>
                    <tbody> 
                        <?php $i=1;?>
                        @foreach($ai_pjb_result as $row)
                            <tr>
                                <td>{{$i++}}</td> 
                                @foreach($row as $key => $item)
                                    @if( is_numeric( $item ))
                                        <td style="text-align: right;">
                                            <!-- jika desimal -->
                                            @if( floor( $item ) != $item )
                                             {{ number_format($item, 2,',','.') }}    
                                            <!-- jika bukan desimal -->
                                            @else
                                             {{ number_format($item, 0,',','.') }}
                                            @endif
                                        </td>
                                    @else
                                        <td>
                                            {{ $item }}
                                        </td>
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                  </table>
                 </div>
            </div>
          </div>
          </div>
        </div>
@endsection
