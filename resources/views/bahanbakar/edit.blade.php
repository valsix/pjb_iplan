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
          <div class="row">
            <div class="page-title">
              <div class="title_left">
                <h3>Edit Jenis Bahan Bakar</h3>
               <div class="panel-default">
                          <br>
                          <div>
                            <div class="col-lg-13">
                              <form method="post">
                              <!-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> -->
                               {{ csrf_field() }} 
                                <div class="row">
                                <div class="col-md-4"><label> Nama </label> </div>
                                <div class="col-md-6 {{ $errors->has('nama') ? ' has-error' : '' }}">
                                <input value="{{ $jenis_bahan_bakar->nama }}" class="form-control" type="text" name="nama" required="required">
                                @if($errors->has('nama'))
                                    <span class="help-block">
                                      <strong>{{ $errors->first('nama') }}</strong>
                                    </span>
                                  @endif
                                </div>
                                <br><br>
                                <div class=" col-xs-12 col-md-offset-4">
                                  <!-- <button class="btn btn-primary" type="button">Tambah</button> -->
                                  <!-- sementara utk demo diganti <a> -->
                                  <button type="submit" class="btn btn-primary">Update</button>
                                  <a href="{{ url('/bahanbakar/daftar') }}" class="btn btn-default" >Kembali</a>                          
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>
                    </div>
              </div>
            </div>
          </div>
  </div>
@endsection
