
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="application/pdf"; charset="utf-8">
  <title>Laporan Status DMR</title>
  <link href="{{ asset('vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">

  <!-- Chart.js') }} -->
  <script src="{{ URL::asset('vendors/Chart.js/dist/Chart.min.js') }}"></script>
  <!-- highcharts -->
  <script src="{{ URL::asset('js/highcharts.js') }}"></script>
  <script src="{{ URL::asset('js/exporting.js') }}"></script>
  <script type="text/javascript">
    var counter = 10;
    function countDown(){
      if(counter>=0){
        document.getElementById("timer").innerHTML = counter;
      }else{
          download();
      return;
      }
      counter -= 1;
      var counter2 = setTimeout("countDown()",100);
      return;
      }
      function download(){
        window.print('status-dmr.pdf');
      }
  </script>
</head>
  <body onload="countDown();">
    <input type="hidden" id="timer">
    <div class="row">
      <div class="col-md-8">
        <table>
          <tr>
            <td>
                  <label class="col-md-2 col-md-3">Tahun Anggaran</label>
            </td>
            <td>: &nbsp;&nbsp;</td>
            <td>
                  <p> {{ $input_tahun }} </p>
            </td>
          </tr>
          <tr>
            <td>
                  <label class="col-md-2 col-md-3">sd Bulan</label>
            </td>
            <td>: &nbsp;&nbsp;</td>
            <td>
                  <p> {{ $months[$input_bulan] }} </p>
            </td>
          </tr>
          <tr>
            <td>
                  <label class="col-md-2 col-md-3 " >Struktur Bisnis</label>
            </td>
            <td>: &nbsp;&nbsp;</td>
            <td>
                  <p> {{ $input_sb->name }} </p>
            </td>
          </tr>
          <tr>
            <td>
                  <label class="col-md-2 col-md-3 " >Distrik</label>
            </td>
            <td>: &nbsp;&nbsp;</td>
            <td>
                  <p> {{ $input_distrik->name }} </p>
            </td>
          </tr>
          <tr>
            <td>
                  <label class="col-md-2 col-md-3 " >Lokasi</label>
            </td>
            <td>: &nbsp;&nbsp;</td>
            <td>
                  <p> {{ $input_lokasi->name }} </p>
            </td>
          </tr>
          <tr>
            <td>
                  <label class="col-md-2 col-md-3 " >Fase</label>
            </td>
            <td>: &nbsp;&nbsp;</td>
            <td>
                  <p> {{ $input_fase->name }} </p>
            </td>
          </tr>
          <tr>
            <td>
              <label class="col-md-2 col-md-3 " >Draft Form Bahan Bakar</label>
            </td>
            <td>: &nbsp;&nbsp;</td>
            <td>
              <p> {{ $input_draft->draft_versi }} </p>
            </td>
          </tr>
        </table>
      </div>
      <div class="col-md-4">
        &nbsp;
      </div>
        <br>
    </div>


  <div class="page-break"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <strong>Rincian Energi Primer</strong>
        <br>
        <div class="x_content">
            <table id="datatable" class="table table-striped table-bordered">
                <thead style="background-color: #2a3f54; color: white;">
                    <tr>
                        <th rowspan="3">Jenis Bahan Bakar</th>
                        <th colspan="5">Produksi</th>
                        <th colspan="11">Kebutuhan Energi Primer</th>
                    </tr>
                    <tr>
                        <th colspan="4">MWh</th>
                        <th rowspan="2" style="min-width: 80px;">(%)</th>
                        <th rowspan="2">Satuan</th>
                        <th colspan="4">Volume</th>
                        <th colspan="2">Biaya Bahan Bakar</th>
                        <th colspan="2">Ongkos Angkut</th>
                        <th>Biaya Pendukung</th>
                        <th rowspan="2">Total Biaya(Rp. Ribu)</th>
                    </tr>
                    <tr>
                        <th>Sendiri</th>
                        <th>Sewa</th>
                        <th>Beli</th>
                        <th>Jumlah</th>

                        <th>Sendiri</th>
                        <th>Sewa</th>
                        <th>Beli</th>
                        <th>Jumlah</th>

                        <th>Harga Satuan</th>
                        <th>Jumlah<br>(Rp. Ribu)</th>

                        <th>OA Rata-rata</th>
                        <th>Jumlah<br>(Rp. Ribu)</th>

                        <th>Jumlah<br>(Rp. Ribu)</th>
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
                                <td style="text-align: right"> {{number_format($value->value,0,",",".")}}</td>
                            @endforeach
                            <td style="text-align: right">{{number_format($data[$i]['total_produksi'],0,",",".")}}</td>

                            <td style="text-align: right">{{number_format(($total_up['total_produksi'] == 0 ? 0 : $data[$i]['total_produksi']/$total_up['total_produksi'] * 100) , 1, ",",".") }} %</td>
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
                    <tr>
                        <th>{{$subtotal_bbm['title']}}</th>
                        
                        <th style="text-align: right">{{number_format($subtotal_bbm['produksi'][0],0,",",".")}}</th>
                        <th style="text-align: right">{{number_format($subtotal_bbm['produksi'][1],0,",",".")}}</th>
                        <th style="text-align: right">{{number_format($subtotal_bbm['produksi'][2],0,",",".")}}</th>
                        <th style="text-align: right">{{number_format($subtotal_bbm['total_produksi'],0,",",".")}}</th>
                        <th style="text-align: right">{{number_format(($total_up['total_produksi'] == 0 ? 0 : $subtotal_bbm['total_produksi'] / $total_up['total_produksi'] * 100 ), 1, "," , ".")}} %</th>
                        <th></th>
                        <th style="text-align: right">{{number_format($subtotal_bbm['kebutuhan_ep'][0],0,",",".")}}</th>
                        <th style="text-align: right">{{number_format($subtotal_bbm['kebutuhan_ep'][1],0,",",".")}}</th>
                        <th style="text-align: right">{{number_format($subtotal_bbm['kebutuhan_ep'][2],0,",",".")}}</th>
                        
                        <th style="text-align: right">{{number_format($subtotal_bbm['total_kebutuhan_ep'],0,",",".")}}</th>
                        <th></th>
                        <th style="text-align: right">{{number_format($subtotal_bbm['jumlah_biaya_bahan_bakar'],0,",",".")}}</th>
                        <th></th>
                        <th style="text-align: right">{{number_format($subtotal_bbm['jumlah_ongkos_angkut'],0,",",".")}}</th>
                        <th style="text-align: right">{{number_format($subtotal_bbm['jumlah_biaya_pendukung'],0,",",".")}}</th>
                        <th style="text-align: right">{{number_format($subtotal_bbm['total_biaya'],0,",",".")}}</th>
                    </tr>

                    @for($i=3; $i<9;$i++)
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
                    <tr>
                        <th>{{$subtotal_nonbbm['title']}}</th>
                        <th style="text-align: right">{{number_format($subtotal_nonbbm['produksi'][0],0,",",".")}}</th>
                        <th style="text-align: right">{{number_format($subtotal_nonbbm['produksi'][1],0,",",".")}}</th>
                        <th style="text-align: right">{{number_format($subtotal_nonbbm['produksi'][2],0,",",".")}}</th>
                        <th style="text-align: right">{{number_format($subtotal_nonbbm['total_produksi'],0,",",".")}}</th>
                        <th style="text-align: right">{{number_format( ($total_up['total_produksi'] == 0 ? 0 : $subtotal_nonbbm['total_produksi'] / $total_up['total_produksi'] * 100 ), 1, ",", ".")}} %</th>
                        <th></th>
                        <th style="text-align: right">{{number_format($subtotal_nonbbm['kebutuhan_ep'][0],0,",",".")}}</th>
                        <th style="text-align: right">{{number_format($subtotal_nonbbm['kebutuhan_ep'][1],0,",",".")}}</th>
                        <th style="text-align: right">{{number_format($subtotal_nonbbm['kebutuhan_ep'][2],0,",",".")}}</th>
                        
                        <th style="text-align: right">{{number_format($subtotal_nonbbm['total_kebutuhan_ep'],0,",",".")}}</th>
                        <th></th>
                        <th style="text-align: right">{{number_format($subtotal_nonbbm['jumlah_biaya_bahan_bakar'],0,",",".")}}</th>
                        <th></th>
                        <th style="text-align: right">{{number_format($subtotal_nonbbm['jumlah_ongkos_angkut'],0,",",".")}}</th>
                        <th style="text-align: right">{{number_format($subtotal_nonbbm['jumlah_biaya_pendukung'],0,",",".")}}</th>
                        <th style="text-align: right">{{number_format($subtotal_nonbbm['total_biaya'],0,",",".")}}</th>
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
                        
                        <th style="text-align: right">{{number_format($total_up['produksi'][0],0,",",".")}}</th>
                        <th style="text-align: right">{{number_format($total_up['produksi'][1],0,",",".")}}</th>
                        <th style="text-align: right">{{number_format($total_up['produksi'][2],0,",",".")}}</th>
                        <th style="text-align: right">{{number_format($total_up['total_produksi'],0,",",".")}}</th>
                        <th style="text-align: right">{{number_format( ($total_up['total_produksi'] == 0 ? 0 : $total_up['total_produksi'] / $total_up['total_produksi'] * 100 ), 1, "," , ".")}} %</th>
                        <th></th>
                        <th style="text-align: right">{{number_format($total_up['kebutuhan_ep'][0],0,",",".")}}</th>
                        <th style="text-align: right">{{number_format($total_up['kebutuhan_ep'][1],0,",",".")}}</th>
                        <th style="text-align: right">{{number_format($total_up['kebutuhan_ep'][2],0,",",".")}}</th>
                        
                        <th style="text-align: right">{{number_format($total_up['total_kebutuhan_ep'],0,",",".")}}</th>
                        <th></th>
                        <th style="text-align: right">{{number_format($total_up['jumlah_biaya_bahan_bakar'],0,",",".")}}</th>
                        <th></th>
                        <th style="text-align: right">{{number_format($total_up['jumlah_ongkos_angkut'],0,",",".")}}</th>
                        <th style="text-align: right">{{number_format($total_up['jumlah_biaya_pendukung'],0,",",".")}}</th>
                        <th style="text-align: right">{{number_format($total_up['total_biaya'],0,",",".")}}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

  </body>
