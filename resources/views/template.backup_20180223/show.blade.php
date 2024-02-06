@extends('layouts.app')

@section('css_page')
    <!-- Datatables -->
    <link href="{{ asset('vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}" rel="stylesheet">
@endsection

@section('js_page')
    <!-- declare ulang jQuery & bootstrap, karena di branch pre-UAT tidak keluar tooltip -->
    <!-- jQuery -->
    <script src="{{ asset('vendors/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap -->
    <script src="{{ asset('vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-scroller/js/dataTables.scroller.min.js') }}"></script>
    <script src="{{ asset('vendors/jszip/dist/jszip.min.js') }}"></script>
    <script src="{{ asset('vendors/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('vendors/pdfmake/build/vfs_fonts.js') }}"></script>

    <script type="text/javascript">
        $('#datatable').dataTable( {
            "searching": true
        } );
    </script>
    <script type="text/javascript">
        $('#myDatepicker2').datetimepicker({
            format: 'YYYY-MM-DD'
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            //auto refresh setiap 10 detik
            setTimeout(function(){
               window.location.reload(1);
            }, 60000);
        });
    </script>
@endsection

@section('content')
    <h1>Daftar Draft {{ $version->template->jenis->name }} Tahun Anggaran {{ $version->template->tahun }}</h1>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ session('success') }}
        </div>
    @endif
    @if($role->is_kantor_pusat)
    @if(!$version->sheets->count())
    <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        Setting File Terlebih dahulu
    </div>
    @endif
    @endif

    <div class="alert alert-info alert-dismissible" role="alert">
      Halaman ini akan refresh otomatis setiap 1 menit, atau click <p class="btn btn-warning" onclick="myFunction()">Tombol Ini</p>untuk melakukan refresh manual</div>

    <script>
      function myFunction() {
        location.reload();
      }
    </script>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Pencarian
                </div>
                <div class="panel-default">
                    <br>
                    <div class="row">
                        <div class="col-lg-13">
                            <form>
                                <div class="col-md-2"><label class="col-md-7 col-md-3"> Draft/Versi </label></div>
                                <div class="col-md-2">
                                    <div class="input-group date">
                                        <input type="text" id='myDatepicker2' name="date" value="{{ old('date') }}" required="required" class="form-control col-md-7 col-xs-12">
                                        <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                    </div>
                                </div>
                                <div> <button type="submit" class="btn btn-primary"><span class="fa fa-search"></span> Cari </button> </div>
                            </form>
                            <br>
                        </div>
                    </div>
                </div>
            </div>

            <a class="btn btn-primary" href="{{ route('template.index', $version->template->jenis_id) }}"> Kembali </a>
            <a class="btn btn-primary" href="{{ route('fileimport.create', $version->id) }}"> Tambah Draft {{ $version->template->jenis->name }} </a>
            @if($role->is_kantor_pusat)
            <a class="btn btn-primary" href="{{ route('sheet.index', $version->id) }}"> Setting </a>
            @endif
            <br>
            <div class="x_content">
                <table id="datatable" class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Tahun Anggaran</th>
                        <th>Distrik</th>
                        <th>Lokasi</th>
                        <th>Draft</th>
                        <th>Status Upload</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($draft as $row)
                        <tr>
                            <td>{{ ++$no }}</td>
                            <td>{{ $row->tahun }}</td>
                            <td>{{ $row->distrik->name }}</td>
                            @if ($row->lokasi == null)
                              <td><?php echo "-"; ?></td>
                            @else
                              <td>{{ $row->lokasi->name }}</td>
                            @endif
                            <td>{{ $row->draft_versi->format('d F Y H:i:s') }} - {{ $row->name }}</td>
                            <td><h4 style="margin:0px"><span class="label {{ $row->status_upload->label }}">{{ $row->status_upload->deskripsi }}</span></h4>
                              {{-- @if ($version->template->jenis->name == 'RKAU')
                                <br>Data yang sudah masuk: {{$proses}} / {{$total}}
                              @endif  --}}
                            </td>
                            <td>
                                <a data-toggle="tooltip" title="Detail" class="btn btn-success" href="{{ route('fileimport.show', ['version_id' => $version->id, 'id' => $row->id]) }}"><span class="fa fa-eye"></span></a>
                                <a data-toggle="tooltip" title="Update" class="btn btn-primary" href="{{ route('fileimport.edit', ['version_id' => $version->id, 'id' => $row->id]) }}"><span class="fa fa-edit"></span></a>
                                <!-- <button data-toggle="tooltip" title="Hapus" type="button" class="btn btn-danger" data-toggle="modal" data-target=".bs-example-modal-sm"><span class="fa fa-trash"></span></button> -->
                                @if($row->status_upload_id==3)
                                <a href="{{ asset($row->file) }}" class="btn btn-warning" data-toggle="tooltip" title="Download Original Excel">
                                  <span class="glyphicon glyphicon-download-alt"></span>
                                </a>
                                <a href="{{ route('fileimport.export.use', ['version_id' => $version->id, 'id' => $row->id]) }}" class="btn btn-info" data-toggle="tooltip" title="Download Processed Excel">
                                  <span class="glyphicon glyphicon-download-alt"></span>
                                </a>
                                @endif

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection

@section ('menu_RKAP')

  <script>

    $(document).ready(function(){
      $("#btn1").click(function(){
        $("#dropdown").show();
        $("#dropdown2").hide();
      });

      $("#btn2").click(function(){
        $("#dropdown2").show();
        $("#dropdown").hide();
      });

      $(document).click( function(){
        $('#dropdown').hide();
        $('#dropdown2').hide();
      });
    });

  </script>


@endsection
