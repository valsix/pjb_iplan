@extends('layouts.app')
@section('content')
<h3>Detail Permission <b>{{$permission->display_name}}</b></h3>
<div class="row">
    <div class="col-md-4">
        <!-- <div class="box box-success"> -->
            <div class="box-header with-border">
              <h3 class="box-title">Permission</h3>
            </div>
            <!-- <div class="body box-body"> -->
                <div class="row">
                    <div class="col-md-12">
                        <!-- <h1>{{$permission->display_name}}</h1> -->
                        <h3 class="profile-username text-center">{{$permission->display_name}}</h3>
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                              <b>Alias</b> <a class="pull-right">{{$permission->name}}</a>
                            </li>
                            <li class="list-group-item">
                              <b>Deskripsi</b> <a class="pull-right">{{$permission->description}}</a>
                            </li>
                             <li class="list-group-item">
                              <b>Route</b> <a class="pull-right">{{$permission->route_permission}}</a>
                            </li>
                            <li class="list-group-item">
                              <b>Dibuat Pada</b> <a class="pull-right">{{ dateIdnFromTimestamp($permission->created_at) }}</a>
                            </li>
                            <li class="list-group-item">
                              <b>Terakhir Update Pada</b> <a class="pull-right">{{ dateIdnFromTimestamp($permission->updated_at) }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            <!-- </div> -->
        <!-- </div> -->
    </div>
    <div class="col-md-8">
        <!-- <div class="box box-success"> -->
			<div class="box-header with-border">
              <h3 class="box-title">Tambah Grup</h3>
            </div>
            <!-- <div class="body box-body"> -->
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label>Daftar nama grup</label>
								<form action="{{route('permission.role.add.action')}}" method="POST">
								{{csrf_field()}}
								<div class="row">
									<div class="col-md-8">
										 <select class="form-control permission_values" id="role_id_to_add" name="role_id_to_add">
											 <option value=""></option>
											 @foreach($roles_to_add as $row)
												<option value="{{$row->id}}" data-id="{{$row->display_name}}">{{$row->display_name}}</option>
											 @endforeach
										 </select>
									</div>
									<input type="hidden" name="permission_id_to_add" value="{{$permission->id}}" />
									<div class="col-md-4"><button type="submit" class="btn btn-success add_permission"><i class="fa fa-plus" style="margin-right: 10px"></i>Tambahkan</button></div>
								</div>
								</form>
						</div>
					</div>
				</div>
			<!-- </div> -->
			<div class="box-header with-border">
              <h3 class="box-title">Data Grup</h3>
            </div>
			<!-- <div class="body box-body"> -->
                <div class="row">
                    <div class="col-md-12">
                        <table id="fasyankes_list" class="table table-striped table-responsive table-bordered dataTable" >
                            <thead>
                                <tr>
                                    <th style="width:2%">No</th>
                                    <th>Nama Grup</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="filterable">
                                <?php $i = 1;?>
                                @foreach($roles as $item)
                                <tr class="filterable">
                                    <td class="text-center">{{$i++}}</td>
                                    <td class="text-left" >{{$item->display_name}}</td>
                                    <td class="text-center">
                                        <a class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit" href="{{route('admin.role.edit.view', ['id' => $item->id])}}">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <a class="btn btn-xs btn-danger" onClick="return confirm('Apakah Anda yakin untuk menghapus group \'{{$item->display_name}}\' dari akses \'{{$permission->display_name}}\' ?')" data-toggle="tooltip" title="Delete" href="{{route('permission.role.delete.action', ['permission_id' => $permission->id, 'role_id' => $item->id])}}">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                
            <!-- </div> -->
        <!-- </div> -->
    </div>
</div>

{!! HTML::style('datatables/css/dataTables.bootstrap.css') !!}
{!! HTML::script('datatables/js/jquery.dataTables.min.js') !!}
{!! HTML::script('datatables/js/dataTables.bootstrap.min.js') !!}
{!! HTML::style('datatables/css/dataTables.custom.css') !!}

<script type="text/javascript">
    var table = $("#fasyankes_list").DataTable(
    {
        "dom": '<"toolbar">l<"filt">frtip',
        "oLanguage": {
           "sLengthMenu": '<select name="fasyankes_list_length" aria-controls="fasyankes_list" class="form-control input-sm select2-hidden-accessible" tabindex="-1" aria-hidden="true">'+
                            '<option value="10">10 Data</option>'+
                            '<option value="25">25 Data</option>'+
                            '<option value="50">50 Data</option>'+
                            '<option value="100">100 Data</option>'+
                        '</select>'
        }
    });
</script>

@endsection
