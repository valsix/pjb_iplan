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
                <h3>Tambah User Internal</h3>
               <div class="panel-default">
                          <br>
                          <div>
                            <div class="col-lg-13">
                              <form method="post" role="form">
                              {{ csrf_field() }}
                              <!-- <br> -->
                                <div class="row {{ $errors->has('nid') ? ' has-error' : '' }}">
                                  <div class="col-md-4">
                                    <label> NID </label> 
                                  </div>
                                  <div class="col-md-6"> 
                                    <input class="form-control" type="text" value="{{ $user_internal->nid??'' }}" name="nid" required="required" {{$disabled??''}}>
                                      @if($errors->has('nid'))
                                        <span class="help-block">
                                          <strong>{{ $errors->first('nid') }}</strong>
                                        </span>
                                      @endif
                                  </div>
                                </div>
                                <br>

                                <div class="row {{ $errors->has('nama_lengkap') ? ' has-error' : '' }}">
                                  <div class="col-md-4">
                                    <label> Nama Lengkap </label> 
                                  </div>
                                  <div class="col-md-6"> 
                                    <input class="form-control" type="text" value="{{ $user_internal->nama_lengkap??'' }}" name="nama_lengkap" required="required" {{$disabled??''}}>
                                      @if($errors->has('nama_lengkap'))
                                        <span class="help-block">
                                          <strong>{{ $errors->first('nama_lengkap') }}</strong>
                                        </span>
                                      @endif
                                  </div>
                                </div>
                                <br>

                                <div class="row {{ $errors->has('email') ? ' has-error' : '' }}">
                                  <div class="col-md-4">
                                    <label> Email </label> 
                                  </div>
                                  <div class="col-md-6"> 
                                    <input class="form-control" type="text" value="{{ $user_internal->email??'' }}" name="email" required="required" {{$disabled??''}}>
                                      @if($errors->has('email'))
                                        <span class="help-block">
                                          <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                      @endif
                                  </div>
                                </div>
                                <br>

                                <div class="row {{ $errors->has('bagian') ? ' has-error' : '' }}">
                                  <div class="col-md-4">
                                    <label> Bagian </label> 
                                  </div>
                                  <div class="col-md-6"> 
                                    <input class="form-control" type="text" value="{{ $user_internal->bagian??'' }}" name="bagian" required="required" {{$disabled??''}}>
                                      @if($errors->has('bagian'))
                                        <span class="help-block">
                                          <strong>{{ $errors->first('bagian') }}</strong>
                                        </span>
                                      @endif
                                  </div>
                                </div>
                                <br>

                                <div class="row {{ $errors->has('ditbid') ? ' has-error' : '' }}">
                                  <div class="col-md-4">
                                    <label> Ditbid </label> 
                                  </div>
                                  <div class="col-md-6"> 
                                    <input class="form-control" type="text" value="{{ $user_internal->ditbid??'' }}" name="ditbid" required="required" {{$disabled??''}}>
                                      @if($errors->has('ditbid'))
                                        <span class="help-block">
                                          <strong>{{ $errors->first('ditbid') }}</strong>
                                        </span>
                                      @endif
                                  </div>
                                </div>
                                <br>

                                <div class="row {{ $errors->has('unit') ? ' has-error' : '' }}">
                                  <div class="col-md-4">
                                    <label> Unit </label> 
                                  </div>
                                  <div class="col-md-6"> 
                                    <input class="form-control" type="text" value="{{ $user_internal->unit??'' }}" name="unit" required="required" {{$disabled??''}}>
                                      @if($errors->has('unit'))
                                        <span class="help-block">
                                          <strong>{{ $errors->first('unit') }}</strong>
                                        </span>
                                      @endif
                                  </div>
                                </div>
                                <br>

                                <div class="row {{ $errors->has('klasifikasi_unit') ? ' has-error' : '' }}">
                                  <div class="col-md-4">
                                    <label> Klasifikasi </label> 
                                  </div>
                                  <div class="col-md-6"> 
                                    <input class="form-control" type="text" value="{{ $user_internal->klasifikasi_unit??'' }}" name="klasifikasi_unit" required="required" {{$disabled??''}}>
                                      @if($errors->has('klasifikasi_unit'))
                                        <span class="help-block">
                                          <strong>{{ $errors->first('klasifikasi_unit') }}</strong>
                                        </span>
                                      @endif
                                  </div>
                                </div>
                                <br>

                                <div class="row {{ $errors->has('nama_posisi') ? ' has-error' : '' }}">
                                  <div class="col-md-4">
                                    <label> Nama Posisi </label> 
                                  </div>
                                  <div class="col-md-6"> 
                                    <input class="form-control" type="text" value="{{ $user_internal->nama_posisi??'' }}" name="nama_posisi" required="required" {{$disabled??''}}>
                                      @if($errors->has('nama_posisi'))
                                        <span class="help-block">
                                          <strong>{{ $errors->first('nama_posisi') }}</strong>
                                        </span>
                                      @endif
                                  </div>
                                </div>
                                <br>

                                <div class=" col-xs-12 col-md-offset-4">
                                  <!-- <button class="btn btn-primary" type="button">Tambah</button> -->
                                  <!-- sementara utk demo diganti <a> -->
                                  @if(!$disabled)
                                    <button class="btn btn-primary" type="submit">Simpan</button>
                                  @endif
                                  <a href="{{url('user_internal/manage')}}" class="btn btn-default" >Kembali</a>                          
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