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
    <h1> Status DMR </h1>
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
                                           @if($input_sb!=null && $input_distrik!=null && $input_lokasi!=null)
                                                @foreach($lokasi as $l)
                                                <option value="{{$l->id}}" <?php echo($l->id == $input_lokasi->id ? 'selected' : '')?>>{{$l->name}}</option>
                                                @endforeach
                                            @endif

                                        </select>
                                    </div>
                                </div>
                                <hr>
                                <div class="row" style="margin: 10px;">
                                    <div class="col-md-2"><label>Fase</label></div>
                                    <div class="col-md-3">
                                      <select class="form-control" name="fase">
                                          <option></option>
                                          @foreach ($fase as $fases => $value)
                                              <option value="{{ $value->id }}" <?php if($input_fase!= null) echo($input_fase->id == $value->id ? 'selected' : '')?>> {{ $value->name }} </option>
                                          @endforeach
                                      </select>
                                    </div>
                                    <div class="col-md-2"><label name="draft">Form 6 Reimburse</label></div>
                                    <div class="col-md-3">
                                        <select class="form-control" name="form_6_reimburse" required="">
                                            @if($input_sb!=null && $input_form_6_reimburse!=null && $input_lokasi!=null)
                                                @foreach($drafts_form_6_reimburse as $d)
                                                    <option value="{{$d->id}}" <?php echo($d->id == $input_form_6_reimburse->id ? 'selected' : '')?>>{{$d->draft_versi}}</option>
                                                @endforeach
                                            @endif

                                        </select>
                                    </div>
                                </div>
                                <div class="row" style="margin: 10px;">
                                    <div class="col-md-2"><label name="draft">Form 6 Rutin</label></div>
                                    <div class="col-md-3">
                                        <select class="form-control" name="form_6_rutin" required="">
                                            @if($input_sb!=null && $input_form_6_rutin!=null && $input_lokasi!=null)
                                                @foreach($drafts_form_6_rutin as $d)
                                                    <option value="{{$d->id}}" <?php echo($d->id == $input_form_6_rutin->id ? 'selected' : '')?>>{{$d->draft_versi}}</option>
                                                @endforeach
                                            @endif

                                        </select>
                                    </div>
                                    <div class="col-md-2"><label name="draft">Form 10 Pengembangan Usaha</label></div>
                                    <div class="col-md-3">
                                        <select class="form-control" name="form_10_pu" required="">
                                            @if($input_sb!=null && $input_form_10_pu!=null && $input_lokasi!=null)
                                                @foreach($drafts_form_10_pu as $d)
                                                    <option value="{{$d->id}}" <?php echo($d->id == $input_form_10_pu->id ? 'selected' : '')?>>{{$d->draft_versi}}</option>
                                                @endforeach
                                            @endif

                                        </select>
                                    </div>
                                </div>
                                <div class="row" style="margin: 10px;">
                                    <div class="col-md-2"><label name="draft">Form 10 Penguatan Kit</label></div>
                                    <div class="col-md-3">
                                        <select class="form-control" name="form_10_pk" required="">
                                            @if($input_sb!=null && $input_form_10_pk!=null && $input_lokasi!=null)
                                                @foreach($drafts_form_10_pk as $d)
                                                    <option value="{{$d->id}}" <?php echo($d->id == $input_form_10_pk->id ? 'selected' : '')?>>{{$d->draft_versi}}</option>
                                                @endforeach
                                            @endif

                                        </select>
                                    </div>
                                    <div class="col-md-2"><label name="draft">Form 10 PLN</label></div>
                                    <div class="col-md-3">
                                        <select class="form-control" name="form_10_pln" required="">
                                            @if($input_sb!=null && $input_form_10_pln!=null && $input_lokasi!=null)
                                                @foreach($drafts_form_10_pln as $d)
                                                    <option value="{{$d->id}}" <?php echo($d->id == $input_form_10_pln->id ? 'selected' : '')?>>{{$d->draft_versi}}</option>
                                                @endforeach
                                            @endif

                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                       <button type="submit" class="btn btn-primary">
                                           <span class="glyphicon glyphicon-search"> </span> cari
                                       </button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        </div>
    </div>
