<body>
	<br><br><br><br><br><br><br><br><br>
 	<table>
 		<thead>	
	 		<tr>
	 		  <th></th>
	          <th style="background-color: #4ECDC4; border:1px solid #000">Risk Tag</th>
	          <th style="background-color: #4ECDC4; border:1px solid #000">Risk Event</th>
	          <th style="background-color: #4ECDC4; border:1px solid #000">Rencana Program Penanganganan Risiko</th>
	          <th style="background-color: #4ECDC4; border:1px solid #000">Nilai Anggaran</th>
	        </tr>
 		</thead>
 			@foreach($queryB as $qr)
 			<?php $i = 0; ?>
 		<tbody>
 			<tr>
 				<td></td>
 				<td style="border:1px solid #000">{{$qr->value}}</td>
 				<td style="border:1px solid #000">
	              	@foreach($queryC as $q)
	              	 @if($qr->row == $q->row)
	              	  {{$q->value}}
	              	 @endif
	              	@endforeach
                </td>
                <td style="border:1px solid #000">
	               	<label>Total PRK: </label>&nbsp;<span>{{ $total_prk_tiap_risk_profile[$i] }}</span>
	              </td>
                  <td style="text-align: right; border:1px solid #000">
	                  <?php 
	                    $total1 = Round($totalB[$i]);
	                    $t      = number_format($total1,0,',','.');
	                    echo $t;
	                   ?>
	               </td>
                </tr>
                 <?php $d = 1; ?>
	              @foreach($detail_anggaran_tiap_risk_profile[$i] as $detail_risk_profile)
	              @if($d<=5)
                <tr>
                	<td></td>
                	<td style="border:1px solid #000"></td>
                	<td style="border:1px solid #000"></td>
                	<td style="border:1px solid #000"></td>
                	<td style="text-align: right; border:1px solid #000">{{ number_format(round($detail_risk_profile),0,',','.') }}</td>
                </tr>
                 @endif
	              <?php $d++; ?>
 		</tbody>
          @endforeach
        <?php $i++; ?>
      @endforeach
 	</table>
</body>
