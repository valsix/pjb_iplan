@extends('layouts.app')

@section('css_page')
    <!-- searching -->
    <script src="{{ asset('js/jquery-1.11.2.min.js') }}" type="text/javascript"></script> 

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
                <h3>Tambah Unit</h3>
               <div class="panel-default">
                          <br>
                          <div>
                            <div class="col-lg-13">

                            <form method="post" role="form">
                              <div class="row {{ $errors->has('strategi_bisnis_id') ? ' has-error' : '' }}">
                              <div class="col-md-4"> 
                              <label> Strategi Bisnis </label> 
                              </div>
                              {{ csrf_field() }}
                              <div class="col-md-6">
                                  <select class="form-control" name="strategi_bisnis_id" >
                                     <option></option>
                                      @foreach ($Sb as $sbs => $value)
                                         <option value="{{ $value->id }}"> {{ $value->name }} </option>
                                       @endforeach
                                  </select>
                                  @if($errors->has('strategi_bisnis_id'))
                                      <span class="help-block">
                                        <strong>{{ $errors->first('strategi_bisnis_id') }}</strong>
                                      </span>
                                  @endif
                              </div>
                              </div>
                              <br>
                              <div class="row {{ $errors->has('distrik_id') ? ' has-error' : '' }}">
                                <div class="col-md-4"> <label> Nama Distrik </label></div>
                                <div class="col-md-6"> 
                                  <select name="distrik_id" class="form-control" required>
                                   
                                  </select>
                                      @if($errors->has('distrik_id'))
                                        <span class="help-block">
                                          <strong>{{ $errors->first('distrik_id') }}</strong>
                                        </span>
                                      @endif
                                </div>
                              </div>

                              <script type="text/javascript">
                                    $(document).ready(function() {
                                        $('select[name="strategi_bisnis_id"]').on('change', function() {
                                            var strategi_bisnisID = $(this).val();
                                            $('select[name="distrik_id"]').empty();
                                            $('select[name="lokasi_id"]').empty();

                                            if(strategi_bisnisID) {
                                                $.ajax({
                                                    url: '/tambah_unit/ajax/'+strategi_bisnisID,
                                                    type: "GET",
                                                    dataType: "json",
                                                    success:function(data) {
                                                console.log(data);
                                                      $('select[name="distrik_id"]').empty();
                                                      $.each(data, function(sb, value) {
                                                          $('select[name="distrik_id"]').append('<option value="'+ value["id"] +'">'+ value["name"] +'</option>');
                                                      });

                                                    }
                                                });
                                            }else{
                                                $('select[name="distrik_id"]').empty();
                                            }
                                        }); 
                                    });
                                </script>
                              <br>
                              <div class="row {{ $errors->has('lokasi_id') ? ' has-error' : '' }}">
                                <div class="col-md-4"> <label> Nama Lokasi </label></div>
                                <div class="col-md-6"> 
                                  <select name="lokasi_id" class="form-control" required>
                                    
                                   
                                  </select>
                                      @if($errors->has('lokasi_id'))
                                        <span class="help-block">
                                          <strong>{{ $errors->first('lokasi_id') }}</strong>
                                        </span>
                                      @endif
                                </div>
                              </div>

                              <script type="text/javascript">
                                    $(document).ready(function() {
                                        $('select[name="distrik_id"]').on('change', function() {
                                            var lokasiID = $(this).val();
                                              
                                            if(lokasiID) {
                                                $.ajax({
                                                    url: '/tambah_unit/ajax2/'+lokasiID,
                                                    type: "GET",
                                                    dataType: "json",
                                                    success:function(data) {
                        
                                                      $('select[name="lokasi_id"]').empty();
                                                      console.log(data);
                                                      // $('select[name="lokasi"]').append('<option value="">==Silahkan Pilih Lokasi==</option>');
                                                      $.each(data, function(ad , value) {
                                                      console.log(ad);
                                                          $('select[name="lokasi_id"]').append('<option value="'+ value["id"] +'">'+ value["name"] +'</option>');
                                                      });

                                                     }
                                                });
                                            }else{
                                                $('select[name="lokasi_id"]').empty();

                                            }
                                        });
                                    });
                                </script>

                              <br>
                              <div class="row {{ $errors->has('entitas_id') ? ' has-error' : '' }}">
                                <div class="col-md-4"> <label> Nama Entitas </label></div>
                                <div class="col-md-6"> 
                                  <select name="entitas_id" class="form-control" required>
                                    
                                    <?php foreach($entitas as $key): ?>
                                    <option value="{{ $key->id }}">{{$key->name}} </option>
                                  <?php endforeach ?>
                                  </select>
                                      @if($errors->has('entitas_id'))
                                        <span class="help-block">
                                          <strong>{{ $errors->first('entitas_id') }}</strong>
                                        </span>
                                      @endif
                                </div>
                              </div>
                              <br>
                                <div class="row {{ $errors->has('name') ? ' has-error' : '' }}">
                                <div class="col-md-4"><label> Nama Unit </label> </div>
                                <div class="col-md-6"> <input class="form-control" type="text" value="{{ old('name') }}" name="name" required>
                                    @if($errors->has('name'))
                                      <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                      </span>
                                    @endif
                                </div>
                                </div>
                              <br>
                                <div class=" col-xs-12 col-md-offset-4">
                                  <button class="btn btn-primary" type="submit">Tambah</button>
                                  <a href="daftar"><button  class="btn btn-default" >Kembali</a>                         
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
