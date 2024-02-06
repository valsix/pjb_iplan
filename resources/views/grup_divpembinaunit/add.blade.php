@extends('layouts.app')
@section('content')
<h3>{{is_null($grupdiv) ? 'Tambah Grup Baru' : 'Edit Grup'}}</h3>
<form action="{{(is_null($grupdiv))?route('admin.grupdiv.add.action'):route('admin.grupdiv.edit.action', ['id'=>$grupdiv->id])}}" method="POST">
    {{csrf_field()}}
    <div class="row">
        <div class="col-md-6">
            <!-- <div class="box box-primary"> -->
                <div class="box-header with-border"><h3 class="box-title">Nama Grup</h3></div>
                <!-- <div class="body box-body"> -->
                    <div class="form-group">
                        <label>Nama Grup*</label>
                        <input name="nama" type="text" class="form-control" placeholder="Nama Group" value="{{(is_null($grupdiv))?'':$grupdiv->display_name}}" required >
                        {!!$errors->first('display_name', '<label class="control-label has-error">:message</label>')!!}
                    </div>
                    <div class="form-group">
                        <label>alias*</label>
                        <input name="alias" type="text" class="form-control" placeholder="Alias" value="{{(is_null($grupdiv))?'':$grupdiv->name}}" required >
                        {!!$errors->first('name', '<label class="control-label has-error">:message</label>')!!}
                    </div>
                    <div class="form-group">
                        <label>Deskripsi Grup</label>
                        <input name="deskripsi" type="text" class="form-control" placeholder="Deskripsi Grup" value="{{(is_null($grupdiv))?'':$grupdiv->description}}">
                        {!!$errors->first('description', '<label class="control-label has-error">:message</label>')!!}
                    </div>
                    
                <!-- </div> -->
            <!-- </div> -->
            @if(!is_null($users))
                <div class="box-header with-border">
                  <h3 class="box-title">Daftar User</h3>
                </div>
                <span class="help_block">Sesuai dengan Distrik terpilih.</a></span>
                <!-- <div class="body box-body"> -->
                <div class="row">
                    <div class="col-md-12">
                        @if(count($users)>0)
                        <table id="fasyankes_list" class="table table-striped table-responsive table-bordered dataTable" >
                            <thead>
                                <tr>
                                    <th style="width:2%">No</th>
                                    <th>Nama User</th>
                                    <!-- <th>Pangkat</th> -->
                                    <!-- <th>Status</th> -->
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="filterable">
                                <?php $i = 1;?>
                                @foreach($users as $item)
                                <tr class="filterable">
                                    <td class="text-center">{{$i++}}</td>
                                    <td class="text-left" >{{$item->name}}</td>
                                    <td class="text-center">
                                         <a class="btn btn-xs btn-success" data-toggle="tooltip" target="_blank" title="View User" href="{{route('admin.user.view.view', ['id' => $item->id])}}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                            Grup ini belum memiliki user terkait
                        @endif
                    </div>
                </div>
                <!-- </div> -->
                @endif
        </div>
        <div class="col-md-6">
            <!-- <div class="box box-primary"> -->
                
                <div class="box-header with-border"><h3 class="box-title">Tambah Distrik</h3></div>
                <!-- <div class="body box-body"> -->
                    <div class="form-group">
                        <label>Struktur Bisnis*</label>
                        <div class="row">
                            <div class="col-md-10">
                                <select class="form-control strategi_bisnis_values" name="strategi_bisnis">
                                   <option selected="" disabled="" value="">-- Pilih Struktur Bisnis --</option>
                                    @foreach ($Sb as $sbs => $value)
                                     <option value="{{ $value->id }}"  data-sb-id="{{$value->name}}"> {{ $value->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- <div class="col-md-2"><a href="#" class="btn btn-success add_distrik"><i class="fa fa-plus"></i></a></div> -->
                        </div>


                        <label>Nama Distrik*</label>
                        <div class="row">
                            <div class="col-md-10">
                                 <select class="form-control distrik_values" id="fasyankesName" name="distrik">
                                     <option value=""></option>
                                     
                                 </select>
                                 <span class="help_block">Link Detail Keterangan <a target="_blank" href="{{route('distrik.daftar')}}">Distrik</a></span>
                            </div>
                            <!-- <div class="col-md-2"><a href="#" class="btn btn-success add_distrik"><i class="fa fa-plus"></i></a></div> -->
                        </div>
                        <script type="text/javascript">
                            $(document).ready(function() {
                                $('select[name="distrik"]').on('change', function() {
                                    var distrikID = $(this).val();
                                    $('select[name="jenis_pembangkit"]').empty();
                                    // $('select[name="lokasi"]').empty();

                                    if(distrikID) {
                                        $.ajax({
                                            // url: '/kkp/daftar/ajax/'+distrikID,
                                            url: "{{ url('/grup_divpembinaunit/jenpembydistrik/') }}/"+distrikID,
                                            type: "GET",
                                            dataType: "json",
                                            success:function(data) {
                                        // console.log(data);
                                              $('select[name="jenis_pembangkit"]').empty();
                                              $('select[name="jenis_pembangkit"]').append('<option value="">-- Pilih Jenis Pembangkit --</option>');
                                              $.each(data, function(sb, value) {
                                                  $('select[name="jenis_pembangkit"]').append('<option value="'+value["id"]+'" data-name="'+value["name"]+'">'+ value["name"] +'</option>');
                                              });

                                            }
                                        });
                                    }else{
                                        $('select[name="jenis_pembangkit"]').empty();
                                    }
                                });
                            });
                        </script>

                        <div class="row">
                            <div class="col-md-10">
                                 <select class="form-control jenpem_values" id="fasyankesName" name="jenis_pembangkit">
                                     <option value=""></option>

                                 </select>
                                 <!-- <span class="help_block">Link Detail Keterangan <a target="_blank" href="{{route('distrik.daftar')}}">Distrik</a></span> -->
                            </div>
                            <div class="col-md-2"><a href="#" class="btn btn-success add_distrik"><i class="fa fa-plus"></i></a></div>
                        </div>
                       
                    </div>
                    <div class="form-group" id="semua_menu_akses">
                        <label>Distrik</label>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Distrik</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="body_value_menu_akses">
                                @if($grupdiv_distrik != NULL)
                                    @foreach($grupdiv_distrik as $row)
                                    <tr>
                                        <td width="93%">
                                            <input class="form-control hidden" name="menu_akses[]" desabled value="{{$row->id}}-{{$row->jenpemid}}" /> 
                                            <span><b>{{$row->name}}</b></span><br>
                                            <span class="help-block"><b>Strategi Bisnis : </b>{{$row->sb_name}}</span>
                                            <span class="help-block"><b>Distrik Code : </b>{{$row->code1}}</span>
                                            <span class="help-block"><b>Jenis Pembangkit : </b>{{$row->jenpem_name}}</span>
                                        </td>
                                        <td width="7%"><a href="#" onClick="return confirm('Apakah Anda yakin untuk menghapus data Distrik {{$row->name}}')" id="close_add" class="btn btn-danger"><i class="fa fa-times" /></i></a></td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    
                <!-- </div> -->
            <!-- </div> -->
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-save"></i>&nbsp;Simpan</button>
            <a href="{{ url('/grup_divpembinaunit/manage') }}" class="btn btn-default pull-right" type="reset">Kembali</a>
        </div>
    </div>
</form>

<script src="{{ asset('js/jquery-1.11.2.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/datepicker/bootstrap-datepicker.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/jquery-ui.min.js') }}" type="text/javascript"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('select[name="strategi_bisnis"]').on('change', function() {
            var strategi_bisnisID = $(this).val();
            $('select[name="distrik"]').empty();

            if(strategi_bisnisID) {
                $.ajax({
                    // url: '/dmr/daftar/ajax/'+strategi_bisnisID,
                    url: "{{ url('/dmr/daftar/ajax/') }}/"+strategi_bisnisID,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {
                // console.log(data);
                      $('select[name="distrik"]').empty();
                      $('select[name="distrik"]').append('<option value="">-- Pilih Distrik --</option>');
                      $.each(data, function(sb, value) {
                          $('select[name="distrik"]').append('<option value="'+ value["id"] +'" data-code="'+value["code1"]+'" data-name="'+value["name"]+'">'+ value["name"] +'</option>');
                      });

                    }
                });
            }else{
                $('select[name="distrik"]').empty();
            }
        });
    });

    var rowCount = $('.body_value_menu_akses tr').length;
    if(rowCount === 0){
        $("#semua_menu_akses").hide('hide');
    }

    var birthdateInput = $( "#birth-date" ).datepicker({
            clearButton: true,
            dateFormat: "dd/mm/yy",
            autoclose: true
    });

    $(".add_distrik").click(function (e) {
        $("#semua_menu_akses").show('hide');

        e.preventDefault();

        var distrik_id = $(".distrik_values").val();
        var strategi_bisnis = $(".strategi_bisnis_values option:selected").attr('data-sb-id');
        var distrik_code = $(".distrik_values option:selected").attr('data-code');
        var distrik_name = $(".distrik_values option:selected").attr('data-name');

        var jenpem_id = $(".jenpem_values").val();
        var jenpem_name = ''

        if (jenpem_id=='null') 
        {
            jenpem_id= '';
        }
        else
        {
            jenpem_name = $(".jenpem_values option:selected").attr('data-name');
        }

        $(".body_value_menu_akses").append('<tr>'+
                                    '<td width="93%">'+
                                    '<span><b>'+ distrik_name +'</b></span><br>'+
                                    '<input class="hidden" type="text" value="'+distrik_id+'-'+jenpem_id+'" name="menu_akses[]" />'+
                                    '<span class="help-block"><b>Strategi Bisnis : </b>'+ strategi_bisnis +'</span>'+
                                    '<span class="help-block"><b>Distrik Code : </b>'+ distrik_code +'</span>'+
                                    '<span class="help-block"><b>Jenis Pembangkit : </b>'+ jenpem_name +'</span>'+
                                    '</td>'+
                                    '<td width="7%"><a href="#" onClick="return confirm(\'Apakah Anda yakin untuk menghapus data Distrik '+ distrik_name +'  ?\')" id="close_add" class="btn btn-danger"><i class="fa fa-times" /></i></a></td>'+
                                '</tr>');
        //$(".action_role_btn").show('hidden');

    });

    // DELETEING FUNCTION JAVASCRIPT
    $(document).on('click', '#close_add' ,function (e) {
        e.preventDefault();
        $(this).parent().parent().remove();

        var rowCount = $('.body_value_menu_akses tr').length;
        if(rowCount === 0){
            $("#semua_menu_akses").hide('hide');
        }
    });

</script>
@stop
