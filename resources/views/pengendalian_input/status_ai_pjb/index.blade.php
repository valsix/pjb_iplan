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

     <script type="text/javascript">
      <?php $k = 1; ?>
      <?php foreach ($ai_pjb_result as $row) { ?>
        <?php foreach ($row as $value) { ?>
          $('#myDatepicker1<?php echo $k++; ?>').datetimepicker({
              format: 'YYYY-MM-DD'
          });
        <?php } ?>
      <?php } ?>
    </script>

    <script type="text/javascript">
      <?php $h = 1; ?>
      <?php foreach ($ai_pjb_result as $row) { ?>
        <?php foreach ($row as $value) { ?>
          $('#myDatepicker2<?php echo $h++; ?>').datetimepicker({
              format: 'YYYY-MM-DD'
          });
        <?php } ?>
      <?php } ?>
    </script>

@endsection

@section('content')
     <div role="main">
          <div class="row">
            <div class="page-title">
              <div>
                <h3> Input Status AI PJB</h3>
              </div>
              <br>
              @if(session('success'))
                  <div class="alert alert-success alert-dismissible" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      {{ session('success') }}
                  </div>
              @endif
              @if(!empty($notification_failed))
                  <div class="alert alert-danger alert-dismissible" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      {{ $notification_failed }}
                  </div>
              @endif
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      Filter
                      </div>
                        <div class="panel-default">
                        <br/>
                        <form method="get" class="form-horizontal form-label-left" action="">
                          <div class="form-group">
                            <label class="col-md-2 col-sm-3 col-xs-12">Tahun Anggaran</label>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                              <select class="form-control col-md-7 col-xs-12" name="tahun_anggaran" required>
                                  <option value="">- Pilih Tahun -</option>
                                    @foreach($tahun as $th)
                                      <option value="{{$th->tahun}}" @if($input_tahun == $th->tahun) {{'selected'}} @endif>{{$th->tahun}}</option>
                                    @endforeach
                              </select>
                            </div>

                            <div class="form-group">
                            <label class="col-md-2 col-sm-3 col-xs-12">Struktur Bisnis</label>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                              <select class="form-control col-md-7 col-xs-12" name="strategi_bisnis" required>
                                  <option value="">- Pilih Struktur Bisnis -</option>
                                    @foreach ($sb as $sbs => $value)
                                      <option value="{{ $value->id }}" <?php if($input_sb!=null) echo($input_sb == $value->id ? 'selected' : '')?> > {{ $value->name }} </option>
                                    @endforeach
                              </select>
                            </div>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-md-2 col-sm-3 col-xs-12" >Distrik</label>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                              <select class="form-control col-md-7 col-xs-12" name="distrik" required>
                                  <option value="3">- Pilih Distrik -</option>
                                    @if($input_sb!=null && $input_distrik!=null)
                                      @foreach($distrik as $d)
                                        <option value="{{$d->id}}" <?php echo($d->id == $input_distrik ? 'selected' : '')?>>{{$d->name}}</option>
                                      @endforeach
                                    @endif
                              </select>
                            </div>


                            <div class="form-group">

                            <label class="col-md-2 col-sm-3 col-xs-12">Lokasi</label>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                              <input class="form-control col-md-7 col-xs-12" name="lokasi" value ="<?php if($input_lokasi) echo $input_lokasi?>" required readonly="true">

                              </input>
                            </div>

                            </div>

                          </div>
                          <div class="form-group">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                            </div>

                            <div class="form-group">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                            </div>
                          </div>
                          </div>
                          <div class="form-group">
                            <label class="col-md-2 col-sm-3 col-xs-12" >Periode sd</label>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                              <select class="form-control col-md-7 col-xs-12" name="bulan">
                                <option value = "12">- Pilih Bulan -</option>
                                  @foreach ($months as $key => $value)
                                    <option value="{{ $key }}" <?php if($input_bulan != null) echo($input_bulan == $key ? 'selected' : '')?>> {{ $value }} </option>
                                  @endforeach
                              </select>
                            </div>
                          </div>

                          <div class="ln_solid"></div>
                          <div class="form-group">
                            <div >
                              <button type="submit" class="btn btn-primary pull-right">
                                  <span class="glyphicon glyphicon-search"> </span> cari
                              </button>
                            </div>
                          </div>

                          </form>
                        </div>
                  </div>
                </div>

                <div class="x_panel">
                <div class="x_content">
                  <form method="POST" action="" class="form-horizontal form-label-left">
                  {{ csrf_field() }}
                  <div class="table-responsive">
                  <!-- <table id="datatable" class="table table-striped table-bordered table-hover"> -->
                  <table class="table table-striped table-bordered table-hover">
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
                        @if(!empty($ai_pjb_result))
                            <input type="hidden" name="distrik_id" value={{$input_distrik}}>
                            <input type="hidden" name="strategi_bisnis_id" value={{$input_sb}}>
                            <input type="hidden" name="tahun_anggaran" value={{$input_tahun}}>
                            <input type="hidden" name="bulan" value={{$input_bulan}}>

                        <?php $i=1;?>
                        @foreach($ai_pjb_result as $row)
                            <tr>
                                <td>
                                    {{$i++}}
                                </td>
                                @foreach($row as $key => $item)
                                    @if($key == 'pgdl_file_import_revisi_id')
                                        <input type="hidden" name="file_import_revisi_id[{{$i}}]" value={{$row['pgdl_file_import_revisi_id']}}>
                                    @else
                                        @if($item['title'] == 'PRK')
                                            <td>
                                                {{$item['value']}}
                                                <input type="hidden" name="prk[{{$i}}]" value="{{$item['value']}}" >
                                            </td>
                                        @elseif($item['title'] == 'Nomor PO')
                                            <td>
                                                {{$item['value']}}
                                                <input type="hidden" name="po_no[{{$i}}]" value="{{$item['value']}}" >
                                            </td>
                                        @elseif($item['title'] == 'Realisasi Bulan Kontrak')
                                        <td>
                                            <input type="text" id='myDatepicker1{{$i}}' name="date_kontrak[{{$i}}]" value="{{ $item['value']}}">
                                        </td>
                                        @elseif($item['title'] == 'Realisasi Bulan Disburse')
                                        <td>
                                            <input type="text" id='myDatepicker2{{$i}}' name="date_disburse[{{$i}}]" value="{{ $item['value'] }}">
                                        </td>
                                        @elseif($item['title'] == 'Status Kontrak')
                                            <td>
                                                <select class="form-control" name="status_kontrak[{{$i}}]" style="min-width: 100px">
                                                    <option value=""></option>
                                                    @foreach($master_status_ai_pjb as $status)
                                                        @if(!empty($item['value']) && $item['value'] == $status->id)
                                                            <option value="{{$status->id}}" selected="">{{$status->name}}</option>
                                                        @else
                                                            <option value="{{$status->id}}">{{$status->name}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </td>
                                        @elseif($item['title'] == 'Status Disburse')
                                            <td>
                                                <select class="form-control" name="status_disburse[{{$i}}]" style="min-width: 100px">
                                                    <option value=""></option>
                                                    @foreach($master_status_ai_pjb as $status)
                                                        @if(!empty($item['value']) && $item['value'] == $status->id)
                                                            <option value="{{$status->id}}" selected="">{{$status->name}}</option>
                                                        @else
                                                            <option value="{{$status->id}}">{{$status->name}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </td>
                                        @elseif( is_numeric( $item ))
                                            <td style="text-align: right;">
                                                <!-- jika desimal -->
                                                @if( floor( $item['value'] ) != $item['value'] )
                                                    {{ number_format($item['value'], 2,',','.') }}
                                                <!-- jika bukan desimal -->
                                                @else
                                                    {{ number_format($item['value'], 0,',','.') }}
                                                @endif
                                            </td>
                                        @else
                                            <td>{{ $item['value'] }}</td>
                                        @endif
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach
                        @endif
                    </tbody>
                  </table>
                  @if(count($ai_pjb_result) > 0)
                  <div class="ln_solid"></div>
                  <div class="form-group">
                      <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-8">
                          <button type="submit" class="btn btn-success">Simpan</button>
                      </div>
                  </div>
                  @endif
                  </div>
                  </form>

                 </div>
            </div>
          </div>
          </div>
        </div>
<script type="text/javascript">
  $(document).ready(function() {
      $('select[name="strategi_bisnis"]').on('change', function() {
          var strategi_bisnisID = $(this).val();
          $('select[name="distrik"]').empty();
          $('select[name="lokasi"]').empty();

          if(strategi_bisnisID) {
              $.ajax({
                  url: "{{ url('/output/pencarian-pengendalian/ajax/') }}/"+strategi_bisnisID,
                  type: "GET",
                  dataType: "json",
                  success:function(data) {
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


<script type="text/javascript">
  function check() {
        var lokasiID = $(this).val();
        $('select[name="lokasi"]').empty();
        //console.log(lokasiID);
        if(lokasiID) {
            $.ajax({
                url: "{{ url('output/pencarian-pengendalian/ajax2/') }}/"+lokasiID,
                type: "GET",
                dataType: "json",
                success:function(data) {

                  var temp_name = [];
                  var temp_id = [];
                  //$('select[name="lokasi"]').empty();
                  //$('select[name="lokasi"]').append('<option selected="" value="" disabled="">Pilih</option>');
                  // $('select[name="lokasi"]').append('<option value="">==Silahkan Pilih Lokasi==</option>');
                  //$('select[name="lokasi"]').append('<option >'+ data[0].name +'</option>');
                  $.each(data, function(ad , value) {
                      //$('select[name="lokasi"]').append('<option value="'+ value["id"] +'">'+ value["name"] +'</option>');
                      temp_name.push(value["name"]);
                      temp_id.push(value["id"]);
                  });
                  //<input class="form-control col-md-7 col-xs-12" type="text" value="Ketetapan" readonly="readonly">
                  //<input name="fase" class="form-control col-md-7 col-xs-12" type="hidden" value="3" readonly="readonly">
                  //$('select[name="lokasi"]').append(
                  //$('input[name="lokasi"]').append("value ="+temp_name).attr("readonly", true);
                  $('input[name="lokasi"]').val(temp_name);
                  //console.log(temp_name);
                }
            });
        }else{
            $('select[name="lokasi"]').empty();
            if(lokasiID) {
                $.ajax({
                    url: '/output/pencarian-pengendalian/ajax2/'+lokasiID,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {
                      /*
                      $('select[name="lokasi"]').empty();
                      // $('select[name="lokasi"]').append('<option value="">==Silahkan Pilih Lokasi==</option>');
                      $.each(data, function(ad , value) {
                          $('select[name="lokasi"]').append('<option value="'+ value["id"] +'">'+ value["name"] +'</option>');
                      });
                      */
                      var temp_name = [];
                      var temp_id = [];
                      //$('select[name="lokasi"]').empty();
                      $.each(data, function(ad , value) {
                          temp_name.push(value["name"]);
                          temp_id.push(value["id"]);
                      });
                      //$('select[name="lokasi"]').append(
                      //$('input[name="lokasi"]').append(temp_name).attr("readonly", true);
                      $('input[name="lokasi"]').val(temp_name);
                      //console.log(temp_name);
                    }
                });
            }else{
                $('input[name="lokasi"]').empty();
            }
        }

        }

    $(document).ready(function() {
        $('select[name="distrik"]').on('change', check);
        $('select[name="distrik"]').on('click', check);
    });
</script>
@endsection
