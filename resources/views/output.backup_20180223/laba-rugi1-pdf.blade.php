
	    <div class="row">
	    	<div class="col-md-8">
		    	<table>
		    		<tr>
		    			<td>
				            <label class="col-md-2 col-md-3">Tahun Anggaran</label>
		    			</td>
		    			<td>: &nbsp;&nbsp;</td>
		    			<td>
			              <p> {{ $fill[0] }} </p>
		    			</td>
		    		</tr>
		    		<tr>
		    			<td>
				            <label class="col-md-2 col-md-3 " >Struktur Bisnis</label>
		    			</td>
		    			<td>: &nbsp;&nbsp;</td>
		    			<td>
				            <p> {{ $fill[1] }} </p>
		    			</td>
		    		</tr>
		    		<tr>
		    			<td>
				            <label class="col-md-2 col-md-3 " >Distrik</label>
		    			</td>
		    			<td>: &nbsp;&nbsp;</td>
		    			<td>
				            <p> {{ $fill[2] }} </p>
		    			</td>
		    		</tr>
		    		<tr>
		    			<td>
				            <label class="col-md-2 col-md-3 " >Lokasi</label>
		    			</td>
		    			<td>: &nbsp;&nbsp;</td>
		    			<td>
				            <p> {{ $fill[3] }} </p>
		    			</td>
		    		</tr>
		    		<tr>
		    			<td>
				            <label class="col-md-2 col-md-3 " >Fase</label>
		    			</td>
		    			<td>: &nbsp;&nbsp;</td>
		    			<td>
				            <p> {{ $fill[4] }} </p>
		    			</td>
		    		</tr>
		    		<tr>
		    			<td>
				            <label class="col-md-2 col-md-3 " >Draft</label>
		    			</td>
		    			<td>: &nbsp;&nbsp;</td>
		    			<td>
				            <p> {{ $fill[5] }} </p>
		    			</td>
		    		</tr>
		    	</table>
	    	</div>
	    	<div class="col-md-4">
	    		&nbsp;
	    	</div>
	        <br>
	    </div>
		<!-- Table LR Unit Pembangkit -->
	    <div class="row">
	            <div class="col-md-12 col-sm-12 col-xs-12">
	                <div class="x_panel">
	                  <div class="x_title">
	                    <h2>{{$judul}}</h2>
	                    <div class="clearfix"></div>
	                  </div>

	                  <div class="x_content">
	                    <table id="datatable" border="0" class="table table-striped table-bordered">
	                       <thead>
	                         <tr>
	                           <th>Keterangan</th>
	                           <th>Estimasi Real <br> {{ $fill[0]-1 }}</th>
	                           <th>RKAP <br> {{ $fill[0] }}</th>
	                         </tr>
	                         <tr>
	                           <th>1</th>
	                           <th>2</th>
	                           <th>3</th>
	                         </tr>
	                       </thead>
	                       <tbody>
	                        <?php $count=0; ?>
	                        @if(isset($hasil1))
	                          @foreach($hasil1 as $i=>$val)
	                            @if($val->row==65)
	                              <tr>
	                                <td>
	                                  {{$val->value}}
	                                </td>
	                                <td colspan="2">&nbsp;</td>
	                              </tr>
	                            @else
	                              @if($count==0)
	                                <tr>
	                                  <td>
	                                    {{ $val->value }}
	                                  </td>
	                                <?php $count++ ?>
	                              @elseif($count==1)
	                                <td style="text-align: right;">
	                                  {{ number_format((float)$val->value,3,",",".") }}
	                                </td>
	                                <?php $count++ ?>
	                              @elseif($count==2)
	                                <td style="text-align: right;">
	                                  {{ number_format((float)$val->value,3,",",".") }}
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
