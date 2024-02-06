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

    <form method="post" role="form">

    <div class="page-title">
      <div class="title_left">
        <h3>Tambah Distrik</h3>
        <div class="panel-default">
          <br>
          <div>
            <div class="col-lg-13">
              <!-- <form method="post" role="form"> -->
              {{ csrf_field() }}
              <div class="row {{ $errors->has('strategi_bisnis_id') ? ' has-error' : '' }}"> 
              <div class="col-md-4"> <label>Strategi Bisnis </label> </div>
              <div class="col-md-6">
              <select class="form-control" name="strategi_bisnis_id">
                <?php foreach ($strategi_bisnis as $key): ?> 
                  <option value="{{ $key->id }}"> {{ $key->name }} </option>
                    <?php endforeach ?>
              </select>
                  @if($errors->has('strategi_bisnis_id'))
                      <span class="help-block">
                        <strong>{{ $errors->first('strategi_bisnis_id') }}</strong>
                      </span>
                  @endif
              </div>

              </div>
              <br>
                <div class="row {{ $errors->has('kode_distrik') ? ' has-error' : '' }}">
                <div class="col-md-4">
                <label> Kode Distrik </label> </div>
                <div class="col-md-6"> <input class="form-control" type="text" value="{{ old('kode_distrik') }}" name="kode_distrik" required="required">
                    @if($errors->has('kode_distrik'))
                      <span class="help-block">
                        <strong>{{ $errors->first('kode_distrik') }}</strong>
                      </span>
                    @endif
                </div>
                </div>
              <br>
                <div class="row {{ $errors->has('name') ? ' has-error' : '' }}">
                <div class="col-md-4">
                <label> Nama Distrik </label> </div>
                <div class="col-md-6"> <input class="form-control" type="text" value="{{ old('name') }}" name="name" required="required">
                    @if($errors->has('name'))
                      <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                      </span>
                    @endif
                </div>
                </div>
              <br>
                <!-- <div class=" col-xs-12 col-md-offset-4"> -->
                  <!-- <button class="btn btn-primary" type="button">Tambah</button> -->
                  <!-- sementara utk demo diganti <a> -->
                  <!-- <button class="btn btn-primary" type="submit">Tambah</button> -->
                  <!-- <a href="daftar" class="btn btn-default" >Kembali</a>                           -->
                <!-- </div> -->
                <!-- </form> -->
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6">
        <div class="box-header with-border"><h3 class="box-title">Tambah Jenis Pembangkit</h3></div>
        <!-- <div class="body box-body"> -->
        <div class="form-group">
            <label>Nama Jenis Pembangkit*</label>
            <div class="row">
                <div class="col-md-10">
                     <select class="form-control jenpem_values" id="fasyankesName">
                         <option value=""></option>
                         @foreach($jenis_pembangkit as $row)
                            <option value="{{$row->id}}" data-name="{{$row->name}}">{{$row->name}}</option>
                         @endforeach
                     </select>
                     <span class="help_block">Link Detail Keterangan <a target="_blank" href="{{route('jenis_pembangkit.daftar')}}">Jenis Pembangkit</a></span>
                </div>
                <div class="col-md-2"><a href="#" class="btn btn-success add_jenpem"><i class="fa fa-plus"></i></a></div>
            </div>
        </div>
        <div class="form-group" id="semua_jenpem">
            <label>Jenis Pembangkit</label>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama Jenis Pembangkit</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="body_value_jenpem">
                    @if($distrik_jenpem != NULL)
                        @foreach($distrik_jenpem as $row)
                        <tr>
                            <td width="93%">
                                <input class="form-control hidden" name="jenpem[]" desabled value="{{$row->id}}" /> 
                                <span><b>{{$row->name}}</b></span><br>
                            </td>
                            <td width="7%"><a href="#" onClick="return confirm('Apakah Anda yakin untuk menghapus data Distrik {{$row->name}}')" id="close_add" class="btn btn-danger"><i class="fa fa-times" /></i></a></td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
      </div>

      <!-- <div class=" col-xs-12 col-md-offset-4"> -->
      <div class="col-md-12">
        <!-- <button class="btn btn-primary" type="button">Simpan</button> -->
        <!-- sementara utk demo diganti <a> -->
        <button class="btn btn-primary pull-right" type="submit">Simpan </button>
        <a href="{{ url('distrik/daftar') }}" class="btn btn-default pull-right" >Kembali</a>
      </div>

    </form>

  </div>
</div>

<script src="{{ asset('js/jquery-1.11.2.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/datepicker/bootstrap-datepicker.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/jquery-ui.min.js') }}" type="text/javascript"></script>

<script type="text/javascript">

    var rowCount = $('.body_value_jenpem tr').length;
    if(rowCount === 0){
        $("#semua_jenpem").hide('hide');
    }

    var birthdateInput = $( "#birth-date" ).datepicker({
            clearButton: true,
            dateFormat: "dd/mm/yy",
            autoclose: true
    });

    $(".add_jenpem").click(function (e) {
        $("#semua_jenpem").show('hide');

        e.preventDefault();

        var jenpem_id = $(".jenpem_values").val();
        var jenpem_name = $(".jenpem_values option:selected").attr('data-name');

        $(".body_value_jenpem").append('<tr>'+
                                    '<td width="93%"><span><b>'+ jenpem_name +'</b></span><br><input class="hidden" type="text" value="'+ jenpem_id+'" name="jenpem[]" /></td>'+
                                    '<td width="7%"><a href="#" onClick="return confirm(\'Apakah Anda yakin untuk menghapus data Jenis Pembangkit '+ jenpem_name +'  ?\')" id="close_add" class="btn btn-danger"><i class="fa fa-times" /></i></a></td>'+
                                '</tr>');
        //$(".action_role_btn").show('hidden');

    });

    // DELETEING FUNCTION JAVASCRIPT
    $(document).on('click', '#close_add' ,function (e) {
        e.preventDefault();
        $(this).parent().parent().remove();

        var rowCount = $('.body_value_jenpem tr').length;
        if(rowCount === 0){
            $("#semua_jenpem").hide('hide');
        }
    });

</script>
@endsection