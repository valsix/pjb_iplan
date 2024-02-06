@extends('layouts.app')

@section('content')
    <h1> Tambah Tahun Anggaran {{ $jenis->name }}</h1>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
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
                    <form method="POST" action="{{ route('template.store', $jenis_id) }}" data-parsley-validate class="form-horizontal form-label-left" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <div class="form-group{{ $errors->has('tahun') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Tahun Anggaran<span class="required">*</span>
                            </label>
                            <?php 
                                $current_year = date('Y'); 
                                $tahun_anggaran = $current_year+1; 
                            ?>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <!-- <input type="text" name="tahun" value="{{ old('tahun') }}" required="required" class="form-control col-md-7 col-xs-12"> -->
                                <select name="tahun" class="form-control col-md-7 col-xs-12" required="required">
                                <?php for($y=$tahun_anggaran;$y>=$current_year;$y--) { ?>
                                    <option value="<?php echo $y ?>"><?php echo $y ?></option>
                                <?php } ?>
                                </select>
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
                                <input type="file" name="file" required="required" class="form-control col-md-7 col-xs-12">
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
    </div>
@endsection