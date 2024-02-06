@extends('layouts.app')

@section('css_page')

    <!-- searching -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

    <!-- Datatables -->
    <!-- <link href="{{ asset('vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}" rel="stylesheet"> -->
@endsection

@section('js_page')
    <script type="text/javascript">
        $('#datatable').dataTable( {
            "searching": true,
            "aLengthMenu": [[10, 25, 50, 100, -1],
                         [10, 25, 50, 100, "All"]]
        } );
    </script>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
<!--         <div role="main"> -->
        <div>
            <div class="page-title">
            <!-- <div> -->
              <h3> TOR Publish</h3>


            </div> <!-- page-title -->
            <!-- </div> -->
              @if(session('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  {{ session('success') }}
                </div>
              @endif
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
                                    <select class="form-control" name="tahun_anggaran" required>
                                       <option selected="" disabled="" value="">-- Pilih Tahun Anggaran--</option>
                                       @for($i=date('Y')-5; $i <= date('Y')+20; $i++)
                                          <option value="{{$i}}" {{ $input_tahun == $i ? 'selected=""' : '' }}>{{$i}}</option>
                                       @endfor
                                    </select>
                                </div>
                                <div class="col-md-2"><label>Struktur Bisnis</label></div>
                                <div class="col-md-3">

                                    <select class="form-control" name="strategi_bisnis" required>
                                       <option selected="" disabled="" value="">-- Pilih Struktur Bisnis --</option>
                                        @foreach ($Sb as $sbs => $value)
                                         <option value="{{ $value->id }}" {{ $input_sb == $value->id ? 'selected=""' : '' }}> {{ $value->name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                               <br>
                               <br>
                                <div class="col-md-2"><label>Distrik</label></div>
                                <div class="col-md-4">
                                    <select class="form-control" name="distrik" required>
                                        <option selected="" disabled="" value="">-- Pilih Distrik --</option>
                                       @if($input_distrik != null)
                                          @foreach($distrik as $value)
                                              <option value="{{$value->id}}" {{ $input_distrik == $value->id ? 'selected=""' : '' }}>{{$value->name}}</option>
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

                                            $('select[name="distrik"]').append('<option value="" disabled>-- Pilih Distrik --</option>');
                                            $('select[name="lokasi"]').append('<option value="" disabled>-- Pilih Lokasi --</option>');

                                            if(strategi_bisnisID) {
                                                $.ajax({
                                                    // url: '/tor/daftar/ajax/'+strategi_bisnisID,
                                                    url: "{{ url('/tor/daftar/ajax/') }}/"+strategi_bisnisID,
                                                    type: "GET",
                                                    dataType: "json",
                                                    success:function(data) {
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
                                    <select class="form-control" name="lokasi" required>
                                       <option selected="" disabled="" value="">-- Pilih Lokasi --</option>
                                       @if($input_distrik != null)
                                          @foreach($lokasi as $value)
                                              <option value="{{$value->id}}" {{ $input_lokasi == $value->id ? 'selected=""' : '' }}>{{$value->name}}</option>
                                          @endforeach
                                       @endif
                                    </select>
                                </div>

                                <script type="text/javascript">
                                    $(document).ready(function() {
                                        $('select[name="distrik"]').on('change', function() {
                                            var lokasiID = $(this).val();
                                            $('select[name="lokasi"]').empty();
                                            $('select[name="lokasi"]').append('<option value="" disabled>-- Pilih Lokasi --</option>');

                                            if(lokasiID) {
                                                $.ajax({
                                                    // url: '/tor/daftar/ajax2/'+lokasiID,
                                                    url: "{{ url('tor/daftar/ajax2/') }}/"+lokasiID,
                                                    type: "GET",
                                                    dataType: "json",
                                                    success:function(data) {
                                                      // console.log(data);

                                                      $.each(data, function(ad , value) {
                                                        // console.log(ad);
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

        <style media="screen">
          thead th {
            text-align: center;
          }
        </style>

          <div class="x_content">
                  <!-- <div>
                    <a href="create" class="btn btn-primary"><span class="glyphicon glyphicon-plus"> </span> Tambah TOR</a>
                  </div> -->
                  <br>
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="datatable" style="width: 100%"> <!-- id="datatable" -->
                      <thead style="background:#2A3F54;color:white">

                        <tr>
                          <th rowspan="2" style="vertical-align: middle;">No</th>
                          <th rowspan="2" style="vertical-align: middle;">Distrik</th>
                          <th rowspan="2" style="vertical-align: middle;">Judul TOR</th>
                          <th rowspan="2" style="vertical-align: middle;">No Dokumen</th>
                          <th rowspan="2" style="vertical-align: middle;">TOR Status</th>
                          <th colspan="2" style="vertical-align: middle;">Review</th>
                          <th rowspan="2" style="vertical-align: middle;">Dokumen</th>
                          <th rowspan="2" style="vertical-align: middle;">Aksi</th>
                        </tr>
                        <tr>
                          <th style="vertical-align: middle;">Reviewer</th>
                          <th style="vertical-align: middle;">Status</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php $no=1; ?>
                      @if($tor!= null)
                      @foreach($tor as $item)
                        <tr>
                          <td>{{ $no++ }}</td>
                          <td>{{ $item->lokasi->distrik->name }}</td>
                          <td>{{ $item->dmr->judul_dokumen  ?? '-- DMR tidak ditemukan --' }}</td>
                          <td>{{ $item->no_dokumen }}</td>
                          <td>
                              @if($item->is_submitted == 0)
                                  N/A
                              @else
                                  Submitted
                              @endif
                          </td>
                          <td>
                              @if($item->is_submitted == 0)
                              -
                              @else
                                  {{$item->tor_review_phase->role->name}}
                              @endif
                          </td>
                          <td>
                              @if($item->is_submitted == 0)
                              -
                              @else
                                  {{$item->tor_review_status->name}}
                              @endif
                          </td>
                          <td class="text-center"> <!-- dokumen upload -->
                            <a href="{{ url('tor/download_attachment') .'/'. $item->id }}" class="btn btn-info btn-xs" data-toggle="tooltip" title="Download">
                              <span class="glyphicon glyphicon-download-alt"></span>
                            </a>
                          </td>
                          <td> <!-- aksi -->
                            <a href="{{ url('tor/detail')}}/{{ $item['id'] }}" class="btn btn-primary btn-xs" data-toggle="tooltip" title="detail">
                              <span class="glyphicon glyphicon-eye-open "></span>
                            </a>

                            <!-- @if($item->is_submitted == 0 || $item->tor_review_status->name == "Revised")
                            <a href="update/{{ $item['id'] }}" class="btn btn-success btn-xs" data-toggle="tooltip" title="edit">
                             <span class="glyphicon glyphicon-edit"></span>
                            </a>

                            @endif
                            @if($item->is_submitted == 0)
                            <a onclick="return konfirmasi()" href="delete/{{ $item['id'] }}" class="btn btn-danger btn-xs" data-toggle="tooltip" title="hapus">
                              <span class="glyphicon glyphicon-trash"></span>
                            </a>
                            @endif -->
                          </td>
                        </tr>
                        @endforeach

                        <script type="text/javascript" language="JavaScript">
                          function konfirmasi()
                          {
                            tanya = confirm("Anda Yakin Akan Menghapus Data ?");
                            if (tanya == true) return true;
                            else return false;
                          }
                        </script>
                      @endif
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>

<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});
</script>

@endsection
