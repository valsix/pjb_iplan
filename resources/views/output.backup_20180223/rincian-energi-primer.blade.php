@extends('layouts.app')

@section('css_page')
    <style type="text/css">
        .table-container
        {
            widtd: 100%;
            overflow-x: auto;
            overflow: auto;
            margin: 0 0 1em;
        }
        thead th{
          text-align: center;
        }

    </style>

@endsection

@section('content')
    <h1> Rincian Energi Primer </h1>
    <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">

    <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                     Pencarian
                </div>
                <div class="panel-default">
                    <br>
                    <div class="row">
                        <div class="col-lg-12">
                            <form>
                                <div class="row" style="margin: 10px;">
                                    <div class="col-md-2"><label>Tahun Anggaran</label></div>
                                    <div class="col-md-3">
                                        <select name="tahun_anggaran" class="form-control">
                                              <option>- Pilih Tahun -</option>
                                              @foreach($tahun as $th)
                                                  <option value="{{$th->tahun}}" @if($input_tahun == $th->tahun) {{'selected'}} @endif>{{$th->tahun}}</option>
                                              @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2"><label>Struktur Bisnis</label></div>
                                    <div class="col-md-3">
                                        <select class="form-control" name="strategi_bisnis" id="strategi_bisnis" required="">
                                            <option>- Pilih Struktur Bisnis -</option>
                                            @foreach ($sb as $sbs => $value)
                                                <option value="{{ $value->id }}" <?php if($input_sb!=null) echo($input_sb->id == $value->id ? 'selected' : '')?> > {{ $value->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row" style="margin: 10px;">
                                    <div class="col-md-2"><label>Distrik</label></div>

                                    <div class="col-md-3">
                                        <select class="form-control" name="distrik" required="">
                                            <option>- Pilih Distrik -</option>
                                            @if($input_sb!=null && $input_distrik!=null)
                                                @foreach($distrik as $d)
                                                <option value="{{$d->id}}" <?php echo($d->id == $input_distrik->id ? 'selected' : '')?>>{{$d->name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                    <div class="col-md-2"><label> Lokasi</label></div>
                                    <div class="col-md-3">
                                        <select class="form-control" name="lokasi" required="">
                                            <option>- Pilih Lokasi -</option>
                                           @if($input_sb!=null && $input_distrik!=null && $input_lokasi!=null)
                                                @foreach($lokasi as $l)
                                                <option value="{{$l->id}}" <?php echo($l->id == $input_lokasi->id ? 'selected' : '')?>>{{$l->name}}</option>
                                                @endforeach
                                            @endif

                                        </select>
                                    </div>
                                </div>
                                <div class="row" style="margin: 10px;">
                                    <div class="col-md-2"><label>Fase</label></div>
                                    <div class="col-md-3">
                                      <select class="form-control" name="fase">
                                          <option>- Pilih Fase -</option>
                                          @foreach ($fase as $fases => $value)
                                              <option value="{{ $value->id }}" <?php if($input_fase!= null) echo($input_fase->id == $value->id ? 'selected' : '')?>> {{ $value->name }} </option>
                                          @endforeach
                                      </select>
                                    </div>
                                    <div class="col-md-2"><label name="draft">Draft</label></div>
                                    <div class="col-md-3">
                                        <select class="form-control" name="draft_id" required="">
                                            <option>- Pilih Draft -</option>
                                            @if($input_sb!=null && $input_draft!=null && $input_lokasi!=null)
                                                @foreach($drafts as $d)
                                                    <option value="{{$d->id}}" <?php echo($d->id == $input_draft->id ? 'selected' : '')?>>{{$d->draft_versi}}</option>
                                                @endforeach
                                            @endif

                                        </select>
                                    </div>
                                </div>
                                <div class="row" style="margin: 10px;">
                                    <div class="col-md-2"><label>s.d Bulan</label></div>
                                    <div class="col-md-3">
                                        <select class="form-control" name="bulan">
                                            <option>- Pilih Bulan -</option>
                                            @foreach ($months as $key => $value)
                                                <option value="{{ $key }}" <?php if($input_bulan != null) echo($input_bulan == $key ? 'selected' : '')?>> {{ $value }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                       <button type="submit" class="btn btn-primary">
                                           <span class="glyphicon glyphicon-search"> </span> cari
                                       </button>
                                    </div>
                                </div>

                            </form>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
@if($input_sb != NULL)
    @if($input_sb->name == "UP")
        <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>KOMPOSISI PRODUKSI, VOLUME, DAN BIAYA BAHAN BAKAR</h2>
                            <div class="clearfix"></div>
                        </div>
                    <a href="{{ Request::fullUrl() }}&download=rincian-ep-om&type=excel" id="get-excel" class="btn btn-success pull-right"><i class="fa fa-download"></i> Download Excel</a>
                    <a href="{{ Request::fullUrl() }}&download=rincian-ep-om&type=pdf" id="get-pdf" class="btn btn-primary pull-right"><i class="fa fa-download"></i> Download PDF</a>

                      <div class="x_content">
                      <div class="table-responsive">
                        <table id="datatable2" class="table table-striped table-bordered">
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
                                <th rowspan="2">Total Biaya<br>(Rp. Ribu)</th>
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
                          <tr style="background-color: #79C1A9; color: white">
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
                              <tr style="background-color: #79C1A9; color: white">
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
                              <tr style="background-color: #0F6F5B; color: white">
                                  <th>{{$total_up['title']}}</th>

                                  <th style="text-align: right;">{{number_format($total_up['produksi'][0],0,",",".")}}</th>
                                  <th style="text-align: right;">{{number_format($total_up['produksi'][1],0,",",".")}}</th>
                                  <th style="text-align: right;">{{number_format($total_up['produksi'][2],0,",",".")}}</th>
                                  <th style="text-align: right;">{{number_format($total_up['total_produksi'],0,",",".")}}</th>
                                  <th style="text-align: right;">{{number_format( ($total_up['total_produksi'] == 0 ? 0 : $total_up['total_produksi'] / $total_up['total_produksi'] * 100 ), 1, "," , ".")}} %</th>
                                  <th></th>
                                  <th style="text-align: right;">{{number_format($total_up['kebutuhan_ep'][0],0,",",".")}}</th>
                                  <th style="text-align: right;">{{number_format($total_up['kebutuhan_ep'][1],0,",",".")}}</th>
                                  <th style="text-align: right;">{{number_format($total_up['kebutuhan_ep'][2],0,",",".")}}</th>

                                  <th style="text-align: right;">{{number_format($total_up['total_kebutuhan_ep'],0,",",".")}}</th>
                                  <th></th>
                                  <th style="text-align: right;">{{number_format($total_up['jumlah_biaya_bahan_bakar'],0,",",".")}}</th>
                                  <th></th>
                                  <th style="text-align: right;">{{number_format($total_up['jumlah_ongkos_angkut'],0,",",".")}}</th>
                                  <th style="text-align: right;">{{number_format($total_up['jumlah_biaya_pendukung'],0,",",".")}}</th>
                                  <th style="text-align: right;">{{number_format($total_up['total_biaya'],0,",",".")}}</th>
                              </tr>
                          </tfoot>
                        </table>
                      </div>
                      </div>
                    </div>
                </div>
        </div>
    @elseif($input_sb->name == "OM")
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>RINCIAN ENERGI PRIMER</h2>
                        <div class="clearfix"></div>
                    </div>

                    <a href="{{ Request::fullUrl() }}&download=rincian-ep-om&type=excel" id="get-excel" class="btn btn-success pull-right"><i class="fa fa-download"></i> Download Excel</a>

                    <a href="{{ Request::fullUrl() }}&download=rincian-ep-om&type=pdf" id="get-pdf" class="btn btn-primary pull-right"><i class="fa fa-download"></i> Download PDF</a>

                    <div class="x_content">
                      <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead style="background-color: #2a3f54; color: white;">
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
                                <tr style="background-color: #0F6F5B; color: white">
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
                </div>
            </div>
        </div>
    @endif
@endif

<script type="text/javascript">
    $(document).ready(function() {
        $('select[name="strategi_bisnis"]').on('change', function() {
            var strategi_bisnisID = $(this).val();
            $('select[name="distrik"]').empty();
            $('select[name="lokasi"]').empty();

            if(strategi_bisnisID) {
                $.ajax({
                    url: "{{ url('/output/list-prk/ajax') }}"+"/"+strategi_bisnisID,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {
                // console.log(data);
                      $('select[name="distrik"]').empty();
                      $('select[name="distrik"]').append('<option selected="" disabled=""> - Pilih Distrik- </option>');
                      $.each(data, function(sb, value) {
                          $('select[name="distrik"]').append('<option value="'+ value["id"] +'">'+ value["name"] +'</option>');
                      });

                    }
                });
            }else{
                $('select[name="distrik"]').empty();
            }
            if(strategi_bisnisID == 1)
              $('label[name="draft"]').text("Draft RKAU");
            else
              $('label[name="draft"]').text("Draft Bahan Bakar");

        });
    });
</script>
<script type="text/javascript">
  function check() {
        var lokasiID = $(this).val();
        $('select[name="lokasi"]').empty();

        if(lokasiID) {
            $.ajax({
                url: "{{ url('/output/list-prk/ajax2') }}"+"/"+lokasiID,
                type: "GET",
                dataType: "json",
                success:function(data) {

                  $('select[name="lokasi"]').empty();
                  $('select[name="lokasi"]').append('<option selected="" value="" disabled="">- Pilih Lokasi - </option>');
                  console.log(data);
                  // $('select[name="lokasi"]').append('<option value="">==Silahkan Pilih Lokasi==</option>');
                  $.each(data, function(ad , value) {
                  console.log(ad);
                      $('select[name="lokasi"]').append('<option value="'+ value["id"] +'">'+ value["name"] +'</option>');
                  });

                }
            });
        }else{
            $('select[name="lokasi"]').empty();

        }
    }
    $(document).ready(function() {
        $('select[name="distrik"]').on('change', check);
        $('select[name="distrik"]').on('click', check);
    });
</script>
<script type="text/javascript">
  $(document).ready(function() {
    $('select[name="lokasi"]').on('change', function() {
      var id_lokasi = $(this).val();
      var id_tahun = $('select[name="tahun_anggaran"]').val();
      var id_strategi_bisnis = $('select[name="strategi_bisnis"]').val();

      $('select[name="draft_id"]').empty();

      if(id_lokasi && id_tahun) {
          $.ajax({
              url: "{{ url('/output/rincian-energi-primer/ajax3') }}"+"/"+id_strategi_bisnis+"/"+id_lokasi+"/"+id_tahun,
              type: "GET",
              dataType: "json",
              success:function(data) {
                  $('select[name="draft_id"]').append('<option selected="" disabled="">- Pilih Draft - </option>');

                $.each(data, function(ad , value) {
                    $('select[name="draft_id"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +'</option>');
                });

              }
          });
      }else{
          $('select[name="draft_id"]').empty();

      }
    })
  })
</script>

@endsection
