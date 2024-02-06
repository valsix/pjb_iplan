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
			font-size: 13px;
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
						<th>No PRK</th>
                        <th>PRK Parent</th>
                        <th>PRK Inti</th>
                        <th>PRK Kegiatan</th>
                        <th>Anggaran</th>
                        <th>Disburse</th>  
                        
					</tr>
					
					
				</thead>
				<tbody>
                           	<!-- Form 6 - Rutin -->
                           	<?php
                              	if ($int_input_lokasi != NULL) {
                              		for ($i = 0 ; $i < $int_count_rutin ; $i++) { ?>
                              			<tr>
	                              			<td>{{ $cellI_rutin[$i]->value }}</td>
	                              			<td>{{ $cellR_rutin[$i]->value }}</td>
	                              			<td>{{ $cellS_rutin[$i]->value }}</td>
	                              			<td>{{ $cellT_rutin[$i]->value }}</td>
	                              			<td>{{ $cellAN_rutin[$i]->value }}</td>
	                              			<td>{{ $cellAV_rutin[$i]->value }}</td>
	                              		</tr>
                              	<?php
                              		}
                              	}
                              ?>

                           	<!-- Form 6 - Reimburse -->
                              <?php
                              	if ($int_input_lokasi != NULL) {
                              		for ($i = 0 ; $i < $int_count_reimburse ; $i++) { ?>
                              			<tr>
	                              			<td>{{ $cellI_reimburse[$i]->value }}</td>
	                              			<td>{{ $cellR_reimburse[$i]->value }}</td>
	                              			<td>{{ $cellS_reimburse[$i]->value }}</td>
	                              			<td>{{ $cellT_reimburse[$i]->value }}</td>
	                              			<td>{{ $cellAN_reimburse[$i]->value }}</td>
	                              			<td>{{ $cellAV_reimburse[$i]->value }}</td>
	                              		</tr>
                              	<?php
                              		}
                              	}
                              ?>

                              <!-- Form 10 - AI KIT -->
                              <?php
                              	if ($int_input_lokasi != NULL) {
                              		for ($i = 0 ; $i < $int_count_penguatan_kit ; $i++) { ?>
                              			<tr>
	                              			<td>{{ $cellI_KIT[$i]->value }}</td>
	                              			<td>{{ $cellR_KIT[$i]->value }}</td>
	                              			<td>{{ $cellS_KIT[$i]->value }}</td>
	                              			<td>{{ $cellT_KIT[$i]->value }}</td>
	                              			<td>{{ $cellAI_KIT[$i]->value }}</td>
	                              			<td>{{ $cellAT_KIT[$i]->value }}</td>
	                              		</tr>
                              	<?php
                              		}
                              	}
                              ?>

                              <!-- Form 10 - AI Pengembangan Usaha -->
                              <?php
                              	if ($int_input_lokasi != NULL) {
                              		for ($i = 0 ; $i < $int_count_pengembangan_usaha ; $i++) { ?>
                              			<tr>
	                              			<td>{{ $cellJ_PU[$i]->value }}</td>
	                              			<td>{{ $cellS_PU[$i]->value }}</td>
	                              			<td>{{ $cellT_PU[$i]->value }}</td>
	                              			<td>{{ $cellU_PU[$i]->value }}</td>
	                              			<td>{{ $cellAJ_PU[$i]->value }}</td>
	                              			<td>{{ $cellAU_PU[$i]->value }}</td>
	                              		</tr>
                              	<?php
                              		}
                              	}
                              ?>

                              <!-- Form 10 - PLN -->
                              <?php
                              	if ($int_input_lokasi != NULL) {
                              		for ($i = 0 ; $i < $int_count_pln ; $i++) { ?>
                              			<tr>
	                              			<td>{{ $cellJ_PLN[$i]->value }}</td>
	                              			<td>{{ $cellS_PLN[$i]->value }}</td>
	                              			<td>{{ $cellT_PLN[$i]->value }}</td>
	                              			<td>{{ $cellU_PLN[$i]->value }}</td>
	                              			<td>{{ $cellAK_PLN[$i]->value }}</td>
	                              			<td>{{ $cellAR_PLN[$i]->value }}</td>
	                              		</tr>
                              	<?php
                              		}
                              	}
                              ?>

                              <!-- Form 6 - RKAU I-PEG -->
                              <?php
                              	if ($int_input_lokasi != NULL) {
                              		for ($i = 0 ; $i < $int_count_I_PEG ; $i++) { ?>
                              			<tr>
	                              			<td>{{ $cellE_I_PEG[$i]->value }}</td>
	                              			<td>{{ $cellF_I_PEG[$i]->value }}</td>
	                              			<td></td>
	                              			<td></td>
	                              			<td>{{ $cellH_I_PEG[$i]->value }}</td>
	                              			<td>{{ $cellJK_I_PEG[$i] }}</td>
	                              		</tr>
                              	<?php
                              		}
                              	}
                              ?>

                              <!-- Form 6 - RKAU I-ADM -->
                              <?php
                              	if ($int_input_lokasi != NULL) {
                              		for ($i = 0 ; $i < $int_count_I_ADM ; $i++) { ?>
                              			<tr>
	                              			<td>{{ $cellE_I_ADM[$i]->value }}</td>
	                              			<td>{{ $cellF_I_ADM[$i]->value }}</td>
	                              			<td></td>
	                              			<td></td>
	                              			<td>{{ $cellH_I_ADM[$i]->value }}</td>
	                              			<td>{{ $cellJK_I_ADM[$i] }}</td>
	                              		</tr>
                              	<?php
                              		}
                              	}
                              ?>

                              <!-- Form 6 - RKAU I-PENDUKUNG EP -->
                              <?php
                              	if ($int_input_lokasi != NULL) {
                              		for ($i = 0 ; $i < $int_count_I_PENDUKUNG ; $i++) { ?>
                              			<tr>
	                              			<td>{{ $cellC_I_PENDUKUNG[$i]->value }}</td>
	                              			<td>{{ $cellD_I_PENDUKUNG[$i]->value }}</td>
	                              			<td></td>
	                              			<td></td>
	                              			<td>{{ $cellE_I_PENDUKUNG[$i]->value }}</td>
	                              			<td>{{ $cellF_I_PENDUKUNG[$i]->value }}</td>
	                              		</tr>
                              	<?php
                              		}
                              	}
                              ?>

                              <!-- Form 6 - RKAU I-BIAYA USAHA LAINNYA -->
                              <?php
                              	if ($int_input_lokasi != NULL) {
                              		for ($i = 0 ; $i < $int_count_I_BIAYA ; $i++) { ?>
                              			<tr>
	                              			<td>{{ $cellE_I_BIAYA[$i]->value }}</td>
	                              			<td>{{ $cellF_I_BIAYA[$i]->value }}</td>
	                              			<td></td>
	                              			<td></td>
	                              			<td>{{ $cellH_I_BIAYA[$i]->value }}</td>
	                              			<td>{{ $cellJK_I_BIAYA[$i] }}</td>
	                              		</tr>
                              	<?php
                              		}
                              	}
                              ?>

                              <!-- Form 6 - RKAU I-DILUAR USAHA -->
                              <?php
                              	if ($int_input_lokasi != NULL) {
                              		for ($i = 0 ; $i < $int_count_I_DILUAR ; $i++) { ?>
                              			<tr>
	                              			<td>{{ $cellE_I_DILUAR[$i]->value }}</td>
	                              			<td>{{ $cellF_I_DILUAR[$i]->value }}</td>
	                              			<td></td>
	                              			<td></td>
	                              			<td>{{ $cellH_I_DILUAR[$i]->value }}</td>
	                              			<td>{{ $cellJK_I_DILUAR[$i] }}</td>
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