@extends('layouts.app')
@section('content')
<h3>Tambah Menu Akses</h3>
<style>
.ui-sortable-handle { background: #2c3b41 }
.ui-sortable-placeholder { border: 1px dotted black; visibility: visible !important; background: #1E282C !important}
.disabled-menu {
  /*pointer-events: none;*/
}
.sub-treeview {
	list-style: none;
	margin-left: 0;
	background: #2C3B41;
	/*margin-right: 1px;*/
}
.sub-treeview > li {
  height: 40px;
  padding-top: 8px;
}
.sub-treeview > li > a {
  color: #8aa4af;
}
.sub-treeview > li > a > .fa{
  width: 20px;
}
.sub-treeview > li.active > a {
   color: yellow;
}
.sub-treeview > li > a:hover {
  color: #ffffff;
}

.main-treeview {
	list-style: none;
	margin-left: 0;
	background: #2C3B41;
	/*margin-right: 1px;*/
}
.main-treeview > li {
  height: 40px;
  padding-top: 8px;
}
.main-treeview > li > a {
  color: #8aa4af;
}
.main-treeview > li > a > .fa{
  width: 20px;
}
.main-treeview > li.active > a {
   color: yellow;
}
.main-treeview > li > a:hover {
  color: #ffffff;
}
</style>

<form action="{{(is_null($permission))?route('admin.permission.add.action'):route('admin.permission.edit.action', ['id'=>$permission->id])}}" method="POST">
    {{csrf_field()}}
    <div class="row">
        <div class="col-md-6">
            <!-- <div class="box box-primary"> -->
                <div class="box-header with-border"><h3 class="box-title">Detail Keterangan Parent Menu Akses</h3></div>
                <div class="body box-body">
					<div class="form-group">
						<label>Menu / Akses</label>
						<select placeholder="Menu" class="form-control" id="pilihan_menu_akses" name="menu_akses">
							<option value=""></option>   
							<option value="akses" <?php if(!is_null($permission)){ if($permission->is_menu==0) echo 'selected'; } ?>>Hak Akses</option> 
						</select>
					</div>
                    
                    <div id="detail_menu" <?php echo (is_null($permission)) ? 'style="display: none"' : ''?> >
                        <div class="form-group">
                            <label>Nama Menu Akses*  <span class="label label-danger required-field" style="display: none">Wajib diisi!</span></label>
                            <input name="nama" id="nama_menu" type="text" class="form-control" placeholder="Nama Permission" value="{{(is_null($permission))?'':$permission->display_name}}" >
                            {!!$errors->first('display_name', '<label class="control-label has-error">:message</label>')!!}
                        </div>

                        <div class="form-group">
                            <label>alias*  <span class="label label-danger required-field" style="display: none">Wajib diisi!</span></label>
                            <input name="alias" id="alias_menu" type="text" class="form-control" placeholder="Alias Permission" value="{{(is_null($permission))?'':$permission->name}}"  >
                            {!!$errors->first('name', '<label class="control-label has-error">:message</label>')!!}
                        </div>
                        <div class="form-group">
                            <label>Deskripsi Permission</label>
                            <input name="deskripsi" type="text" class="form-control" placeholder="Deskripsi Permission" value="{{(is_null($permission))?'':$permission->description}}">
                            {!!$errors->first('description', '<label class="control-label has-error">:message</label>')!!}
                        </div>
                    </div>
					
					<div id="div_menu_sub_menu" <?php echo (is_null($permission)) ? 'style="display: none"' : ($permission->parent_id==null && $permission->is_parent==0) ? 'style="display: none"' : (!is_null($rootparent)) ? 'style="display: none"' : ''?>>		
						<div class="form-group">
							<label>Menu utama / Sub Menu</label>
							<select name="menu" placeholder="Ketentuan Menu" class="form-control" id="menu">
								<option value=""></option>  
								<option value="is_parent" <?php if(!is_null($permission)){ if($permission->is_parent==1) echo 'selected'; } ?>>Menu utama</option>
								<option value="sub_menu" <?php if(!is_null($permission)){ if($permission->is_parent==0) echo 'selected'; } ?>>Sub menu</option>
							</select>
						</div>
					</div>

					<div id="div_menu_utama"  <?php echo (is_null($permission)) ? 'style="display: none"' : ($permission->is_parent==1) ? 'style="display: none"' : ($permission->parent_id==null) ? 'style="display: none"' : (!is_null($rootparent)) ? 'style="display: none"' : ''; ?>>
						<div class="form-group">
							<label>Daftar Menu Utama</label>
							<select placeholder="Ketentuan Menu" class="form-control" id="menu_utama" name="parent_menu">
								@if(!is_null($permission))
									<option value=""></option>
									@foreach($allparents as $row)
										<option value="{{$row->id}}" <?php if($permission->parent_id==$row->id) echo 'selected';?>>{{$row->display_name}}</option>
									@endforeach
								@endif
							</select>
						</div>
					</div>
					
					<div id="sequence_list" <?php echo (is_null($permission) || is_null($sequence)) ? 'style="display: none"' : ($permission->parent_id==null && $permission->is_parent==0) ? 'style="display: none"' : ''?> >
						<div class="form-group">
							<label>Urutan Menu</label>
							<ul class="nav side-menu" id="sortable">
								@if(!is_null($permission) && !is_null($sequence))
									@foreach($sequence as $seq)
										@if($seq->id==$permission->id)
											<li class="main-treeview active" id="sequence_{{$seq->id}}"><a href="#" style="margin-bottom: 0px;"><i class="fa fa-folder"></i> <span id="sequencelabel">{{$seq->display_name}}</span></a></li>
										@else
											<li class="main-treeview disabled-menu" id="sequence_{{$seq->id}}"><a href="#" style="margin-bottom: 0px;"><i class="fa fa-folder"></i> <span>{{$seq->display_name}}</span></a></li>
										@endif
									@endforeach
								@endif
							</ul>
							<div class="help-block"><span class="label label-info">Drag and drop untuk menentukan urutan</span></div>
						</div>
					</div>
					
					<div id="sequence_list_sub" <?php echo (is_null($permission) || is_null($sequencesub) || is_null($parent)) ? 'style="display: none"' : ($permission->parent_id==null) ? 'style="display: none"' : ''?> >
						<div class="form-group">
							<label>Urutan Sub Menu</label>
							<ul class="nav side-menu">
								@if(!is_null($permission) && !is_null($sequencesub) && !is_null($parent))
								<li class="treeview active">
									  <a href="#" style="margin-bottom: 0px;">
										  <i class="fa fa-folder" ></i> <span id="header_parent_menu">{{$parent->display_name}}</span>
										  <i class="fa fa-angle-left pull-right"></i>
									  </a>
									  <ul class="sub-treeview" id="sortable_sub">
											@foreach($sequencesub as $seq)
												@if($seq->id==$permission->id)
													<li class="active" id="sequencesub_{{$seq->id}}"><a href="#"><i class="fa <?php echo (!is_null($rootparent)) ? 'fa-circle-o' : 'fa-folder'; ?>"></i> <span id="sequencesublabel">{{$seq->display_name}}</span></a></li>
												@else
													<li class="disabled-menu" id="sequencesub_{{$seq->id}}"><a href="#"><i class="fa <?php echo (!is_null($rootparent)) ? 'fa-circle-o' : 'fa-folder'; ?>"></i> <span>{{$seq->display_name}}</span></a></li>
												@endif
											@endforeach
									  </ul>
								  </li>
								  @else
								  <li class="treeview active">
									  <a href="#" style="margin-bottom: 0px;">
										  <i class="fa fa-folder" ></i> <span id="header_parent_menu"></span>
										  <i class="fa fa-angle-left pull-right"></i>
									  </a>
									  <ul class="sub-treeview" id="sortable_sub">
									  </ul>
								  </li>  
								  @endif
							</ul>
							<div class="help-block"><span class="label label-info">Drag and drop untuk menentukan urutan</span></div>
						</div>
					</div>
						
                    <div class="route_div" id="route_div"  <?php echo (is_null($permission)) ? 'style="display: none"' : ($permission->is_parent==1) ? 'style="display: none"' : ''; ?>>
                        <div class="form-group">
                            <label>Route Permission</label>
							@if(!is_null($permission))
							    {{-- @if(!is_null($rootparent))
									<div class="input-group">
										<span class="input-group-addon label-info">{{$parent->route_permission}}</span>									
										<input id="route_parent_permission" name="route_permission" type="text" class="form-control" placeholder="Route Permission" value="{{str_replace($parent->route_permission,'',$permission->route_permission)}}">
									</div>
								@endif --}}
								<input id="route_parent_permission" name="route_permission" type="text" class="form-control" placeholder="Route Permission" value="{{(is_null($permission))?'':$permission->route_permission}}">
							@else	
							<input id="route_parent_permission" name="route_permission" type="text" class="form-control" placeholder="Route Permission" value="{{(is_null($permission))?'':$permission->route_permission}}">
							@endif
                            {!!$errors->first('route_permission', '<label class="control-label has-error">:message</label>')!!}
                            <div class="help-block"><span class="label label-danger">Peringatan !</span> Jangan menggunakan spasi</div>
                        </div>
                    </div>

                    
                    <div class="checkbox" id="pilihan_auto_generate" style="display: none">
                      <label>
                        <input type="checkbox" id="is_menu"> Auto Generate CRUD Routes
                      </label>
                    </div>

					@if(!is_null($permission))
						@if(!is_null($rootparent))
							<input type="hidden" value="{{$parent->id}}" id="sub_menu_id" name="sub_menu_id">
						@endif
					@endif
					<input type="hidden" value="" id="sequence_number" name="sequence_number">
					<input type="hidden" value="" id="sequence_number_sub" name="sequence_number_sub">
                </div>
				<div class="overlay" id="loading_state" style="display: none">
				  <i class="fa fa-refresh fa-spin"></i>
				</div>
            <!-- </div> -->
        </div>
        <div class="col-md-6" id="div_is_menu">
            <!-- <div class="box box-primary"> -->
                <div class="box-header with-border"><h3 class="box-title">Detail Child Permission</h3></div>
                <div class="body box-body">
                    <div id="contentChildMenu"></div>
                    <hr>
                    <div id="autocrud"></div>
                </div>
            <!-- </div> -->
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary pull-left"><i class="fa fa-save"></i>&nbsp;Simpan</button>
        </div>
    </div>
</form>

<script src="{{ asset('js/jquery-1.11.2.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/datepicker/bootstrap-datepicker.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/jquery-ui.min.js') }}" type="text/javascript"></script>

<script type="text/javascript">
	@if(!is_null($permission) && !is_null($sequence))
		activateSortable();
	@endif
	
	@if(!is_null($permission) && !is_null($sequencesub) && !is_null($parent))
		activateSortableSub();
	@endif
	
	@if(!is_null($permission) && !is_null($sequencesub) && !is_null($parent) && is_null($rootparent))
		$('#is_menu').prop('checked', true).triggerHandler('click');
		generateAutoCRUD();
	@endif
	
    var birthdateInput = $( "#birth-date" ).datepicker({
            clearButton: true,
            dateFormat: "dd/mm/yy",
            autoclose: true
    });
	
	$('#route_parent_permission').keypress(function(e){
		var value = $(this).val();
		var menu_akses = $('#pilihan_menu_akses option:selected').val();
		
		// handling input spasi pada route permission
		if (e.keyCode === 0 || e.keyCode === 32) {
			e.preventDefault();
		 }
		
		// handling perubahan pada route permission untuk auto CRUD
		if($('#is_menu').is(':checked') && menu_akses == 'menu'){
			generateAutoCRUD();
		}
	});
	
	$('form').submit(function(){
		var menuakses = $('#pilihan_menu_akses').val();
		if(menuakses==null || menuakses==''){
			alert('Pilih Menu / Akses terlebih dahulu');
			return false;
		} else {
			var nama = $('#nama_menu').val();
			var alias = $('#alias_menu').val();
			if(nama=='' || alias==''){
				alert('Nama dan alias wajib diisi terlebih dahulu');
				return false;
			}
			
			var route = $('#route_parent_permission').val();
			if(menuakses=='akses'){
				if(route==''){
					alert('Isi route terlebih dahulu');
					return false;
				}
			} else {
				var menusubmenu = $('#menu').val();
				if(menusubmenu==null || menusubmenu==''){
					alert('Pilih Menu / Sub Menu terlebih dahulu');
					return false;
				} else if(menusubmenu=='sub_menu'){
					var submenuid = $('#sub_menu_id').val();
					var parentmenu = $('#menu_utama').val();
					if((parentmenu==null || parentmenu=='') && (submenuid==null || submenuid=='')){
						alert('Pilih Parent Menu terlebih dahulu');
						return false;
					}
					
					if(route==''){
						alert('Isi route terlebih dahulu');
						return false;
					}
					
					if(!$('#is_menu').is(':checked') && (submenuid==null || submenuid=='')){
						var result = confirm("Anda yakin untuk submit sub menu tanpa generate CRUD?");
						if (result) {
							return true;
						} else {
							return false;
						}
					}
				}
			}
		}
		return true;
	});
	
	$('#nama_menu').keyup(function(){
		var menusubmenu = $('#menu').val();
		if(menusubmenu == 'is_parent'){
			$('#sequencelabel').text($(this).val());
		} else {
			var parentmenu = $('#menu_utama').val();
			if(parentmenu!=null && parentmenu!=''){
				$('#sequencesublabel').text($(this).val());
			}
		}
	});
    
    $("#pilihan_menu_akses").change(function () {
        var menu_akses = $('#pilihan_menu_akses option:selected').val();
		$('#menu').val('').trigger('change');
        if(menu_akses == 'menu'){
            $("#detail_menu").show();
			$("#div_menu_sub_menu").show();
			
			var menu_sub_menu = $('#menu option:selected').val();
			if(menu_sub_menu == 'sub_menu'){
				$("#route_div").show();
				$("#pilihan_auto_generate").show();
			} else {
				$("#route_div").hide();
				$("#pilihan_auto_generate").hide();
			}
        }else if(menu_akses == 'akses'){
			$('#autocrud').empty();
            $("#detail_menu").show();
			$("#div_menu_sub_menu").hide();
			$("#div_menu_utama").hide();
			$("#sequence_list").hide();
			$("#sequence_list_sub").hide();
			$("#route_div").show();
			$("#pilihan_auto_generate").hide();
        } else {
			$("#detail_menu").hide();
			$("#div_menu_sub_menu").hide();
			$("#div_menu_utama").hide();
			$("#sequence_list").hide();
			$("#sequence_list_sub").hide();
			$("#route_div").hide();
			$("#pilihan_auto_generate").hide();
		}
    });

	function generateAutoCRUD(){
		$('#autocrud').empty();
		var parentidvalue = $('#route_parent_permission').val();

		@if(!is_null($children))
			var textContent = '<table class="table table-bordered"><tr><td width="100%" align="center"><b>Routes Child Permission Terkait</b></td></tr>';
			@foreach($children as $child)
				textContent += '<tr><td><input name="crud[]" type="text" class="form-control hidden" value="'+ parentidvalue+'{{str_replace($permission->route_permission,'',$child->route_permission)}}'+'" />'+ parentidvalue+'{{str_replace($permission->route_permission,'',$child->route_permission)}}' +'</td></tr>';
			@endforeach
			textContent += '</table>';
		@else
			var textContent = '<table class="table table-bordered">'+
									'<tr>'+
										'<td width="100%" align="center"><b>Auto Generate Routes Child Permission</b></td>'+
									'</tr>'+
									'<tr>'+
										'<td><input name="crud[]" type="text" class="form-control hidden" value="'+ parentidvalue+'manage'+'" />'+ parentidvalue+'manage' +'</td>'+
									'</tr>'+
									'<tr>'+
										'<td><input name="crud[]" type="text" class="form-control hidden" value="'+ parentidvalue+'create'+'" />'+ parentidvalue+'create' +'</td>'+
									'</tr>'+
									'<tr>'+
										'<td><input name="crud[]" type="text" class="form-control hidden" value="'+ parentidvalue+'update/{id}'+'" />'+ parentidvalue+'update/{id}' +'</d>'+
									'</tr>'+
									'<tr>'+
										'<td><input name="crud[]" type="text" class="form-control hidden" value="'+ parentidvalue+'delete/{id}'+'" />'+ parentidvalue+'delete/{id}' +'</td>'+
									'</tr>'+
									'<tr>'+
										'<td><input name="crud[]" type="text" class="form-control hidden" value="'+ parentidvalue+'detail/{id}'+'" />'+ parentidvalue+'detail/{id}' +'</td>'+
									'</tr>'+
								'</table>';
		@endif
		
		$('#autocrud').append(textContent);
	}
	
    $('#is_menu').click(function(){
        if (this.checked) {
            generateAutoCRUD();
        }else{
            $('#autocrud').empty();
        }
    }); 


    $("#refresh_child").click(function () {
        $('#contentChildMenu').empty();
    });

    $("#menu_utama").change(function () {
		$("#sequence_list").hide();
		$("#sortable").empty();
		
		$("#sequence_list_sub").hide();
		$("#sortable_sub").empty();
		
		var parentID = $("#menu_utama option:selected").val();
		
		if(parentID==null || parentID==''){
			return;
		}
		
		var parentName = $("#menu_utama option:selected").text();
		var menu = $("#menu option:selected").val();
        var token = $('input[name=_token]').val();
		var nama_menu = $("#nama_menu").val();
		
		$('#loading_state').show();
		$.ajax({
			url: '{{route('permission.ajax.parentMenu.get')}}',
			headers: {'X-CSRF-TOKEN': token},
			type: 'POST',
			dataType: 'JSON',
			data: {code: menu , token : token, parentID: parentID},
			cache: false,
			complete: function(){
				$('#loading_state').hide();
			},
			success: function (data) {
				$('#header_parent_menu').text(parentName);
				var count = data.permission_data.length;
				
				@if(!is_null($permission) && !is_null($sequencesub) && !is_null($parent))
					var current_parent_id = {{$permission->parent_id}};
					var current_permission_id = {{$permission->id}};
					for(i = 0 ; i < count ; i++){
						if(current_parent_id==parentID && current_permission_id==data.permission_data[i].id){
							$("#sortable_sub").append('<li class="active" id="sequencesub_' + data.permission_data[i].id + '"><a href="#"><i class="fa fa-folder"></i> <span id="sequencesublabel">' + data.permission_data[i].display_name + '</span></a></li>');
						} else {
							$("#sortable_sub").append('<li class="disabled-menu" id="sequencesub_' + data.permission_data[i].id + '"><a href="#"><i class="fa fa-folder"></i> <span>' + data.permission_data[i].display_name + '</span></a></li>');
						}						
					}
					if(current_parent_id!=parentID){
						$("#sortable_sub").append('<li class="active" id="sequencesub_new"><a href="#"><i class="fa fa-folder"></i> <span id="sequencesublabel">' + nama_menu + '</span></a></li>');
					}
				@else
					for(i = 0 ; i < count ; i++){
						$("#sortable_sub").append('<li class="disabled-menu" id="sequencesub_' + data.permission_data[i].id + '"><a href="#"><i class="fa fa-folder"></i> <span>' + data.permission_data[i].display_name + '</span></a></li>');
					}
					$("#sortable_sub").append('<li class="active" id="sequencesub_new"><a href="#"><i class="fa fa-folder"></i> <span id="sequencesublabel">' + nama_menu + '</span></a></li>');
				@endif
				activateSortableSub();
				$("#sequence_list_sub").show();
			}   
		});
	});
	
    // APPEND DATA 
    $("#menu").change(function () {
		$('.required-field').hide();
		$("#route_div").hide();
		$("#pilihan_auto_generate").hide();
		$("#sequence_list").hide();
		$("#sequence_list_sub").hide();
		var nama_menu = $("#nama_menu").val();
		var alias_menu = $("#alias_menu").val();
		if(nama_menu=='' || alias_menu==''){
			$('.required-field').show();
			$(this).val('');
			return false;
		}
		
        var menu = $("#menu option:selected").val();
        var token = $('input[name=_token]').val();
        var parentidvalue = $('#route_parent_permission').val();

		$("#menu_utama").empty();
        $("#contentChildMenu").empty();
            if(menu === 'sub_menu'){
                // POST DATA COMMENDTED 
				$('#loading_state').show();
                $.ajax({
                    url: '{{route('permission.ajax.parentId.get')}}',
                    headers: {'X-CSRF-TOKEN': token},
                    type: 'POST',
                    dataType: 'JSON',
                    data: {code: menu , token : token},
                    cache: false,
					complete: function(){
						$('#loading_state').hide();
					},
                    success: function (data) {
						$("#menu_utama").append('<option value=""></option>');
                        for(i = 0 ; i < data.permission_data.length ; i++){
							@if(!is_null($permission))
							if(data.permission_data[i].id!={{$permission->id}})
							@endif
                            $("#menu_utama").append('<option value="'+ data.permission_data[i].id+'">'+ data.permission_data[i].display_name +'</option>');
                        }
						$("#menu_utama").val('').trigger("change");
						$("#div_menu_utama").show();
						$("#route_div").show();
						$("#pilihan_auto_generate").show();
                    }   
                });
            }else if(menu === 'is_parent'){
				$("#sortable").empty();
				$('#loading_state').show();
                // POST DATA COMMENDTED 
                $.ajax({
                    url: '{{route('permission.ajax.parentId.get')}}',
                    headers: {'X-CSRF-TOKEN': token},
                    type: 'POST',
                    dataType: 'JSON',
                    data: {code: menu , token : token},
                    cache: false,
					complete: function(){
						$('#loading_state').hide();
					},
                    success: function (data) {
                        for(i = 0 ; i < data.permission_data.length ; i++){
                            $("#sortable").append('<li class="main-treeview disabled-menu" id="sequence_' + data.permission_data[i].id + '"><a href="#"><i class="fa fa-folder"></i> <span>' + data.permission_data[i].display_name + '</span></a></li>');
                        }
						$("#sortable").append('<li class="main-treeview active" id="sequence_new"><a href="#"><i class="fa fa-folder"></i> <span id="sequencelabel">' + nama_menu + '</span></a></li>');
						activateSortable();
						$("#sequence_list").show();
                    }   
                });
				
                $("#route_div").hide();
                $("#div_menu_utama").hide();
                $(".checkbox").hide();
            }

    });
	
	function activateSortableSub(){
		$("#sortable_sub").sortable({
			helper: function(event, ui){
				var $clone =  $(ui).clone();
				$clone .css('position','absolute');
				return $clone.get(0);
			},
			update: function () {
				var data = $('#sortable_sub').sortable('serialize');
				data = data.replace(/sequencesub\[\]\=/g, "");
				data = data.replace(/\&/g, ",");
				$('#sequence_number_sub').val(data);
			},
		});
		var temp = $('#sortable_sub').sortable('serialize');
		temp = temp.replace(/sequencesub\[\]\=/g, "");
		temp = temp.replace(/\&/g, ",");
		$('#sequence_number_sub').val(temp);
	}
	
	function activateSortable(){
		$("#sortable").sortable({
			helper: function(event, ui){
				var $clone =  $(ui).clone();
				$clone .css('position','absolute');
				return $clone.get(0);
			},
			update: function () {
				var data = $('#sortable').sortable('serialize');
				data = data.replace(/sequence\[\]\=/g, "");
				data = data.replace(/\&/g, ",");
				$('#sequence_number').val(data);
			}
		});
		var temp = $('#sortable').sortable('serialize');
		temp = temp.replace(/sequence\[\]\=/g, "");
		temp = temp.replace(/\&/g, ",");
		$('#sequence_number').val(temp);
	}

    // APPEND DATA FROM INPUT NEW ROUTES
    $(document).on('click', '#add_new_route' ,function () {
        var data_new_routes = $("#value_route").val();
        $("#body_child_table").append('<tr>'+
                                        '<td width="90%">'+
                                            '<input type="text" class="form-control" value="'+ data_new_routes +'" disabled />'+
                                        '</td>'+
                                        '<td width="10%" align="center"><a onClick="return confirm(\'Apakah Anda yakin untuk menghapus data routes '+ data_new_routes +'  ?\')" class="btn btn-danger" id="close_add"><i class="fa fa-times""></i></a></td>'+
                                    '</tr>');
        $("#value_route").val($('#route_parent_permission').val());
    });

    // DELETEING FUNCTION JAVASCRIPT
    $(document).on('click', '#close_add' ,function () {
        $(this).parent().parent().remove();
    });

	/*
    $(document).on('change', '#menu_parent' ,function () {
        var val_parent = $("#menu_parent").val();
        if(val_parent != ''){
            $("#route_div").hide();
            $("#value_route").val();
        }else{
            $("#route_div").show();
        }
    });*/
   
	$("#sortable_sub").sortable({
		helper: function(event, ui){
			var $clone =  $(ui).clone();
			$clone .css('position','absolute');
			return $clone.get(0);
		}
	});

</script>
<script src="{{ asset('js/jquery-ui.min.js') }}" type="text/javascript"></script>


@stop
