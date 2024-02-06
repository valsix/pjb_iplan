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
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Edit Lokasi</h3>
                @if(session('error'))
                    <div class="alert alert-error alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {{ session('error') }}
                    </div>
                @endif
               <div class="panel-default">
                          <br>
                          <div>
                            <div class="col-lg-13">

                            <form method="post" role="form"> 
                              <div class="row"> 
                              <div class="col-md-4"> 
                              <label> Strategi Bisnis </label> 
                              </div>
                              {{ csrf_field() }}
                              <div class="col-md-6">
                              <select name="strategi_bisnis" class="form-control" disabled="disabled">
                                <?php foreach($strategi_bisnis as $key): ?>
                                    <option value="{{ $key->id }}"
                                    <?php if ($key->id == $lokasi->distrik->strategi_bisnis->id) : ?>
                                      selected
                                    <?php endif ?>
                                    >{{$key->name}} 
                                    </option>
                                <?php endforeach ?>
                              </select>
                              </div>

                              </div>
                              
                              <br>
                              <div class="row">
                                <div class="col-md-4"> <label> Nama Distrik </label></div>
                                <div class="col-md-6"> 
                                  <select name="distrik_id" class="form-control" disabled="disabled">
                                    <?php foreach($distrik as $key): ?>
                                    <option value="{{ $key->id }}"
                                    <?php if ($key->id == $lokasi->distrik_id) : ?>
                                      selected
                                    <?php endif ?>
                                    >{{$key->name}} 
                                    </option>
                                  <?php endforeach ?>
                                  </select>
                                </div>
                              </div>
                             
                              <br>
                                <div class="row {{ $errors->has('name') ? ' has-error' : '' }}">
                                <div class="col-md-4"><label> Nama Lokasi </label> </div>
                                <div class="col-md-6"> <input class="form-control" value="{{ $lokasi['name']}}" type="text" name="name"  required>
                                  @if($errors->has('name'))
                                    <span class="help-block">
                                      <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                  @endif
                                </div>
                                </div>
                              <br>
                              <div class="row {{ $errors->has('min_uploaded_form') ? ' has-error' : '' }}">
                              <div class="col-md-4"> 
                                <label> Minimal Form di-Upload </label> 
                              </div>
                              <div class="col-md-6">
                                 <select class="form-control" name="min_uploaded_form" value = "">
                                     <option></option>
                                      @for($x = 1; $x <= 9; $x++)
                                          <option value="{{$x}}"<?php if ($lokasi['min_uploaded_form'] == $x) echo ' selected'; ?>> {{ $x }} </option>
                                      @endfor
                                  </select>
                                  @if($errors->has('min_uploaded_form'))
                                      <span class="help-block">
                                        <strong>{{ $errors->first('min_uploaded_form') }}</strong>
                                      </span>
                                  @endif
                              </div>
                              </div>
                              <br>
                              <div class="form-group">
                    <label>Tambah Form : </label><br>
                        <table class="table table-bordered">
                            <tr>
                                <td width="90%">
                                    <select class="form-control forms_values" id="fasyankesName" >
                                        @foreach ($jenis as $jen)
                                            <option value="{{$jen->id}}" data-id="{{$jen->name}}">{{$jen->name}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td width="3%">
                                    <a href="#" class="btn btn-success add_form"><i class="fa fa-plus"></i></a>
                                </td>
                            </tr>
                        </table>

                    <hr>
                    <div class="table_append_forms">
                        <table class="table table-bordered table_forms">
                          <script type="text/javascript">
                            var formslist = [];
                          </script>
                          @if($lokasijenis != NULL)
                            @foreach ($lokasijenis as $lj)
                              <script type="text/javascript">
                                var id = "<?php echo $lj->jenis_id; ?>";
                                formslist.push(id);
                              </script>
                              <tr class="jenis">
                                <td width="93%"><input class="form-control" type="text" value="{{$lj->name}}" disabled/><input type="hidden" value="{{$lj->jenis_id}}" name="formke[]" /></td>
                                <td width="7%"><a data-id="{{$lj->jenis_id}}" href="#" id="close_add" class="btn btn-danger"><i class="fa fa-times" /></i></a></td>
                              </tr>
                            @endforeach
                          @endif
                        </table>
                    </div>
                </div>
                       <div class=" col-xs-12 col-md-offset-4">
                          <button class="btn btn-primary" type="submit">Edit</button>
                          <a href="{{ url('/lokasi/daftar') }}"><button type="button" class="btn btn-default" >Kembali</button></a>                       
                        </div>
                        </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
<script type="text/javascript">

    var rowCount = $('.table_append_forms tr').length;
    if(rowCount === 0){
        $(".table_append_forms").hide('hidden');
    }

    $(".add_form").click(function (e) {
        $(".table_append_forms").show('hide');

        e.preventDefault();
        var forms = $(".forms_values option:selected").attr('data-id');
        var forms_id = $(".forms_values").val();

         if(!formslist.includes(forms_id))
          {
           formslist.push(forms_id);
          $(".table_forms").append('<tr>'+
                                      '<td width="93%"><input class="form-control" type="text" value="'+ forms +'" disabled/><input type="hidden" value="'+ forms_id+'" name="formke[]" /></td>'+
                                      '<td width="7%"><a data-id="'+forms_id+'" href="#" id="close_add" class="btn btn-danger"><i class="fa fa-times" /></i></a></td>'+
                                  '</tr>');
          }

    });
    
    $(document).on('click', '#close_add' ,function (e) {
        e.preventDefault();

        var answer = confirm('Apakah Anda yakin untuk menghapus form ini?');
        if (answer) {
           var $removeid = $(this).attr('data-id');
           var $indexrid = formslist.indexOf($removeid);
           formslist.splice($indexrid, 1);           
           $(this).parent().parent().remove();
        } else {
        }
    });




</script>
@endsection