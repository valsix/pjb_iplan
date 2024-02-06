@extends('layouts.app')
@section('content')
<h3>Detail User</h3>
@include('partials.has-info-message')
<div class="row">
    <div class="col-md-6">
        <!-- <div class="box box-success"> -->
            <!-- <div class="body box-body"> -->
  	<div class="row">
	    <div class="col-md-12">
	      <h1>{{$user->name}}</h1>
	        <div class="divider"></div>
	        	<div class="col-md-4 col-md-offset-4">
	          		<center><img src="{{(is_null($user))? asset('images/user.png') : 'http://ellipse.ptpjb.com/profiles/photo.do?uid='.$user->username }}" class="profile-user-img img-responsive img-circle" alt="User Image"></center>
	          	</div>

			<table class="table table-hover">
				<tbody>
					<tr>
						<th style="width: 150px;">Username</th>
						<td>:</td>
						<td>{{ $user->username }}</td>
					</tr>
					<tr>
						<th>Grup saat ini</th>
						<td>:</td>
						<td>
							@if($current_roles != NULL)
								@foreach ($current_roles as $row)
									<span class="label label-success">{{$row->display_name}}</span>
								@endforeach
							@else
								<span>tidak memiliki grup</span>
							@endif
						</td>
					</tr>
					<tr>
						<th>Status user</th>
						<td>:</td>
						<td> {{ ($user->enabled=='1') ? 'Aktif' : 'Tidak Aktif' }}</td>
					</tr>
					<tr>
						<th>Nama</th>
						<td>:</td>
						<td>{{ $user->name }}</td>
					</tr>
					<tr>
						<th>Email</th>
						<td>:</td>
						<td>{{ $user->email }}</td>
					</tr>
				</tbody>
			</table>
	    </div>
    </div>

    <h3>Notifikasi Email</h3>
    <div class="row">
    	<div class="col-md-12">
    		<form action="{{route('admin.user.edit.view.action', ['id'=>$user->id])}}" method="POST">
    			{{csrf_field()}}
				<table class="table table-hover">
					<tbody>
						<tr>
							<th style="width: 150px;">Notifikasi Email</th>
							<td>:</td>
							<td><input type="checkbox" name="status_notif_email" value="1" {{ ($user->status_notif_email == 1) ? 'checked' : '' }}> </td>
						</tr>
						<tr>
							<th style="width: 150px;"></th>
							<td></td>
							<td>
								<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;Simpan</button>
							</td>
						</tr>
					</tbody>
				</table>
			</form>
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

</script>

@endsection
