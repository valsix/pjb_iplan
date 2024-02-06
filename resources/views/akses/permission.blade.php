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
<div class="row">
    <div class="col-md-1">
        <a href="akses/create" class="btn btn-primary">Tambah Permission</a>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-12">
        <div>
            
            <div class="body box-body">
                <div class="row">
                    <div class="col-md-12">
          <div class="row">
              <form action="">
                <div class="col-md-3">
                    <select name="is_menu_filter" class="form-control">
                      <option value="" <?php if($is_menu_filter==null) echo 'selected' ;?>>Semua Akses</option>
                      <option value="1" <?php if($is_menu_filter=='1') echo 'selected' ;?> >Menu</option>
                      <option value="0" <?php if($is_menu_filter=='0') echo 'selected' ;?>>Hak Akses</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary" type="submit">Filter</button>
                </div>
              </form>         
          </div>
          <br>
                        <table id="fasyankes_list" class="table table-striped table-responsive table-bordered dataTable" >
                            <thead>
                                <tr>
                                    <th style="width:2%">No</th>
                                    <th>Nama Permission</th>
                                    <th>Route</th>
                                    <th>List Menu</th>
                                    <th>Info</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="filterable">
                                <?php $i = 1;?>
                                @foreach($permission as $item)
                                <tr class="filterable">
                                    <td class="text-center">{{$i++}}</td>
                                    <td class="text-left" >{{$item->name}}</td>
                                    <td class="text-left" >{{$item->display_name}}</td>
                                    <!-- <td class="text-left" >{{$item->route_permission}}</td> -->
                                    <td class="text-center" >
                                    @if($item->is_menu == 1)
                                        <span class="label bg-primary">List Menu</span>
                                    @else
                                        <span class="label bg-red">Bukan List Menu</span>
                                    @endif
                                    </td>
                                    <td class="text-center" >
                                    @if($item->enabled == 1)
                                        <span class="label bg-green">Aktif</span>
                                    @else
                                        <span class="label bg-red">Tidak Aktif</span>
                                    @endif
                                    </td>
                                    <td class="text-center">
                                        <a class="btn btn-xs btn-success" data-toggle="tooltip" title="View Detail" href="#">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit" href="#">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <a class="btn btn-xs btn-danger" onClick="return confirm('Apakah Anda yakin untuk menghapus permission \'{{$item->display_name}}\'? \n\nPeringatan!!! Mengehapus item menu memungkinkan sub menu di dalamnya tidak dapat ditampilkan')" data-toggle="tooltip" title="Delete" href="akses/daftar">
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
    </div>
</div>

@endsection