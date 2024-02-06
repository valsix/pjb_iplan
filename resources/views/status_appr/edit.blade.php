@extends('layouts.app')

@section('css_page')
    <!-- Datatables -->
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
                <h3>Edit Distrik</h3>
               <div class="panel-default">
                          <br>
                          <div>
                            <div class="col-lg-13">
                              <form method="post" role="form">
                              {{ csrf_field() }}
                              <div class="row"> 
                              <div class="col-md-4"> <label>Nama Strategi Bisnis </label> </div>
                              <div class="col-md-6">
                              <select class="form-control" disabled="disabled" name="strategi_bisnis_id">
                                  <?php foreach ($strategi_bisnis as $key): ?>
                                    <option value="{{ $key->id }}" <?php if($key->id == $distrik->name) : ?> selected 
                                  <?php endif ?>
                                    >{{ $key->name }} </option>
                                  <?php endforeach ?>
                              </select>
                              </div>
                              </div>
                                <br>
                                <div class="row {{ $errors->has('kode_distrik') ? ' has-error' : '' }}"> 
                                <div class="col-md-4"> <label> Kode Distrik </label> </div>
                                <div class="col-md-6"> <input class="form-control" type="text" value="{{ $distrik->kode_distrik }}" name="kode_distrik" required="required" disabled="disabled">
                                  @if($errors->has('kode_distrik'))
                                      <span class="help-block">
                                        <strong>{{ $errors->first('kode_distrik') }}</strong>
                                      </span>
                                  @endif
                                </div>
                                </div>
                                <br>
                                <div class="row {{ $errors->has('name') ? ' has-error' : '' }}">
                                <div class="col-md-4"> <label> Nama Distrik </label> </div>
                                <div class="col-md-6"> <input class="form-control" type="text" value="{{ $distrik->name }}" name="name" required="required">
                                  @if($errors->has('name'))
                                    <span class="help-block">
                                      <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                  @endif
                                </div>
                                </div>
                              
                              <br>
                                <div class=" col-xs-12 col-md-offset-4">
                                  <!-- <button class="btn btn-primary" type="button">Simpan</button> -->
                                  <!-- sementara utk demo diganti <a> -->
                                  <button class="btn btn-primary" type="submit">Simpan </button>
                                  <a href="{{ url('distrik/daftar') }}" class="btn btn-default" >Kembali</a>                          
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
