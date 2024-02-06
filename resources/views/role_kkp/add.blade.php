@extends('layouts.app')
@section('content')
<h3>{{is_null($rolekkp) ? 'Tambah Grup Baru' : 'Edit Grup'}}</h3>
<form action="{{(is_null($rolekkp))?route('admin.rolekkp.add.action'):route('admin.rolekkp.edit.action', ['id'=>$rolekkp->id])}}" method="POST">
    {{csrf_field()}}
    <div class="row">
        <div class="col-md-6">
            <!-- <div class="box box-primary"> -->
                <div class="box-header with-border"><h3 class="box-title">Nama Grup</h3></div>
                <!-- <div class="body box-body"> -->
                    <div class="form-group">
                        <label>Nama Grup*</label>
                        <input name="nama" type="text" class="form-control" placeholder="Nama Group" value="{{(is_null($rolekkp))?'':$rolekkp->display_name}}" required >
                        {!!$errors->first('display_name', '<label class="control-label has-error">:message</label>')!!}
                    </div>
                    <div class="form-group">
                        <label>alias*</label>
                        <input name="alias" type="text" class="form-control" placeholder="Alias" value="{{(is_null($rolekkp))?'':$rolekkp->name}}" required >
                        {!!$errors->first('name', '<label class="control-label has-error">:message</label>')!!}
                    </div>
                    <div class="form-group">
                        <label>Deskripsi Grup</label>
                        <input name="deskripsi" type="text" class="form-control" placeholder="Deskripsi Grup" value="{{(is_null($rolekkp))?'':$rolekkp->description}}">
                        {!!$errors->first('description', '<label class="control-label has-error">:message</label>')!!}
                    </div>
                    
                <!-- </div> -->
            <!-- </div> -->
        </div>
        <div class="col-md-6">
            
                <div class="box-header with-border"><h3 class="box-title">Tambah User</h3></div>
                <!-- <div class="body box-body"> -->
                    <div class="form-group">
                        <label> Daftar Nama User</label>
                        <div class="row">
                            <div class="col-md-10">
                                 <select class="form-control user_values" id="fasyankesName">
                                     <option value=""></option>
                                     @foreach($users_to_add as $row)
                                        <option value="{{$row->id}}" data-id="{{$row->name}}">{{$row->name}}</option>
                                     @endforeach
                                 </select>
                            </div>
                            <div class="col-md-2"><a href="#" class="btn btn-success add_user"><i class="fa fa-plus"></i></a></div>
                        </div>
                    </div>
                    <div class="form-group" id="semua_data_user">
                        <label>Data User</label>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <!-- <th style="width:2%">No</th> -->
                                    <th>Nama User</th>
                                    <!-- <th>Pangkat</th> -->
                                    <!-- <th>Status</th> -->
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="body_value_data_user">
                                @if($rolekkp_user != NULL)
                                    <?php $i = 1;?>
                                    @foreach($rolekkp_user as $item)
                                    <tr class="filterable">
                                        <!-- <td class="text-center">{{$i++}}</td> -->
                                        <td class="text-left" ><input class="hidden" type="text" value="{{$item->id}}" name="data_user[]" />{{$item->name}}</td>
                                        <td class="text-center">
                                            <a class="btn btn-xs btn-success" data-toggle="tooltip" target="_blank" title="View User" href="{{route('admin.user.view.view', ['id' => $item->id])}}">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="#" onClick="return confirm('Apakah Anda yakin untuk menghapus data Menu Akses {{$item->name}}')" id="close_add" class="btn btn-xs btn-danger"><i class="fa fa-times" /></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    
                <!-- </div> -->
            <!-- </div> -->
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-save"></i>&nbsp;Simpan</button>
            <a href="{{ url('/role_kkp/manage') }}" class="btn btn-default pull-right" type="reset">Kembali</a>
        </div>
    </div>
</form>

<script src="{{ asset('js/jquery-1.11.2.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/datepicker/bootstrap-datepicker.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/jquery-ui.min.js') }}" type="text/javascript"></script>

<script type="text/javascript">

    var rowCount = $('.body_value_data_user tr').length;
    if(rowCount === 0){
        $("#semua_data_user").hide('hide');
    }

    var birthdateInput = $( "#birth-date" ).datepicker({
            clearButton: true,
            dateFormat: "dd/mm/yy",
            autoclose: true
    });

    $(".add_user").click(function (e) {
        $("#semua_data_user").show('hide');

        e.preventDefault();

        var user_ids = $(".user_values").val();
        var user_id = $(".user_values option:selected").val();
        var user_name = $(".user_values option:selected").attr('data-id');
        // var strategi_bisnis = $(".user_values option:selected").attr('data-sb-id');
        // var distrik_code = $(".user_values option:selected").attr('data-code');

        $(".body_value_data_user").append('<tr class="filterable">'+
                                    '<td class="text-left" ><input class="hidden" type="text" value="'+ user_id+'" name="data_user[]" />'+ user_name +'</td>'+
                                    '<td class="text-center">'+
                                    '<a href="#" onClick="return confirm(\'Apakah Anda yakin untuk menghapus data User '+ user_name +'  ?\')" id="close_add" class="btn btn-xs btn-danger"><i class="fa fa-times" /></i></a></td>'+
                                '</tr>');
        //$(".action_role_btn").show('hidden');

    });

    // DELETEING FUNCTION JAVASCRIPT
    $(document).on('click', '#close_add' ,function (e) {
        e.preventDefault();
        $(this).parent().parent().remove();

        var rowCount = $('.body_value_data_user tr').length;
        if(rowCount === 0){
            $("#semua_data_user").hide('hide');
        }
    });

</script>
@stop
