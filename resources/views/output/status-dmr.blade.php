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

        /*Update line height & font-size*/
        .table thead tr th{
          line-height: 1;
        }
        .table tbody tr td{
          /*Untuk data yang deskripsi panjang*/
          line-height: 1;

          /*Untuk data yang tidak ada deskripsi panjang*/
          /*line-height: 0.5; */
        }
        .table {
          font-size: 11px;
        }

        .form-horizontal .form-group
        {
            margin-right: 0;
            margin-left: 0;
            margin-top: -12px;
        }
    </style>

@endsection

@section('content')
    <h3> Status DMR dan TOR </h3>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel collapse">
        <!-- <div class="panel-heading"> -->
        <div class="x_title">
          <h2 style="font-size: 18px;">PENCARIAN</h2>
          <ul class="nav navbar-right panel_toolbox">
            <li>
              <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </li>
            <li>
              <a class="close-link"><i class="fa fa-close"></i></a>
            </li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <!-- <div class="panel-default"> -->
        <div class="x_content" style="display: none;">
          <br/>
          <form class="form-horizontal form-label-left">

            <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12">Tahun Anggaran</label>
              <div class="col-md-4 col-sm-4 col-xs-12">
                <!-- <select class="form-control col-md-7 col-xs-12" name="tahun_anggaran">
                  <option>- Pilih Tahun -</option>
                      @foreach($tahun as $th)
                        <option value="{{$th->tahun}}" @if($input_tahun == $th->tahun) {{'selected'}} @endif>{{$th->tahun}}</option>
                      @endforeach
                </select> -->
                <input type="text" class="form-control col-md-7 col-xs-12" name="tahun_anggaran" readonly="readonly" value="{{$input_tahun}}">
              </div>

              <div class="form-group">
              <label class="col-md-2 col-sm-3 col-xs-12">Struktur Bisnis</label>
              <div class="col-md-3 col-sm-4 col-xs-12">
                <!-- <select class="form-control col-md-7 col-xs-12" name="strategi_bisnis" id="strategi_bisnis" required="">
                  <option>- Pilih Struktur Bisnis -</option>
                    @foreach ($sb as $sbs => $value)
                      <option value="{{ $value->id }}" <?php if($input_sb!=null) echo($input_sb->id == $value->id ? 'selected' : '')?> > {{ $value->name }} </option>
                    @endforeach
                </select> -->
                <input type="text" class="form-control col-md-7 col-xs-12" name="strategi_bisnis" readonly="readonly" value="{{($input_sb!=null) ? $input_sb->name : ''}}">
              </div>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12" >Distrik</label>
              <div class="col-md-4 col-sm-4 col-xs-12">
                <!-- <select class="form-control col-md-7 col-xs-12" name="distrik" required="">
                  @if($input_sb!=null && $input_distrik!=null)
                      @foreach($distrik as $d)
                        <option value="{{$d->id}}" <?php echo($d->id == $input_distrik->id ? 'selected' : '')?>>{{$d->name}}</option>
                      @endforeach
                  @endif
                </select> -->
                <input type="text" class="form-control col-md-7 col-xs-12" name="distrik" readonly="readonly" value="{{($input_distrik!=null) ? $input_distrik->name : ''}}">
              </div>

              <div class="form-group">
              <label class="col-md-2 col-sm-3 col-xs-12">Lokasi</label>
              <div class="col-md-3 col-sm-4 col-xs-12">
                <!-- <select class="form-control col-md-7 col-xs-12" name="lokasi" required="">
                  @if($input_sb!=null && $input_distrik!=null && $input_lokasi!=null)
                    @foreach($lokasi as $l)
                      <option value="{{$l->id}}" <?php echo($l->id == $input_lokasi->id ? 'selected' : '')?>>{{$l->name}}</option>
                    @endforeach
                  @endif
                </select> -->
                <input type="text" class="form-control col-md-7 col-xs-12" name="lokasi" readonly="readonly" value="{{$input_lokasi!=null ? $input_lokasi->name : ''}}">
              </div>
            </div>
            </div>

            <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12">Fase</label>
              <div class="col-md-4 col-sm-4 col-xs-12">
                <!-- <select class="form-control col-md-7 col-xs-12" name="fase">
                  <option></option>
                    @foreach ($fase as $fases => $value)
                      <option value="{{ $value->id }}" <?php if($input_fase!= null) echo($input_fase->id == $value->id ? 'selected' : '')?>> {{ $value->name }} </option>
                    @endforeach
                </select> -->
                <input type="text" name="fase" value="{{($input_fase!= null) ? $input_fase->name : ''}}" class="form-control col-md-7 col-xs-12" readonly="readonly">
              </div>

              <div class="form-group">
              <label class="col-md-2 col-sm-3 col-xs-12"></label>
              <div class="col-md-3 col-sm-4 col-xs-12"></div>
              </div>
            </div>
            <hr>

            <div class="form-group" style="margin-top: 5px;">
              <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12">Form 6 - Reimburse</label>
              <div class="col-md-6 col-sm-4 col-xs-12">
                <!-- <select class="form-control col-md-7 col-xs-12" name="form_6_reimburse" required="">
                  @if($input_sb!=null && $input_form_6_reimburse!=null && $input_lokasi!=null)
                      @foreach($drafts_form_6_reimburse as $d)
                        <option value="{{$d->id}}" <?php echo($d->id == $input_form_6_reimburse->id ? 'selected' : '')?>>{{$d->draft_versi}}</option>
                      @endforeach
                  @endif
                </select> -->
                <input type="text" name="form_6_reimburse" value="{{ ($input_form_6_reimburse!= null) ? $input_form_6_reimburse->draft_versi.' - '.$input_form_6_reimburse->name : '' }}" class="form-control col-md-7 col-xs-12" readonly="readonly">
              </div>
              </div>
            </div>

            <div class="form-group" style="margin-top: 5px;">
              <label class="col-md-3 col-sm-3 col-xs-12">Form 6 - Rutin</label>
              <div class="col-md-6 col-sm-4 col-xs-12">
                <!-- <select class="form-control col-md-7 col-xs-12" name="form_6_rutin" required="">
                  @if($input_sb!=null && $input_form_6_rutin!=null && $input_lokasi!=null)
                      @foreach($drafts_form_6_rutin as $d)
                        <option value="{{$d->id}}" <?php echo($d->id == $input_form_6_rutin->id ? 'selected' : '')?>>{{$d->draft_versi}}</option>
                      @endforeach
                  @endif
                </select> -->
                <input type="text" name="form_6_rutin" value="{{ ($input_form_6_rutin!= null) ? $input_form_6_rutin->draft_versi.' - '.$input_form_6_rutin->name : '' }}" class="form-control col-md-7 col-xs-12" readonly="readonly">
              </div>
            </div>

            <div class="form-group" style="margin-top: 5px;">
              <label class="col-md-3 col-sm-3 col-xs-12">Form 10 - Pengembangan Usaha</label>
              <div class="col-md-6 col-sm-4 col-xs-12">
                <!-- <select class="form-control col-md-7 col-xs-12" name="form_10_pu" required="">
                  @if($input_sb!=null && $input_form_10_pu!=null && $input_lokasi!=null)
                      @foreach($drafts_form_10_pu as $d)
                        <option value="{{$d->id}}" <?php echo($d->id == $input_form_10_pu->id ? 'selected' : '')?>>{{$d->draft_versi}}</option>
                      @endforeach
                  @endif
                </select> -->
                <input type="text" name="form_10_pu" value="{{ ($input_form_10_pu!= null) ? $input_form_10_pu->draft_versi.' - '.$input_form_10_pu->name : '' }}" class="form-control col-md-7 col-xs-12" readonly="readonly">
              </div>
            </div>

            <div class="form-group" style="margin-top: 5px;">
              <label class="col-md-3 col-sm-3 col-xs-12">Form 10 - Penguatan Kit</label>
              <div class="col-md-6 col-sm-4 col-xs-12">
                <!-- <select class="form-control col-md-7 col-xs-12" name="form_10_pk" required="">
                  @if($input_sb!=null && $input_form_10_pk!=null && $input_lokasi!=null)
                      @foreach($drafts_form_10_pk as $d)
                        <option value="{{$d->id}}" <?php echo($d->id == $input_form_10_pk->id ? 'selected' : '')?>>{{$d->draft_versi}}</option>
                      @endforeach
                  @endif
                </select> -->
                <input type="text" name="form_10_pk" value="{{ ($input_form_10_pk!= null) ? $input_form_10_pk->draft_versi.' - '.$input_form_10_pk->name : '' }}" class="form-control col-md-7 col-xs-12" readonly="readonly">
              </div>
            </div>

            <div class="form-group" style="margin-top: 5px;">
              <label class="col-md-3 col-sm-3 col-xs-12">Form 10 - PLN</label>
              <div class="col-md-6 col-sm-4 col-xs-12">
                <!-- <select class="form-control col-md-7 col-xs-12" name="form_10_pln" required="">
                  @if($input_sb!=null && $input_form_10_pln!=null && $input_lokasi!=null)
                      @foreach($drafts_form_10_pln as $d)
                        <option value="{{$d->id}}" <?php echo($d->id == $input_form_10_pln->id ? 'selected' : '')?>>{{$d->draft_versi}}</option>
                      @endforeach
                  @endif
                </select> -->
                <input type="text" name="form_10_pln" value="{{ ($input_form_10_pln!= null) ? $input_form_10_pln->draft_versi.' - '.$input_form_10_pln->name : '' }}" class="form-control col-md-7 col-xs-12" readonly="readonly">
              </div>
            </div>


           <!--  <div class="ln_solid"></div>

              <div class="form-group">
                <div >
                  <button type="submit" class="btn btn-primary pull-right">
                      <span class="glyphicon glyphicon-search"> </span> cari
                  </button>
                </div>
              </div> -->

            </form>
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
                      categories: ['Queue','Rejected', 'Revised', 'Approved', 'Draft', 'Submitted', 'DMR'],

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

