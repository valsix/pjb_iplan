@extends('layouts.app')

@section('content')
    <h1> Update Template Pengendalian {{ $template->jenis->name }}</h1>

    @if(session('salah'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ session('salah') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-9 col-sm-9 col-xs-9">
            <div class="x_panel">
                <div class="x_title">
                    <h2></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form method="POST" action="{{ route('templatepengendalian.update', ['jenis_id' => $jenis_id, 'id' => $template->id]) }}" data-parsley-validate class="form-horizontal form-label-left" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        {!! method_field('PUT') !!}
                        <div class="form-group{{ $errors->has('tahun') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Tahun Ketetapan<span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" name="tahun" value="{{ old('tahun', $template->tahun) }}" required="required" class="form-control col-md-7 col-xs-12" readonly>
                                @if ($errors->has('tahun'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('tahun') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('file') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Template<span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="file" name="file" required="required" class="form-control col-md-7 col-xs-12" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                                @if ($errors->has('file'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('file') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <button type="submit" class="btn btn-success">Submit</button>
                                <a href="{{ url()->previous() }}" class="btn btn-primary">Kembali</a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        {{--
        <!-- <div class="col-md-3 col-sm-3 col-xs-3">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Versi</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    @foreach($versions as $row)
                        <table>
                            <tr>
                                @if($row->active)
                                    <td><b>Versi {{ $row->versi }}</b></td>
                                @else
                                    <td>Versi {{ $row->versi }}</td>
                                @endif
                                <td>: <a href="{{ asset($row->file) }}"> {{ $row->file }}</a></td>
                            </tr>
                        </table>

                    @endforeach
                </div>
            </div>
        </div> -->
        --}}
    </div>
@endsection
