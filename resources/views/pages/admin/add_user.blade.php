@extends('layouts.app')
@section('content')
<h3>{{is_null($user) ? 'Tambah Akun Baru' : 'Edit Akun'}}</h3>
@include('partials.validation-error-message')
@include('partials.has-info-message')
<div role="main">
<form action="{{(is_null($user))?route('admin.user.add.action'):route('admin.user.edit.action', ['id'=>$user->id])}}" method="POST">
    {{csrf_field()}}
    <div class="row">
    <div class="x_content">
        <div class="col-md-4">
        <!-- Profile Image -->
          <!-- <div class="box box-primary"> -->
            <!-- <div class="box-body box-profile"> -->
              {{-- <img class="profile-user-img img-responsive img-circle" src="../../dist/img/user4-128x128.jpg" alt="User profile picture"> --}}
              <center><img src="{{(is_null($user))? asset('images/user.png') : 'http://ellipse.ptpjb.com/profiles/photo.do?uid='.$user->username }}" class="profile-user-img img-responsive img-circle" alt="User Image"></center>
              <h3 class="profile-username text-center">{{(is_null($user))?'':$user->name}}</h3>

              <p class="text-muted text-center">{{(is_null($user))?'':$user->nip}}</p>

              <!-- declare id user -->
              <input type="text" value="{{(is_null($user))?'':$user->id}}" id="user_id_value" class="hidden" />
              <!--  -->

                @if($user_internal)
                    <div class="form-group">
                        <table class="table table-bordered">
                            <tr>
                                <td width="90%">
                                    <select class="form-control usersin_values" id="fasyankesName" >
                                        @foreach ($user_internal as $userss)
                                            <option value="{{$userss->id}}" data-nid="{{$userss->nid}}" data-nama="{{$userss->nama_lengkap}}" data-email="{{$userss->email}}" data-positionid="{{$userss->position_id}}" data-positionnama="{{$userss->nama_posisi}}" >{{$userss->nama_lengkap ." - ". $userss->nama_posisi}}</option>
                                        @endforeach
                                    </select>
                                    <!-- <span class="help_block">Link Detail Keterangan <a target="_blank" href="{{route('admin.role.list')}}">Data Group</a></span> -->
                                </td>
                                <td width="3%">
                                    <a href="#" class="btn btn-success add_usersin"><i class="fa fa-plus"></i></a>
                                </td>
                            </tr>
                        </table>
                    </div>
                @endif

                <div class="form-group">
                    <label>Username*</label>
                    <input name="username" type="text" class="form-control" placeholder="Username" value="{{(is_null($user))?old('username'):$user->username}}" onkeydown="upperCaseF(this)" required>
                </div>

                <script type="text/javascript">
                  function upperCaseF(a){
                    setTimeout(function(){
                      a.value = a.value.toUpperCase();
                    }, 1);
                  }
                </script>

                <div class="form-group">
                    <label>Password*</label>
                    <input name="password" type="password" class="form-control password" placeholder="Password" value="" <?php if(is_null($user)) echo 'required'; ?>>
                </div>
                <div class="form-group">
                    <label>ReType Password*</label>
                    <input name="re-password" type="password" class="form-control re-password" placeholder="ReType Password" value="" <?php if(is_null($user)) echo 'required'; ?>>
                    <div id="peringatan_password"></div>
                </div>
                <div class="form-group">
                    <label>Group Saat ini : </label><br>
                    @if($current_roles != NULL)
                        @foreach ($current_roles as $roless)
                            <span class="label label-success">{{$roless->display_name}}</span>
                        @endforeach
                    @else
                        <span>tidak memiliki grup</span>
                    @endif
                </div>
                <div class="form-group">
                    <label>Tambah Group : </label><br>
                        <table class="table table-bordered">
                            <tr>
                                <td width="90%">
                                    <select class="form-control roles_values" id="fasyankesName" >
                                        @foreach ($roles as $role)
                                            <option value="{{$role->id}}" data-id="{{$role->display_name}}" {{(is_null($user)?'':($user->group->data->id==$role->id)?'selected':'')}}>{{$role->display_name}}</option>
                                        @endforeach
                                    </select>
                                    <span class="help_block">Link Detail Keterangan <a target="_blank" href="{{route('admin.role.list')}}">Data Group</a></span>
                                </td>
                                <td width="3%">
                                    <a href="#" class="btn btn-success add_role"><i class="fa fa-plus"></i></a>
                                </td>
                            </tr>
                        </table>

                    <hr>
                    <div class="table_append_roles">
						<table class="table table-bordered table_roles">
						@if($current_roles != NULL)
							@foreach ($current_roles as $roless)
								<tr>
									<td width="97%"><span>{{$roless->display_name}}</span><input class="hidden" type="text" value="{{$roless->id}}" name="posisi[]" /></td>
									<td width="3%"><a data-id="{{$roless->id}}" onClick="return confirm('Apakah Anda yakin untuk menghapus data Grup {{$roless->display_name}} ?')" href="{{route('admin.user.role.delete', ['code' => $user->id , 'code_role' => $roless->id])}}" class="btn btn-danger"><i class="fa fa-times" /></i></a></td>
								</tr>
							@endforeach
						@endif
						</table>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-12 pull-right">
                        <a class="btn btn-warning action_password">update password</a>
                        <a class="btn btn-danger action_role_btn">update group</a>
                    </div>
                </div>

              {{-- <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a> --}}
            <!-- </div> -->
            <!-- /.box-body -->
          <!-- </div> -->
          <!-- /.box -->
    </div>



    <div class="col-md-8">
        <!-- <div class="box box-primary"> -->
            <div class="box-header with-border"><h3 class="box-title">Informasi Pribadi</h3></div>
            <!-- <div class="body box-body"> -->
				@if(!is_null($user))
				<div class="form-group">
                    <label>Status User</label>
                    <select class="form-control" name="status">
                      <option value="1" {{(is_null($user))?'':($user->status == '1')?'selected':''}}>Aktif</option>
                      <option value="0" {{(is_null($user))?'':($user->status == '0')?'selected':''}}>Tidak aktif</option>
                    </select>
                </div>
				@endif
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input name="nama" type="text" class="form-control" placeholder="Contoh: Ahmad Fauzi" value="{{(is_null($user))?old('nama'):$user->name}}">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input name="email" type="email" class="form-control" placeholder="Contoh: admin@gmail.com" value="{{(is_null($user))?old('email'):$user->email}}" id="email_input">
                </div>
                 <div class="form-group">
                    <label> Strategi Bisnis* </label>
                            <div class="row {{ $errors->has('strategi_bisnis') ? ' has-error' : '' }}">
                              {{ csrf_field() }}
                              <div class="col-md-12">
                                 <select class="form-control" name="strategi_bisnis">
                                     <option></option>
                                      @foreach ($Sb as $sbs => $value)
                                         <option <?php
                                         if(!is_null($user)) {
                                         if ($strategi_bisnis_id->id == $value->id ){ ?>
                                              selected
                                         <?php }} ?> value="{{ $value->id }}"> {{ $value->name }} </option>
                                       @endforeach
                                  </select>
                                  @if($errors->has('strategi_bisnis'))
                                      <span class="help-block">
                                        <strong>{{ $errors->first('strategi_bisnis') }}</strong>
                                      </span>
                                  @endif
                              </div>

                              </div>
                              <br>
                            <label> Distrik* </label>

                              <div class="row {{ $errors->has('distrik') ? ' has-error' : '' }}">

                                <div class="col-md-12">
                                  <select name="distrik_id" class="form-control" required>
                                    @foreach ($distrik as $sbs => $value)
                                         <option <?php
                                         if(!is_null($user)) {
                                         if ($user->distrik_id == $value->id ){ ?>
                                              selected
                                         <?php }} ?> value="{{ $value->id }}"> {{ $value->name }} </option>
                                       @endforeach
                                  </select>
                                      @if($errors->has('distrik_id'))
                                        <span class="help-block">
                                          <strong>{{ $errors->first('distrik') }}</strong>
                                        </span>
                                      @endif
                                </div>
                              </div>


                                <!-- <script type="text/javascript">
                                    $(document).ready(function() {
                                        
                                    });
                                </script> -->

                                @if($bagian)
                                    <br>
                                    <label id="bidang_divisi_idlabel"> Bagian* </label>
                                    <div class="row {{ $errors->has('bidang_divisi_id') ? ' has-error' : '' }}">
                                        <div class="col-md-12">
                                          <select name="bidang_divisi_id" class="form-control" required>
                                            @foreach ($bagian as $bag => $value)
                                                <option <?php
                                                if(!is_null($user)) {
                                                if ($user->bidang_divisi_id == $value->id ){ ?>
                                                    selected
                                                <?php }} ?> value="{{ $value->id }}"> {{ $value->name }} </option>
                                            @endforeach
                                          </select>
                                              @if($errors->has('bidang_divisi_id'))
                                                <span class="help-block">
                                                  <strong>{{ $errors->first('bidang_divisi_id') }}</strong>
                                                </span>
                                              @endif
                                        </div>
                                    </div>
                                @endif
                              

                              <br>
                                <label> Jabatan* </label>
                                <div class="row {{ $errors->has('jabatan') ? ' has-error' : '' }}">
                                    <div class="col-md-12">
                                        <input type="hidden" name="position_id" value="{{$position_id}}">
                                        <input name="jabatan" type="text" class="form-control" value="{{$jabatan}}" disabled>
                                          @if($errors->has('jabatan'))
                                            <span class="help-block">
                                              <strong>{{ $errors->first('jabatan') }}</strong>
                                            </span>
                                          @endif
                                    </div>
                                </div>

                              <br>
                              <br>

                            </div>
                <div class="form-group">
                    <label>Tambah Group Divisi Pembina Unit : </label><br>
                        <table class="table table-bordered">
                            <tr>
                                <td width="90%">
                                    <select class="form-control grupdiv_values" id="fasyankesName" >
                                        @foreach ($grupdiv as $role)
                                            <option value="{{$role->id}}" data-id="{{$role->display_name}}" {{(is_null($user)?'':($user->group->data->id==$role->id)?'selected':'')}}>{{$role->display_name}}</option>
                                        @endforeach
                                    </select>
                                    <span class="help_block">Link Detail Keterangan <a target="_blank" href="{{route('admin.grupdiv.list')}}">Data Group Divisi Pembina Unit</a></span>
                                </td>
                                <td width="3%">
                                    <a href="#" class="btn btn-success add_grupdiv"><i class="fa fa-plus"></i></a>
                                </td>
                            </tr>
                        </table>

                    <hr>
                    <div class="table_append_grupdiv">
                        <table class="table table-bordered table_grupdiv">
                        @if($current_grupdiv != NULL)
                            @foreach ($current_grupdiv as $grupdivs)
                                <tr>
                                    <td width="97%"><span>{{$grupdivs->display_name}}</span><input class="hidden" type="text" value="{{$grupdivs->id}}" name="grupdiv[]" /></td>
                                    <td width="3%"><a data-id="{{$grupdivs->id}}" onClick="return confirm('Apakah Anda yakin untuk menghapus data Grup {{$grupdivs->display_name}} ?')" href="{{route('admin.user.grupdiv.delete', ['code' => $user->id , 'code_role' => $grupdivs->id])}}" class="btn btn-danger"><i class="fa fa-times" /></i></a></td>
                                </tr>
                            @endforeach
                        @endif
                        </table>
                    </div>

                </div>
                <!-- <div class="form-group">
                    <label>NIP*</label>
                    <input name="nip" type="text" class="form-control" placeholder="Contoh: 198305232008121001" value="{{(is_null($user))?old('nip'):$user->nip}}" onkeypress="return angka()" required>
                </div>
                <div class="form-group">
                    <label>Tempat Lahir*</label>
                    <input name="tempat_lahir" type="text" class="form-control" placeholder="Contoh: Malang" value="{{(is_null($user))?old('tempat_lahir'):$user->tempat_lahir}}" onkeypress="return huruf_dan_spasi()" required>
                </div>
                <div class="form-group">
                    <label>Tanggal Lahir*</label>
                    <input name="tanggal_lahir" type="text" class="form-control" id="birth-date" placeholder="Contoh: 03/11/1993" value="{{(is_null($user))?old('tanggal_lahir'):date_format(date_create($user->tanggal_lahir),'d/m/Y')}}" required>
                </div>
                <div class="form-group">
                    <label>Pangkat Golongan</label>
					<?php $golongan = ['I/a','I/b','I/c','I/d','II/a','II/b','II/c','II/d','III/a','III/b','III/c','III/d','IV/a','IV/b','IV/c','IV/d','IV/e']; ?>
                    <select class="form-control" name="golongan">
                        <option value="-" {{(is_null($user))?((old('golongan') != null) ? ((old('golongan') == '-') ? 'selected' : '') : ''):($user->pangkat_golongan == '-')?'selected':''}}>Pilih Golongan</option>
						@foreach($golongan as $gol)
                        <option value="{{$gol}}" {{(is_null($user))?((old('golongan') != null) ? ((old('golongan') == $gol) ? 'selected' : '') : ''):($user->pangkat_golongan == $gol)?'selected':''}}>{{$gol}}</option>
                        @endforeach
                    </select>
                </div>
				<div class="form-group">
                    <label>Pangkat TMT</label>
                    <input name="pangkat_tmt" type="text" class="form-control" id="tmt-date" placeholder="Pangkat TMT" value="{{(is_null($user)) ? ((old('pangkat_tmt') != null) ? old('pangkat_tmt') : date('d/m/Y')) : date_format(date_create($user->pangkat_tmt),'d/m/Y')}}">
                </div>
				<div class="form-group">
                    <label>Jenjang Pendidikan Terakhir</label>
                    <input name="ijazah" type="text" class="form-control" placeholder="Contoh: S1" value="{{(is_null($user))?old('ijazah'):$user->pendidikan_ijazah}}" onkeypress="return angka_huruf_dan_spasi()">
                </div>
                <div class="form-group">
                    <label>Jurusan Pendidikan</label>
                    <input name="pendidikan" type="text" class="form-control" placeholder="Contoh: Teknik Informatika" value="{{(is_null($user))?old('pendidikan'):$user->pendidikan_jurusan}}" onkeypress="return huruf_dan_spasi()">
                </div>
                <div class="form-group">
                    <label>Lulus Tahun</label>
                    <input name="lulus" type="text" class="form-control" placeholder="Contoh: 2015" value="{{(is_null($user))?old('lulus'):$user->pendidikan_lulus}}" onkeypress="return angka()">
                </div> -->
            <!-- </div> -->
        <!-- </div> -->
    </div>

    </div>
    <!-- end of div x_content  -->
    </div>
    <div class="row" >
        <div class="col-md-12" >
            <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-save"></i>&nbsp;Simpan</button>
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
            $('select[name="distrik_id"]').empty();
            $('select[name="lokasi"]').empty();

            if(strategi_bisnisID) {
                $.ajax({
                    // url: '/lokasi/create/ajax/'+strategi_bisnisID,
                    url: "{{ url('/lokasi/create/ajax/') }}/"+strategi_bisnisID,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {
                // console.log(data);
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

        var distrik_id = $('select[name="distrik_id"]').val();

        if (distrik_id==28 || distrik_id==21) 
        {
            $('select[name="bidang_divisi_id"]').show();
            $('#bidang_divisi_idlabel').show();
        } 
        else 
        {
            $('select[name="bidang_divisi_id"]').hide();
            $('#bidang_divisi_idlabel').hide();
        }

        $('select[name="distrik_id"]').on('change', function() {
            var distrik_id = $(this).val();
            // console.log(distrik_id);return false;
            // $('select[name="bidang_divisi_id"]').empty();

            if (distrik_id==28 || distrik_id==21) 
            {
                $('select[name="bidang_divisi_id"]').show();
                $('#bidang_divisi_idlabel').show();
            } 
            else 
            {
                $('select[name="bidang_divisi_id"]').hide();
                $('#bidang_divisi_idlabel').hide();
            }

            // if(strategi_bisnisID) {
            //     $.ajax({
            //         // url: '/lokasi/create/ajax/'+strategi_bisnisID,
            //         url: "{{ url('/lokasi/create/ajax/') }}/"+strategi_bisnisID,
            //         type: "GET",
            //         dataType: "json",
            //         success:function(data) {
            //     // console.log(data);
            //           $('select[name="distrik_id"]').empty();
            //           $.each(data, function(sb, value) {
            //               $('select[name="distrik_id"]').append('<option value="'+ value["id"] +'">'+ value["name"] +'</option>');
            //           });

            //         }
            //     });
            // }else{
            //     $('select[name="distrik_id"]').empty();
            // }
        });
    });

	function angka() {
		// body...
		return event.charCode == 8 ||event.charCode >= 48 && event.charCode <= 57;
	}

	function angka_dan_huruf() {
		// body...
		return event.charCode == 8 ||  event.charCode == 8 ||event.charCode >= 48 && event.charCode <= 57 || event.charCode >= 65 && event.charCode <= 90 || event.charCode >= 97 && event.charCode <= 122
	}

	function angka_huruf_dan_spasi() {
		// body...
		return event.charCode == 8 || (event.charCode >= 48 && event.charCode <= 57) || (event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || event.keyCode == 0 || event.keyCode == 32;
	}

	function angka_huruf_spasi_dan_titik() {
		// body...
		return event.charCode == 8 || (event.charCode >= 48 && event.charCode <= 57) || (event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || event.keyCode == 0 || event.keyCode == 32 || event.keyCode == 190;
	}

	function angka_dan_karakter_bebas(){
		return event.charCode == 8 ||event.charCode <= 63;
	}

	function huruf_dan_spasi() {
		// body...
		return event.charCode == 8 ||  (event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || event.keyCode == 0 || event.keyCode == 32;
	}
    //initial pages

    var rowCount = $('.table_append_roles tr').length;
    if(rowCount === 0){
        $(".table_append_roles").hide('hidden');
    }

    $(".action_role_btn").hide('hidden');
    $(".action_password").hide('hidden');

    var birthdateInput = $( "#birth-date" ).datepicker({
            clearButton: true,
            dateFormat: "dd/mm/yy",
            autoclose: true
    });

	var pangkatTMTInput = $( "#tmt-date" ).datepicker({
            clearButton: true,
            dateFormat: "dd/mm/yy",
            autoclose: true
    });

    $(".re-password").keyup(function () {
        var value_password = $(".password").val();
        var value_re_type_password = $(".re-password").val();

        if(value_password === value_re_type_password){
            $("#peringatan_password").empty();
            $("#peringatan_password").append('<span style="color:green;">field password dan field retype password sudah sama</span>');
            //$(".action_password").show('hidden');
        }else{
            $("#peringatan_password").empty();
            $("#peringatan_password").append('<span style="color:red;">field password dan field retype password belum sama</span>');
            //$(".action_password").hide('hidden');
        }
    });

    $(".action_password").click(function () {
        alert("password berhasil di update");
        location.reload();
    });

    
    // add role button
    $(".add_grupdiv").click(function (e) {
        $(".table_append_grupdiv").show('hide');

        e.preventDefault();
        var grupdiv = $(".grupdiv_values option:selected").attr('data-id');
        var grupdiv_id = $(".grupdiv_values").val();

        $(".table_grupdiv").append('<tr>'+
                                    '<td width="93%"><input class="form-control" type="text" value="'+ grupdiv +'" disabled/><input class="hidden" type="text" value="'+ grupdiv_id+'" name="grupdiv[]" /></td>'+
                                    '<td width="7%"><a  data-id="'+grupdiv_id+'" href="#" id="close_add" class="btn btn-danger"><i class="fa fa-times" /></i></a></td>'+
                                '</tr>');

        //$(".action_role_btn").show('hidden');

    });

    // DELETING FUNCTION JAVASCRIPT
    $(document).on('click', '#close_add' ,function (e) {
        e.preventDefault();

        var answer = confirm('Apakah Anda yakin untuk menghapus data group user Anda?');
        if (answer) {
           $(this).parent().parent().remove();
        } else {
        }

        // var id_user = $("#user_id_value").val();
        // var id_role = $(this).attr('data-id');
        //sementara
        // var id_user = 1;
        // var id_role = 1;
        // var token = '{{csrf_token()}}';
        // console.log('hapus data user');

        // $(this).parent().parent().remove();

        // //alert(id_user);
        // if(id_user === ""){

        //     alert('data terhapus');
        // }else{
        //     //sementara

        // }

    });


    // add role button
    $(".add_role").click(function (e) {
        $(".table_append_roles").show('hide');

        e.preventDefault();
        var roles = $(".roles_values option:selected").attr('data-id');
        var roles_id = $(".roles_values").val();

        $(".table_roles").append('<tr>'+
                                    '<td width="93%"><input class="form-control" type="text" value="'+ roles +'" disabled/><input class="hidden" type="text" value="'+ roles_id+'" name="posisi[]" /></td>'+
                                    '<td width="7%"><a  data-id="'+roles_id+'" href="#" id="close_add_grupdiv" class="btn btn-danger"><i class="fa fa-times" /></i></a></td>'+
                                '</tr>');

        //$(".action_role_btn").show('hidden');

    });

    // DELETING FUNCTION JAVASCRIPT
    $(document).on('click', '#close_add_grupdiv' ,function (e) {
        e.preventDefault();

        var answer = confirm('Apakah Anda yakin untuk menghapus data group divisi pembina unit Anda?');
        if (answer) {
           $(this).parent().parent().remove();
        } else {
        }

        // var id_user = $("#user_id_value").val();
        // var id_role = $(this).attr('data-id');
        //sementara
        // var id_user = 1;
        // var id_role = 1;
        // var token = '{{csrf_token()}}';
        // console.log('hapus data user');

        // $(this).parent().parent().remove();

        // //alert(id_user);
        // if(id_user === ""){

        //     alert('data terhapus');
        // }else{
        //     //sementara

        // }

    });

    // add role button
    $(".add_usersin").click(function (e) {
        // $(".table_append_roles").show('hide');

        e.preventDefault();

        var nid = $(".usersin_values option:selected").attr('data-nid');
        var nama = $(".usersin_values option:selected").attr('data-nama');
        var email = $(".usersin_values option:selected").attr('data-email');
        var positionid = $(".usersin_values option:selected").attr('data-positionid');
        var positionnama = $(".usersin_values option:selected").attr('data-positionnama');
        var roles_id = $(".usersin_values").val();

        $("input[name='username']").val(nid);
        $("input[name='password']").val(nid);
        $("input[name='re-password']").val(nid);
        $("input[name='nama']").val(nama);
        $("input[name='email']").val(email);
        $("input[name='position_id']").val(positionid);
        $("input[name='jabatan']").val(positionnama);


        //$(".action_role_btn").show('hidden');

    });


</script>
@stop