<!-- Grafik Status TOR -->

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
          <div id="status-tor">
            <script type="text/javascript">
              Highcharts.chart('status-tor', {
                  chart: {
                      type: 'bar'
                  },
                  title: {
                      text: 'Status TOR'
                  },
                  xAxis: {
                      categories: ['Queue','Rejected', 'Revised', 'Approved', 'Draft', 'Submitted', 'DMR'],

                  },
                  yAxis: {
                      min: 0,
                      title: {
                          text: 'Jumlah TOR',
                          align: 'high'
                      },
                      labels: {
                          overflow: 'justify'
                      }
                  },
                  tooltip: {
                      valueSuffix: ' TOR'
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
                      data: [{{json_encode($summary_tor['OH']['queue'])}}, {{json_encode($summary_tor['OH']['rejected'])}}, {{json_encode($summary_tor['OH']['revised'])}}, {{json_encode($summary_tor['OH']['approved'])}}, {{json_encode($summary_tor['OH']['total'] - ($summary_tor['OH']['queue'] + $summary_tor['OH']['rejected'] + $summary_tor['OH']['revised'] + $summary_tor['OH']['approved']))}}]
                  }, {
                      name: 'EJ',
                      data: [{{json_encode($summary_tor['EJ']['queue'])}}, {{json_encode($summary_tor['EJ']['rejected'])}}, {{json_encode($summary_tor['EJ']['revised'])}}, {{json_encode($summary_tor['EJ']['approved'])}}, {{json_encode($summary_tor['EJ']['total'] - ($summary_tor['EJ']['queue'] + $summary_tor['EJ']['rejected'] + $summary_tor['EJ']['revised'] + $summary_tor['EJ']['approved']))}}]
                  }, {
                      name: 'Investasi',
                      data: [{{json_encode($summary_tor['Investasi']['queue'])}}, {{json_encode($summary_tor['Investasi']['rejected'])}}, {{json_encode($summary_tor['Investasi']['revised'])}}, {{json_encode($summary_tor['Investasi']['approved'])}}, {{json_encode($summary_tor['Investasi']['total'] - ($summary_tor['Investasi']['queue'] + $summary_tor['Investasi']['rejected'] + $summary_tor['Investasi']['revised'] + $summary_tor['Investasi']['approved']))}}]
                  }, {
                      name: 'Lainnya',
                      data: [{{json_encode($summary_tor['Lainnya']['queue'])}}, {{json_encode($summary_tor['Lainnya']['rejected'])}}, {{json_encode($summary_tor['Lainnya']['revised'])}}, {{json_encode($summary_tor['Lainnya']['approved'])}}, {{json_encode($summary_tor['Lainnya']['total'] - ($summary_tor['Lainnya']['queue'] + $summary_tor['Lainnya']['rejected'] + $summary_tor['Lainnya']['revised'] + $summary_tor['Lainnya']['approved']))}}]
                  }]
              });
            </script>
          </div>
      </div>
    </div>
  </div>
