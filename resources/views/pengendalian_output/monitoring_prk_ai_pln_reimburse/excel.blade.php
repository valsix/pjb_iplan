<table>
    <tr>
        <td colspan="14">
            <h1>Monitoring PRK AI PLN</h1>
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
    <tr>
        <th>Form 10 Penguatan PLN</th>
        <td>{{ $name_draft_form_10_pln }}</td>
    </tr>
</table>
<div class="row">
    <div class="col-md-12">
      <table>
        <thead>
          <tr>
            <th rowspan="2" style="vertical-align:middle; text-align: center;">No</th>
            <!-- <th rowspan="2" style="vertical-align:middle">PRK Kegiatan</th> -->
            <!-- <th rowspan="2" style="vertical-align:middle">Identity PRK Kegiatan</th> -->
            <th rowspan="2" style="vertical-align:middle; text-align: center;">Nomor PRK</th>
            <th rowspan="2" style="vertical-align:middle; text-align: center;">Uraian Kegiatan</th>

             <th colspan="2" style="text-align: center;">Rencana</th>

             <th colspan="2" style="text-align: center;">Rencana Update</th>

             <th rowspan="2" style="vertical-align:middle; text-align: center;">No PO</th>

             <th rowspan="2" style="vertical-align:middle; text-align: center;">Item PO</th>

             <th rowspan="2" style="vertical-align:middle; text-align: center;">Kode Account Code</th>

             <th colspan="2" style="text-align: center;">Realisasi</th>

          </tr>
          <tr>
            <th></th>
            <th></th>
            <th></th>
            <th style="text-align: center;">AI Ketetapan</th>
            <th style="text-align: center;">AKI Ketetapan</th>

            <th style="text-align: center;">AI Ketetapan</th>
            <th style="text-align: center;">AKI Ketetapan</th>

            <th></th>
            <th></th>
            <th></th>
            <th style="text-align: center;">Kontrak</th>
            <th style="text-align: center;">Disburse</th>
          </tr>

        </thead>
        <tbody>
            <?php $urut = 0;?>
            @foreach($data_prk_item as $key_prk_po => $value)
                <!-- pengelompokan berdasarkan PRK dan No PO -->
                <tr style="background:#8EC7D1;color:black;">
                    <td>{{++$urut}}</td>
                    @if(strlen($value['prk_kegiatan'])!=8)
                        <td>{{substr($value['prk_kegiatan'],2,8)}}</td><!-- <th>PRK kegiatan</th> -->
                    @else
                        <td>{{$value['prk_kegiatan']}}</td>
                    @endif
                    <td>{{$value['desc_prk_kegiatan']}}</td><!-- <th>Identity PRK Kegiatan</th> -->
                    <td style="text-align: right;">{{ number_format(1000 * $value['ai_ketetapan'],0,",",".") }}</td><!-- <th>AI Ketetapan</th> -->
                    <td style="text-align: right;">{{ number_format(1000 * $value['total_year_estimate'],0,",",".") }}</td><!-- <th>AKI Ketetapan</th> -->

                    <td style="text-align: right;">{{ number_format(1000 * $value['ai_ketetapan_update'],0,",",".") }}</td><!-- <th>AI Ketetapan</th> -->
                    <td style="text-align: right;">{{ number_format(1000 * $value['total_year_estimate_update'],0,",",".") }}</td><!-- <th>AKI Ketetapan</th> -->

                    <td style="text-align: right;">
                    <!-- {{-- number_format( $value['po_no'],0,",",".") --}} -->
                    {{ $value['po_no'] }}
                    </td><!-- <th>Nomor PO</th> -->
                    <td style="text-align: right;">{{ number_format( $value['item_po'],0,",",".") }}</td><!-- <th>Item PO</th> -->

                    <td style="text-align: right;">{{ $value['account_code'] }}</td><!-- <th>Kode Account Code</th> -->

                    <td style="text-align: right;">{{ number_format((float)$value['kontrak'],0,",",".") }}</td><!-- <th>Kontrak</th> -->
                    <td style="text-align: right;">{{ number_format((float)$value['disburse'],0,",",".") }}</td><!-- <th>Disburse</th> -->
                </tr>
                <?php $sub_urut = 1;?>
                @if($value['item_po'] > 0)
                    <?php $baris = 0; ?>
                    <!-- detail dari tiap Item PO sesuai PRK dan No PO -->
                    @foreach($value['per_item'] as $key_item => $item)
                        <?php $baris = $baris+1; $warna=($baris % 2 == 0) ? "#ffffff" : "#E8EDEF"; ?>
                        <tr style="background-color: <?= $warna?>;color=black;" class="hidetrinti{{$key_prk_po}} hidetrinit">
                            <td>{{$urut.".".$sub_urut++}}</td>
                            @if(strlen($item['prk_kegiatan'])!=8)
                                <td>{{substr($item['prk_kegiatan'],2,8)}}</td><!-- <th>PRK kegiatan</th> -->
                            @else
                                <td>{{$item['prk_kegiatan']}}</td>
                            @endif
                            <td>{{$item['desc_prk_kegiatan']}}</td><!-- <th>Identity PRK Kegiatan</th> -->
                            <td style="text-align: right;">{{ number_format(1000 * $item['ai_ketetapan'],0,",",".") }}</td><!-- <th>AI Ketetapan</th> -->
                            <td style="text-align: right;">{{ number_format(1000 * $item['total_year_estimate'],0,",",".") }}</td><!-- <th>AKI Ketetapan</th> -->

                            <td style="text-align: right;">{{ number_format(1000 * $item['ai_ketetapan_update'],0,",",".") }}</td><!-- <th>AI Ketetapan</th> -->
                            <td style="text-align: right;">{{ number_format(1000 * $item['total_year_estimate_update'],0,",",".") }}</td><!-- <th>AKI Ketetapan</th> -->

                            <td style="text-align: right;">
                            <!-- {{-- number_format( $item['po_no'],0,",",".") --}} -->
                            {{ $item['po_no'] }}
                            </td><!-- <th>Nomor PO</th> -->
                            <td style="text-align: right;">{{ number_format( $item['item_po'],0,",",".") }}</td><!-- <th>Item PO</th> -->

                            <td style="text-align: right;">{{ $item['account_code'] }}</td><!-- <th>Kode Account Code</th> -->

                            <td style="text-align: right;">{{ number_format((float)$item['kontrak'],0,",",".") }}</td><!-- <th>Kontrak</th> -->
                            <td style="text-align: right;">{{ number_format((float)$item['disburse'],0,",",".") }}</td><!-- <th>Disburse</th> -->
                        </tr>
                    @endforeach
                @endif
            @endforeach
        </tbody>
      </table>
    </div>
</div>
