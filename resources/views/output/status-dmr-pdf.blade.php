
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="application/pdf"; charset="utf-8">
	<title>Laporan Status DMR</title>
	<link href="{{ asset('vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">

	<!-- Chart.js') }} -->
	<script src="{{ URL::asset('vendors/Chart.js/dist/Chart.min.js') }}"></script>
	<!-- highcharts -->
	<script src="{{ URL::asset('js/highcharts.js') }}"></script>
	<script src="{{ URL::asset('js/exporting.js') }}"></script>
	<script type="text/javascript">
		var counter = 10;
		function countDown(){
			if(counter>=0){
				document.getElementById("timer").innerHTML = counter;
			}else{
					download();
			return;
			}
			counter -= 1;
			var counter2 = setTimeout("countDown()",100);
			return;
			}
			function download(){
				window.print('status-dmr.pdf');
			}
	</script>
</head>
	<body onload="countDown();">
		<input type="hidden" id="timer">
		<div class="row">
			<div class="col-md-8">
				<table>
					<tr>
						<td>
									<label class="col-md-2 col-md-3">Tahun Anggaran</label>
						</td>
						<td>: &nbsp;&nbsp;</td>
						<td>
									<p> {{ $input_tahun }} </p>
						</td>
					</tr>
					<tr>
						<td>
									<label class="col-md-2 col-md-3 " >Struktur Bisnis</label>
						</td>
						<td>: &nbsp;&nbsp;</td>
						<td>
									<p> {{ $input_sb->name }} </p>
						</td>
					</tr>
					<tr>
						<td>
									<label class="col-md-2 col-md-3 " >Distrik</label>
						</td>
						<td>: &nbsp;&nbsp;</td>
						<td>
									<p> {{ $input_distrik->name }} </p>
						</td>
					</tr>
					<tr>
						<td>
									<label class="col-md-2 col-md-3 " >Lokasi</label>
						</td>
						<td>: &nbsp;&nbsp;</td>
						<td>
									<p> {{ $input_lokasi->name }} </p>
						</td>
					</tr>
					<tr>
						<td>
									<label class="col-md-2 col-md-3 " >Fase</label>
						</td>
						<td>: &nbsp;&nbsp;</td>
						<td>
									<p> {{ $input_fase->name }} </p>
						</td>
					</tr>
					<tr>
						<td>
							<label class="col-md-2 col-md-3 " >Draft Form 6 Reimburse</label>
						</td>
						<td>: &nbsp;&nbsp;</td>
						<td>
							<p> {{ $input_form_6_reimburse!= null ? $input_form_6_reimburse->draft_versi : ''}} </p>
						</td>
					</tr>
					<tr>
						<td>
							<label class="col-md-2 col-md-3 " >Draft Form 6 Rutin</label>
						</td>
						<td>: &nbsp;&nbsp;</td>
						<td>
							<p> {{ $input_form_6_rutin != null ? $input_form_6_rutin->draft_versi : ''}} </p>
						</td>
					</tr>
					<tr>
						<td>
							<label class="col-md-2 col-md-3 " >Draft Form 10 Pengembangan Usaha</label>
						</td>
						<td>: &nbsp;&nbsp;</td>
						<td>
							<p> {{ $input_form_10_pu != null ? $input_form_10_pu->draft_versi : ''}} </p>
						</td>
					</tr>
					<tr>
						<td>
							<label class="col-md-2 col-md-3 " >Draft Form 10 Penguatan Kit</label>
						</td>
						<td>: &nbsp;&nbsp;</td>
						<td>
							<p> {{ $input_form_10_pk != null ? $input_form_10_pk->draft_versi : ''}} </p>
						</td>
					</tr>
					<tr>
						<td>
							<label class="col-md-2 col-md-3 " >Draft Form 10 PLN</label>
						</td>
						<td>: &nbsp;&nbsp;</td>
						<td>
							<p> {{ $input_form_10_pln != null ? $input_form_10_pln->draft_versi : ''}} </p>
						</td>
					</tr>
				</table>
			</div>
			<div class="col-md-4">
				&nbsp;
			</div>
				<br>
		</div>

		<div class="row">
	  <div class="col-md-7 col-sm-7 col-xs-7">
	    <div class="x_panel">
	      <div class="x_title">
	                    <div id="status-dmr">
            <script type="text/javascript">
              Highcharts.chart('status-dmr', {
                  chart: {
                      type: 'bar'
                  },
                  title: {
                      text: 'Status DMR'
                  },
                  xAxis: {
                      categories: ['Queue','Rejected', 'Revised', 'Approved', 'Draft', 'Submitted', 'DMR'],
      
                  },
                  yAxis: {
                      min: 0,
                      title: {
                          text: 'Population (Points)',
                          align: 'high'
                      },
                      labels: {
                          overflow: 'justify'
                      }
                  },
                  tooltip: {
                      valueSuffix: ' Points'
                  },
                  plotOptions: {
                      bar: {
                          dataLabels: {
                              enabled: true
                          }
                      }
                  },
                  legend: {
                      layout: 'vertical',
                      align: 'right',
                      verticalAlign: 'top',
                      x: -40,
                      y: 80,
                      floating: true,
                      borderWidth: 1,
                      backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
                      shadow: true
                  },
                  credits: {
                      enabled: false
                  },
                  series: [{
                      name: 'OH',
                      data: [{{json_encode($summary_dmr['OH']['queue'])}}, {{json_encode($summary_dmr['OH']['rejected'])}}, {{json_encode($summary_dmr['OH']['revised'])}}, {{json_encode($summary_dmr['OH']['approved'])}}, {{json_encode($summary_dmr['OH']['total'] - ($summary_dmr['OH']['queue'] + $summary_dmr['OH']['rejected'] + $summary_dmr['OH']['revised'] + $summary_dmr['OH']['approved']))}}]
                  }, {
                      name: 'EJ',
                      data: [{{json_encode($summary_dmr['EJ']['queue'])}}, {{json_encode($summary_dmr['EJ']['rejected'])}}, {{json_encode($summary_dmr['EJ']['revised'])}}, {{json_encode($summary_dmr['EJ']['approved'])}}, {{json_encode($summary_dmr['EJ']['total'] - ($summary_dmr['EJ']['queue'] + $summary_dmr['EJ']['rejected'] + $summary_dmr['EJ']['revised'] + $summary_dmr['EJ']['approved']))}}]
                  }, {
                      name: 'Investasi',
                      data: [{{json_encode($summary_dmr['Investasi']['queue'])}}, {{json_encode($summary_dmr['Investasi']['rejected'])}}, {{json_encode($summary_dmr['Investasi']['revised'])}}, {{json_encode($summary_dmr['Investasi']['approved'])}}, {{json_encode($summary_dmr['Investasi']['total'] - ($summary_dmr['Investasi']['queue'] + $summary_dmr['Investasi']['rejected'] + $summary_dmr['Investasi']['revised'] + $summary_dmr['Investasi']['approved']))}}]
                  }, {
                      name: 'Lainnya',
                      data: [{{json_encode($summary_dmr['Lainnya']['queue'])}}, {{json_encode($summary_dmr['Lainnya']['rejected'])}}, {{json_encode($summary_dmr['Lainnya']['revised'])}}, {{json_encode($summary_dmr['Lainnya']['approved'])}}, {{json_encode($summary_dmr['Lainnya']['total'] - ($summary_dmr['Lainnya']['queue'] + $summary_dmr['Lainnya']['rejected'] + $summary_dmr['Lainnya']['revised'] + $summary_dmr['Lainnya']['approved']))}}]
                  }]
              });
            </script>
          </div>
	      </div>
	    </div>
	  </div>



	  <div class="col-md-7 col-sm-7 col-xs-7">
	    <div class="x_panel">
	      <div class="x_title">
	          <div id="status-dmr-total">
            <script type="text/javascript">
              Highcharts.chart('status-dmr-total', {
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        type: 'pie'
                    },
                    title: {
                        text: 'Status DMR Total'
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                                style: {
                                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                }
                            }
                        }
                    },
                    series: [{
                        name: 'Brands',
                        colorByPoint: true,
                        data: [{
                            name: 'Draft',
                            y: {{json_encode($piechart_summary['N-A'])}}
                        }, {
                            name: 'Approved',
                            y: {{json_encode($piechart_summary['Approved'])}},
                            sliced: true,
                            selected: true
                        }, {
                            name: 'Revised',
                            y:{{json_encode($piechart_summary['Revised'])}}
                        }, {
                            name: 'Rejected',
                            y: {{json_encode($piechart_summary['Rejected'])}}
                        }, {
                            name: 'Queue',
                            y: {{json_encode($piechart_summary['Queue'])}}
                        }]
                    }]
                });
            </script>
          </div>
	      </div>
	    </div>
	  </div>
	</div>

	<div class="page-break"></div>

	<!-- Table LR Unit Pembangkit -->
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<strong>SUMMARY STATUS DMR AVAILABLE</strong>
				<br>
				<div class="x_content">
					<table id="datatable" class="table table-striped table-bordered">
						<thead style="background-color: #2a3f54; color: white;">
	                        <tr>
	                          	<th colspan="2"></th>
	                          	<th colspan="2">DMR Status</th>
	                          	<th colspan="4">Review Status</th>
	                        </tr>
	                        <tr>
	                          	<th>Parent</th>
	                          	<th>DMR</th>
	                          	<th>Submitted</th>
	                          	<th>Draft</th>
	                          	<th>Approved</th>
	                          	<th>Revised</th>
	                          	<th>Rejected</th>
	                          	<th>Queue</th>
	                        </tr>
	                    </thead>
	                    <tbody>
	                        @foreach($summary_dmr as $key => $summary)
	                        <tr>
	                          	<td>{{$key}}</td>
	                          	<td style="text-align: right">{{$summary['total']}}</td>
	                          	<td style="text-align: right">{{$summary['approved'] + $summary['revised'] + $summary['rejected'] + $summary['queue'] }}</td>
	                          	<td style="text-align: right">{{$summary['total'] - ($summary['approved'] + $summary['revised'] + $summary['rejected'] + $summary['queue']) }}</td>
	                          	<td style="text-align: right">{{$summary['approved']}}</td>
	                          	<td style="text-align: right">{{$summary['revised']}}</td>
	                          	<td style="text-align: right">{{$summary['rejected']}}</td>
	                          	<td style="text-align: right">{{$summary['queue']}}</td>
	                          
	                        </tr>
	                        @endforeach
	                    </tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<strong>Rincian Status DMR</strong>
				<br>
				<div class="x_content">
					<table id="datatable" class="table table-striped table-bordered">
	                    <thead style="background-color: #2a3f54; color: white;">
	                        <tr>
	                          <th>ID Dokumen</th>
	                          <th>Parent</th>
	                          <th>No PRK</th>
	                          <th>Nama PRK</th>
	                          <th>Anggaran PRK</th>
	                          <th>DMR Status</th>
	                          <th>Review Status</th>
	                          <th>Approved</th>
	                          <th>Approved by</th>
	                          <th>Rejected</th>
	                          <th>Revised</th>
	                          <th>Submitted</th>
	                        </tr>
                      	</thead>
                     	<tbody>
	                        @foreach($data_dmr as $key => $dmr)
	                        <tr>
	                          	<td>{{$dmr['dokumen_id']}}</td>
	                          	<td>{{$dmr['parent']}}</td>
	                          	<td>{{$dmr['prk']}}</td>
	                          	<td>{{$dmr['nama_prk']}}</td>
	                          	<td style="text-align: right">{{number_format($dmr['anggaran'], 0, '.', ',')}}</td>
	                          	<td>{{$dmr['dmr_status']}}</td>
	                          	<td>{{$dmr['review_status']}}</td>
	                          	<td>{{$dmr['approved_at']}}</td>
	                          	<td>{{$dmr['approved_by']}}</td>
	                          	<td>{{$dmr['rejected_at']}}</td>
	                          	<td>{{$dmr['revised_at']}}</td>
	                          	<td>{{$dmr['submitted_at']}}</td>
	                        </tr>
	                        @endforeach
                      	</tbody>
					</table>
				</div>
		 </div>
	 </div>

	</body>
</html>

		<style type="text/css">
		.page-break {
		    page-break-after: always;
		}
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