@if(($input_form_10_pln!= null || $input_form_10_pk!= null || $input_form_10_pu!= null || $input_form_6_rutin != null || $input_form_6_reimburse != null) && $input_lokasi!= null)

<!-- Grafik Status DMR -->

  <div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
          <div id="status-dmr">
            <script type="text/javascript">
              Highcharts.chart('status-dmr', {
                  chart: {
                      type: 'bar'
                  },
                  title: {
                      text: 'Status DMR'
                  },
                  xAxis: {
                      categories: ['Queue','Rejected', 'Revised', 'Approved', 'N/A', 'Submitted', 'DMR'],

                  },
                  yAxis: {
                      min: 0,
                      title: {
                          text: 'Jumlah DMR',
                          align: 'high'
                      },
                      labels: {
                          overflow: 'justify'
                      }
                  },
                  tooltip: {
                      valueSuffix: ' DMR'
                  },
                  plotOptions: {
                      bar: {
                          dataLabels: {
                              enabled: true
                          }
                      }
                  },
                  legend: {
                      layout: 'vertical',
                      align: 'right',
                      verticalAlign: 'top',
                      x: -40,
                      y: 80,
                      floating: true,
                      borderWidth: 1,
                      backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
                      shadow: true
                  },
                  credits: {
                      enabled: false
                  },
                  series: [{
                      name: 'OH',
                      data: [{{json_encode($summary_dmr['OH']['queue'])}}, {{json_encode($summary_dmr['OH']['rejected'])}}, {{json_encode($summary_dmr['OH']['revised'])}}, {{json_encode($summary_dmr['OH']['approved'])}}, {{json_encode($summary_dmr['OH']['total'] - ($summary_dmr['OH']['queue'] + $summary_dmr['OH']['rejected'] + $summary_dmr['OH']['revised'] + $summary_dmr['OH']['approved']))}}]
                  }, {
                      name: 'EJ',
                      data: [{{json_encode($summary_dmr['EJ']['queue'])}}, {{json_encode($summary_dmr['EJ']['rejected'])}}, {{json_encode($summary_dmr['EJ']['revised'])}}, {{json_encode($summary_dmr['EJ']['approved'])}}, {{json_encode($summary_dmr['EJ']['total'] - ($summary_dmr['EJ']['queue'] + $summary_dmr['EJ']['rejected'] + $summary_dmr['EJ']['revised'] + $summary_dmr['EJ']['approved']))}}]
                  }, {
                      name: 'Investasi',
                      data: [{{json_encode($summary_dmr['Investasi']['queue'])}}, {{json_encode($summary_dmr['Investasi']['rejected'])}}, {{json_encode($summary_dmr['Investasi']['revised'])}}, {{json_encode($summary_dmr['Investasi']['approved'])}}, {{json_encode($summary_dmr['Investasi']['total'] - ($summary_dmr['Investasi']['queue'] + $summary_dmr['Investasi']['rejected'] + $summary_dmr['Investasi']['revised'] + $summary_dmr['Investasi']['approved']))}}]
                  }, {
                      name: 'Lainnya',
                      data: [{{json_encode($summary_dmr['Lainnya']['queue'])}}, {{json_encode($summary_dmr['Lainnya']['rejected'])}}, {{json_encode($summary_dmr['Lainnya']['revised'])}}, {{json_encode($summary_dmr['Lainnya']['approved'])}}, {{json_encode($summary_dmr['Lainnya']['total'] - ($summary_dmr['Lainnya']['queue'] + $summary_dmr['Lainnya']['rejected'] + $summary_dmr['Lainnya']['revised'] + $summary_dmr['Lainnya']['approved']))}}]
                  }]
              });
            </script>
          </div>
      </div>
    </div>
  </div>
</div>

