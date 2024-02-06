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
    <h3>Draft RKAP</h3>
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
                    <h2><i class="fa fa-bars"></i> Template <small>Sheet</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li>
                            <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li>
                            <a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form method="POST" action="{{ route('fileimport.updateimport', ['version_id' => $version->id, 'id' => $id, 'sheet_id' => $sheet_id]) }}">
                        {!! csrf_field() !!}
                        {!! method_field('PUT') !!}
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Update Data
                            </div>
                            <div class="panel-default">
                                <br>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-1 col-sm-1 col-xs-12">Keterangan<span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <textarea class="form-control" name="keterangan"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary"> Update </button>
                                        </div>
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="scroll form-group" style="width: 100%; height: 500px; overflow:scroll;">
                        <table class="table">
                            <?php $k = 1 ?>
                            @foreach($sheet as $row2)
                                <tr>
                                    @foreach($row2 as $value)
                                        @if(in_array($k, $updatable['updatable']))
                                            <td style="min-width: 200px;"><input type="text" name="update[{{ $updatable['updatable_id'][$k] }}]" class="form-control" value="{{ $value }}"></td>
                                        @else
                                            <td>{{ $value }}</td>
                                        @endif
                                        <?php $k++ ?>
                                    @endforeach
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
@endsection
