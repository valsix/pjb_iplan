
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="application/pdf"; charset="utf-8">
	<title>Laporan Program Strategis</title>
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
				window.print('biayapemeliharaan.pdf');
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

		<div class="row">
	  <div class="col-md-7 col-sm-7 col-xs-7">
	    <div class="x_panel">
	      <div class="x_title">
	          <div id="Jumlah-program-strategis">
	            <script type="text/javascript">
	              Highcharts.chart('Jumlah-program-strategis', {
	                  chart: {
	                      plotBackgroundColor: null,
	                      plotBorderWidth: null,
	                      plotShadow: false,
	                      type: 'pie'
	                  },
	                    title: {
	                        text: 'Jumlah Program Strategis'
	                    },
	                    tooltip: {
	                        pointFormat: '{series.name}: {point.cursore},<b>{point.percentage:.1f}%</b>'
	                    },
	                    plotOptions: {
	                        pie: {
	                            allowPointSelect: true,
	                            cursor: 'pointer',
	                            dataLabels: {
	                                enabled: true,
	                                format: '<b>{point.name}</b>: {point.cursore},{point.percentage:.1f} %',
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
	                            name: 'Program Investasi',
	                            y: @if(isset($percentPI)){{$percentPI}} @else 0 @endif,
	                            cursore: @if(isset($countPI)){{$countPI}} @else 0 @endif
	                        }, {
	                            name: 'Program Proyek',
	                            y: @if(isset($percentPP)){{$percentPP}} @else 0 @endif,
	                            sliced: true,
	                            selected: true,
	                            cursore: @if(isset($countPP)){{$countPP}} @else 0 @endif
	                        }, {
	                            name: 'Program Overhaul',
	                            y: @if(isset($percentPO)){{$percentPO}} @else 0 @endif,
	                            cursore: @if(isset($countPO)){{$countPO}} @else 0 @endif
	                        }]
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
	          <div id="anggaran-program-strategis">
	            <script type="text/javascript">
	               Highcharts.chart('anggaran-program-strategis', {
	                  chart: {
	                        type: 'column'
	                    },
	                    title: {
	                        text: 'Anggaran Program Strategis'
	                    },
	                    xAxis: {
	                        type: 'category'
	                    },

	                    legend: {
	                        enabled: false
	                    },
	                    plotOptions: {
	                        series: {
	                            borderWidth: 0,
	                            dataLabels: {
	                                enabled: true,
	                                format: '{point.y:,.0f}'
	                            }
	                        }
	                    },

	                    tooltip: {
	                        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
	                        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:,.0f}</b> of total<br/>'
	                    },

	                    series: [{
	                        name: 'Brands',
	                        colorByPoint: true,
	                        data: [{
	                            name: 'Program Investasi',
	                            y: @if(isset($sumPI)){{$sumPI}} @else 0 @endif,

	                        }, {
	                            name: 'Program Proyek',
	                            y: @if(isset($sumPP)){{$sumPP}} @else 0 @endif,

	                        }, {
	                            name: 'Program Overhaul',
	                            y: @if(isset($sumPO)){{$sumPO}} @else 0 @endif,

	                        }]
	                    }],

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
							<strong>Tabel Program Investasi</strong>
							<br>
							<div class="x_content">
								<table id="datatable" class="table table-striped table-bordered">
									<thead>
										<tr>
											<th>No</th>
											<th>No PRK</th>
											<th>Program</th>
											<th>AKI</th>
										</tr>
									</thead>
									<body>
										<?php $count = 0 ?>
										@isset($combineall)
										@foreach($combineall as $c1)
											<tr>
												<td>{{$count+1}}</td>
												<td>{{$c1["noprk"]}}</td>
												<td>{{$c1["desprk"]}}</td>
												<td style="text-align: right
">{{App\Http\Controllers\Controller::numberFormat($c1["aki"],0)}}</td>
											</tr>
											<?php $count+=1; ?>
										@endforeach
										@endisset
									</body>
								</table>
							</div>
						</div>
		</div>

		<div class="row">
					 <div class="col-md-12 col-sm-12 col-xs-12">
							<strong>Tabel Program Proyek</strong>
							<br>
							<div class="x_content">
								<table id="datatable" class="table table-striped table-bordered">
									<thead>
										<tr>
											<th>No</th>
											<th>No PRK</th>
											<th>Program</th>
											<th>Anggaran</th>
										</tr>
									</thead>
									<body>
										<?php $count = 0 ?>
										@isset($combine2)
											@foreach($combine2 as $c2)
											<tr>
												<td>{{$count+1}}</td>
												<td>{{$c2['I']}}</td>
												<td>{{$c2['T']}}</td>
												<td style="text-align: right
">{{App\Http\Controllers\Controller::numberFormat($c2['AN'],0)}} </td>
											</tr>
												<?php $count+=1; ?>
											@endforeach
										@endisset
									</body>
								</table>
							</div>
					 </div>
	 </div>

	 <div class="page-break"></div>

	 <div class="row">
					 <div class="col-md-12 col-sm-12 col-xs-12">
						 <strong>Tabel Program Overhaul</strong>
						 <br>
						 <div class="x_content">
							 <table id="datatable" class="table table-striped table-bordered">
								 <thead>
									 <tr>
										 <th>No</th>
										 <th>No PRK</th>
										 <th>Program</th>
										 <th>Anggaran</th>
									 </tr>
								 </thead>
								 <body>
									 <?php $count = 0 ?>
									 @isset($prkInti)
										 @foreach($prkInti as $pi)
										 <tr>
											 <td>{{$count+1}}</td>
											 <td>{{$pi['key']}}</td>
											 <td>{{$pi['inti']}}</td>
											 <td style="text-align: right
">{{App\Http\Controllers\Controller::numberFormat($pi['sum'],0)}}</td>
										 </tr>
											 <?php $count+=1; ?>
										 @endforeach
									 @endisset
								 </body>
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