<!-- Grafik Status DMR Total -->

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <div id="status-dmr-total">
                    <script type="text/javascript">
                      Highcharts.chart('status-dmr-total', {
                            chart: {
                                plotBackgroundColor: null,
                                plotBorderWidth: null,
                                plotShadow: false,
                                type: 'pie'
                            },
                            title: {
                                text: 'Status DMR Total'
                            },
                            tooltip: {
                                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                            },
                            plotOptions: {
                                pie: {
                                    allowPointSelect: true,
                                    cursor: 'pointer',
                                    dataLabels: {
                                        enabled: true,
                                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                                        style: {
                                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                        }
                                    }
                                }
                            },
                            series: [{
                                name: 'Brands',
                                colorByPoint: true,
                                data: [{
                                    name: 'N/A',
                                    y: {{json_encode($piechart_summary['N-A'])}}
                                }, {
                                    name: 'Approved',
                                    y: {{json_encode($piechart_summary['Approved'])}},
                                    sliced: true,
                                    selected: true
                                }, {
                                    name: 'Revised',
                                    y:{{json_encode($piechart_summary['Revised'])}}
                                }, {
                                    name: 'Rejected',
                                    y: {{json_encode($piechart_summary['Rejected'])}}
                                }, {
                                    name: 'Queue',
                                    y: {{json_encode($piechart_summary['Queue'])}}
                                }]
                            }]
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>


    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>SUMMARY STATUS DMR AVAILABLE</h2>
                    <div class="clearfix"></div>
                </div>

                <a href="{{ Request::fullUrl() }}&download=status-dmr&type=excel" id="get-excel" class="btn btn-success pull-right"><i class="fa fa-download"></i> Download Excel</a>
                <a href="{{ Request::fullUrl() }}&download=status-dmr&type=pdf" id="get-pdf" class="btn btn-primary pull-right"><i class="fa fa-download"></i> Download PDF</a>

                <div class="x_content">
                    <table id="datatable2" class="table table-striped table-bordered">
                        <thead style="background-color: #2a3f54; color: white;">
                          <tr>
                            <th colspan="2"></th>
                            <th colspan="2">DMR Status</th>
                            <th colspan="4">Review Status</th>
                          </tr>
                          <tr>
                            <th>Parent</th>
                            <th>DMR</th>
                            <th>Submitted</th>
                            <th>N/A</th>
                            <th>Approved</th>
                            <th>Revised</th>
                            <th>Rejected</th>
                            <th>Queue</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach($summary_dmr as $key => $summary)
                            <tr>
                                <td>{{$key}}</td>
                                <td style="text-align: right">{{$summary['total']}}</td>
                                <td style="text-align: right">{{$summary['approved'] + $summary['revised'] + $summary['rejected'] + $summary['queue'] }}</td>
                                <td style="text-align: right">{{$summary['total'] - ($summary['approved'] + $summary['revised'] + $summary['rejected'] + $summary['queue']) }}</td>
                                <td style="text-align: right">{{$summary['approved']}}</td>
                                <td style="text-align: right">{{$summary['revised']}}</td>
                                <td style="text-align: right">{{$summary['rejected']}}</td>
                                <td style="text-align: right">{{$summary['queue']}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Rincian Status DMR</h2>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead style="background-color: #2a3f54; color: white;">
                              <!-- <tr>
                                <th colspan="7"></th>
                                <th colspan="4">Date</th>
                              </tr> -->
                              <tr>
                                <th>Parent</th>
                                <th>No PRK</th>
                                <th>Nama PRK</th>
                                <th>Anggaran PRK</th>
                                <th>DMR Status</th>
                                <th>Review Status</th>
                                <th>Document</th>
                                <!-- <th>Approved</th>
                                <th>Rejected</th>
                                <th>Revised</th>
                                <th>Queue</th> -->
                              </tr>
                            </thead>
                            <tbody>
                              @foreach($data_dmr as $key => $dmr)
                              <tr>
                                <td>{{$dmr['parent']}}</td>
                                <td>{{$dmr['prk']}}</td>
                                <td>{{$dmr['nama_prk']}}</td>
                                <td style="text-align: right">{{number_format($dmr['anggaran'], 0, '.', ',')}}</td>
                                <td>{{$dmr['dmr_status']}}</td>
                                <td>{{$dmr['review_status']}}</td>
                                <td>
                                    @if($dmr['document']!= '-')
                                    <a href="{{ asset($dmr['document']) }}" class="btn btn-primary pull-right"><i class="fa fa-download"></i></a>
                                    @else
                                    -
                                    @endif
                                </td>
                                <!-- <td>{{$dmr['approved_at']}}</td>
                                <td>{{$dmr['rejected_at']}}</td>
                                <td>{{$dmr['revised_at']}}</td>
                                <td>{{$dmr['submitted_at']}}</td> -->
                              </tr>
                              @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
<script type="text/javascript">
    $(document).ready(function() {
        $('select[name="strategi_bisnis"]').on('change', function() {
            var strategi_bisnisID = $(this).val();
            $('select[name="distrik"]').empty();
            $('select[name="lokasi"]').empty();
            $('select[name="lokasi"]').append('<option value="">- Pilih Lokasi -</option>');

            if(strategi_bisnisID) {
                $.ajax({
                    url: "{{url('/output/risk-profile/ajax/')}}/"+strategi_bisnisID,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {
                // console.log(data);
                      $('select[name="distrik"]').empty();
                      $('select[name="distrik"]').append('<option value="">- Pilih Distrik -</option>');
                      $.each(data, function(sb, value) {
                          $('select[name="distrik"]').append('<option value="'+ value["id"] +'">'+ value["name"] +'</option>');
                      });

                    }
                });
            }else{
                $('select[name="distrik"]').empty();
            }
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

      $('select[name="form_10_pln"]').empty();
      $('select[name="form_10_pk"]').empty();
      $('select[name="form_10_pu"]').empty();
      $('select[name="form_6_rutin"]').empty();
      $('select[name="form_6_reimburse"]').empty();

      if(id_lokasi && id_tahun) {
          $.ajax({
              url: "{{ url('/output/status-dmr/ajax') }}"+"/"+id_lokasi+"/"+id_tahun+"/2",
              type: "GET",
              dataType: "json",
              success:function(data) {
                  $('select[name="form_6_reimburse"]').append('<option selected="" disabled="">- Pilih Draft - </option>');

                $.each(data, function(ad , value) {
                    $('select[name="form_6_reimburse"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +'</option>');
                });

              }
          });
          $.ajax({
              url: "{{ url('/output/status-dmr/ajax') }}"+"/"+id_lokasi+"/"+id_tahun+"/3",
              type: "GET",
              dataType: "json",
              success:function(data) {
                  $('select[name="form_6_rutin"]').append('<option selected="" disabled="">- Pilih Draft - </option>');

                $.each(data, function(ad , value) {
                    $('select[name="form_6_rutin"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +'</option>');
                });

              }
          });
          $.ajax({
              url: "{{ url('/output/status-dmr/ajax') }}"+"/"+id_lokasi+"/"+id_tahun+"/4",
              type: "GET",
              dataType: "json",
              success:function(data) {
                  $('select[name="form_10_pu"]').append('<option selected="" disabled="">- Pilih Draft - </option>');

                $.each(data, function(ad , value) {
                    $('select[name="form_10_pu"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +'</option>');
                });

              }
          });
          $.ajax({
              url: "{{ url('/output/status-dmr/ajax') }}"+"/"+id_lokasi+"/"+id_tahun+"/5",
              type: "GET",
              dataType: "json",
              success:function(data) {
                  $('select[name="form_10_pk"]').append('<option selected="" disabled="">- Pilih Draft - </option>');

                $.each(data, function(ad , value) {
                    $('select[name="form_10_pk"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +'</option>');
                });

              }
          });
          $.ajax({
              url: "{{ url('/output/status-dmr/ajax') }}"+"/"+id_lokasi+"/"+id_tahun+"/6",
              type: "GET",
              dataType: "json",
              success:function(data) {
                  $('select[name="form_10_pln"]').append('<option selected="" disabled="">- Pilih Draft - </option>');

                $.each(data, function(ad , value) {
                    $('select[name="form_10_pln"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +'</option>');
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
