<!DOCTYPE html>
<html>

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
		@media print {
			body, page{
				margin: 0;
				box-shadow: 0;
				font-family: "Arial Black", Helvetica, sans-serif;
			}
		}
		.table-ai{
			margin-left: 0;
			margin-right: 0;
			margin-bottom: 0px;
			font-size: 13px;
			border-spacing: 0px;
			border-collapse: collapse;
			/*border: 1px solid #ddd!important;*/
		}
		td, th {padding: 4px;}
		.table-ai th, .table-ai td, .table-ai tr{border: 1px solid #ddd}
	</style>

<body>

	<div class="row" style>
		<div class="col-md-12">
			
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-12">
			<div><h2>{{$judul}}</h2></div>
		</div>
		<div class="col-md-12">
			<table>
				<tr>
					<th>Strategi Bisnis</th>
					<td>: {{$input_tahun}}</td>
				</tr>
				<tr>
					<th>Strategi Bisnis</th>
					<td>: {{$input_sb->name}}</td>
				</tr>
				<tr>
					<th>Distrik</th>
					<td>: {{$input_distrik->name}}</td>
				</tr>
				<tr>
					<th>Lokasi</th>
					<td>: {{$input_lokasi->name}}</td>
				</tr>				
			</table>
		</div>
		<div class="col-md-12">
			<table class="table-ai">
				<thead>
					<tr>
						<th>No </th>
                    	<th>Nomor PRK </th>
                    	<th>Deskripsi Kegiatan </th>
                    	<th>Laba Rugi </th>
                    	<th>Cashflow </th>
                    	<th>Lokasi</th>
                    	<th>Nilai Persetujuan Proses Kontrak Pengadaan (Rp) </th>
                    	<th>Bulan Disburse Beban</th>
                    	<th>Bulan Disburse Cashflow</th>
					</tr>
				</thead>
				<tbody>
					<?php $i = 1?>
                            @foreach($data['C'] as $key => $colC)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$colC->value}}</td>
                                    <td>{{$data['D'][$key]->value}}</td>
                                    <td style="text-align: right">{{number_format($data['E'][$key]->value,0,",",".")}}</td>
                                    <td style="text-align: right">{{number_format($data['F'][$key]->value,0,",",".")}}</td>
                                    <td>{{$data['G'][$key]->value}}</td>
                                    <td style="text-align: right">{{number_format(($data['H'][$key]->value=="" ? 0 : $data['H'][$key]->value),0,",",".")}}</td>
                                    <td>{{$data['I'][$key]->value}}</td>
                                    <td>{{$data['J'][$key]->value}}</td>
                                </tr>
                            @endforeach

                        </tbody>
                        <tfoot>
                            <tr>
                                 <th colspan="3">TOTAL </th>
                                 <th style="text-align: right">{{number_format($data['totalE'][0]->value,2,",",".")}} </th>
                                 <th style="text-align: right">{{number_format($data['totalF'][0]->value,2,",",".")}} </th>
                                 <th></th>
                                 <th style="text-align: right">{{number_format($data['totalH'][0]->value,2,",",".")}} </th>
                                 <th></th>
                                 <th></th>
                            </tr>
                        </tfoot>
			</table>
		</div>
	</div>

</body>
</html>