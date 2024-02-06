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
<div role="main">
          <div class="row">
            <div class="page-title">
                <h3> Daftar Grup</h3>
                <div class="panel panel-default">
                        <div class="panel-heading">
                            Pencarian
                        </div>
                        <div class="panel-default">
                        <br>
                          <div class="row">
                            <div class="col-md-10">
                              <form>
                                <div class="col-md-2"> <label> Nama Grup </label> </div>
                                <div class="col-md-3"> <input class="form-control" type="text"></div>
                              
                                <div> <button type="submit" class="btn btn-primary"> Cari </button> </div>
                                
                              </form>
                              <br>
                            </div>
                          </div>
                        </div>
                    </div>
                <div> <a href="create" class="btn btn-primary"> Tambah Grup</a> </div>
                  
                <div class="x_content">
                  <table id="fasyankes_list" class="table table-striped table-responsive table-bordered dataTable" >
                            <thead>
                                <tr>
                                    <th style="width:2%">No</th>
                                    <th>Nama Grup</th>
                                    <th>Tanggal Pembuatan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="filterable">
                                <?php $i = 1;?>
                                @foreach($roles as $item)
                                <tr class="filterable">
                                    <td>{{$i++}}</td>
                                    <td>{{$item->display_name}}</td>
                                    <td>{{--dateIdnFromTimestamp($item->created_at)--}}</td>
                                    <td>
                                        <a class="btn btn-xs btn-success" data-toggle="tooltip" title="View Detail" href="{{--route('admin.role.view.view', ['id' => $item->id])--}}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit" href="{{--route('admin.role.edit.view', ['id' => $item->id])--}}">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <a class="btn btn-xs btn-danger" onClick="return confirm('Apakah Anda yakin untuk menghapus grup {{$item->display_name}}, user dengan grup ini juga akan kehilangan hak akses?')" data-toggle="tooltip" title="Delete" href="
                                        {{--route('role.delete.action', ['id' => $item->id])--}}">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                </div>
            </div>
          </div>
</div>
@endsection