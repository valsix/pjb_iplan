@extends('layouts.app')

@section('css_page')

    <link href="{{ asset('vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}" rel="stylesheet">
@endsection

@section('js_page')
    <script src="{{ asset('vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-scroller/js/dataTables.scroller.min.js') }}"></script>
    <script src="{{ asset('vendors/jszip/dist/jszip.min.js') }}"></script>
    <script src="{{ asset('vendors/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('vendors/pdfmake/build/vfs_fonts.js') }}"></script>

    <script type="text/javascript">
        $('#datatable').dataTable( {
            "searching": false
        } );
    </script>
@endsection

@section('content')
  <div role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Edit Rencana Kerja</h3>
               <div class="panel-default">
                          <br>
                          <div class="row">
                            <div class="col-lg-13">
                              <form method="post">
                              {{ csrf_field() }}
                                <div> 
                                  <div class="col-md-4"><label> Nama Lokasi </label></div>
                                  <div class="col-md-8">
                                    <select name="lokasi_id" class="form-control" readonly="readonly">
                                      <?php foreach ($lokasi as $key): ?>
                                        <option value="{{ $key->id }}" <?php if ($key->id == $rencanakerja->nama): ?> selected <?php endif ?> > {{ $key->nama }} </option>
                                      <?php endforeach ?>
                                    </select>
                                  </div>
                                </div>
                                <br>
                                <br>
                                <div class="row {{ $errors->has('tahun_anggaran') ? ' has-error' : '' }}">
                                <div class="col-md-4"><label> Tahun Anggaran </label>
                                </div>
                                <div class="col-md-8"> <input class="form-control" type="text" name="tahun_anggaran" required="required"  value="<?php date('Y')+1 ?>"">
                                  @if($errors->has('tahun_anggaran'))
                                    <span class="help-block">
                                      <strong>{{ $errors->first('tahun_anggaran') }}</strong>
                                    </span>
                                  @endif
                                </div>
                                </div>
                                <br>
                                <div class="row {{ $errors->has('nama_unit') ? ' has-error' : '' }}">
                                <div class="col-md-4"><label> Nama Unit </label>
                                </div>
                                <div class="col-md-8"> <input class="form-control" type="text" name="nama_unit" required="required" value="{{ $rencanakerja->nama_unit }}">
                                  @if($errors->has('nama_unit'))
                                    <span class="help-block">
                                      <strong>{{ $errors->first('nama_unit') }}</strong>
                                    </span>
                                  @endif
                                </div>
                                </div>
                                <br>
                                <div class="row {{ $errors->has('satuan_unit') ? ' has-error' : '' }}">
                                <div class="col-md-4"><label> Satuan Unit </label>
                                </div>
                                <div class="col-md-8"> <input class="form-control" type="text" name="satuan_unit" required="required" value="{{ $rencanakerja->satuan_unit }}">
                                  @if($errors->has('satuan_unit'))
                                    <span class="help-block">
                                      <strong>{{ $errors->first('satuan_unit') }}</strong>
                                    </span>
                                  @endif
                                </div>
                                </div>
                                <br>
                                <div class="row {{ $errors->has('rkap_n_1') ? ' has-error' : '' }}">
                                <div class="col-md-4"><label> RKAP n 1 </label>
                                </div>
                                <div class="col-md-8"> <input class="form-control" type="text" name="rkap_n_1" required="required" value="{{ $rencanakerja->rkap_n_1 }}">
                                  @if($errors->has('rkap_n_1'))
                                    <span class="help-block">
                                      <strong>{{ $errors->first('rkap_n_1') }}</strong>
                                    </span>
                                  @endif
                                </div>
                                </div>
                                <br>
                                <div class="row {{ $errors->has('prak_real_n_1') ? ' has-error' : '' }}">
                                <div class="col-md-4"><label> PRAK real n 1 </label>
                                </div>
                                <div class="col-md-8"> <input class="form-control" type="text" name="prak_real_n_1" required="required" value="{{ $rencanakerja->prak_real_n_1 }}">
                                  @if($errors->has('prak_real_n_1'))
                                    <span class="help-block">
                                      <strong>{{ $errors->first('prak_real_n_1') }}</strong>
                                    </span>
                                  @endif
                                </div>
                                </div>
                                <br>
                                <div class="row {{ $errors->has('rkap_n') ? ' has-error' : '' }}">
                                <div class="col-md-4"><label> RKAP n </label>
                                </div>
                                <div class="col-md-8"> <input class="form-control" type="text" name="rkap_n" required="required" value="{{ $rencanakerja->rkap_n }}">
                                  @if($errors->has('rkap_n'))
                                    <span class="help-block">
                                      <strong>{{ $errors->first('rkap_n') }}</strong>
                                    </span>
                                  @endif
                                </div>
                                </div>
                              <br>
                              <br>
                                <div class=" col-xs-12 col-md-offset-4">
                                  <!-- <button class="btn btn-primary" type="button">Tambah</button> -->
                                  <!-- sementara utk demo diganti <a> -->
                                  <button class="btn btn-primary" type="submit">Simpan</button>
                                  <a href="{{ url('/rencana_kerja') }}" class="btn btn-default" >Kembali</a> 
                                </div>
                              </form>
                              
                            </div>
                          </div>
                        </div>
            </div>
          </div>
        </div>
        </div>
@endsection


<!--  -->