<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="application/pdf"; charset="utf-8">
	<title>Laporan Biaya Pemeliharaan Rutin</title>
	<!-- Chart.js') }} -->
	<script src="{{ URL::asset('vendors/Chart.js/dist/Chart.min.js') }}"></script>
	<!-- highcharts -->
	<script src="{{ URL::asset('js/highcharts.js') }}"></script>
	<script type="text/javascript">
		var counter = 10;
		function countDown(){
			if(counter >= 0){
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
				window.print();
				return false;
			}
	</script>
</head>
<body onload="countDown();">
	<div class="row">
	<div class="col-md-8">
		<table border="1">
			<input type="hidden" id="timer">
			<tr>
				<td>
					<label class="col-md-2 col-md-3">Tahun Anggaran</label>
				</td>
				<td>&nbsp;: &nbsp;&nbsp;</td>
				<td><label class="col-md-2 col-md-3">{{$tahun}}</label></td>
			</tr>
			<tr>
				<td>
					<label class="col-md-2 col-md-3 " >Struktur Bisnis</label>
				</td>
				<td>&nbsp;: &nbsp;&nbsp;</td>
				<td><label class="col-md-2 col-md-3">{{$sbb->name}}</label></td>
			</tr>
			<tr>
				<td>
					<label class="col-md-2 col-md-3 " >Distrik</label>
				</td>
				<td>&nbsp;: &nbsp;&nbsp;</td>
				<td><label class="col-md-2 col-md-3">{{$distr1}}</label></td>
			</tr>
			<tr>
				<td>
					<label class="col-md-2 col-md-3 " >Lokasi</label>
				</td>
				<td>&nbsp;: &nbsp;&nbsp;</td>
				<td> <label class="col-md-2 col-md-3">{{ $lokasi }}</label></td>
			</tr>
      <tr>
        <td>
          <label class="col-md-2 col-md-3 " >Fase</label>
        </td>
        <td>&nbsp;: &nbsp;&nbsp;</td>
        <td><label class="col-md-2 col-md-3">{{ $fase }}</label></td>
      </tr>
			<tr>
				<td>
					<label class="col-md-2 col-md-3 " >Form 6 - Rutin</label>
				</td>
				<td>&nbsp;: &nbsp;&nbsp;</td>
				<td><label class="col-md-2 col-md-3">{{ isset($input_rutin) ? $input_rutin->draft_versi.' - '.$input_rutin->name : '' }}</label></td>
			</tr>
		</table>
		<center><h2>LAPORAN BIAYA PEMELIHARAAN RUTIN</h2></center>
	</div>
	<div class="col-md-4">
		&nbsp;
	</div>
	<br>
</div>

<?php 
  if ($lokasi) {
      $nl = $overhaul_nilai;

      $en = $engineering_nilai;

      $tr = $total - $nl - $en;
      if ($nl=="0" && $en=="0") {
        echo "";
      }else{
        $nl_persen = $nl/$total*100;
        $en_persen = $en/$total*100;
        $tr_persen = $tr/$total*100;
      }
    }
 ?>
<!-- Grafik  -->
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
          <div id="rincian-aktifitas-pemeliharaan">
        	<script type="text/javascript"> try { this.print(); } </script>
            <script type="text/javascript">
              Highcharts.chart('rincian-aktifitas-pemeliharaan', {
                  chart: {
                      plotBackgroundColor: null,
                      plotBorderWidth: null,
                      plotShadow: false,
                      type: 'pie'
                  },
                    title: {
                        text: 'Rincian Biaya Pemeliharaan Rutin'
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.percentage:.1f}</b>'
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
                        data:[@if($lokasi ){
                               name: '<?php echo $overhaul.', '.Round($nl/1000000).'<br>'; ?>',
                               y: <?php if($nl == '0' && $en== '0'){echo "0";}else{echo $nl_persen;} ?>
                              },
                              {
                                name: '<?php echo $engineering.', '.Round($en/1000000).'<br>'; ?>',
                                y: <?php if($nl == '0' && $en== '0'){echo "0";}else{echo $en_persen;} ?>
                              },
                              {
                                name: '<?php echo 'Rutin Pemeliharaan'.', '.Round($tr/1000000).'<br>'; ?>',
                                y: <?php if($nl == '0' && $en== '0'){echo "0";}else{echo $tr_persen;} ?>
                              }
                              @endif]   
                            
                        
                    }]
                });

            </script>
          </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2 style="font-size: 18px; margin-right: 10px;">ANGGARAN BIAYA PEMELIHARAAN RUTIN PER AKTIVITAS</h2>
        <h5 style="margin-top: 8px;"> (Dalam Ribuan Rupiah)</h5>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<table id="datatable" border="0" class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>Keterangan</th>
							<th>RKAP <br>{{$tahun}}</th>
						</tr>
						<tr>
							<th>1</th>
							<th>2</th>
						</tr>
					</thead>
					<tbody>
					@if($tahun && $lokasi && $fase)
                <tr>
                  <td style="text-align: left">{{$oj}}</td>
                  <td style="text-align: right">
                    {{number_format(round($oj_nilai), 0,',','.')}}
                  </td>
                </tr>
                <tr>
                  <td style="text-align: left">{{$operasi}}</td>
                  <td style="text-align: right">
                    {{number_format(round($nilai_operasi), 0,',','.')}}
                  </td>
                </tr>
                <tr>
                  <td style="text-align: left"><?php echo $kimia .' & '.$lab; ?></td>
                  <td style="text-align: right">
                    {{number_format(round($nilai_kimia),0,',','.')}}
                  </td>
                </tr>
                <tr>
                  <td style="text-align: left">{{$k3}}</td>
                  <td style="text-align: right">
                    {{number_format(round($nilai_k3),0,',','.')}}
                  </td>
                </tr>
                <tr>
                  <td style="text-align: left">{{$lingkungan}}</td>
                  <td style="text-align: right">
                    {{number_format(round($nilai_lingkungan), 0,',','.')}} 
                  </td>
                </tr>
                <tr>
                  <td style="text-align: left">{{$preventive}}</td>
                  <td style="text-align: right">
                    {{number_format(round($nilai_preventive), 0,',','.')}}
                  </td>
                </tr>
                <tr>
                  <td style="text-align: left">{{$predictive}}</td>
                  <td style="text-align: right">
                    {{number_format(round($nilai_predictive), 0,',','.')}}
                  </td>
                </tr>
                <tr>
                  <td style="text-align: left">{{$corective}}</td>
                  <td style="text-align: right">
                    {{number_format(round($nilai_corective),0, ',','.')}}
                  </td>
                </tr>
                <tr>
                  <td style="text-align: left">{{$emergency}}</td>
                  <td style="text-align: right">
                    {{number_format(round($nilai_emergency),0, ',','.')}}
                  </td>
                </tr>
                <tr>
                  <td style="text-align: left"><?php echo $overhaul.' / '.$inspection; ?></td>
                  <td style="text-align: right">
                    {{number_format(round($overhaul_nilai), 0,',','.')}}
                  </td>
                </tr> 
                <tr>
                  <td style="text-align: left">{{$breakdown}}</td>
                  <td style="text-align: right">
                    {{number_format(round($nilai_breakdown),0, ',','.')}}
                  </td>
                </tr>
                <tr>
                  <td style="text-align: left"><?php echo $engineering.' / '.$project.' / '.$modifikasi; ?></td>
                  <td style="text-align: right">
                    {{number_format(round($engineering_nilai), 0,',','.')}}
                  </td>
                </tr>
                <tr>
                  <td style="text-align: left"><?php echo $non.' / '.$tu.' / '.$sarana; ?></td>
                  <td style="text-align: right">
                    {{number_format(round($nilai_non), 0,',','.')}} 
                  </td>
                </tr>
                <tr>
                  <td style="background:aqua; text-align: left;">Total Pemeliharaan</td>
                  <td style="background:aqua; text-align: right;">
                    {{number_format(round($total),0, ',','.')}}
                  </td>
                </tr>
            @endif
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<style type="text/css">
	#chartdiv {
	  width: 100%;
	  height: 500px;
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
</body>
</html>
