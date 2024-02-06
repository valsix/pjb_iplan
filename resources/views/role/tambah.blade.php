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
<form action="{{--(is_null($roles))?route('admin.role.add.action'):route('admin.role.edit.action', ['id'=>$roles->id])--}}" method="POST">
    {{csrf_field()}}
    <div class="row">
        <div class="col-md-6">
            <div>
                <div class="box-header with-border"><h3 class="box-title">Nama Grup</h3></div>
                <div class="body box-body">
                    <div class="form-group">
                        <label>Nama Grup*</label>
                        <input name="nama" type="text" class="form-control" placeholder="Nama Group" value="{{(is_null($roles))?'':$roles->display_name}}" required >
                        {!!$errors->first('display_name', '<label class="control-label has-error">:message</label>')!!}
                    </div>
                    <div class="form-group">
                        <label>alias*</label>
                        <input name="alias" type="text" class="form-control" placeholder="Alias" value="{{(is_null($roles))?'':$roles->name}}" required >
                        {!!$errors->first('name', '<label class="control-label has-error">:message</label>')!!}
                    </div>
                    <div class="form-group">
                        <label>Deskripsi Grup</label>
                        <input name="deskripsi" type="text" class="form-control" placeholder="Deskripsi Grup" value="{{(is_null($roles))?'':$roles->description}}">
                        {!!$errors->first('description', '<label class="control-label has-error">:message</label>')!!}
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="col-md-6">
        <div>
        @if(!is_null($users))
        <div class="box-header with-border">
          <h3 class="box-title">Daftar User</h3>
        </div>
        <div class="body box-body">
          <div class="row">
            <div class="col-md-12">
              @if(count($users)>0)
              <table id="fasyankes_list" class="table table-striped table-responsive table-bordered dataTable" >
                <thead>
                  <tr>
                    <th style="width:2%">No</th>
                    <th>Nama User</th>
                    <th>Pangkat</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody class="filterable">
                  <?php $i = 1;?>
                  @foreach($users as $item)
                  <tr class="filterable">
                    <td class="text-center">{{$i++}}</td>
                    <td class="text-left" >{{$item->nama}}</td>
                    <td class="text-center" >{{$item->pangkat_golongan}}</td>
                    <td class="text-center" >
                      @if($item->status==1)
                        <span class="label label-success">Aktif</span>
                      @else
                        <span class="label label-danger">Tidak Aktif</span>
                      @endif
                    </td>
                    <td class="text-center">
                       <a class="btn btn-xs btn-success" data-toggle="tooltip" target="_blank" title="View User" href="{{--route('admin.user.view.view', ['id' => $item->id])--}}">
                        <i class="fa fa-eye"></i>
                      </a>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              @else
                Grup ini belum memiliki user terkait
              @endif
            </div>
          </div>
        </div>
        @endif
                <div class="box-header with-border"><h3 class="box-title">Tambah Menu Akses</h3></div>
                <div class="body box-body">
                    <div class="form-group">
                        <label>Nama Menu Akses*</label>
                            <div class="row">
                                <div class="col-md-10">
                                     <select class="form-control permission_values" id="fasyankesName">
                                         <option value=""></option>
                                         @foreach($permission as $row)
                                            <option value="{{$row->id}}" data-id="{{$row->display_name}}" route-id="{{$row->route_permission}}">{{$row->display_name}}</option>
                                         @endforeach
                                     </select>
                                </div>
                                <div class="col-md-2"><a href="#" class="btn btn-success add_permission"><i class="fa fa-plus"></i></a></div>
                            </div>
                       
                    </div>
                    <div class="form-group" id="semua_menu_akses">
                        <label>Menu Akses</label>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Menu Akses</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="body_value_menu_akses">
                                @if($permission_role != NULL)
                                    @foreach($permission_role as $row)
                                    <tr>
                                        <td width="93%"><input class="form-control hidden" name="menu_akses[]" desabled value="{{$row->id}}" /> <span><b>{{$row->display_name}}</b></span><br><span class="help-block">Route Permission : {{$row->route_permission}}</span></td>
                                        <td width="7%"><a href="#" onClick="return confirm('Apakah Anda yakin untuk menghapus data Menu Akses {{--$row->display_name--}}')" id="close_add" class="btn btn-danger"><i class="fa fa-times" /></i></a></td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-save"></i>&nbsp;Simpan</button>
        </div>
    </div>
</form>
@endsection

@section('custom-foot')
<script type="text/javascript">

    var rowCount = $('.body_value_menu_akses tr').length;
    if(rowCount === 0){
        $("#semua_menu_akses").hide('hide');
    }

    var birthdateInput = $( "#birth-date" ).datepicker({
            clearButton: true,
            dateFormat: "dd/mm/yy",
            autoclose: true
    });

    $(".add_permission").click(function (e) {
        $("#semua_menu_akses").show('hide');

        e.preventDefault();

        var permission = $(".permission_values option:selected").attr('data-id');
        var permission_id = $(".permission_values").val();
        var permission_route = $(".permission_values option:selected").attr('route-id');

        $(".body_value_menu_akses").append('<tr>'+
                                    '<td width="93%"><span><b>'+ permission +'</b></span><br><input class="hidden" type="text" value="'+ permission_id+'" name="menu_akses[]" /><span class="help-block"><b>Route Permission : </b>'+ permission_route +'</span></td>'+
                                    '<td width="7%"><a href="#" onClick="return confirm(\'Apakah Anda yakin untuk menghapus data Menu Akses '+ permission +'  ?\')" id="close_add" class="btn btn-danger"><i class="fa fa-times" /></i></a></td>'+
                                '</tr>');
        //$(".action_role_btn").show('hidden');

    });

    // DELETEING FUNCTION JAVASCRIPT
    $(document).on('click', '#close_add' ,function (e) {
        e.preventDefault();
        $(this).parent().parent().remove();

        var rowCount = $('.body_value_menu_akses tr').length;
        if(rowCount === 0){
            $("#semua_menu_akses").hide('hide');
        }
    });

</script>
@stop
