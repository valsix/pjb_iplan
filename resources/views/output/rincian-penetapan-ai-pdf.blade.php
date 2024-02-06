<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title></title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
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
		page[size="A4"][layout="portrait"]{
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
<body>
<page size="A4">
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
					<td><p>{{$fill[1]}}</p></td>
				</tr>
				<tr>
					<td><label class="col-md-3">Distrik</label></td>
					<td>: &nbsp;&nbsp;</td>
					<td><p>{{$fill[2]}}</p></td>
				</tr>
				<tr>
					<td><label class="col-md-3">Lokasi</label></td>
					<td>: &nbsp;&nbsp;</td>
					<td><p>{{$fill[3]}}</p></td>
				</tr>
				<tr>
					<td><label class="col-md-3">Fase</label></td>
					<td>: &nbsp;&nbsp;</td>
					<td><p>{{$fill[4]}}</p></td>
				</tr>
				<tr>
					<td><label class="col-md-3">Draft</label></td>
					<td>: &nbsp;&nbsp;</td>
					<td><p>{{$fill[5]}}</p></td>
				</tr>
			</table>
		</div>
	</div>
	<br>
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
                        <th colspan="2">Disburse tahun ke- n</th>
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
						<td>{{$anggaranIL[$i]->value}}</td>
						<td>{{$anggaranIM[$i]->value}}</td>
						<td>{{$totalAnggaranInvest[$i]->value}}l</td>
						<td>{{$targetBulan[$i]->value}}</td>
						<td>{{$targetTahun[$i]->value}}</td>
						<td>{{$levering[$i]->value}}</td>
						<td>{{$pengadaanPusat[$i]->value}}</td>
						<td>{{$disburseBulan[$i]->value}}</td>
						<td>{{$disburseNilai[$i]->value}}</td>
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