@extends('layouts.app')
@section('content')
<h3> Daftar User</h3>
@include('partials.validation-error-message')
@include('partials.has-info-message')

<div class="row">
    <div class="col-md-1">
        <a href="create" class="btn btn-primary">Tambah User</a>
    </div>
</div>
<br>

<div role="main">
<div class="row">
    <!-- <div class="col-md-12"> -->
        <!-- <div class="box box-primary">
            <div class="body box-body"> -->
                <!-- <div class="row"> -->
                <div class="x_content">
                    <div class="col-md-12">
                        <table id="fasyankes_list" class="table table-striped table-responsive table-bordered dataTable" >
                            <thead>
                                <tr>
                                    <th style="width:2%">No</th>
                                    <th style="width:7%">Nama Lengkap</th>
                                    <th style="width:7%">Username</th>
                                    <th style="width:6%">Posisi</th>
                                    <th style="width:6%">Kode Distrik</th>
                                    <th style="width:6%">Distrik</th>
                                    <th style="width:7%">Jabatan</th>
                                    <!-- <th style="width:6%">Tanggal Create</th>
                                    <th style="width:6%">Tanggal Update</th>
                                    <th style="width:6%">Status User</th> -->
                                    <th style="width:6%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="filterable">
                                <?php $i = 1;?>
                                @foreach($users as $item)
                                @if($current_id_user!=1)
                                    <!-- login selain developer: tidak bisa lihat user developer -->
                                    @if($item->id != 1)
                                    <tr class="filterable">
                                        <td class="text-center">{{$i++}}</td>
                                        <td class="text-left" >{{$item->name}}</td>
                                        <td class="text-left" >{{$item->username}}</td>
                                        <td class="text-left" >{{$item->group->data->display_name}}</td>
                                        <td class="text-left" >{{$item->distrik->code1 }}</td>
                                        <td class="text-left" >{{$item->distrik->name }}</td>
                                        <td class="text-left" >{{$item->nama_posisi }}</td>
                                        <!-- <td class="text-left" >{{date("d-m-Y H:i:s",$item->create_at)}}</td>
                                        <td class="text-left" >{{date("d-m-Y H:i:s",$item->update_at)}}</td>
                                        <td class="text-center" >
                                            @if($item->status == 1)
                                                <span class="label bg-green">Aktif</span>
                                            @else
                                                <span class="label bg-red">Tidak Aktif</span>
                                            @endif
                                        </td> -->
                                        <td class="text-center">
                                            <a class="btn btn-xs btn-success" data-toggle="tooltip" title="View Detail" href="{{route('admin.user.view.view', ['id' => $item->id])}}">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                                <a class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit" href="{{route('admin.user.edit.view', ['id' => $item->id])}}">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                               
                                                <a class="btn btn-xs btn-danger" onClick="return confirm('Apakah Anda yakin untuk menghapus user {{$item->name}}  ?')" data-toggle="tooltip" title="Delete" href="{{route('user.delete.action', ['id' => $item->id , 'role_id' => $item->group->data->id])}}">
                                                    <i class="fa fa-times"></i>
                                                </a>
                                        </td>
                                    </tr>
                                    @endif
                                @else
                                    <tr class="filterable">
                                        <td class="text-center">{{$i++}}</td>
                                        <td class="text-left" >{{$item->name}}</td>
                                        <td class="text-left" >{{$item->username}}</td>
                                        <td class="text-left" >{{ $item->group->data->display_name }}</td>
                                        <td class="text-left" >{{$item->distrik->code1 }}</td>
                                        <td class="text-left" >{{$item->distrik->name }}</td>
                                        <!-- <td class="text-left" >{{date("d-m-Y H:i:s",strtotime($item->created_at))}}</td>
                                        <td class="text-left" >{{date("d-m-Y H:i:s",strtotime($item->updated_at))}}</td>
                                        <td class="text-center" >
                                            @if($item->status == 1)
                                                <span class="label bg-green">Aktif</span>
                                            @else
                                                <span class="label bg-red">Tidak Aktif</span>
                                            @endif
                                        </td> -->
                                        <td class="text-center">
                                            <a class="btn btn-xs btn-success" data-toggle="tooltip" title="View Detail" href="{{route('admin.user.view.view', ['id' => $item->id])}}">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                                <a class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit" href="{{route('admin.user.edit.view', ['id' => $item->id])}}">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                            <a class="btn btn-xs btn-danger" onClick="return confirm('Apakah Anda yakin untuk menghapus user {{$item->nama}}  ?')" data-toggle="tooltip" title="Delete" href="{{route('user.delete.action', ['id' => $item->id , 'role_id' => $item->group->data->id])}}">
                                                    <i class="fa fa-times"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endif
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

<script src="{{ asset('js/jquery-1.11.2.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/datepicker/bootstrap-datepicker.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/jquery-ui.min.js') }}" type="text/javascript"></script>

{!! HTML::style('datatables/css/dataTables.bootstrap.css') !!}
{!! HTML::script('datatables/js/jquery.dataTables.min.js') !!}
{!! HTML::script('datatables/js/dataTables.bootstrap.min.js') !!}
{!! HTML::style('datatables/css/dataTables.custom.css') !!}

<script type="text/javascript">
    var table = $("#fasyankes_list").DataTable(
    {
        "dom": '<"toolbar">l<"filt">frtip',
        // "oLanguage": {
        //    "sLengthMenu": '<select name="fasyankes_list_length" aria-controls="fasyankes_list" class="form-control input-sm select2-hidden-accessible" tabindex="-1" aria-hidden="true"><option value="10">10 Data</option><option value="25">25 Data</option><option value="50">50 Data</option><option value="100">100 Data</option></select>'
        // }
    });
</script>

@endsection
