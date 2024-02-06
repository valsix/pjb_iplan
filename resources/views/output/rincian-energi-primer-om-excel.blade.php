<table>
    <tr>
        <td colspan="9">
            <h1>Rincian Energi Primer</h1>    
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
        <td>{{$input_lokasi->name}}</td>
    </tr>
</table>
<div class="row">
    <div class="col-md-12">
        <thead>
            <tr>
                 <th>No </th>
                 <th>Nomor PRK </th>
                 <th>Deskripsi Kegiatan </th>
                 <th>Laba Rugi </th>
                 <th>Cashflow </th>
                 <th>LOKASI</th>
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
    </div>
</div>
