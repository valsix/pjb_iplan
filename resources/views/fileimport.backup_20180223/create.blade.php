@extends('layouts.app')

@section('js_page')
    <script type="text/javascript">
        $('#myDatepicker2').datetimepicker({
            format: 'YYYY-MM-DD'
        });
    </script>
@endsection

@section('content')
    <!-- <h1> Tambah Draft/Revisi</h1> -->
    <h1> Tambah Draft {{ $version->template->jenis->name }}</h1>
    <div class="row">
        <div class="col-md-10 col-sm-10 col-xs-10">
            <div class="x_panel">
                <div class="x_title">
                    <!-- <h2>Tambah Draft/Revisi</h2> -->
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form method="POST" action="{{ route('fileimport.store', $version->id) }}" class="form-horizontal form-label-left">
                        {!! csrf_field() !!}
                        <div class="form-group{{ $errors->has('tahun') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Tahun Anggaran<span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" name="tahun" value="{{ $version->template->tahun }}" readonly required="required" class="form-control col-md-7 col-xs-12">
                                @if ($errors->has('tahun'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('tahun') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Fase <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select name="fase_id" class="form-control" readonly>
                                    @foreach($fase as $row)
                                        @if($row->id==1)
                                        <option value="{{ $row->id }}" {{ (old('fase_id') == $row->id)?'selected':'' }}>{{ $row->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('draft_versi') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Draft/Revisi<span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <!-- <div class="input-group date"> -->
                                    <!-- <input type="text" id='myDatepicker2' name="draft_versi" value="{{-- old('draft_versi') --}}" required="required" class="form-control col-md-7 col-xs-12"> -->
                                    <!-- <span class="input-group-addon"> -->
                                    <!-- <span class="glyphicon glyphicon-calendar"></span> -->
                                    <input type="text" name="draft_versi" value="{{ date('Y-m-d H:i:s') }}" required="required" class="form-control col-md-12 col-xs-12" readonly="readonly">
                                    </span>
                                    @if ($errors->has('draft_versi'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('draft_versi') }}</strong>
                                    </span>
                                    @endif
                                <!-- </div> -->
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nama <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="text" name="name" required="required" class="form-control col-md-12 col-xs-12">
                            </div>
                        </div>
                        @if($version->template->jenis_id ==1)
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Form 6 Rutin
                                <!-- <span class="required">*</span> -->
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select name="form6_rutin_file_import_id" class="form-control">
                                        <option value="0"></option>
                                        @if($role->is_kantor_pusat)
                                            @foreach($template_6_rutin as $version_row)
                                                <optgroup label="{{ 'Tahun Anggaran '.$version_row->template->tahun }}">
                                                    @foreach($version_row->file_imports as $row)
                                                        <option value="{{ $row->id }}" {{ (old('form6_rutin_file_import_id') == $row->id)?'selected':'' }}>{{ $row->distrik->name.' - '.$row->draft_versi->format('Y-m-d H:i:s').' - '.$row->name }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        @else
                                            @foreach($template_6_rutin as $version_row)
                                                <optgroup label="{{ 'Tahun Anggaran '.$version_row->template->tahun }}">
                                                    @foreach($version_row->file_imports as $row)
                                                        @if($row->distrik_id == $user->distrik_id)
                                                        <option value="{{ $row->id }}" {{ (old('form6_rutin_file_import_id') == $row->id)?'selected':'' }}>{{ $row->distrik->name.' - '.$row->draft_versi->format('Y-m-d H:i:s').' - '.$row->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Form 6 Reimburse
                                <!-- <span class="required">*</span> -->
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select name="form6_reimburse_file_import_id" class="form-control">
                                        <option value="0"></option>
                                        @if($role->is_kantor_pusat)
                                            @foreach($template_6_reimburse as $version_row)
                                                <optgroup label="{{ 'Tahun Anggaran '.$version_row->template->tahun }}">
                                                    @foreach($version_row->file_imports as $row)
                                                        <option value="{{ $row->id }}" {{ (old('form6_reimburse_file_import_id') == $row->id)?'selected':'' }}>{{ $row->distrik->name.' - '.$row->draft_versi->format('Y-m-d H:i:s').' - '.$row->name }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        @else
                                            @foreach($template_6_reimburse as $version_row)
                                                <optgroup label="{{ 'Tahun Anggaran '.$version_row->template->tahun }}">
                                                    @foreach($version_row->file_imports as $row)
                                                        @if($row->distrik_id == $user->distrik_id)
                                                        <option value="{{ $row->id }}" {{ (old('form6_reimburse_file_import_id') == $row->id)?'selected':'' }}>{{ $row->distrik->name.' - '.$row->draft_versi->format('Y-m-d H:i:s').' - '.$row->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Form 10 Pengembangan Usaha
                                <!-- <span class="required">*</span> -->
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select name="form10_pu_file_import_id" class="form-control">
                                        <option value="0"></option>
                                        @if($role->is_kantor_pusat)
                                            @foreach($template_10_pu as $version_row)
                                                <optgroup label="{{ 'Tahun Anggaran '.$version_row->template->tahun }}">
                                                    @foreach($version_row->file_imports as $row)
                                                        <option value="{{ $row->id }}" {{ (old('form10_pu_file_import_id') == $row->id)?'selected':'' }}>{{ $row->distrik->name.' - '.$row->draft_versi->format('Y-m-d H:i:s').' - '.$row->name }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        @else
                                            @foreach($template_10_pu as $version_row)
                                                <optgroup label="{{ 'Tahun Anggaran '.$version_row->template->tahun }}">
                                                    @foreach($version_row->file_imports as $row)
                                                        @if($row->distrik_id == $user->distrik_id)
                                                        <option value="{{ $row->id }}" {{ (old('form10_pu_file_import_id') == $row->id)?'selected':'' }}>{{ $row->distrik->name.' - '.$row->draft_versi->format('Y-m-d H:i:s').' - '.$row->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Form 10 Penguatan Kit
                                <!-- <span class="required">*</span> -->
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select name="form10_penguatankit_file_import_id" class="form-control">
                                        <option value="0"></option>
                                        @if($role->is_kantor_pusat)
                                            @foreach($template_10_penguatankit as $version_row)
                                                <optgroup label="{{ 'Tahun Anggaran '.$version_row->template->tahun }}">
                                                    @foreach($version_row->file_imports as $row)
                                                        <option value="{{ $row->id }}" {{ (old('form10_penguatankit_file_import_id') == $row->id)?'selected':'' }}>{{ $row->distrik->name.' - '.$row->draft_versi->format('Y-m-d H:i:s').' - '.$row->name }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        @else
                                            @foreach($template_10_penguatankit as $version_row)
                                                <optgroup label="{{ 'Tahun Anggaran '.$version_row->template->tahun }}">
                                                    @foreach($version_row->file_imports as $row)
                                                        @if($row->distrik_id == $user->distrik_id)
                                                        <option value="{{ $row->id }}" {{ (old('form10_penguatankit_file_import_id') == $row->id)?'selected':'' }}>{{ $row->distrik->name.' - '.$row->draft_versi->format('Y-m-d H:i:s').' - '.$row->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Form 10 PLN
                                <!-- <span class="required">*</span> -->
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select name="form10_pln_file_import_id" class="form-control">
                                        <option value="0"></option>
                                        @if($role->is_kantor_pusat)
                                            @foreach($template_10_pln as $version_row)
                                                <optgroup label="{{ 'Tahun Anggaran '.$version_row->template->tahun }}">
                                                    @foreach($version_row->file_imports as $row)
                                                        <option value="{{ $row->id }}" {{ (old('form10_pln_file_import_id') == $row->id)?'selected':'' }}>{{ $row->distrik->name.' - '.$row->draft_versi->format('Y-m-d H:i:s').' - '.$row->name }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        @else
                                            @foreach($template_10_pln as $version_row)
                                                <optgroup label="{{ 'Tahun Anggaran '.$version_row->template->tahun }}">
                                                    @foreach($version_row->file_imports as $row)
                                                        @if($row->distrik_id == $user->distrik_id)
                                                        <option value="{{ $row->id }}" {{ (old('form10_pln_file_import_id') == $row->id)?'selected':'' }}>{{ $row->distrik->name.' - '.$row->draft_versi->format('Y-m-d H:i:s').' - '.$row->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Form Bahan Bakar
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select name="form_bahan_bakar_file_import_id" class="form-control">
                                        <option value="0"></option>
                                        @if($role->is_kantor_pusat)
                                            @foreach($template_bahan_bakar as $version_row)
                                                <optgroup label="{{ 'Tahun Anggaran '.$version_row->template->tahun }}">
                                                    @foreach($version_row->file_imports as $row)
                                                        <option value="{{ $row->id }}" {{ (old('form_bahan_bakar_id') == $row->id)?'selected':'' }}>{{ $row->distrik->name.' - '.$row->draft_versi->format('Y-m-d H:i:s').' - '.$row->name }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        @else
                                            @foreach($template_bahan_bakar as $version_row)
                                                <optgroup label="{{ 'Tahun Anggaran '.$version_row->template->tahun }}">
                                                    @foreach($version_row->file_imports as $row)
                                                        @if($row->distrik_id == $user->distrik_id)
                                                        <option value="{{ $row->id }}" {{ (old('form_bahan_bakar_id') == $row->id)?'selected':'' }}>{{ $row->distrik->name.' - '.$row->draft_versi->format('Y-m-d H:i:s').' - '.$row->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Form Penyusutan
                                <!-- <span class="required">*</span> -->
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select name="form_penyusutan_file_import_id" class="form-control">
                                        <option value="0"></option>
                                        @if($role->is_kantor_pusat)
                                            @foreach($template_penyusutan as $version_row)
                                                <optgroup label="{{ 'Tahun Anggaran '.$version_row->template->tahun }}">
                                                    @foreach($version_row->file_imports as $row)
                                                        <option value="{{ $row->id }}" {{ (old('form_penyusutan_id') == $row->id)?'selected':'' }}>{{ $row->distrik->name.' - '.$row->draft_versi->format('Y-m-d H:i:s').' - '.$row->name }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        @else
                                            @foreach($template_penyusutan as $version_row)
                                                <optgroup label="{{ 'Tahun Anggaran '.$version_row->template->tahun }}">
                                                    @foreach($version_row->file_imports as $row)
                                                        @if($row->distrik_id == $user->distrik_id)
                                                        <option value="{{ $row->id }}" {{ (old('form_penyusutan_id') == $row->id)?'selected':'' }}>{{ $row->distrik->name.' - '.$row->draft_versi->format('Y-m-d H:i:s').' - '.$row->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <button type="submit" class="btn btn-success">Simpan</button>
                                <a href="{{ url()->previous() }}" class="btn btn-primary">Kembali</a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