</div>


<!-- Grafik Status DMR dan TOR Total -->

<div class="row">
    <!-- Grafik Status DMR Total -->
    <div class="col-md-6 col-sm-6 col-xs-6">
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
                                    name: 'Draft',
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

    <!-- Grafik Status TOR Total-->
    <div class="col-md-6 col-sm-6 col-xs-6">
        <div class="x_panel">
            <div class="x_title">
                <div id="status-tor-total">
                    <script type="text/javascript">
                      Highcharts.chart('status-tor-total', {
                            chart: {
                                plotBackgroundColor: null,
                                plotBorderWidth: null,
                                plotShadow: false,
                                type: 'pie'
                            },
                            title: {
                                text: 'Status TOR Total'
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
                                    name: 'Draft',
                                    y: {{json_encode($piechart_summary_tor['N-A'])}}
                                }, {
                                    name: 'Approved',
                                    y: {{json_encode($piechart_summary_tor['Approved'])}},
                                    sliced: true,
                                    selected: true
                                }, {
                                    name: 'Revised',
                                    y:{{json_encode($piechart_summary_tor['Revised'])}}
                                }, {
                                    name: 'Rejected',
                                    y: {{json_encode($piechart_summary_tor['Rejected'])}}
                                }, {
                                    name: 'Queue',
                                    y: {{json_encode($piechart_summary_tor['Queue'])}}
                                }]
                            }]
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Summary Status DMR AVAILABLE -->
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2 style="font-size: 18px;">SUMMARY STATUS DMR AVAILABLE</h2>
                    <div class="clearfix"></div>
                </div>

                <a href="{{ Request::fullUrl() }}&download=status-dmr&type=excel" id="get-excel" class="btn btn-success pull-right"><i class="fa fa-download"></i> Download Excel</a>
                <a href="{{ Request::fullUrl() }}&download=status-dmr&type=pdf" id="get-pdf" class="btn btn-primary pull-right"><i class="fa fa-download"></i> Download PDF</a>

                <div class="x_content">
                    <table id="datatable2" class="table table-striped table-bordered" style="font-size:11px;">
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
                            <th>Draft</th>
                            <th>Approved</th>
                            <th>Revised</th>
                            <th>Rejected</th>
                            <th>Queue</th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php $baris = 0; ?>
                            @foreach($summary_dmr as $key => $summary)
                            <?php $baris = $baris+1; $warna=($baris % 2 == 0) ? "#ffffff" : "#E8EDEF"; ?>
                            <tr style="background-color: <?= $warna?>">
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
<!-- End of Summary Status DMR AVAILABLE -->

