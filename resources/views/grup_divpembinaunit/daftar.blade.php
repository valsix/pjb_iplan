@extends('layouts.app')
@section('content')
<h3>Daftar Grup</h3>
@include('partials.validation-error-message')
@include('partials.has-info-message')
<div class="row">
    <div class="col-md-1">
        <a href="create" class="btn btn-primary">Tambah Grup</a>
    </div>
</div>
<br>

<div class="row">
    <div class="col-md-12">
        <!-- <div class="box box-primary"> -->
            <div class="body box-body">
                <div class="row">
                    <div class="col-md-12">
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
                                @foreach($grupdiv as $item)
                                <tr class="filterable">
                                    <td class="text-center">{{$i++}}</td>
                                    <td class="text-left" >{{$item->display_name}}</td>
									<td class="text-center" >{{dateIdnFromTimestamp($item->created_at)}}</td>
                                    <td class="text-center">
                                        <a class="btn btn-xs btn-success" data-toggle="tooltip" title="View Detail" href="{{route('admin.grupdiv.view.view', ['id' => $item->id])}}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit" href="{{route('admin.grupdiv.edit.view', ['id' => $item->id])}}">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <a class="btn btn-xs btn-danger" onClick="return confirm('Apakah Anda yakin untuk menghapus grup {{$item->display_name}}, user dengan grup ini juga akan kehilangan hak akses?')" data-toggle="tooltip" title="Delete" href="
                                        {{route('grupdiv.delete.action', ['id' => $item->id])}}">
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
           
        }
    });

</script>

@endsection
