<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="application/pdf">
	<title>Laporan Rincian Penetapan Anggaran Investasi</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link href="{{ asset('vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">

	<!-- Chart.js-->
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
				window.print('rincian-anggaran-investasi.pdf');
			}
	</script>

	<style type="text/css">
		body {
			background: rgb(255, 255, 255);
		}
		page {
			background: white;
			display: block;
			margin: 0 auto;
			margin-bottom: 0.5cm;
			box-shadow: 0 0 0.5cm rgba(0, 0, 0, 0.5);
		}
		page[size="A4"]{
			width: 21cm;
			height: 29.7cm;
		}
		page[size="A4"][layout="landscape"]{
			width: 29.7cm;
			height: 21cm;
		}
		@media print {
			body, page{
				margin: 0;
				box-shadow: 0;
				font-family: "Arial Black", Helvetica, sans-serif;
			}
		}
		.table-ai{
			margin-left: 16%;
			margin-right: 16%;
			margin-bottom: 20px;
			font-size: 12px;
			border-spacing: 0px;
			border-collapse: collapse;
			/*border: 1px solid #ddd!important;*/
		}
		td, th {padding: 4px;}
		.table-ai th, .table-ai td, .table-ai tr{border: 1px solid #ddd}
	</style>
</head>
<body onload="countDown();">
<page size="A4">
<input type="hidden" id="timer">
	<div class="row">
		<div class="col-md-8">
			<table>
				<tr>
					<td><label class="col-md-3">Tahun Anggaran</label></td>
					<td>: &nbsp;&nbsp;</td>
          <td><p>{{$fill[0]}}</p></td>
				</tr>
				<tr>
					<td><label class="col-md-3">Struktur Bisnis</label></td>
					<td>: &nbsp;&nbsp;</td>
          <td><p>{{$fill[1]->name}}</p></td>
				</tr>
				<tr>
					<td><label class="col-md-3">Distrik</label></td>
					<td>: &nbsp;&nbsp;</td>
          <td><p>{{$fill[2]->name}}</p></td>
				</tr>
				<tr>
					<td><label class="col-md-3">Lokasi</label></td>
					<td>: &nbsp;&nbsp;</td>
          <td><p>{{$fill[3]->name}}</p></td>
				</tr>
				<tr>
					<td><label class="col-md-3">Fase</label></td>
					<td>: &nbsp;&nbsp;</td>
					<td><p>{{$fill[4]->name}}</p></td>
				</tr>
				<tr>
					<td><label class="col-md-3">Draft</label></td>
					<td>: &nbsp;&nbsp;</td>
					<td><p>{{$fill[5]}}</p></td>
				</tr>
			</table>
		</div>
	</div>
	<br><br>

    <!-- Grafik Rencana Disburse -->
<div class="row">
  <div class="col-md-6 col-sm-6 col-xs-6">
    <div class="x_panel">
      <div class="x_title">
                 
          <div id="rencana-disburse">
             <script type="text/javascript">
               Highcharts.chart('rencana-disburse', {

                  title: {
                      text: 'Rencana Disburse'
                  },

                  yAxis: {
                      title: {
                          text: 'Dalam Milyar Rp'
                      }
                  },

                  legend: {
                      layout: 'vertical',
                      align: 'right',
                      verticalAlign: 'middle'
                  },

                  xAxis: {
                        //disburseBulan
                        categories: [
                        <?php
                        if ($count != NULL) {
                          foreach ($totalSumByMonth1 as $key => $value) {
                            echo "'".$value["bulan"]."',";
                          }
                        }
                        ?>
                        ]
                        //categories: ['Mar', 'Mar', 'Apr', 'Jul', 'Dec', 'Dec', ' ov', 'Sep', 'Jun', 'Mar']
                    },

                  series: [{
                            name: 'Disburse',
                      data: [
                        <?php
                        if ($count != NULL) {
                          $keys = array_keys($totalSumByMonth1);
                          foreach ($totalSumByMonth1 as $key => $value) {
                            echo $value["value"].',';
                          }
                        }
                        ?>
                        ]
                       //data: [250, 617, 3467, 3467, 3542, 8327, 8327, 9042, 10632, 10757 ]
                  },],

                  responsive: {
                      rules: [{
                          condition: {
                              maxWidth: 500
                          },

                  chartOptions: {
                      legend: {
                          layout: 'horizontal',
                          align: 'center',
                          verticalAlign: 'bottom'
                        }
                    }
                  }]
                }
            });

           </script>
         </div>
      </div>
    </div>
  </div>

<!-- Grafik Target Terkontrak -->

  <div class="col-md-6 col-sm-6 col-xs-6">
    <div class="x_panel">
      <div class="x_title">
          <div id="target-terkontrak">
              <script type="text/javascript">
                Highcharts.chart('target-terkontrak', {

                  title: {
                      text: 'Target Terkontrak'
                  },

                  yAxis: {
                      title: {
                          text: 'Dalam Milyar Rp'
                      }
                  },

                  legend: {
                      layout: 'vertical',
                      align: 'right',
                      verticalAlign: 'middle'
                  },

                  xAxis: {
                      categories: [
                        <?php
                        if ($count != NULL) {
                          foreach ($totalSumByMonth2 as $key => $value) {
                            echo "'".$value["bulan"]."',";
                          }
                        }
                        ?>
                      ]
                      //categories: ['Mar', 'Mar', 'Apr', 'Jul', 'Dec', 'Dec', 'Nov', 'Sep', 'Jun', 'Mar']
                  },

                  series: [ {
                      name: 'Ai',
                      data: [
                        <?php
                        if ($count != NULL) {
                          $keys = array_keys($totalSumByMonth2);
                          foreach ($totalSumByMonth2 as $key => $value) {
                            // dd($value["value"]);
                            echo $value["value"].',';
                          }
                        }
                        ?>
                      ]
                  },],

                  responsive: {
                      rules: [{
                          condition: {
                              maxWidth: 500
                          },
                  chartOptions: {
                      legend: {
                          layout: 'horizontal',
                          align: 'center',
                          verticalAlign: 'bottom'
                        }
                      }
                    }]
                  }

               });

              </script>
          </div>       
      </div>
    </div>
  </div>
</div>
<br><br>
<br><br>
	<div class="row">
		<div class="col-md-12">
			<div><h2>{{$judul}}</h2></div>
		</div>
		<div class="col-md-12">
			<table class="table-ai">
				<thead>
					<tr>
						<th rowspan="2">Kode PRK</th>
                        <th rowspan="2">Deskripsi PRK Kegiatan</th>
                        <th rowspan="2">Anggaran Investasi Luncuran</th>
                        <th rowspan="2">Anggaran Investasi Murni</th>
                        <th rowspan="2">Total Anggaran Investasi</th>
                        <th colspan="2">Target Terkontrak</th>  
                        <th rowspan="2">Levering (Bulan)</th>
                        <th rowspan="2">Pengadaan Pusat/Unit</th>
                        <th colspan="2">Disburse tahun ke- {{$fill[0]}}</th>
					</tr>
					<tr>
						<th>Bulan</th>
						<th>Tahun</th>
						<th>Bulan</th>
						<th>Nilai</th>
					</tr>
					<tr>
						<th>1</th>
						<th>2</th>
						<th>3</th>
						<th>4</th>
						<th>5=3+4</th>
						<th>6</th>
						<th>7</th>
						<th>8</th>
						<th>9</th>
						<th>10</th>
						<th>11</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if( $count != NULL ) {
						for ($i = 0 ; $i < $count; $i++) { 
						?> 
					<tr>
						<td>{{$kodePRK[$i]->value}}</td>
            <td>{{$deskPRK[$i]->value}}</td>
            <td style="text-align: right">{{App\Http\Controllers\Controller::numberFormat(round($anggaranIL[$i]->value),0)}}</td>
            <td style="text-align: right">{{App\Http\Controllers\Controller::numberFormat(round($anggaranIM[$i]->value),0,0)}}</td>
            <td style="text-align: right">{{App\Http\Controllers\Controller::numberFormat(round($totalAnggaranInvest[$i]->value),0)}}</td>
            <td>{{$targetBulan[$i]->value}}</td>
            <td>{{$targetTahun[$i]->value}}</td>
            <td>{{$levering[$i]->value}}</td>
            <td>{{$pengadaanPusat[$i]->value}}</td>
            <td>{{$disburseBulan[$i]->value}}</td>
            <td style="text-align: right">{{App\Http\Controllers\Controller::numberFormat(round($disburseNilai[$i]->value),0)}}</td>
					</tr>
						<?php 
							}
					} 
					?>
				</tbody>
			</table>
		</div>
	</div>


</page>

</body>
</html>