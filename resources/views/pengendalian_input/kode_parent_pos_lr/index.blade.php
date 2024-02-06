@extends('layouts.app')
@section('css_page')
<link href="{{ asset('css/sweetalert2.min.css') }}" rel="stylesheet">
    <style type="text/css">
        .table-container
        {
            width: 100%;
            overflow-x: auto;
            overflow: auto;
            margin: 0 0 1em;
        }
        thead th{
            text-align: center;
        }

        /Update line height & font-size/
        .table thead tr th{
          line-height: 1;
        }
        .table tbody tr td{
          /Untuk data yang deskripsi panjang/
          line-height: 1; 

          /Untuk data yang tidak ada deskripsi panjang/
          /*line-height: 0.5; */
        }
        .table {
          font-size: 11px;
        }

        .form-horizontal .form-group
        {
            margin-right: 0;
            margin-left: 0;
            margin-top: -13px;
        }

    </style>

@endsection
@section('content')
<div class="row " >
    <div class="col-md-10 col-sm-10 col-xs-10">
        <div class="x_panel" style="margin-left:75px;">
            <div class="x_title">
              <h2 style="font-size: 18px;">SETTING KODE PARENT LR</h2>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <div class="col-md-12 bg-info" style="margin-bottom:7px;">
                 <p style="margin-top:7px;"><span class="glyphicon glyphicon-info-sign"></span><b> Double click input readonly jika ingin melakukan edit</b></p>
              </div>
              <table id="tabel" class="table table-striped table-bordered table-hover">
                <thead style="background:#2A3F54;color:white;">
                  <th colspan="1" rowspan="1">Keterangan </th>
                  <th colspan="1" rowspan="1" style="width:200px;">Kode Parent UP </th>
                  <th colspan="1" rowspan="1" style="width:200px;">Kode Parent OM </th>
                </thead>
                <tbody>
                @foreach($lrc as $lr)
                  @if($lr->kode_parent_up == '#' && $lr->kode_parent_om == '#')
                  <tr style="background-color:#1A5B6F ; color:white" role="row" >
                      <td colspan="3" style="font-size:15px; padding-top:15px;">{{$lr->keterangan}}</td>
                  </tr>
                  @else
                  <tr style="background-color:#ffffff ; color:black" role="row" >
                      <td style="font-size:15px; padding-top:15px;">{{$lr->keterangan}}</td>
                      <input type="hidden" name="id[]" value="{{$lr->id}}">
                      <td> <input type="text" readonly="true" ondblclick="this.readOnly='';" value="{{$lr->kode_parent_up}}" name="up[]" class="form-control" style="width:200px;"></td>
                      <td> <input type="text" readonly="true" ondblclick="this.readOnly='';" value="{{$lr->kode_parent_om}}" name="om[]" class="form-control" style="width:200px;"></td>
                  </tr>
                  @endif
                  
                @endforeach
                </tbody>
              </table>
              <br>
              <button type="button" id="simpan" class="btn btn-primary pull-center" style="margin-left:500px;">Simpan</button>
            </div>
        </div>
    </div>
</div>

@endsection
@section('js_page')
<script src="{{ asset('js/sweetalert2.all.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/sweetalert2.min.js') }}" type="text/javascript"></script>

<script type="text/javascript">
  $(document).ready(function(){
    $('#simpan').click(function(e){
      e.preventDefault();
      var up = [];
      var om = [];
      var id = [];
      
      $('input[name="id[]"]').each(function() {
        id.push($(this).val());
		  });
      $('input[name="up[]"]').each(function() {
        up.push($(this).val());
		  });
      $('input[name="om[]"]').each(function() {
        om.push($(this).val());
		  });

      console.log(id,up,om);
      var data={
                   _token: '{!! csrf_token() !!}',
                   id:id,
                   up:up,
                   om:om,
                };
      $.ajax({
                type: 'POST',
                url: "{{url('/pengendalian/input_kode_parent_pos_lr')}}",
                data: data,
                success: function(data)
                {
                  $('table tr').each(function() {
                    $(this).find('td').each(function() {
                        $(this).find('input').attr("readonly", true);
                    });
                  });
                      const toast = swal.mixin({
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 2500
                      });

                      toast({
                        type: 'success',
                        title: 'Setting Kode LR  Berhasil Disimpan'
                      });
                },
                error: function(error)
                {
                  console.log(error.status);
                }
            });

    });


  });


</script>
@endsection