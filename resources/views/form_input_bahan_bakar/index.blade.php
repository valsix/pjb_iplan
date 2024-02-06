@extends('layouts.app')

@section('content')
    <h1>Daftar Form Input Bahan Bakar</h1>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading"></div>
            </div>
            @if (session('message') OR session('error'))
                <div class="alert alert-{{ session('message') ? 'success' : 'danger' }} alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    {{ session('message') ?? session('error') }}
                </div>
            @endif
            @if($user->current_role->is_kantor_pusat)
                <a href="{{ route('form_bahan_bakar.create') }}" class="btn btn-primary">Tambah Form Input Bahan Bakar</a>
            @endif
            <div class="x_content">
                <table id="datatable" class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>Tahun</th>
                        <th>Nama</th>
                        <th>Revisi Ke</th>
                        <th>Uploaded by</th>
                        <th>Status Data</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($files as $file)
                            <tr>
                                <td>{{ $file->tahun }}</td>
                                <td>{{ $file->name }}</td>
                                <td> Revisi ke-{{ $file->version }} {{ $file->created_at }}</td>
                                <td>{{ $file->uploaded->name }}</td>
                                <td>
                                    @if( $file->excel_bahan_bakar->count() == 0 )
                                        <span class="label label-danger">Kosong</span>
                                    @else
                                        <span class="label label-success">Ada data</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display: flex;">

                                        @if($file->excel_bahan_bakar->count() > 0)
                                            <a style="margin-right: 4px;" data-toggle="tooltip" title="Detail" class="btn btn-success" href="{{ route('form_bahan_bakar.show', $file->id)  }}">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        @endif

                                        <a style="margin-right: 4px" href="{{ url('form_bahan_bakar/download') .'/'. $file->id }}" class="btn btn-info" data-toggle="tooltip" title="Download Original Excel">
                                            <span class="glyphicon glyphicon-download-alt"></span>
                                        </a>

                                        @if($file->excel_bahan_bakar->count() == 0)
                                            <form action="{{ route('form_bahan_bakar.destroy', $file->id) }}" method="POST">
                                                {!! csrf_field() !!}
                                                {!! method_field('DELETE') !!}
                                                <button
                                                        data-toggle="tooltip" data-placement="top" title="Hapus"
                                                        class="btn btn-danger"
                                                        onclick="return confirm('Data yang terkait dengan data ini akan ikut terhapus, apakah anda yakin untuk menghapus data ini?');"
                                                >
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

