
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
				            <p> {{ $fill[1]->name }} </p>
		    			</td>
		    		</tr>
		    		<tr>
		    			<td>
				            <label class="col-md-2 col-md-3 " >Distrik</label>
		    			</td>
		    			<td>: &nbsp;&nbsp;</td>
		    			<td>
				            <p> {{ $fill[2]->name }} </p>
		    			</td>
		    		</tr>
		    		<tr>
		    			<td>
				            <label class="col-md-2 col-md-3 " >Lokasi</label>
		    			</td>
		    			<td>: &nbsp;&nbsp;</td>
		    			<td>
				            <p> {{ $fill[3]->name }} </p>
		    			</td>
		    		</tr>
		    		<tr>
		    			<td>
				            <label class="col-md-2 col-md-3 " >Fase</label>
		    			</td>
		    			<td>: &nbsp;&nbsp;</td>
		    			<td>
				            <p> {{ $fill[4]->name }} </p>
		    			</td>
		    		</tr>

		    		<tr>
		    			<td>
				            <label class="col-md-2 col-md-3 " >Draft</label>
		    			</td>
		    			<td>: &nbsp;&nbsp;</td>
		    			<td>
				            <p> {{ $fill[5]->draft_versi }} </p>
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
												<div class="x_content" >
													<table class="gant" border="1"  style="width:100%;"> <!-- cellspacing="10" cellpadding="20" -->
														<tbody>

															<?php $i=1; ?>

															@foreach($tingkat_kemungkinan as $tk)
															<?php echo '<tr>' ?>
																<?php if($i==1) { ?>
																	<td rowspan="5"><span  class="vertical_Text" title="vertical text">Kemungkinan</span></td>
																<?php } ?>
																<?php echo '<td>'.$tk->nama_tingkat_kemungkinan.'</td>'; ?>
																<?php echo '<td>'.$tk->no_tingkat_kemungkinan.'</td>'; ?>

																<?php foreach($tingkat_dampak as $td) { ?>



																	<?php foreach($level_resiko as $lr) { ?>


																		<?php if($lr->tingkat_kemungkinan_id==$tk->id
																		&& $lr->tingkat_dampak_id==$td->id)
																		{ ?>

																			<?php echo '<td style="background-color:#'.$lr->warna_level_resiko.'"; >'; ?>
																				@isset($combineall)
																				@foreach($combineall as $c1)
																				@if($c1['E'] == $tk->nama_tingkat_kemungkinan && $c1['F'] == $td->nama_tingkat_dampak)
																				<div style="background:white;">
																					{{$c1['A']}}
																				</div>
																				@endif
																				@endforeach
																				@endisset
																				<?php echo $lr->nama_level_resiko; ?>
																				<?php echo '</td>'; ?>

																			<?php } ?>

																		<?php } ?>

																	<?php } ?>

																	<?php echo '</tr>' ?>
																	<?php $i++; ?>
																	@endforeach

																	<tr>
																		<td colspan="3"></td>
																		@foreach($tingkat_dampak as $td)
																		<?php echo '<td>'.$td->no_tingkat_dampak.'</td>'; ?>
																		@endforeach
																	</tr>

																	<tr>
																		<td colspan="3"></td>
																		@foreach($tingkat_dampak as $td)
																		<?php echo '<td>'.$td->nama_tingkat_dampak.'</td>'; ?>
																		@endforeach
																	</tr>

																	<tr>
																		<td colspan="3"></td>
																		<td colspan="5" class="pad">Tingkat Dampak</td>
																	</tr>

																</tbody>
															</table>
														</div>
													</div>
												</div>
											</div>

                  <div class="page-break"></div>
									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12">
											<div class="x_panel">
												<div class="x_title">
													<h2>Risk Profile Table</h2>
													<div class="clearfix"></div>
												</div>

												<div class="x_content">
													<table id="datatable" class="table table-striped table-bordered">
														<thead>
															<tr>
																<th>No</th>
																<th>Risk Tag</th>
																<th>Risk Event</th>
															</tr>
														</thead>
														<body>
															@isset($combineall)
															@foreach($combineall as $c1)
															<tr>
																<td>{{$c1['A']}}</td>
																<td>{{$c1['B']}}</td>
																<td>{{$c1['C']}}</td>
															</tr>
															@endforeach
															@endisset
														</body>
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
    <style type="text/css">
        .table-container
        {
            width: 100%;
            overflow-x: auto;
            overflow: auto;
            margin: 0 0 1em;
        }

        .text-td {
              text-align: -webkit-left;
        }

        th{
            text-align: center;
        }
        td, th {
            padding: 0;
            text-align: center;
        }
    </style>
    <style>
.page-break {
    page-break-after: always;
}
.vertical_Text {

            display: block;
            color: #000;
            width: 15px;

            -webkit-transform: rotate(-90deg);
            -moz-transform: rotate(-90deg);
     }
</style>
