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
<div  role="main">
    <div class="page-title">
      <div class="title_left">
        <h3>Edit Grup</h3>
          <div class="panel-default">
          <br>
            <div class="row">
                <div class="col-lg-13">
                  <form method="post" role="form">
                  {{ csrf_field() }}
                    <div> 
                      <div class="col-md-4"><label> Name </label></div>
                      <div class="col-md-8">
                        <input class="form-control" type="text" name="name" value="{{ $grup->name }}">
                      </div>
                    </div>
                    <br>
                    <br>
                    <div> 
                      <div class="col-md-4"><label> Display Name </label></div>
                      <div class="col-md-8">
                        <input class="form-control" type="text" name="display_name" value="{{ $grup->display_name }}">
                      </div>
                    </div>
                    <br>
                    <br>
                    <div> 
                      <div class="col-md-4"><label> Description </label></div>
                      <div class="col-md-8">
                        <input class="form-control" type="text" name="description" value="{{ $grup->description }}">
                      </div>
                    </div>
                    <br>
                    <br>
                    <div class=" col-xs-12 col-md-offset-4">
                    <!-- <button class="btn btn-primary" type="button">Simpan</button> -->
                    <!-- sementara untuk demo -->
                    <button class="btn btn-primary" type="submit">Simpan</button>
                    <a href="{{ url('/grup/daftar') }}" class="btn btn-default" type="reset">Kembali</a>
                  </div>
                  </form>
                </div>
              </div>
      </div>
    </div>
  </div>
</div>
@endsection