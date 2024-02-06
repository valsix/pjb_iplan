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
                <h3>Detail Distrik</h3>
               <div class="panel-default">
                          <br>
                          <div>
                            <div class="col-lg-13">
                              <form method="post" role="form">
                              {{ csrf_field() }}
                              <div class="row"> 
                              <div class="col-md-4"> <label>Strategi Bisnis </label> </div>
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
        
                                <br>
                                <div class="row">
                                <div class="col-md-4"> <label> Nama Distrik </label> </div>
                                <div class="col-md-6"> <input class="form-control" type="text" name="name" value="name_distrik" disabled="disabled"></div>
                                </div>
                                <br>
                                <div class="row">
                                <div class="col-md-4"> <label> Nama Lokasi </label> </div>
                                <div class="col-md-6"> 
                                  <p> Lokasi A
                                  <br> Lokasi B
                                  <br> Lokasi C </p>
                                </div>
                                <div class="col-md-4"> <label> Nama Entitas </label> </div>
                                <div class="col-md-6"> 
                                  <p> Entitas 1
                                  <br> Entitas 2
                                  <br> Entitas 3 </p>
                                </div>
                                <div class="col-md-4"> <label> Nama Unit </label> </div>
                                <div class="col-md-6"> 
                                  <p> Unit 1
                                  <br> Unit 2
                                  <br> Unit 3 </p>
                                </div>

                                </div>
                                                      
                              <br>
                                <div class=" col-xs-12 col-md-offset-4">
                                  <!-- <button class="btn btn-primary" type="button">Simpan</button> -->
                                  <!-- sementara utk demo diganti <a> -->
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
