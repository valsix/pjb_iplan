<table id="header">
    <tr>
        <td>Tahun Anggaran</td>
        <td style="text-align: left;">{{$input_tahun}}</td>
    </tr>
    <tr>
        <td>Strategi Bisnis</td>
        <td>{{$input_sb->name}}</td>
    </tr>
    <tr>
        <td>Distrik</td>
        <td>{{$input_distrik->name}}</td>
    </tr>
    <tr>
        <td>Lokasi</td>
        <td>{{$input_lokasi->name}}</td>
    </tr>
    <tr>
        <td>Fase</td>
        <td>{{$input_fase->name}}</td>
    </tr>
    <tr>
        <td>Draft Form 6 Rutin</td>
        <td>{{$input_form_6_rutin->draft_versi}} - {{$input_form_6_rutin->name}}</td>
    </tr>
</table>


<table>
        <thead>
          <tr>
            <th rowspan="3" style="text-align: center">Kode Aktifitas</th>
            <th rowspan="3" style="text-align: center">Kode PRK</th>
            <th rowspan="3" style="text-align: center">Deskripsi PRK Kegiatan</th>
            <th colspan="4" style="text-align: center">TOTAL PEMAKAIAN (LABA RUGI)</th>
            <th colspan="7" style="text-align: center">TOTAL PEMAKAIAN (CASH FLOW)</th>
            <th rowspan="3" style="text-align: center">ALOKASI<br>(UP/UBJOM, UPHAR/STOCKIST, UPHB, PJAC, PJB2)</th>
            <th rowspan="3" style="text-align: center">Persetujuan Proses Kontrak Pengadaan</th>
            <th rowspan="3" style="text-align: center">Disburse</th>
          </tr>
          <tr>
            <th></th>
            <th></th>
            <th></th>
            <th colspan="2" style="text-align: center">Material</th>
            <th rowspan="2" style="text-align: center">Jasa</th>
            <th rowspan="2" style="text-align: center">Total</th>
            <th colspan="2" style="text-align: center">Pembayaran Hutang</th>
            <th colspan="2" style="text-align: center">Material</th>
            <th rowspan="2" style="text-align: center">Jumlah Material</th>
            <th rowspan="2" style="text-align: center">Jumlah Jasa</th>
            <th rowspan="2" style="text-align: center">Total</th>
            <th></th>
            <th></th>
            <th></th>
          </tr>
          <tr>
            <th></th>
            <th></th>
            <th></th>
            <th style="text-align: center">Persediaan</th>
            <th style="text-align: center">Pengadaan Langsung Pakai</th>
            <th></th>
            <th></th>
            <th style="text-align: center">Material</th>
            <th style="text-align: center">Jasa</th>
            <th style="text-align: center">Pengadaan Langsung Pakai</th>
            <th style="text-align: center">Persediaan</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
          </tr>
          <tr>
            <th style="text-align: center">1</th>
            <th style="text-align: center">2</th>
            <th style="text-align: center">3</th>
            <th style="text-align: center">4</th>
            <th style="text-align: center">5</th>
            <th style="text-align: center">6</th>
            <th style="text-align: center">7=4+5+6</th>
            <th style="text-align: center">8</th>
            <th style="text-align: center">9</th>
            <th style="text-align: center">10</th>
            <th style="text-align: center">11</th>
            <th style="text-align: center">12</th>
            <th style="text-align: center">13</th>
            <th style="text-align: center">14=12+13</th>
            <th style="text-align: center">15</th>
            <th style="text-align: center">16</th>
            <th style="text-align: center">17</th>
          </tr>
        </thead>
        <tbody>

          <?php $baris = 0; ?>
          <!-- form 6 -->
          @foreach($dataparent as $key_form => $parent_per_form)
          @foreach($parent_per_form as $key_parent => $parent)
          <!-- parent -->
              @if($key_parent!= '')
              <?php $baris = $baris+1; $warna=($baris % 2 == 0) ? "#ffffff" : "#E8EDEF"; ?>
              <tr style="background-color: <?= $warna?>">
                  <td>{{$parent['kode_aktivitas']}}</td><!-- Kode Aktifitas -->
                  <td>{{$key_parent}}</td><!-- Kode PRK Kegiatan -->
                  <td>{{$parent['desc_prk_parent']}}</td><!-- Deskripsi PRK Kegiatan -->
                  <td style="text-align: right;">{{ number_format($parent['persediaan_lr'],0) }}</td>
                  <td style="text-align: right;">{{ number_format($parent['pengadaan_lr'],0) }}</td>
                  <td style="text-align: right;">{{ number_format($parent['jasa_lr'],0) }}</td>
                  <td style="text-align: right;">{{ number_format($parent['total_lr'],0) }}</td>
                  <td style="text-align: right;">{{ number_format($parent['material_hutang_cf'],0) }}</td>
                  <td style="text-align: right;">{{ number_format($parent['jasa_hutang_cf'],0) }}</td>
                  <td style="text-align: right;">{{ number_format($parent['pengadaan_cf'],0) }}</td>
                  <td style="text-align: right;">{{ number_format($parent['persediaan_cf'],0) }}</td>
                  <td style="text-align: right;">{{ number_format($parent['jml_material_cf'],0) }}</td>
                  <td style="text-align: right;">{{ number_format($parent['jml_jasa_cf'],0) }}</td>
                  <td style="text-align: right;">{{ number_format($parent['total_cf'],0) }}</td>
                  <td></td>
                  <td style="text-align: right;">{{ number_format($parent['persetujuan'],0) }}</td>
                  <td></td>
              </tr>

              <!-- inti -->
              @foreach($datainti[$key_form] as $key_inti=>$inti)
                  @if($inti['prk_parent'] == $key_parent)
                  <?php $baris = $baris+1; $warna=($baris % 2 == 0) ? "#ffffff" : "#E8EDEF"; ?>
                  <tr style="background-color: <?= $warna?>">
                      <td>{{$inti['kode_aktivitas']}}</td><!-- Kode Aktifitas -->
                      <td>{{$key_inti}}</td><!-- Kode PRK -->
                      <td>{{$inti['desc_prk_inti']}}</td><!-- Deskripsi PRK Kegiatan -->
                      <td style="text-align: right;">{{ number_format($inti['persediaan_lr'],0) }}</td>
                      <td style="text-align: right;">{{ number_format($inti['pengadaan_lr'],0) }}</td>
                      <td style="text-align: right;">{{ number_format($inti['jasa_lr'],0) }}</td>
                      <td style="text-align: right;">{{ number_format($inti['total_lr'],0) }}</td>
                      <td style="text-align: right;">{{ number_format($inti['material_hutang_cf'],0) }}</td>
                      <td style="text-align: right;">{{ number_format($inti['jasa_hutang_cf'],0) }}</td>
                      <td style="text-align: right;">{{ number_format($inti['pengadaan_cf'],0) }}</td>
                      <td style="text-align: right;">{{ number_format($inti['persediaan_cf'],0) }}</td>
                      <td style="text-align: right;">{{ number_format($inti['jml_material_cf'],0) }}</td>
                      <td style="text-align: right;">{{ number_format($inti['jml_jasa_cf'],0) }}</td>
                      <td style="text-align: right;">{{ number_format($inti['total_cf'],0) }}</td>
                      <td></td>
                      <td style="text-align: right;">{{ number_format($inti['persetujuan'],0) }}</td>
                      <td></td>
                  </tr>

                  <!-- kegiatan -->
                  @foreach($datakegiatan[$key_form] as $key_kegiatan => $kegiatan)
                      @if($kegiatan['prk_inti'] == $key_inti)
                      <?php $baris = $baris+1; $warna=($baris % 2 == 0) ? "#ffffff" : "#E8EDEF"; ?>
                      <tr style="background-color: <?= $warna?>">
                          <td>{{$kegiatan['kode_aktivitas']}}</td><!-- Kode Aktifitas -->
                          <td>{{$kegiatan['prk_kegiatan']}}</td><!-- Kode PRK -->
                          <td>{{$kegiatan['desc_prk_kegiatan']}}</td><!-- Deskripsi PRK Kegiatan -->
                          <td style="text-align: right;">{{ number_format($kegiatan['persediaan_lr'],0) }}</td>
                          <td style="text-align: right;">{{ number_format($kegiatan['pengadaan_lr'],0) }}</td>
                          <td style="text-align: right;">{{ number_format($kegiatan['jasa_lr'],0) }}</td>
                          <td style="text-align: right;">{{ number_format($kegiatan['total_lr'],0) }}</td>
                          <td style="text-align: right;">{{ number_format($kegiatan['material_hutang_cf'],0) }}</td>
                          <td style="text-align: right;">{{ number_format($kegiatan['jasa_hutang_cf'],0) }}</td>
                          <td style="text-align: right;">{{ number_format($kegiatan['pengadaan_cf'],0) }}</td>
                          <td style="text-align: right;">{{ number_format($kegiatan['persediaan_cf'],0) }}</td>
                          <td style="text-align: right;">{{ number_format($kegiatan['jml_material_cf'],0) }}</td>
                          <td style="text-align: right;">{{ number_format($kegiatan['jml_jasa_cf'],0) }}</td>
                          <td style="text-align: right;">{{ number_format($kegiatan['total_cf'],0) }}</td>
                          <td>{{ $kegiatan['alokasi'] }}</td>
                          <td style="text-align: right;">{{ number_format($kegiatan['persetujuan'],0) }}</td>
                          <td>{{ $kegiatan['disburse'] }}</td>
                      </tr>
                      @endif
                  @endforeach <!-- end of kegiatan -->
                  @endif
              @endforeach <!-- end of inti -->
              @endif
          @endforeach <!-- end of parent -->
          @endforeach
          <!-- end of form 6 -->
        </tbody>
      </table>
