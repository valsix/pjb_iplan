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
                <h3> Penomoran PRK</h3>

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
                                <!-- <div class="col-md-2"> <label> UP,OM </label> </div>
                                <div class="col-md-3">
                                  <select class="form-control">
                                    <option> --Pilih UP,OM--</option>
                                    <option> UP </option>
                                    <option> OM </option>
                                    <option> UP & OM </option>
                                  </select>
                                </div> -->
                                  
                                <div class="col-md-2"> <label> Kode Distrik </label> </div>
                                <div class="col-md-3"> <input class="form-control" type="text"></div>
                              
                                <div> <button type="submit" class="btn btn-primary"> cari </button> </div>
                                
                              </form>
                              <br>
                            </div>
                          </div>
                        </div>
                    </div>
              </div>
                  <div> <a href="create" class="btn btn-primary">Tambah Penomoran PRK</a> </div>
              <br>
                <div class="x_content">
                  <table id="datatable" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                          <th>No.</th>
                          <th>Kode distrik</th>
                          <th>Lokasi</th>
                          <th>Tahun</th>
                          <th>Identity Parent</th>
                          <th>Identity Inti</th>
                          <th>Identity Kegiatan</th>
                          <th>Keterangan Identity Inti</th>
                          <th>Keterangan Identity Kegiatan</th>
                          <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $i=1; ?>
                    @foreach ( $prk as $item)
                        <tr>
                          <td>{{ $i++ }}</td>
                          <td>{{ $item->kode_distrik }}</td>
                          <td>{{ $item->lokasi->name }}</td>
                          <td>{{ $item->tahun }}</td>
                          <td>{{ $item->identity_parent }}</td>
                          <td>{{ $item->identity_inti }}</td>
                          <td>{{ $item->identity_kegiatan }}</td>
                          <td>{{ $item->ket_identity_inti }}</td>
                          <td>{{ $item->ket_identity_kegiatan }}</td>
                            <td> 
                          <a href=""><button type="button" class="btn btn-primary"><span class="glyphicon glyphicon-eye-open"></span></button></a>

                          <a href="update/{{ $item['id'] }}"><button type="button" class="btn btn-success"><span class="glyphicon glyphicon-edit"></span></button></a>

                          <a href="delete/{{ $item['id'] }}" class="btn btn-danger" data-toggle="tooltip" title="hapus" onclick="return konfirmasi()"> <span class="glyphicon glyphicon-trash"></span> </a>
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
