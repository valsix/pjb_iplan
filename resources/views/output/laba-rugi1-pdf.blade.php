<style type="text/css">
        /*Update line height & font-size*/
        .table thead tr th{
          line-height: 0.5;
          text-align: center;
        }
        .table tbody tr td{
          /*Untuk data yang deskripsi panjang*/
          /*line-height: 1; */

          /*Untuk data yang tidak ada deskripsi panjang*/
          line-height: 0.5; 
        }
        .table {
          font-size: 11px;
        }

        .form-horizontal .form-group
        {
            margin-right: 0;
            margin-left: 0;
            margin-top: -12px;
        }

    </style>

	    <div class="row">
	    	<div class="col-md-8">
		    	<table class="table">
		    		<tr>
		    			<td width="20%">Tahun Anggaran</td>
		    			<td>: &nbsp;{{Request::get('tahun1')}}</td>
		    		</tr>
		    		<tr>
		    			<td width="20%">Struktur Bisnis</td>
		    			<td>: &nbsp;{{isset($input_sb) ? $input_sb->name : ''}}</td>
		    		</tr>
		    		<tr>
		    			<td width="20%">Distrik</td>
		    			<td>: &nbsp;{{ $fill[2] }}</td>
		    		</tr>
		    		<tr>
		    			<td width="20%">Lokasi</td>
		    			<td>: &nbsp;{{ $fill[3] }}</td>
		    		</tr>
		    		<tr>
		    			<td width="20%">Fase</td>
		    			<td>: &nbsp;{{isset($input_fase) ? $input_fase->name : ''}}</td>
		    		</tr>
		    		<tr>
		    			<td width="20%">Form RKAU</td>
		    			<td>: &nbsp;{{ isset($input_draft_rkau) ? $input_draft_rkau->draft_versi.' - '.$input_draft_rkau->name : '' }}</td>
		    		</tr>
		    	</table>
	    	</div>
	    	<!-- <div class="col-md-4">
	    		&nbsp;
	    	</div>
	        <br> -->
	    </div>
		<!-- Table LR Unit Pembangkit -->
	    <div class="row">
	            <div class="col-md-12 col-sm-12 col-xs-12">
	                <div class="x_panel">
	                  <div class="x_title">
	                    <h4>PROYEKSI LABA RUGI KOMPARATIF</h4>
              			<h5 style="margin-top: 8px;"> (Dalam Ribuan Rupiah)</h5>
	                    <div class="clearfix"></div>
	                  </div>

	                  <div class="x_content">
	                    <table id="datatable" border="0" class="table table-striped table-bordered">
	                       	<thead>
			                   <tr>
			                     <th class="col-md-4">KETERANGAN</th>
			                     <th>Estimasi Real <br> <?php if($tahun1 == NULL){echo '';}else {
			                       echo $tahun2;
			                     } ?> </th>
			                     <th>RKAP <br> {{ $tahun1 }}</th>
			                   	</tr>
			                   	<tr>
			                     <th>1</th>
			                     <th>2</th>
			                     <th>3</th>
			                   </tr>
			                </thead>

	                        <?php $count=0; ?>
	                        <?php $baris=0; ?>
	                        @if(isset($hasil1))
	                          @foreach($hasil1 as $i=>$val)
	                          <?php $baris = $baris+1; $warna=($baris % 2 == 0) ? "#ffffff" : "#E8EDEF"; ?>
	                            @if($val->row==64)
	                              <tr style="background-color: <?= $warna?>">
	                                <td>
	                                  {{$val->value}}
	                                </td>
	                                <td colspan="2">&nbsp;</td>
	                              </tr>
	                            @else
	                              	{{-- @if($count==0) --}}
                        			@if($val->kolom=='D')
	                                <tr style="background-color: <?= $warna?>">
	                                  <td>
	                                    @if($baris==4 || $baris==22 || $baris==31 
										|| $baris==37 || $baris==67 || $baris==88 || $baris==91 || $baris==94 || $baris==97
										|| $baris==106 || $baris==109 || $baris==112 || $baris==115 || $baris==118
										|| $baris==124 || $baris==127
										|| $baris==140 || $baris==143 || $baris==146 || $baris==149
										)
										&nbsp;&nbsp;&nbsp;&nbsp;{{ $val->value }}
										@elseif($baris==7 || $baris==10 || $baris==13 || $baris==16 || $baris==19
										|| $baris==25 || $baris==28
										|| $baris==40 || $baris==43 || $baris==46 || $baris==49 || $baris==52 || $baris==55
										|| $baris==58 || $baris==61 || $baris==64
										|| $baris==70 || $baris==79
										)
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $val->value }}
										@elseif($baris==73 || $baris==76
										|| $baris==82 || $baris==85
										)
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $val->value }}
										@else
										{{ $val->value }}
										@endif
	                                  </td>
	                                <?php $count++ ?>
	                              	{{-- @elseif($count==1) --}}
                        			@elseif($val->kolom=='E')
	                                <td style="text-align: right;">
	                                  {{ number_format((float)$val->value,0,",",".").'' }}
	                                </td>
	                                <?php $count++ ?>
				                    {{-- @elseif($count==2) --}}
			                        @elseif($val->kolom=='F')
	                                <td style="text-align: right;">
	                                  {{ number_format((float)$val->value,0,",",".").'' }}
	                                </td>
	                                </tr>
	                                <?php $count=0; ?>
	                              @endif
	                            @endif
	                          @endforeach
	                        @endif
	                       </tbody>
	                    </table>
	                  </div>
	                </div>
	            </div>
	    </div>

		<style type="text/css">
			table{border-spacing:0;border-collapse:collapse}td,th{padding:0}
			.table td,.table th{background-color:#fff!important}
			.table-bordered td,.table-bordered th{border:1px solid #ddd!important}
			.table-striped>tbody>tr:nth-of-type(odd){background-color:#f9f9f9}
			.table>tbody>tr.active>td,.table>tbody>tr.active>th,.table>tbody>tr>td.active,.table>tbody>tr>th.active,.table>tfoot>tr.active>td,.table>tfoot>tr.active>th,.table>tfoot>tr>td.active,.table>tfoot>tr>th.active,.table>thead>tr.active>td,.table>thead>tr.active>th,.table>thead>tr>td.active,.table>thead>tr>th.active{background-color:#f5f5f5}
			.table{width:100%;max-width:100%;margin-bottom:20px}.table>tbody>tr>td,.table>tbody>tr>th,.table>tfoot>tr>td,.table>tfoot>tr>th,.table>thead>tr>td,.table>thead>tr>th{padding:8px;line-height:1.42857143;vertical-align:top;border-top:1px solid #ddd}.table>thead>tr>th{vertical-align:bottom;border-bottom:2px solid #ddd}.table>caption+thead>tr:first-child>td,.table>caption+thead>tr:first-child>th,.table>colgroup+thead>tr:first-child>td,.table>colgroup+thead>tr:first-child>th,.table>thead:first-child>tr:first-child>td,.table>thead:first-child>tr:first-child>th{border-top:0}.table>tbody+tbody{border-top:2px solid #ddd}.table .table{background-color:#fff}
			.row{margin-right:-15px;margin-left:-15px}.col-lg-1,.col-lg-10,.col-lg-11,.col-lg-12,.col-lg-2,.col-lg-3,.col-lg-4,.col-lg-5,.col-lg-6,.col-lg-7,.col-lg-8,.col-lg-9,.col-md-1,.col-md-10,.col-md-11,.col-md-12,.col-md-2,.col-md-3,.col-md-4,.col-md-5,.col-md-6,.col-md-7,.col-md-8,.col-md-9,.col-sm-1,.col-sm-10,.col-sm-11,.col-sm-12,.col-sm-2,.col-sm-3,.col-sm-4,.col-sm-5,.col-sm-6,.col-sm-7,.col-sm-8,.col-sm-9,.col-xs-1,.col-xs-10,.col-xs-11,.col-xs-12,.col-xs-2,.col-xs-3,.col-xs-4,.col-xs-5,.col-xs-6,.col-xs-7,.col-xs-8,.col-xs-9{position:relative;min-height:1px;padding-right:15px;padding-left:15px}
			.form-control{display:block;width:100%;height:34px;padding:6px 12px;font-size:14px;line-height:1.42857143;color:#555;background-color:#fff;background-image:none;border:1px solid #ccc;border-radius:4px;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075);box-shadow:inset 0 1px 1px rgba(0,0,0,.075);-webkit-transition:border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;-o-transition:border-color ease-in-out .15s,box-shadow ease-in-out .15s;transition:border-color ease-in-out .15s,box-shadow ease-in-out .15s}.form-control:focus{border-color:#66afe9;outline:0;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6);box-shadow:inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6)}
			.label{display:inline;padding:.2em .6em .3em;font-size:75%;font-weight:700;line-height:1;color:#fff;text-align:center;white-space:nowrap;vertical-align:baseline;border-radius:.25em}
		</style>	
