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
            "searching": false
        } );
    </script>
@endsection

@section('content')
    <h1> Daftar Tahun Anggaran Ketetapan {{ $jenis->name }}</h1>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
          {{ session('success') }}
        </div>
    @endif
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
                                <div class="col-md-5 col-md-1">
                                    <label class="col-md-7 col-md-3"> Tahun </label>
                                </div>
                                <div class="col-md-2">
                                    <input class="form-control" type="number" name="tahun">
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Cari </button>
                                </div>
                            </form>
                            <br>
                        </div>
                    </div>
                </div>
            </div>


            <div class="x_content">
                <table id="datatable" class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Tahun Anggaran</th>
                        <!-- <th>Fase</th> -->
                        <th>Jumlah Ketetapan</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($templates as $row)
                        <tr>
                            <td>{{ ++$no }}</td>
                            <td>{{ $row->tahun }}</td>
                            <!-- <td>{{ 'Usulan Unit' }}</td> -->
                            @if($role->is_kantor_pusat)
                            <td>{{ ($row->pgdl_file_imports_revisi->count())?$row->pgdl_file_imports_revisi->count():'-' }}</td>
                            @else
                            <td>{{ ($row->pgdl_file_imports_revisi->where('distrik_id', $user->distrik_id)->count())?$row->file_imports->where('distrik_id', $user->distrik_id)->count():'-' }}</td>
                            @endif
                            <td>
                                {{-- AKSI --}}
                                <a data-toggle="tooltip" title="Daftar Ketetapan" href="{{ route('templatepengendalian.show', ['jenis_id' => $jenis->id, 'id' => $row->id]) }}" class="btn btn-primary" ><i class="fa fa-file-text"></i></a>
                                {{-- @if($role->is_kantor_pusat)
                                <a data-toggle="tooltip" title="Update" href="{{ route('templatepengendalian.edit', ['jenis_id' => $jenis->id, 'id' => $row->id]) }}" class="btn btn-primary" ><span class="fa fa-edit"></span></a>
                                @endif --}}
                                <a href="{{ route('templatepengendalian.edit', ['jenis_id' => $jenis->id, 'id' => $row->id]) }}" class="btn btn-success" data-toggle="tooltip" title="Update Template Pengendalian">
                                  <span class="glyphicon glyphicon-edit"></span>
                                </a>
                                <!-- <button data-toggle="tooltip" title="Hapus" type="button" class="btn btn-danger" data-toggle="modal" data-target=".bs-example-modal-sm"><span class="fa fa-trash"></span></button> -->
                                <a href="{{ asset($row->file) }}" class="btn btn-info" data-toggle="tooltip" title="Unduh Template Pengendalian">
                                  <span class="glyphicon glyphicon-download-alt"></span>
                                </a>
                                <a href="{{ route('sheetpengendalian.index', $version->id) }}" class="btn btn-danger" data-toggle="tooltip" title="Update Setting Pengendalian">
                                  <span class="glyphicon glyphicon-cog"></span>
                                </a>
                                @if($row->setting_filepath)
                                <a href="{{ asset($row->setting_filepath) }}" class="btn btn-warning" data-toggle="tooltip" title="Download Setting Pengendalian">
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
      $("#dropdown").toggle();
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
