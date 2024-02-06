<table>
    <tr>
        <td colspan="14">
            <h1>MONITORING AI PJB</h1>
        </td>
    </tr>
    <tr>
        <th>Tahun Anggaran</th>
        <td>{{$input_tahun}}</td>
    </tr>

    <tr>
        <th>Strategi Bisnis</th>
        <td>{{$distrik->strategi_bisnis->name}}</td>
    </tr>
    <tr>
        <th>Distrik</th>
        <td>{{$distrik->name}}</td>
    </tr>
    <tr>
        <th>Lokasi</th>
        <td>{{(!empty($lokasi) ? $lokasi->name : '')}}</td>
    </tr>
    <tr>
        <th>Fase</th>
        <td>Ketetapan</td>
    </tr>
    <tr>
        <th>s.d Bulan</th>
        <td>{{$nama_bln_dipilih}}</td>
    </tr>
    <tr>
        <th>Form 10 Pengembangan Usaha</th>
        <td></td>
    </tr>
    <tr>
        <th>Form 10 Penguatan KIT</th>
        <td></td>
    </tr>
    <tr>
        <th>Form 10 Penguatan PLN</th>
        <td></td>
    </tr>
</table>
<div class="row">
    <div class="col-md-12">
      	<table>
	        <thead>
			<!-- START - Perubahan CHANGE C-066842-->
				<tr>
					<th rowspan="2" style="vertical-align: middle;text-align: center;">No.</th>
					<th rowspan="2" style="vertical-align: middle;text-align: center;">PRK</th>
					<th rowspan="2" style="vertical-align: middle;text-align: center;">Program</th>
					<th rowspan="2" style="vertical-align: middle;text-align: center;">AI Awal</th>
					<th rowspan="2" style="vertical-align: middle;text-align: center;">AI Update</th>
					<th rowspan="2" style="vertical-align: middle;text-align: center;">Target Terkontrak</th>
					<th rowspan="2" style="vertical-align: middle;text-align: center;">AKI Awal</th>
					<th rowspan="2" style="vertical-align: middle;text-align: center;">AKI Update</th>
					<th rowspan="2" style="vertical-align: middle;text-align: center;">Target Bulan Disburse</th>
					<th colspan="2" style="vertical-align: middle;text-align: center;">Total Program</th>
					<th rowspan="2" style="vertical-align: middle;text-align: center;">Realisasi Bulan Kontrak</th>
					<th rowspan="2" style="vertical-align: middle;text-align: center;">Realisasi Bulan Disburse</th>
					<th rowspan="2" style="vertical-align: middle;text-align: center;">Nomor PO</th>
					<th colspan="2" style="vertical-align: middle;text-align: center;">Realisasi(Rp)</th>
					<th colspan="2" style="vertical-align: middle;text-align: center;">Status</th>
					<th colspan="2" style="vertical-align: middle;text-align: center;">Realisasi(Program)</th>
					<th colspan="2" style="vertical-align: middle;text-align: center;">Pencapaian Program(%)</th>
					<th colspan="1" style="vertical-align: middle;text-align: center;">Pencapaian Kontrak Rp(%)</th>
					<th colspan="1" style="vertical-align: middle;text-align: center;">Pencapaian Disburse Rp(%)</th>
				</tr>
				<tr>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th>Kontrak</th>
					<th>Disburse</th>
					<th></th>
					<th></th>
					<th></th>
					<th>Kontrak</th>
					<th>Disburse</th>
					<th>Kontrak</th>
					<th>Disburse</th>
					<th>"Selesai" Kontrak</th>
					<th>"Selesai" Disburse</th>
					<th>Kontrak</th>
					<th>Disburse</th>
					<th>Thd AI Update sd TA</th>
					<!-- <th>Thd AI Update sd Bulan</th> -->
					<th>Thd AKI Update TA ke N</th>
					<!-- <th>Thd AKI Update TA ke Bulan</th> -->
				</tr>
				<tr>
					<th>1</th>
					<th>2</th>
					<th>3</th>
					<th>4</th>
					<th>5</th>
					<th>6</th>
					<th>7</th>
					<th>8</th>
					<th>9</th>
					<th>10</th>
					<th>11</th>
					<th>12</th>
					<th>13</th>
					<th>14</th>
					<th>15</th>
					<th>16</th>
					<th>17</th>
					<th>18</th>
					<th>19</th>
					<th>20</th>
					<th>21=19/10</th>
					<th>22=20/11</th>
					<th>23=15/5</th>
					<!-- <th>24=11/5</th> -->
					<th>24=16/8</th>
					<!-- <th>26=12/7</th> -->
				</tr>
			</thead>
			<tbody>
				<?php $i=1;?>
				@foreach($ai_pjb_result as $row)
					<tr>
						<td>{{$i++}}</td>
						@foreach($row as $key => $item)
							@if( is_numeric( $item ))
								<td style="text-align: right;">
									<!-- jika desimal -->
									{{number_format(1000 *(is_numeric($item) ? round($item, 2) : $item))}}
								</td>
							@else
								<td>
									{{ $item }}
								</td>
							@endif
						@endforeach
					</tr>
				@endforeach
			</tbody>
			<!-- END - Perubahan CHANGE C-066842-->
	      	</tbody>
	    </table>
	</div>
</div>