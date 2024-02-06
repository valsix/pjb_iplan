@extends('layouts.app')
@section('content')
<h3>List Data Permission</h3>
@include('partials.validation-error-message')
@include('partials.has-info-message')
<div class="row">
    <div class="col-md-1">
        <a href="create" class="btn btn-primary">Tambah Permission</a>
    </div>
</div>
<br>

<div class="row">
    <div class="col-md-12">
        <!-- <div class="box box-primary"> -->
            
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
                                    <td class="text-left" >{{$item->display_name}}</td>
                                    <td class="text-left" >{{$item->route_permission}}</td>
                                    <td class="text-center" >
                                    @if($item->is_menu == 1)
                                        <span class="label bg-primary">List Menu</span>
                                    @else
                                        <span class="label bg-red">Bukan List Menu</span>
                                    @endif
                                    <td class="text-center" >
                                    @if($item->enabled == 1)
                                        <span class="label bg-green">Aktif</span>
                                    @else
                                        <span class="label bg-red">Tidak Aktif</span>
                                    @endif
                                    </td>
                                    <td class="text-center">
                                        <a class="btn btn-xs btn-success" data-toggle="tooltip" title="View Detail" href="{{route('admin.permission.view.view', ['id' => $item->id])}}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit" href="{{route('admin.permission.edit.view', ['id' => $item->id])}}">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <a class="btn btn-xs btn-danger" onClick="return confirm('Apakah Anda yakin untuk menghapus permission \'{{$item->display_name}}\'? \n\nPeringatan!!! Mengehapus item menu memungkinkan sub menu di dalamnya tidak dapat ditampilkan')" data-toggle="tooltip" title="Delete" href="
                                        {{route('permission.delete.action', ['id' => $item->id])}}">
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
