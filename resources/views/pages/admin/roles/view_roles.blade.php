@extends('layouts.app')
@section('content')
<h3>Detail Grup {{$roles->display_name}}</h3>
<div class="row">
    <div class="col-md-4">
        <!-- <div class="box box-success"> -->
            <!-- <div class="body box-body"> -->
                <div class="row">
                    <div class="col-md-12">
                        <h1>{{$roles->display_name}}</h1>
                        <div class="divider"></div>
                        <p><b>Alias</b> : {{$roles->name}} </p>
                        <p><b>Deskripsi</b> : {{$roles->description}} </p>
                        <p><b>Dibuat Pada</b> : {{ dateIdnFromTimestamp($roles->created_at) }} </p>
                        <p><b>Terakhir Update Pada</b> : {{ dateIdnFromTimestamp($roles->updated_at) }} </p>
                    </div>
                </div>
				<div class="row" style="margin-top: 40px">
                    <div class="col-md-12">
                        <div class="form-group" id="semua_menu_akses">
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>Nama Menu Akses</th>
									</tr>
								</thead>
								<tbody class="body_value_menu_akses">
									@if($permission_role != NULL)
										@foreach($permission_role as $row)
										<tr>
											<td><input class="form-control hidden" name="menu_akses[]" desabled value="{{$row->id}}" /> <span><b>{{$row->display_name}}</b></span><br><span class="help-block">Route Permission : {{$row->route_permission}}</span></td>
										</tr>
										@endforeach
									@endif
								</tbody>
							</table>
						</div>
                    </div>
                </div>
            <!-- </div> -->
        <!-- </div> -->
    </div>
    <div class="col-md-8">
        <!-- <div class="box box-success"> -->
            <!-- <div class="body box-body"> -->
				<div class="box-header with-border">
				  <h3 class="box-title">Tambah User</h3>
				</div>
				<!-- <div class="body box-body"> -->
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Daftar nama user</label>
									<form action="{{route('role.user.add.action')}}" method="POST">
									{{csrf_field()}}
									<div class="row">
										<div class="col-md-8">
											 <select class="form-control permission_values" id="user_id_to_add" name="user_id_to_add">
												 <option value=""></option>
												 @foreach($users_to_add as $row)
													<option value="{{$row->id}}" data-id="{{$row->name}}">{{$row->name}}</option>
												 @endforeach
											 </select>
										</div>
										<input type="hidden" name="role_id_to_add" value="{{$roles->id}}" />
										<div class="col-md-4"><button type="submit" class="btn btn-success"><i class="fa fa-plus" style="margin-right: 10px"></i>Tambahkan</button></div>
									</div>
									</form>
							</div>
						</div>
					</div>
				<!-- </div> -->
				<div class="box-header with-border">
				  <h3 class="box-title">Data User</h3>
				</div>
				<!-- <div class="body box-body"> -->
					<div class="row">
						<div class="col-md-12">
							<table id="fasyankes_list" class="table table-striped table-responsive table-bordered dataTable" >
								<thead>
									<tr>
										<th style="width:2%">No</th>
										<th>Nama User</th>
										<!-- <th>Pangkat</th> -->
										<th>Status</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody class="filterable">
									<?php $i = 1;?>
									@foreach($users as $item)
									<tr class="filterable">
										<td class="text-center">{{$i++}}</td>
										<td class="text-left" >{{$item->name}}</td>
										<!-- <td class="text-center" >{{-- $item->pangkat_golongan --}}</td> -->
										<td class="text-center" >
											{{-- @if($item->status==1) --}}
												<span class="label label-success">Aktif</span>
											{{-- @else --}}
												<span class="label label-danger">Tidak Aktif</span>
											{{-- @endif --}}
										</td>
										<td class="text-center">
											<a class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit" href="{{route('admin.user.edit.view', ['id' => $item->id])}}">
												<i class="fa fa-pencil"></i>
											</a>
											<a class="btn btn-xs btn-danger" onClick="return confirm('Apakah Anda yakin untuk menghapus user {{$item->name}} dari grup {{$roles->display_name}} ?')" data-toggle="tooltip" title="Delete" href="{{route('role.user.delete.action', ['role_id' => $roles->id , 'user_id' => $item->id])}}">
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
        <!-- </div> -->
    </div>
</div>

@stop

@section('custom-foot')
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
