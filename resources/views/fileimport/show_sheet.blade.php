@extends('layouts.app')

@section('css_page')
    <style type="text/css">
        .table-container
        {
            width: 100%;
            overflow-x: auto;
            overflow: auto;
            margin: 0 0 1em;
        }
    </style>
@endsection

@section('content')
    <h3>Draft {{ $version->template->jenis->name }} Tahun Anggaran {{ $version->template->tahun }}</h3>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <ul>
                @foreach (session('error') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Sheet {{ $sheet_md->name }}</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <a href="{{ route('fileimport.show', ['version_id' => $version->id, 'id' => $id]) }}" class="btn btn-primary"> Back </a>
                    @if($cek_sheet_data)
                        <a href="{{ route('fileimport.editimport', ['version_id' => $version->id, 'id' => $id, 'sheet_id' => $sheet_md->id]) }}" class="btn btn-primary"> Edit </a>
                    @endif
                    <div id="myTabContent2" class="tab-content">
                        <div class="scroll">
                            <table class="table">
                                <thead>
                                    @foreach($sheet_header as $row2)
                                        <tr>
                                            @foreach($row2 as $value)
                                                <th style="min-width:200px">{{ $value }}</th>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </thead>
                                <tbody>
                                    @foreach($sheet as $row2)
                                        <tr>
                                            @foreach($row2 as $value)
                                                <td style="min-width:200px">{{ $value }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
@endsection
