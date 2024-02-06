@extends('layouts.app')

@section('css_page')

    <!-- swal -->
    <script src="{{ asset('js/sweetalert2.all.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetalert2.min.js') }}" type="text/javascript"></script>

    <!-- Datatables -->
    <link href="{{ asset('vendors/datatables.net-bs/css/dataTables-khusus-summary.bootstrap.min.css') }}" rel="stylesheet">
    <!-- Swal -->
    <link href="{{ asset('css/sweetalert2.min.css') }}" rel="stylesheet">


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
          width: 60%;
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

        function myFunction() {
          document.getElementById("bisa").readOnly = false;
      }
    </script>


@endsection

@section('content')
     <div role="main">
          <div class="row">
          	<div class="page-title">
              <div>
                <h3> FORM INPUT REALISASI PRODUKSI DAN PENJUALAN </h3>
              </div>
              <br>
                <div class="col-md-12 col-sm-12 col-xs-12">

                        <div class="panel panel-default">
                        <div class="panel-heading">
                        	Filter
                        </div>
                        <br/>
                        <div class="panel-default">
                        <form method="post" class="form-horizontal form-label-left" action="">

                          <div class="form-group"  >
                              <label class="col-md-2 col-sm-3 col-xs-12">Tahun Anggaran</label>
                              <div class="col-md-4 col-sm-4 col-xs-12">
                                <select class="form-control col-md-7 col-xs-12" name="tahun_anggaran">
                                	<option>----</option>
                                  @foreach($tahun as $thn)
                                	<option value="{{$thn->tahun}}">{{$thn->tahun}}</option>
                                  @endforeach
                                </select>
                              </div>

                                <label class="col-md-2 col-sm-3 col-xs-12" >Struktur Bisnis</label>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <select class="form-control col-md-7 col-xs-12" name="strategi_bisnis">
                                      <option>---</option>
                                        <option value="2">UP</option>
                                    </select>
                                </div>
                          </div>

                          <div class="form-group" style="margin-top:5px;">

                              <label class="col-md-2 col-sm-3 col-xs-12">Distrik</label>
                              <div class="col-md-4 col-sm-4 col-xs-12">
                                <select class="form-control col-md-7 col-xs-12" name="distrik">
                                    <option>----</option>
                                </select>
                              </div>

                              <label class="col-md-2 col-sm-3 col-xs-12">Lokasi</label>
                              <div class="col-md-4 col-sm-4 col-xs-12">
                                <select class="form-control col-md-7 col-xs-12" name="lokasi">
                                    <option>---</option>
                                </select>
                              </div>
                          </div>
                          <div class="form-group" style="margin-top:5px;">
                            <label class="col-md-2 col-sm-3 col-xs-12" >Bulan Anggaran</label>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                              <select class="form-control col-md-7 col-xs-12" name="bulan_anggaran">
                                	<option>----</option>
                                  @for($i=1;$i<=12;$i++)
                                  <option value="{{$i}}">{{$bulan[$i]}}</option>
                                  @endfor
                              </select>
                            </div>
                          </div>

                              <div class="ln_solid"></div>
                                  <button class="btn btn-primary pull-right" id="filter">
                                      <span class="glyphicon glyphicon-search"> </span> Filter
                                  </button>
                              </div>
                      </div>
                        </div>
                    </div>
                </div>


              <div class="x_panel"  >
                <center><h3>FORM INPUT PRODUKSI DAN PENJUALAN</h3>
                	<h4>(Dalam Ribuan Rupiah)</h4>
                	<h3 style="text-decoration: bold">Dalam Pembangkit</h3>
                </center>

                <div class="x_content">
                	<center>
                  <table id="" class="table table-striped table-bordered table-hover">
                    <thead  style="background:#282865;color: white">
                        <tr>
                          <th  style="vertical-align: middle;text-align: center;">Keterangan</th>
                          <th   style="vertical-align: middle;text-align: center;">REALISASI s.d Bulan</th>
                          <th   style="vertical-align: middle;text-align: center;">RENCANA s.d Bulan</th>
                        </tr>
                        <tr>
                          <th>1</th>
                          <th>2</th>
                          <th>3</th>
                        </tr>
                        <tr>
                        	<th style="text-align: left;">1. PRODUKSI DAN PENJUALAN</th>
                          <th></th>
                        	<th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                        	<td>1.1 Produksi(MWh)</td>
                        	<td><input type="text" name="produksi" class="form-control" readonly="" id=""></td>
                          <td><input type="text" name="rencana_produksi" class="form-control" readonly="" id=""></td>
                        </tr>
                        <tr>
                        	<td>1.2 Penjualan(MWh)</td>
                          <td><input type="text" name="penjualan" class="form-control" readonly="" id=""></td>
                        	<td><input type="text" name="rencana_penjualan" class="form-control" readonly="" id=""></td>
                        </tr>
                        <tr>
                        	<td>1.3 Harga Jual(Rp/KWh)</td>
                          <td><input id="" type="text" name="harga" class="form-control" readonly=""></td>
                        	<td><input id="" type="text" name="rencana_harga" class="form-control" readonly=""></td>

                        </tr>
                        <tr>
                        	<td >1.4 BPP(Rp/KWh)</td>
                          <td><input id="" type="text" name="bpp" class="form-control" readonly=""></td>
                        	<td><input id="" type="text" name="rencana_bpp" class="form-control" readonly=""></td>
                        </tr>

                    </tbody>
                   </table>
               		</center>
               		</div>
               		<center>
               		<button type="button" class="btn btn-primary" id="edit">
                      <span class="glyphicon glyphicon-pencil"> </span> Edit
                    </button>
               		<br>
               		<button id="submit" class="btn btn-primary"  style="display:none;">
                      <span class=""> </span> Submit
                  </button>
               		</center>

               	</div>
             	</div>
             </div>
			</div>
			</div>
      <script type="text/javascript">

        $(document).ready(function() {


            $('select[name="strategi_bisnis"]').on('change', function() {
                var strategi_bisnisID = $(this).val();
                if(strategi_bisnisID ==1){
                  $('.x_panel').hide();
                  }
              else{
                  $('.x_panel').show();
                  }
                $('select[name="distrik"]').empty();
                if(strategi_bisnisID) {
                    $.ajax({
                        url: "{{ url('/output/summary/ajax/') }}/"+strategi_bisnisID,
                        type: "GET",
                        dataType: "json",
                        success:function(data) {
                          $('select[name="distrik"]').empty();
                          $('select[name="distrik"]').append('<option value="">- Pilih Distrik -</option>')
                          $.each(data, function(sb, value) {
                              $('select[name="distrik"]').append('<option value="'+ value["id"] +'">'+ value["name"] +'</option>');
                          });

                        }
                    });
                }else{
                    $('select[name="distrik"]').empty();
                }
            });

            $('select[name="distrik"]').on('change', function() {
                var distrik_id = $(this).val();
                console.log("id distrik= "+distrik_id);
                $('select[name="lokasi"]').empty();
                if(distrik_id) {
                    $.ajax({
                        url: "{{url('/lokasi/daftar_lokasi/ajax2/')}}/"+distrik_id,
                        type: "GET",
                        dataType: "json",
                        success:function(data) {
                          console.log(data);
                          $('select[name="lokasi"]').empty();
                          $('select[name="lokasi"]').append('<option value="">- Pilih Lokasi -</option>')
                          $.each(data, function(sb, value) {
                              $('select[name="lokasi"]').append('<option value="'+ value["id"] +'">'+ value["name"] +'</option>');
                          });

                        },
                        error: function() {
                          console.log('gagal load data');
                        },
                    });
                }else{
                    $('select[name="lokasi"]').empty();
                }
            });
            var lokasi = $('select[name="lokasi"]').val();
            console.log(lokasi);
            $("#filter").click( function(e)
            { e.preventDefault();
              var bulan =  $('select[name="bulan_anggaran"]').val();
              var tahun =  $('select[name="tahun_anggaran"]').val();
              var lokasi =  $('select[name="lokasi"]').val();
              //  alert('button clicked');
              // console.log(tahun,bulan,lokasi);
              $.ajax({
                  url: "{{url('/data/input/realisasi/ajax?')}}" +'tahun='+tahun+'&bulan='+bulan+'&lokasi='+lokasi,
                  type: "GET",
                  dataType: "json",
                  success:function(data) {
                    console.log(data);
                    $('input[name="produksi"]').val(data.produksi);
                    $('input[name="penjualan"]').val(data.penjualan);
                    $('input[name="harga"]').val(data.harga);
                    $('input[name="bpp"]').val(data.bpp);

                    $('input[name="produksi"]').attr('id',data.produksi_id);
                    $('input[name="penjualan"]').attr('id',data.penjualan_id);
                    $('input[name="harga"]').attr('id',data.harga_id);
                    $('input[name="bpp"]').attr('id',data.bpp_id);

                    $('input[name="rencana_produksi"]').val(data.rencana_produksi);
                    $('input[name="rencana_penjualan"]').val(data.rencana_penjualan);
                    $('input[name="rencana_harga"]').val(data.rencana_harga);
                    $('input[name="rencana_bpp"]').val(data.rencana_bpp);

                    $('input[name="rencana_produksi"]').attr('id',data.rencana_produksi_id);
                    $('input[name="rencana_penjualan"]').attr('id',data.rencana_penjualan_id);
                    $('input[name="rencana_harga"]').attr('id',data.rencana_harga_id);
                    $('input[name="rencana_bpp"]').attr('id',data.rencana_bpp_id);
                  },
                  error: function() {
                    console.log('gagal load data');
                  },
            });
           }
          );

          $('#edit').click(function(e){
            e.preventDefault();
            $('#submit').show();
            $('input[name="produksi"]').removeAttr('readonly');
            $('input[name="penjualan"]').removeAttr('readonly');
            $('input[name="harga"]').removeAttr('readonly');
            $('input[name="bpp"]').removeAttr('readonly');

            $('input[name="rencana_produksi"]').removeAttr('readonly');
            $('input[name="rencana_penjualan"]').removeAttr('readonly');
            $('input[name="rencana_harga"]').removeAttr('readonly');
            $('input[name="rencana_bpp"]').removeAttr('readonly');
          });

          $('#submit').click(function(e){
            e.preventDefault();
            var produksi = $('input[name="produksi"]').val();
            var penjualan =  $('input[name="penjualan"]').val();
            var harga =  $('input[name="harga"]').val();
            var bpp =  $('input[name="bpp"]').val();

            var produksi_id = $('input[name="produksi"]').attr('id');
            var penjualan_id = $('input[name="penjualan"]').attr('id');
            var harga_id = $('input[name="harga"]').attr('id');
            var bpp_id = $('input[name="bpp"]').attr('id');
            // console.log(bpp_id, 'tes');
            var rencana_produksi = $('input[name="rencana_produksi"]').val();
            var rencana_penjualan =  $('input[name="rencana_penjualan"]').val();
            var rencana_harga =  $('input[name="rencana_harga"]').val();
            var rencana_bpp =  $('input[name="rencana_bpp"]').val();

            var rencana_produksi_id = $('input[name="rencana_produksi"]').attr('id');
            var rencana_penjualan_id = $('input[name="rencana_penjualan"]').attr('id');
            var rencana_harga_id = $('input[name="rencana_harga"]').attr('id');
            var rencana_bpp_id = $('input[name="rencana_bpp"]').attr('id');

            var bulan =  $('select[name="bulan_anggaran"]').val();
            var tahun =  $('select[name="tahun_anggaran"]').val();
            var lokasi =  $('select[name="lokasi"]').val();
            var data={
                        _token: '{!! csrf_token() !!}',
                        produksi  : produksi,
                        produksi_id : produksi_id,
                        penjualan : penjualan,
                        penjualan_id : penjualan_id,
                        harga   : harga,
                        harga_id   : harga_id,
                        bpp   : bpp,
                        bpp_id   : bpp_id,

                        rencana_produksi  : rencana_produksi,
                        rencana_produksi_id : rencana_produksi_id,
                        rencana_penjualan : rencana_penjualan,
                        rencana_penjualan_id : rencana_penjualan_id,
                        rencana_harga   : rencana_harga,
                        rencana_harga_id   : rencana_harga_id,
                        rencana_bpp   : rencana_bpp,
                        rencana_bpp_id   : rencana_bpp_id,

                        bulan   : bulan,
                        tahun   : tahun,
                        lokasi  : lokasi
            };

            // console.log(produksi_id,penjualan_id,bulan,tahun,lokasi);

            $.ajax({
                type: 'POST',
                url: "{{ url('/realisasi/store/ajax') }}",
                data: data,
                success: function(data)
                {
                    console.log(data);
                    $('input[name="produksi"]').attr('readonly', true);
                    $('input[name="penjualan"]').attr('readonly', true);
                    $('input[name="harga"]').attr('readonly', true);
                    $('input[name="bpp"]').attr('readonly', true);

                    $('input[name="rencana_produksi"]').attr('readonly', true);
                    $('input[name="rencana_penjualan"]').attr('readonly', true);
                    $('input[name="rencana_harga"]').attr('readonly', true);
                    $('input[name="rencana_bpp"]').attr('readonly', true);

                    const toast = swal.mixin({
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 3500
                      });

                      toast({
                        type: 'success',
                        title: 'Input Realisasi Produksi dan Penjualan Berhasil Disimpan'
                      })

                },
                error: function(error)
                {
                  console.log(error.status);
                }
            });

          });

        });
      </script>

@endsection