</html>

    <style type="text/css">
    .page-break {
        page-break-after: always;
    }
      table{border-spacing:0;border-collapse:collapse}td,th{padding:0}
      .table td,.table th{background-color:#fff!important}
      .table-bordered td,.table-bordered th{border:1px solid #ddd!important}
      .table-striped>tbody>tr:nth-of-type(odd){background-color:#f9f9f9}
      .table>tbody>tr.active>td,.table>tbody>tr.active>th,.table>tbody>tr>td.active,.table>tbody>tr>th.active,.table>tfoot>tr.active>td,.table>tfoot>tr.active>th,.table>tfoot>tr>td.active,.table>tfoot>tr>th.active,.table>thead>tr.active>td,.table>thead>tr.active>th,.table>thead>tr>td.active,.table>thead>tr>th.active{background-color:#f5f5f5}
      .table{width:100%;max-width:100%;margin-bottom:20px}.table>tbody>tr>td,.table>tbody>tr>th,.table>tfoot>tr>td,.table>tfoot>tr>th,.table>thead>tr>td,.table>thead>tr>th{padding:8px;line-height:1.42857143;vertical-align:top;border-top:1px solid #ddd}.table>thead>tr>th{vertical-align:bottom;border-bottom:2px solid #ddd}.table>caption+thead>tr:first-child>td,.table>caption+thead>tr:first-child>th,.table>colgroup+thead>tr:first-child>td,.table>colgroup+thead>tr:first-child>th,.table>thead:first-child>tr:first-child>td,.table>thead:first-child>tr:first-child>th{border-top:0}.table>tbody+tbody{border-top:2px solid #ddd}.table .table{background-color:#fff}
      .row{margin-right:-15px;margin-left:-15px}.col-lg-1,.col-lg-10,.col-lg-11,.col-lg-12,.col-lg-2,.col-lg-3,.col-lg-4,.col-lg-5,.col-lg-6,.col-lg-7,.col-lg-8,.col-lg-9,.col-md-1,.col-md-10,.col-md-11,.col-md-12,.col-md-2,.col-md-3,.col-md-4,.col-md-5,.col-md-6,.col-md-7,.col-md-8,.col-md-9,.col-sm-1,.col-sm-10,.col-sm-11,.col-sm-12,.col-sm-2,.col-sm-3,.col-sm-4,.col-sm-5,.col-sm-6,.col-sm-7,.col-sm-8,.col-sm-9,.col-xs-1,.col-xs-10,.col-xs-11,.col-xs-12,.col-xs-2,.col-xs-3,.col-xs-4,.col-xs-5,.col-xs-6,.col-xs-7,.col-xs-8,.col-xs-9{position:relative;min-height:1px;padding-right:15px;padding-left:15px}
      .form-control{display:block;width:100%;height:34px;padding:6px 12px;font-size:14px;line-height:1.42857143;color:#555;background-color:#fff;background-image:none;border:1px solid #ccc;border-radius:4px;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075);box-shadow:inset 0 1px 1px rgba(0,0,0,.075);-webkit-transition:border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;-o-transition:border-color ease-in-out .15s,box-shadow ease-in-out .15s;transition:border-color ease-in-out .15s,box-shadow ease-in-out .15s}.form-control:focus{border-color:#66afe9;outline:0;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6);box-shadow:inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6)}
      .label{display:inline;padding:.2em .6em .3em;font-size:75%;font-weight:700;line-height:1;color:#fff;text-align:center;white-space:nowrap;vertical-align:baseline;border-radius:.25em}
    </style>



			<table class="table-ai">
				
			</table>
		</div>
	</div>

</body>
</html>