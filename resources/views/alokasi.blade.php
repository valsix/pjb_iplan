@extends('layouts.app')

@section('css_page')

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
    <div role="main">
          <div class="">
            <div class="page-title">
              <div>
                <h3> Daftar Alokasi</h3>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {{ session('success') }}
                    </div>
                @endif
                
                  <div class="panel panel-default">
                        <div class="panel-heading">
                            Pencarian
                        </div>
                        <div class="panel-default">
                        <br>
                          <div class="row">
                            <div class="col-md-10">
                              <form>
                                  
                                <div class="col-md-2"> <label> Nama Alokasi </label> </div>
                                <div class="col-md-3"> <input class="form-control" type="text"></div>
                              
                                <div> <button type="submit" class="btn btn-primary"> cari </button> </div>
                                
                              </form>
                              <br>
                            </div>
                          </div>
                        </div>
                    </div>
              </div>
                  <div> <a href="tambah_alokasi" class="btn btn-primary">Tambah Alokasi</a> </div>
              <br>
                <div class="x_content">
                  <table id="datatable" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                          <th>No.</th>
                          <th>Nama Alokasi</th>
                          <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $i=1; ?>
                    @foreach ( $alokasi as $item)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $item->name }}</td>
                            <td> 
                            <a href="detail_distrik.html" class="btn btn-primary" data-toggle="tooltip" title="detail"> <span class="glyphicon glyphicon-eye-open"></span></a>
                            
                            <a href="daftar_lokasi.html" class="btn btn-success" data-toggle="tooltip" title="daftar lokasi"> <span class="glyphicon glyphicon-file"></span></a>

                            <a href="update_alokasi/{{ $item['id'] }}" class="btn btn-primary" data-toggle="tooltip" title="edit"> <span class="glyphicon glyphicon-edit"></span></a>
                              
                            <a href="delete_alokasi/{{ $item['id'] }}" class="btn btn-danger" data-toggle="tooltip" title="hapus" onclick="return konfirmasi()"> <span class="glyphicon glyphicon-trash"></span> </a>
                            </td>
                        </tr>
                        <script type="text/javascript" language="JavaScript">
                          function konfirmasi()
                          {
                            tanya = confirm("Anda Yakin Akan Menghapus Data?");
                            if (tanya == true) 
                              return true;
                            else return false;
                          }
                        </script>
                        @endforeach
                        </tbody>
                      </table>
                 </div>
            </div>
          </div>
        </div>
@endsection
