<table>
    <tr>
        <td colspan="19">
            <h1>Rincian Energi Primer</h1>    
        </td>
    </tr>
    <tr>
        <th>Tahun Anggaran</th>
        <td>{{$input_tahun}}</td>
    </tr>
    <tr>
        <th>s.d. Bulan</th>
        <td>: {{$months[$input_bulan]}}</td>
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
                <th rowspan="3">Jenis Bahan Bakar</th>
                <th colspan="5">Produksi</th>
                <th colspan="11">Kebutuhan Energi Primer</th>
            </tr>
            <tr>
                <th></th>
                <th colspan="4">MWh</th>
                <th rowspan="2">(%)</th>
                <th rowspan="2">Satuan</th>
                <th colspan="4">Volume</th>
                <th colspan="2">Biaya Bahan Bakar</th>
                <th colspan="2">Ongkos Angkut</th>
                <th>Biaya Pendukung</th>
                <th rowspan="2">Total Biaya(Rp. Ribu)</th>
            </tr>
            <tr>
                <th></th>
                <th>Sendiri</th>
                <th>Sewa</th>
                <th>Beli</th>
                <th>Jumlah</th>
                <th></th>
                <th></th>

                <th>Sendiri</th>
                <th>Sewa</th>
                <th>Beli</th>
                <th>Jumlah</th>

                <th>Harga Satuan</th>
                <th>Jumlah(Rp. Ribu)</th>

                <th>OA Rata-rata</th>
                <th>Jumlah(Rp. Ribu)</th>

                <th>Jumlah(Rp. Ribu)</th>
            </tr>
            <tr>
                <th>a</th>
                <th>b</th>
                <th>c</th>
                <th>d</th>
                <th>e=b+c+d</th>
                <th>f</th>
                <th>g</th>
                <th>h</th>
                <th>i</th>
                <th>j</th>
                <th>k=h+i+j</th>
                <th>l</th>
                <th>m</th>
                <th>n</th>
                <th>o</th>
                <th>p</th>
                <th>q</th>
            </tr>
        </thead>
        <tbody>
            @for($i=0; $i<3;$i++)
                <tr>
                    <td>{{$data[$i]['jenis']}}</td>
                    @foreach($data[$i]['produksi'] as $key => $value) 
                        <td style="text-align: right"> {{number_format($value->value,0)}}</td>
                    @endforeach
                    <td style="text-align: right">{{number_format($data[$i]['total_produksi'],0)}}</td>

                    <td style="text-align: right">{{number_format($data[$i]['total_produksi']/$total_up['total_produksi'] * 100 , 1) }} %</td>
                    <td>{{$data[$i]['satuan']}}</td>
                    
                    @foreach($data[$i]['kebutuhan_ep'] as $key => $value) 
                        <td style="text-align: right"> {{number_format($value->value,0)}}</td>
                    @endforeach
                    <td style="text-align: right">{{number_format($data[$i]['total_kebutuhan_ep'],0)}}</td>
                    
                    <td style="text-align: right">{{number_format($data[$i]['satuan_biaya_bahan_bakar'],0)}}</td>
                    <td style="text-align: right">{{number_format($data[$i]['jumlah_biaya_bahan_bakar'],0)}}</td>
                    
                    <td style="text-align: right">{{number_format($data[$i]['ratarata_ongkos_angkut'],0)}}</td>
                    <td style="text-align: right">{{number_format($data[$i]['jumlah_ongkos_angkut'],0)}}</td>

                    <td style="text-align: right">{{number_format($data[$i]['jumlah_biaya_pendukung'],0)}}</td>
                    
                    <td style="text-align: right">{{number_format($data[$i]['total_biaya'],0)}}</td>
                </tr>
            @endfor
            <tr>
                <th>{{$subtotal_bbm['title']}}</th>
                
                <th style="text-align: right">{{number_format($subtotal_bbm['produksi'][0],0)}}</th>
                <th style="text-align: right">{{number_format($subtotal_bbm['produksi'][1],0)}}</th>
                <th style="text-align: right">{{number_format($subtotal_bbm['produksi'][2],0)}}</th>
                <th style="text-align: right">{{number_format($subtotal_bbm['total_produksi'],0)}}</th>
                <th style="text-align: right">{{number_format( $subtotal_bbm['total_produksi'] / $total_up['total_produksi'] * 100 , 1)}} % </th>
                <th></th>
                <th style="text-align: right">{{number_format($subtotal_bbm['kebutuhan_ep'][0],0)}}</th>
                <th style="text-align: right">{{number_format($subtotal_bbm['kebutuhan_ep'][1],0)}}</th>
                <th style="text-align: right">{{number_format($subtotal_bbm['kebutuhan_ep'][2],0)}}</th>
                
                <th style="text-align: right">{{number_format($subtotal_bbm['total_kebutuhan_ep'],0)}}</th>
                <th></th>
                <th style="text-align: right">{{number_format($subtotal_bbm['jumlah_biaya_bahan_bakar'],0)}}</th>
                <th></th>
                <th style="text-align: right">{{number_format($subtotal_bbm['jumlah_ongkos_angkut'],0)}}</th>
                <th style="text-align: right">{{number_format($subtotal_bbm['jumlah_biaya_pendukung'],0)}}</th>
                <th style="text-align: right">{{number_format($subtotal_bbm['total_biaya'],0)}}</th>
            </tr>

            @for($i=3; $i<9;$i++)
                <tr>
                    <td>{{$data[$i]['jenis']}}</td>
                  
                    @foreach($data[$i]['produksi'] as $key => $value) 
                        <td style="text-align: right"> {{number_format($value->value,0)}}</td>         
                    @endforeach
                    <td style="text-align: right">{{number_format($data[$i]['total_produksi'],0)}}</td>
                  
                    <td style="text-align: right">{{number_format($data[$i]['total_produksi']/$total_up['total_produksi'] * 100,1) }} %</td>
                    <td>{{$data[$i]['satuan']}}</td>
                  
                    @foreach($data[$i]['kebutuhan_ep'] as $key => $value) 
                        <td style="text-align: right"> {{number_format($value->value,0)}}</td>
                    @endforeach
                    <td style="text-align: right">{{number_format($data[$i]['total_kebutuhan_ep'],0)}}</td>
                  
                    <td style="text-align: right">{{number_format($data[$i]['satuan_biaya_bahan_bakar'],0)}}</td>
                    <td style="text-align: right">{{number_format($data[$i]['jumlah_biaya_bahan_bakar'],0)}}</td>
                  
                    <td style="text-align: right">{{number_format($data[$i]['ratarata_ongkos_angkut'],0)}}</td>
                    <td style="text-align: right">{{number_format($data[$i]['jumlah_ongkos_angkut'],0)}}</td>

                    <td style="text-align: right">{{number_format($data[$i]['jumlah_biaya_pendukung'],0)}}</td>
                    
                    <td style="text-align: right">{{number_format($data[$i]['total_biaya'],0)}}</td>
                  </tr>
            @endfor
            <tr>
                <th>{{$subtotal_nonbbm['title']}}</th>
                <th style="text-align: right">{{number_format($subtotal_nonbbm['produksi'][0],0)}}</th>
                <th style="text-align: right">{{number_format($subtotal_nonbbm['produksi'][1],0)}}</th>
                <th style="text-align: right">{{number_format($subtotal_nonbbm['produksi'][2],0)}}</th>
                <th style="text-align: right">{{number_format($subtotal_nonbbm['total_produksi'],0)}}</th>
                <th style="text-align: right">{{number_format( $subtotal_nonbbm['total_produksi'] / $total_up['total_produksi'] * 100 , 1)}} % </th>
                <th></th>
                <th style="text-align: right">{{number_format($subtotal_nonbbm['kebutuhan_ep'][0],0)}}</th>
                <th style="text-align: right">{{number_format($subtotal_nonbbm['kebutuhan_ep'][1],0)}}</th>
                <th style="text-align: right">{{number_format($subtotal_nonbbm['kebutuhan_ep'][2],0)}}</th>
                
                <th style="text-align: right">{{number_format($subtotal_nonbbm['total_kebutuhan_ep'],0)}}</th>
                <th></th>
                <th style="text-align: right">{{number_format($subtotal_nonbbm['jumlah_biaya_bahan_bakar'],0)}}</th>
                <th></th>
                <th style="text-align: right">{{number_format($subtotal_nonbbm['jumlah_ongkos_angkut'],0)}}</th>
                <th style="text-align: right">{{number_format($subtotal_nonbbm['jumlah_biaya_pendukung'],0)}}</th>
                <th style="text-align: right">{{number_format($subtotal_nonbbm['total_biaya'],0)}}</th>
            </tr>
            @for($i=9; $i<11;$i++)
                <tr>
                    <td>{{$data[$i]['jenis']}}</td>
                  
                    @foreach($data[$i]['produksi'] as $key => $value) 
                        <td style="text-align: right"> {{number_format($value->value,0,",",".")}}</td>         
                    @endforeach
                    <td style="text-align: right">{{number_format($data[$i]['total_produksi'],0,",",".")}}</td>
                  
                    <td style="text-align: right">{{number_format(($total_up['total_produksi'] == 0 ? 0 : $data[$i]['total_produksi']/$total_up['total_produksi'] * 100),1, "," , ".") }} %</td>
                    <td>{{$data[$i]['satuan']}}</td>
                  
                    @foreach($data[$i]['kebutuhan_ep'] as $key => $value) 
                        <td style="text-align: right"> {{number_format($value->value,0,",",".")}}</td>
                    @endforeach
                    <td style="text-align: right">{{number_format($data[$i]['total_kebutuhan_ep'],0,",",".")}}</td>
                  
                    <td style="text-align: right">{{number_format($data[$i]['satuan_biaya_bahan_bakar'],0,",",".")}}</td>
                    <td style="text-align: right">{{number_format($data[$i]['jumlah_biaya_bahan_bakar'],0,",",".")}}</td>
                  
                    <td style="text-align: right">{{number_format($data[$i]['ratarata_ongkos_angkut'],0,",",".")}}</td>
                    <td style="text-align: right">{{number_format($data[$i]['jumlah_ongkos_angkut'],0,",",".")}}</td>

                    <td style="text-align: right">{{number_format($data[$i]['jumlah_biaya_pendukung'],0,",",".")}}</td>
                    
                    <td style="text-align: right">{{number_format($data[$i]['total_biaya'],0,",",".")}}</td>
                </tr>
            @endfor
        </tbody>
        <tfoot>
            <tr>
                <th>{{$total_up['title']}}</th>
              
                <th style="text-align: right">{{number_format($total_up['produksi'][0],0)}}</th>
                <th style="text-align: right">{{number_format($total_up['produksi'][1],0)}}</th>
                <th style="text-align: right">{{number_format($total_up['produksi'][2],0)}}</th>
                <th style="text-align: right">{{number_format($total_up['total_produksi'],0)}}</th>
                <th style="text-align: right">{{number_format( $total_up['total_produksi'] / $total_up['total_produksi'] * 100 , 1)}} % </th>
                <th></th>
                <th style="text-align: right">{{number_format($total_up['kebutuhan_ep'][0],0)}}</th>
                <th style="text-align: right">{{number_format($total_up['kebutuhan_ep'][1],0)}}</th>
                <th style="text-align: right">{{number_format($total_up['kebutuhan_ep'][2],0)}}</th>
                
                <th style="text-align: right">{{number_format($total_up['total_kebutuhan_ep'],0)}}</th>
                <th></th>
                <th style="text-align: right">{{number_format($total_up['jumlah_biaya_bahan_bakar'],0)}}</th>
                <th></th>
                <th style="text-align: right">{{number_format($total_up['jumlah_ongkos_angkut'],0)}}</th>
                <th style="text-align: right">{{number_format($total_up['jumlah_biaya_pendukung'],0)}}</th>
                <th style="text-align: right">{{number_format($total_up['total_biaya'],0)}}</th>
            </tr>
        </tfoot>
    </div>
</div>