<!-- Start Summary Status TOR Available -->
<div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2 style="font-size: 18px;">SUMMARY STATUS TOR AVAILABLE</h2>
                    <div class="clearfix"></div>
                </div>

                <a href="{{ Request::fullUrl() }}&download=status-tor&type=excel" id="get-excel" class="btn btn-success pull-right"><i class="fa fa-download"></i> Download Excel</a>
                <a href="{{ Request::fullUrl() }}&download=status-tor&type=pdf" id="get-pdf" class="btn btn-primary pull-right"><i class="fa fa-download"></i> Download PDF</a>

                <div class="x_content">
                    <table id="datatable2" class="table table-striped table-bordered" style="font-size:11px;">
                        <thead style="background-color: #2a3f54; color: white;">
                          <tr>
                            <th colspan="2"></th>
                            <th colspan="2">TOR Status</th>
                            <th colspan="4">Review Status</th>
                          </tr>
                          <tr>
                            <th>Parent</th>
                            <th>TOR</th>
                            <th>Submitted</th>
                            <th>Draft</th>
                            <th>Approved</th>
                            <th>Revised</th>
                            <th>Rejected</th>
                            <th>Queue</th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php $baris = 0; ?>
                            @foreach($summary_tor as $key => $summary)
                            <?php $baris = $baris+1; $warna=($baris % 2 == 0) ? "#ffffff" : "#E8EDEF"; ?>
                            <tr style="background-color: <?= $warna?>">
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
<!-- End of Summary Status TOR Available -->
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2 style="font-size: 18px;">RINCIAN STATUS DMR DAN TOR</h2>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered" style="font-size:11px;">
                            <thead style="background-color: #2a3f54; color: white;">
                              <!-- <tr>
                                <th colspan="7"></th>
                                <th colspan="4">Date</th>
                              </tr> -->
                              <tr>
                                <th style="vertical-align: middle;">Parent</th>
                                <th style="vertical-align: middle;">No PRK</th>
                                <th style="vertical-align: middle;">Nama PRK</th>
                                <th style="vertical-align: middle;">Anggaran PRK</th>
                                <th style="vertical-align: middle;">DMR Status</th>
                                <th style="vertical-align: middle;">TOR Status</th>
                                <!-- <th style="vertical-align: middle;">Review Status</th>
                                <th style="vertical-align: middle;">Document</th> -->
                                <!-- <th>Approved</th>
                                <th>Rejected</th>
                                <th>Revised</th>
                                <th>Queue</th> -->
                              </tr>
                            </thead>
                            <tbody>
                              <?php $baris = 0; ?>
                              @foreach($data_dmr as $key => $dmr)
                              <?php $baris = $baris+1; $warna=($baris % 2 == 0) ? "#ffffff" : "#E8EDEF"; ?>
                              <tr style="background-color: <?= $warna?>">
                                <td>{{$dmr['parent']}}</td>
                                <td>{{$dmr['prk']}}</td>
                                <td>{{$dmr['nama_prk']}}</td>
                                <td style="text-align: right">{{number_format($dmr['anggaran'], 0, '.', ',')}}</td>
                                <td style="text-align: center;">{{$dmr['dmr_status']}}</td>
                                <td>
                                  <?php $flag = false;?>
                                  @foreach($data_tor as $tor)
                                    @if($tor['no_dokumen_dmr'] == $dmr['no_dokumen'])
                                      {{$tor['tor_status']}}
                                      @break
                                    @endif
                                  @endforeach
                                </td>
                                <!-- <td>{{$dmr['review_status']}}</td>
                                <td>
                                    @if($dmr['document']!= '-')
                                    <a href="{{ asset($dmr['document']) }}" class="btn btn-primary pull-right"><i class="fa fa-download"></i></a>
                                    @else
                                    -
                                    @endif
                                </td> -->
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

<script type="text/javascript">
  $(document).ready(function() {
    var table = $('#datatable').DataTable( {
        "scrollY": "800px",
        // "scrollX": "300px",
        "scrollCollapse": true,
        "fixedHeader": true,
        "paging" : false,
        "ordering" : false,
        "paging": true,
        ordering: false,
        searching: false,
        aLengthMenu: [
        [10, 25, 50, 100, -1],
        [10, 25, 50, 100, "All"]],
        // iDisplayLength: 10
    } );

} );
</script>

@endsection
