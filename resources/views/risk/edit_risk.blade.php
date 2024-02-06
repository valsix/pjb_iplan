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
                <h3>Edit Risk Profile</h3>
               <div class="panel-default">
                          <br>
                          <div class="row">
                            <div class="col-lg-13">
                              <form method="post">
                              {{ csrf_field() }}
                                <div> 
                                  <div class="col-md-4"><label> Nama Lokasi </label></div>
                                  <div class="col-md-8">
                                    <select name="lokasi_id" class="form-control" disabled="disabled">
                                      <?php foreach ($lokasi as $key): ?>
                                        <option value="{{ $key->id }}" <?php if ($key->id == $riskprofile->name): ?> selected <?php endif ?> > {{ $key->name }} </option>
                                      <?php endforeach ?>
                                    </select>
                                  </div>
                                </div>
                                <div class="col-md-4"><label> Risk Tag </label>
                                </div>
                                <div class="col-md-8"> <input class="form-control" type="text" name="risk_tag" required="required" value="{{ $riskprofile->risk_tag }}"></div>
                                <br>
                                <br>
                                <div class="col-md-4"><label> Risk Event </label>
                                </div>
                                <div class="col-md-8"> <input class="form-control" type="text" name="risk_event" required="required" value="{{ $riskprofile->risk_event }}"></div>
                                <br>
                                <br>
                                <div class="col-md-4"><label> Risiko Korporat </label>
                                </div>
                                <div class="col-md-8"> <input class="form-control" type="text" name="risk_corporate" required="required" value="{{ $riskprofile->risk_corporate }}"></div>
                                <br>
                                <br>
                                <div class="col-md-4"><label> Tingkat Kemungkinan </label>
                                </div>
                                <div class="col-md-8"> <input class="form-control" type="text" name="possibility_level" required="required" value="{{ $riskprofile->possibility_level }}"></div>
                                <br>
                                <br>
                                <div class="col-md-4"><label> Tingkat Dampak </label>
                                </div>
                                <div class="col-md-8"> <input class="form-control" type="text" name="impact_level" required="required" value="{{ $riskprofile->impact_level }}"></div>
                                <br>
                                <br>
                                <div class="col-md-4"><label> Level Resiko </label>
                                </div>
                                <div class="col-md-8"> <input class="form-control" type="text" name="risk_level" required="required" value="{{ $riskprofile->risk_level }}"></div>
                              <br>
                              <br>
                                <div class=" col-xs-12 col-md-offset-4">
                                  <!-- <button class="btn btn-primary" type="button">Tambah</button> -->
                                  <!-- sementara utk demo diganti <a> -->
                                  <button class="btn btn-primary" type="submit">Simpan</button>
                                  <a href="{{ url('/risk_profile') }}" class="btn btn-default" >Kembali</a> 
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