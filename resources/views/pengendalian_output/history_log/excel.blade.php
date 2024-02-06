<!-- <table>
    <tr>
        <td colspan="14">
            <h1>History Log</h1>
        </td>
    </tr>
</table>
 -->
<table>
    <tr>
        <td colspan="14">
            <h1>History Log <?php echo $judul; ?> </h1>
        </td>
    </tr>
    <tr>
        <th>Tahun Anggaran</th>
        <td>{{$input_tahun}}</td>
    </tr>

    <tr>
        <th>Strategi Bisnis</th>
        <td>{{$input_sb->name}}</td>
    </tr>
    <tr>
        <th>Distrik</th>
        <td>{{$input_distrik->name}}</td>
    </tr>
    <tr>
        <th>Lokasi</th>
        <td>
          @if($input_lokasi)
          <?php $val = null; ?>
          <?php $ival = null; ?>
          @foreach($input_lokasi as $l)
          <?php
            $ival++;
            if($ival==1)  
              $val = $val.' '.$l->name; 
            else
              $val = $val.', '.$l->name; 
          ?>
          @endforeach
          {{ $val }}
        @else
          {{ '' }}
        @endif
        </td>
    </tr>
    <tr>
        <th>Fase</th>
        <td>{{'Ketetapan'}}</td>
    </tr>
    <tr>
        <th>s.d Bulan</th>
        <td>{{$nama_bln_dipilih}}</td>
    </tr>
</table>

<div style="overflow-x:auto;">
	<table id="table-history-log" class="table table-striped table-bordered" cellspacing="0" width="50%" style="height: 100px !important;font-size:11px;">
	   <thead>
	     <tr>
	       <th rowspan="2" style="vertical-align:middle">Tanggal</th>
	       <!-- <th rowspan="2" style="vertical-align:middle">Nomor PRK</th> -->
	       <!-- <th rowspan="2" style="vertical-align:middle">Uraian Kegiatan</th> -->
	       <th rowspan="2" style="vertical-align:middle">Nomor PRK</th>
	       <th rowspan="2" style="vertical-align:middle">Identity PRK</th>

	       <th colspan="2">Deskripsi PRK</th>

	        <th colspan="2">Beban</th>

	        <th colspan="2">Cash Flow</th>

	        <th colspan="2">Ijin Proses</th>

	        <th rowspan="2" style="vertical-align:middle">PIC</th>

	     </tr>
	     <tr>
         <th></th>
         <th></th>
         <th></th>

	       <th>Awal</th>
	       <th>Revisi</th>

	       <th>Awal</th>
	       <th>Revisi</th>

	       <th>Awal</th>
	       <th>Revisi</th>

	       <th>Awal</th>
	       <th>Revisi</th>
	     </tr>

	   </thead>

	  <tbody>
	    <?php $baris = 0; ?>
	    <?php $urut = 1;?>
	    @foreach($pgdl_history_log as $phl)

	     <tr>

          <td>{{ \Carbon\Carbon::parse($phl->updated_at)->format('d M Y') }}</td>
          <td>{{ $phl->prk }}</td>
          @if($phl->identity_prk == null)
            <td>{{ '-' }}</td>
            @else
            <td>{{ $phl->identity_prk }}</td>
          @endif

          @if($phl->deskripsi_prk_awal == null)
            <td>{{ '-' }}</td>
            @else
            <td>{{ $phl->deskripsi_prk_awal }}</td>
          @endif
          @if($phl->deskripsi_prk_akhir == null)
            <td>{{ '-' }}</td>
            @else
            <td>{{ $phl->deskripsi_prk_akhir }}</td>
          @endif
          @if($phl->beban_awal == null)
            <td>{{ '-' }}</td>
            @else
            <td>{{ $phl->beban_awal }}</td>
          @endif
          @if($phl->beban_akhir == null)
            <td>{{ '-' }}</td>
            @else
            <td>{{ $phl->beban_akhir }}</td>
          @endif
          @if($phl->cashflow_awal == null)
            <td>{{ '-' }}</td>
            @else
            <td>{{ $phl->cashflow_awal }}</td>
          @endif
          @if($phl->cashflow_akhir == null)
            <td>{{ '-' }}</td>
            @else
            <td>{{ $phl->cashflow_akhir }}</td>
          @endif
          @if($phl->ijin_proses_awal == null)
            <td>{{ '-' }}</td>
            @else
            <td>{{ $phl->ijin_proses_awal }}</td>
          @endif

          @if($phl->ijin_proses_akhir == null)
            <td>{{ '-' }}</td>
            @else
            <td>{{ $phl->ijin_proses_akhir }}</td>
          @endif

          <td>{{ $phl->name }}</td>

          
      </tr>
	    @endforeach
	  </tbody>
	</table>
</div>