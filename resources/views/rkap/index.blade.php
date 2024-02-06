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
    <h1> Daftar RKAP Unit per Tahun </h1>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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
                                    <input class="form-control" type="text" name="tahun">
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary"><span class="fa fa-search"></span> Cari </button>
                                </div>
                            </form>
                            <br>
                        </div>
                    </div>
                </div>
            </div>

            <a href="{{ route('rkap.create') }}" class="btn btn-primary">Tambah Tahun RKAP Unit</a>
            <div class="x_content">
                <table id="datatable" class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Tahun</th>
                        <th>Status</th>
                        <th>Jumlah Draft/Versi</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($templates as $row)
                        <tr>
                            <td>{{ ++$no }}</td>
                            <td>{{ $row->tahun }}</td>
                            <td>{{ $row->fase->name }}</td>
                            <td>{{ ($row->file_imports->count())?$row->file_imports->count():'-' }}</td>
                            <td>
                                <a data-toggle="tooltip" title="Detail" href="{{ route('rkap.show', $row->id) }}" class="btn btn-primary" ><span class="fa fa-file-text"></span></a>
                                <a data-toggle="tooltip" title="Edit" href="{{ route('rkap.edit', $row->id) }}" class="btn btn-primary" ><span class="fa fa-edit"></span></a>
                                <button data-toggle="tooltip" title="Hapus" type="button" class="btn btn-danger" data-toggle="modal" data-target=".bs-example-modal-sm"><span class="fa fa-trash"></span></button>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
