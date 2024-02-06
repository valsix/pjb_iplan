@extends('layouts.app')

@section('css_page')
    <!-- Datatables -->
    <link href="{{ asset('vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}" rel="stylesheet">

    <style type="text/css">
        /*untuk style gambar*/
        .td_text img {
            max-height: 100%;
            max-width: 100%;
            border: 1px solid black;
        }
    </style>
@endsection

@section('js_page')
    <script src="{{ asset('vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-scroller/js/dataTables.scroller.min.js') }}"></script>
    <script src="{{ asset('vendors/jszip/dist/jszip.min.js') }}"></script>
    <script src="{{ asset('vendors/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('vendors/pdfmake/build/vfs_fonts.js') }}"></script>

    <script type="text/javascript">
        $('#datatable').dataTable( {
            "searching": false
        } );
    </script>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">


<div role="main">
    @if(session('fail'))
        <div class="alert alert-danger alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          {{ session('fail') }}
        </div>
    @endif
    <div class="page-title">
        <h3> Detail KKP</h3>
        <div class="row">

            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                         Form
                    </div>
                    <div class="panel-default">
                        <br>
                        <div class="row">
                            <div class="col-lg-12">
                                <form class="form-horizontal form-label-left">

                        <div class="form-group">
                            <label class="col-md-2 col-md-4" >Tahun Anggaran Tetap</label>
                            <div class="col-md-6 col-sm-6 col-xs-12 ">
                              <input type="text " id="tahun "  class="form-control" readonly="" value="{{$dmr->tahun_anggaran}}">
                            </div>
                          </div>

                          <div class="form-group ">
                            <label class="col-md-2 col-md-4 " >Strategi Bisnis</label>
                            <div class="col-md-6 col-sm-6 col-xs-12 ">
                              <input type="text " id="struktur-bisnis "  class="form-control col-md-7" readonly="" value="{{$dmr->lokasi->distrik->strategi_bisnis->name}}">
                            </div>
                          </div>

                          <div class="form-group ">
                            <label class="col-md-2 col-md-4">Distrik</label>
                            <div class="col-md-6 col-sm-6 col-xs-12 ">
                              <input id="distrik " class="form-control col-md-7 col-xs-12 " type="text" readonly="" value="{{$dmr->lokasi->distrik->name}}">
                            </div>
                          </div>

                          @if($bidang_divisi)
                            <div class="form-group bagianclass">
                                <div class="col-md-2 col-md-4"><label>Bagian</label></div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="bidang_divisi_id " class="form-control col-md-7 col-xs-12 " type="text" readonly="" value="{{$bidang_divisi->name}}">
                                </div>
                            </div>
                          @endif

                          <div class="form-group ">
                            <label class="col-md-2 col-md-4">Lokasi Pembangkit</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input id="lokasi" class="form-control col-md-7 " type="text" readonly="" value="{{$dmr->lokasi->name}}">
                            </div>
                        </div>

                      </form>

                          </div>
                       </div>
                    </div>
                </div>
            </div>
        </div>

        <h4> Detail KKP</h4>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                    Detail
                    </div>
                    <div class="panel-default">
                    <br>
                    <table class="table table-bordered table-hover" style="table-layout: fixed">
                        <tr>
                            <th width="25%">Nomor KKP</th>
                            <td width="75%" class="text-left">{{ $dmr['no_dokumen'] }}</td>
                        </tr>
                        <tr>
                            <th width="25%">Judul KKP</th>
                            <td width="75%" class="text-left">{{ $dmr['judul_dokumen'] ? $dmr['judul_dokumen'] : "-" }}</td>
                        </tr>
                        <tr>
                            <th width="25%">Jenis Cluster</th>
                            <td width="75%" class="text-left">{{ $dmr['jenis_cluster'] ? $dmr['jenis_cluster'] : "-" }}</td>
                        </tr>
                        <!-- <tr>
                            <th>No PRK Form</th>
                            <td>{{ $dmr['no_prk_form'] ? $dmr['no_prk_form'] : "-" }}</td>
                        </tr> -->
                        <!-- <tr>
                            <th>Anggaran PRK Form</th>
                            <td>{{ $dmr['anggaran_prk_form'] ? $dmr['anggaran_prk_form'] : "-" }}</td>
                        </tr> -->
                        <tr>
                            <th>Anggaran Investasi untuk Cluster</th>
                            <td class="text-left">Rp. {{ number_format($dmr['jumlah_anggaran'],0,',','.') }}</td>
                        </tr>
                        <tr>
                            <th>Anggaran Kas Investasi per-cluster</th>
                            <td class="text-left">Rp. {{ number_format($dmr['anggaran_percluster'],0,',','.') }}</td>
                        </tr>
                        <tr>
                            <th>Dokumen KKP</td>
                            <td class="text-left"><a href="{{ url('kkp/download_attachment') .'/'. $dmr->id }}">{{ basename($dmr['dmr_filepath']) }}</a></td>
                            <!-- <td><a href="{{ asset($dmr->dmr_filepath) }}">{{ basename($dmr['dmr_filepath']) }}</a></td> -->
                        </tr>
                        <tr>
                            <th colspan="2">Summary</th>
                            <!-- <td></td> -->
                        </tr>
                        <tr>
                            <th>1.1 Latar Belakang Masalah</th>
                            <td class="td_text text-left">
                                <input class="hidden" type="text" value="{{ $dmr['kondisi_aicluster_id'] }}" id="cekkondisi" />
                                <input class="hidden" type="text" value="{{ $dmr['status_appr_id'] }}" id="cekstatus" />
                               <?php //echo strip_tags($dmr['latar_belakang']);  ?>
                               {!! $dmr['latar_belakang'] !!}
                            </td>
                        </tr>
                    </table>
                    </div>
                </div>

                <div class="row">
                    @if(($dmr->dmr_review_phase_id==3 || $dmr->dmr_review_status_id==4))
                    <form method="post" class="form">
                    <input type="hidden" name="_token" class="{{ csrf_token() }}">
                    <input type="hidden" name="is_submitted" id="is_submitted" value="0">
                    {{ csrf_field() }}
                    <div class="col-lg-12">
                      <div class="panel panel-default">
                          <div class="panel-heading">
                               Set Approval
                          </div>
                          <div class="panel-default">
                            <br>
                              <div class="row">
                                  @if(($dmr->status_appr_id==1 || $dmr->status_appr_id==2 || $dmr->status_appr_id==4 || $dmr->status_appr_id=='') && $fungsi=='setappr')
                                  <div class="col-lg-12">
                                      <div class="col-md-2"><label>Grup Divisi</label></div>
                                      <div class="col-md-8">
                                          <select class="form-control grupdiv_values" name="grup_div">
                                              <option selected="" disabled="" value="">-- Pilih Grup Divisi --</option>
                                             @if(!empty($grupdiv))
                                                @foreach($grupdiv as $gd => $val)
                                                    <option value="{{ $val->id }}" data-name="{{ $val->name }}"> {{ $val->name }}</option>
                                                @endforeach
                                             @endif
                                          </select>
                                      </div>

                                      <div>
                                         <a href="#" class="btn btn-success add_grupdiv"><i class="fa fa-plus"></i></a>
                                      </div>
                                      <br>
                                  </div>
                                  @endif


                                  <div class="col-lg-12" id="semua_data_approval">
                                    <label>Data Approval</label>

                                    @if($dmr['kondisi_aicluster_id']=='1' OR $dmr['kondisi_aicluster_id']=='')
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <!-- <th style="width:2%">No</th> -->
                                                    <th>Nama Grup</th>
                                                    <th>Urutan</th>
                                                    <th>Peran</th>
                                                    @if(($dmr->status_appr_id==2 || $dmr->status_appr_id==4 || $dmr->status_appr_id=='') && $fungsi=='setappr')
                                                        <th>Aksi</th>
                                                    @endif

                                                    @if($fungsi=='detail')
                                                        <th>Status</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody class="body_value_data_approval">
                                                @if(count($dat_approval)>0)
                                                    <?php $i = 1;?>
                                                    @foreach($dat_approval as $item)
                                                    <tr class="filterable">
                                                        <!-- <td class="text-center">{{$i++}}</td> -->
                                                        <td class="text-left" >
                                                            <input class="hidden" type="text" value="{{$item->grupdiv_id}}" name="data_approval_grup[]" />{{$item->grupdiv_name}}
                                                        </td>
                                                        <td class="text-left" >
                                                            <input type="text" value="{{$item->urutan}}" class="form-control" name="data_approval_urut[]" {{$disabled}} />
                                                        </td>
                                                        <td class="text-left" >
                                                            <select class="form-control" name="data_approval_peran[]" required {{$disabled}}>
                                                                <!-- <option value="checker" <?php if($item->peran != null) echo( $item->peran == 'checker' ? 'selected=""' : '' )?>>Checker</option> -->
                                                                <option value="approval" <?php if($item->peran != null) echo( $item->peran == 'approval' ? 'selected=""' : '' )?>>Approval</option>
                                                            </select>
                                                        </td>
                                                        @if(($dmr->status_appr_id==2 || $dmr->status_appr_id==4 || $dmr->status_appr_id=='') && ($item->grupdiv_id!='3' && $item->grupdiv_id!='4') && $fungsi=='setappr')
                                                            <td class="text-center">
                                                                <a href="#" onClick="return confirm('Apakah Anda yakin untuk menghapus data Gruop {{$item->grupdiv_name}}')" id="close_add" class="btn btn-xs btn-danger"><i class="fa fa-times" /></i></a>
                                                            </td>
                                                        @endif

                                                        @if($fungsi=='detail')
                                                            <td>
                                                                @if($item->status=='0')
                                                                    <p style="color:red;">&#10008;</p>
                                                                @else
                                                                    <p style="color:blue;">&#10004;</p>
                                                                @endif
                                                            </td>
                                                        @endif
                                                    </tr>
                                                    @endforeach
                                                @else
                                                    <tr class="filterable">
                                                        <td class="text-left" >
                                                            <input class="hidden" type="text" value="4" name="data_approval_grup[]" />BIDFIN
                                                        </td>
                                                        <td class="text-left" >
                                                            <input type="text" value="1" class="form-control" name="data_approval_urut[]" readonly />
                                                        </td>
                                                        <td class="text-left" >
                                                            <select class="form-control" name="data_approval_peran[]" required readonly>
                                                                <option value="approval" >Approval</option>
                                                            </select>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr class="filterable">
                                                        <td class="text-left" >
                                                            <input class="hidden" type="text" value="3" name="data_approval_grup[]" />RISK
                                                        </td>
                                                        <td class="text-left" >
                                                            <input type="text" value="1" class="form-control" name="data_approval_urut[]" readonly />
                                                        </td>
                                                        <td class="text-left" >
                                                            <select class="form-control" name="data_approval_peran[]" required readonly>
                                                                <option value="approval" >Approval</option>
                                                            </select>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    @else
                                        @if(($dmr['kondisi_aicluster_id']=='2' OR $dmr['kondisi_aicluster_id']=='3') AND ($dmr['status_appr_id']=='' || $dmr['status_appr_id']=='8'))
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <!-- <th style="width:2%">No</th> -->
                                                        <th>Nama Grup </th>
                                                        <th>Urutan</th>
                                                        <th>Peran</th>
                                                        @if(($dmr->status_appr_id==2 || $dmr->status_appr_id==4 || $dmr->status_appr_id=='') && $fungsi=='setappr')
                                                            <th>Aksi</th>
                                                        @endif

                                                        @if($fungsi=='detail')
                                                            <th>Status</th>
                                                        @endif
                                                    </tr>
                                                </thead>
                                                <tbody class="body_value_data_approval">
                                                    @if(count($dat_approval)>0)
                                                        <?php $i = 1;?>
                                                        @foreach($dat_approval as $item)
                                                        <tr class="filterable">
                                                            <!-- <td class="text-center">{{$i++}}</td> -->
                                                            <td class="text-left" >
                                                                <input class="hidden" type="text" value="{{$item->inputke}}" name="data_approval_inputke[]" />
                                                                <input class="hidden" type="text" value="{{$item->grupdiv_id}}" name="data_approval_grup[]" />{{$item->grupdiv_name}}
                                                            </td>
                                                            <td class="text-left" >
                                                                <input type="text" value="{{$item->urutan}}" class="form-control" name="data_approval_urut[]" {{$disabled}} />
                                                            </td>
                                                            <td class="text-left" >
                                                                <select class="form-control" name="data_approval_peran[]" required {{$disabled}}>
                                                                    <option value="checker" <?php if($item->peran != null) echo( $item->peran == 'checker' ? 'selected=""' : '' )?>>Checker</option>
                                                                    <!-- <option value="approval" <?php if($item->peran != null) echo( $item->peran == 'approval' ? 'selected=""' : '' )?>>Approval</option> -->
                                                                </select>
                                                            </td>
                                                            @if(($dmr->status_appr_id==2 || $dmr->status_appr_id==4 || $dmr->status_appr_id=='') && ($item->grupdiv_id!='3' && $item->grupdiv_id!='4') && $fungsi=='setappr')
                                                                <td class="text-center">
                                                                    <a href="#" onClick="return confirm('Apakah Anda yakin untuk menghapus data Gruop ')" id="close_add" class="btn btn-xs btn-danger"><i class="fa fa-times" /></i></a>
                                                                </td>
                                                            @endif

                                                            @if($fungsi=='detail')
                                                                <td>
                                                                    @if($item->status=='0')
                                                                        <p style="color:red;">&#10008;</p>
                                                                    @else
                                                                        <p style="color:blue;">&#10004;</p>
                                                                    @endif
                                                                </td>
                                                            @endif
                                                        </tr>
                                                        @endforeach
                                                    @else
                                                        <tr class="filterable">
                                                            <td class="text-left" >
                                                                <input class="hidden" type="text" value="1" name="data_approval_inputke[]" />
                                                                <input class="hidden" type="text" value="4" name="data_approval_grup[]" />BIDFIN
                                                            </td>
                                                            <td class="text-left" >
                                                                <input type="text" value="1" class="form-control" name="data_approval_urut[]" readonly />
                                                            </td>
                                                            <td class="text-left" >
                                                                <select class="form-control" name="data_approval_peran[]" required readonly>
                                                                    <option value="checker" >Checker</option>
                                                                </select>
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                        <tr class="filterable">
                                                            <td class="text-left" >
                                                                <input class="hidden" type="text" value="1" name="data_approval_inputke[]" />
                                                                <input class="hidden" type="text" value="3" name="data_approval_grup[]" />RISK
                                                            </td>
                                                            <td class="text-left" >
                                                                <input type="text" value="1" class="form-control" name="data_approval_urut[]" readonly />
                                                            </td>
                                                            <td class="text-left" >
                                                                <select class="form-control" name="data_approval_peran[]" required readonly>
                                                                    <option value="checker" >Checker</option>
                                                                </select>
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        @elseif(($dmr['kondisi_aicluster_id']=='2' OR $dmr['kondisi_aicluster_id']=='3') AND ($dmr['status_appr_id']!='' && $dmr['status_appr_id']!='8'))
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <!-- <th style="width:2%">No</th> -->
                                                        <th>Nama Grup</th>
                                                        <th>Urutan</th>
                                                        <th>Peran</th>

                                                        @if($fungsi=='detail')
                                                            <th>Status</th>
                                                        @endif
                                                    </tr>
                                                </thead>
                                                <tbody class="body_value_data_approval1">
                                                    @if($dat_approval != NULL)
                                                        <?php $i = 1;?>
                                                        @foreach($dat_approval as $item)
                                                        <tr class="filterable">
                                                            <!-- <td class="text-center">{{$i++}}</td> -->
                                                            <td class="text-left" >
                                                                <input class="hidden" type="text" value="{{$item->inputke}}" name="" />
                                                                <input class="hidden" type="text" value="{{$item->grupdiv_id}}" name="" />{{$item->grupdiv_name}}
                                                            </td>
                                                            <td class="text-left" >
                                                                <input type="text" value="{{$item->urutan}}" class="form-control" name="" disabled />
                                                            </td>
                                                            <td class="text-left" >
                                                                <select class="form-control" name="" required disabled>
                                                                    <option value="checker" <?php if($item->peran != null) echo( $item->peran == 'checker' ? 'selected=""' : '' )?>>Checker</option>
                                                                    <option value="approval" <?php if($item->peran != null) echo( $item->peran == 'approval' ? 'selected=""' : '' )?>>Approval</option>
                                                                </select>
                                                            </td>
                                                            @if($fungsi=='detail')
                                                                <td>
                                                                    @if($item->status=='0')
                                                                        <p style="color:red;">&#10008;</p>
                                                                    @else
                                                                        <p style="color:blue;">&#10004;</p>
                                                                    @endif
                                                                </td>
                                                            @endif
                                                        </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>

                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <!-- <th style="width:2%">No</th> -->
                                                        <th>Nama Grup</th>
                                                        <th>Urutan</th>
                                                        <th>Peran</th>
                                                        @if(($dmr->status_appr_id==2 || $dmr->status_appr_id==4) && $fungsi=='setappr')
                                                            <th>Aksi</th>
                                                        @endif

                                                        @if($fungsi=='detail')
                                                            <th>Status</th>
                                                        @endif
                                                    </tr>
                                                </thead>
                                                <tbody class="body_value_data_approval">
                                                    @if($dat_approval2 != NULL)
                                                        <?php $i = 1;?>
                                                        @foreach($dat_approval2 as $item)
                                                        <tr class="filterable">
                                                            <!-- <td class="text-center">{{$i++}}</td> -->
                                                            <td class="text-left" >
                                                                <input class="hidden" type="text" value="{{$item->inputke}}" name="data_approval_inputke[]" />
                                                                <input class="hidden" type="text" value="{{$item->grupdiv_id}}" name="data_approval_grup[]" />{{$item->grupdiv_name}}
                                                            </td>
                                                            <td class="text-left" >
                                                                <input type="text" value="{{$item->urutan}}" class="form-control" name="data_approval_urut[]" {{$disabled}} />
                                                            </td>
                                                            <td class="text-left" >
                                                                <select class="form-control" name="data_approval_peran[]" required {{$disabled}}>
                                                                    <!-- <option value="checker" <?php if($item->peran != null) echo( $item->peran == 'checker' ? 'selected=""' : '' )?>>Checker</option> -->
                                                                    <option value="approval" <?php if($item->peran != null) echo( $item->peran == 'approval' ? 'selected=""' : '' )?>>Approval</option>
                                                                </select>
                                                            </td>
                                                            @if(($dmr->status_appr_id==2 || $dmr->status_appr_id==4) && $fungsi=='setappr')
                                                                <td class="text-center">
                                                                    <a href="#" onClick="return confirm('Apakah Anda yakin untuk menghapus data Gruop {{$item->grupdiv_name}}')" id="close_add" class="btn btn-xs btn-danger"><i class="fa fa-times" /></i></a>
                                                                </td>
                                                            @endif

                                                            @if($fungsi=='detail')
                                                                <td>
                                                                    @if($item->status=='0')
                                                                        <p style="color:red;">&#10008;</p>
                                                                    @else
                                                                        <p style="color:blue;">&#10004;</p>
                                                                    @endif
                                                                </td>
                                                            @endif
                                                        </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        @endif
                                    @endif

                                    @if(($dmr->status_appr_id==2 || $dmr->status_appr_id==4 || $dmr->status_appr_id=='') && $fungsi=='setappr')
                                        <button class="btn btn-success pull-right" type="submit" onclick="submitappr()">Submit</button>
                                        <button class="btn btn-primary pull-right" type="submit" onclick="submitappr(0)">Simpan sebagai Draft Approval</button>
                                    @endif
                                  </div>
                              </div>
                            </div>
                          </div>
                      </div>
                    </div>

                    </form>
                    @endif


                    @if(($dmr_reviews) && $fungsi=='detail')
                    <div class="col-lg-12">
                      <div class="panel panel-default">
                          <div class="panel-heading">
                               Pencarian KKP Review
                          </div>
                          <div class="panel-default">
                              <br>
                              <div class="row">
                                  <div class="col-lg-12">
                                      <form method="post" class="form">
                                          <input type="hidden" name="_token" class="{{ csrf_token() }}">
                                          {{ csrf_field() }}
                                          <div class="col-md-2"><label>KKP Review</label></div>
                                          <div class="col-md-8">
                                              <select class="form-control" name="dmr_review_id" required>
                                                  <option selected="" disabled="" value="">-- Pilih KKP Review --</option>
                                                 @if(!empty($dmr_reviews))
                                                    @foreach($dmr_reviews as $dr)
                                                            @if($dr->dmr_review_status_id=='8')
                                                                <option value="{{ $dr->id }}" <?php echo( $input_dmr_review == $dr->id ? 'selected=""' : '' )?> >
                                                                    {{ $dr->created_at }} - {{$dr->grupdiv->name }} - {{$dr->user_revised->username}} - {{ $dr->status_appr->name }}
                                                                </option>
                                                            @elseif($dr->appr_ke=='' OR $dr->appr_ke==null)
                                                                <option value="{{ $dr->id }}" <?php echo( $input_dmr_review == $dr->id ? 'selected=""' : '' )?> >
                                                                    {{ $dr->created_at }} - {{$dr->dmr_review_phase->role->name }} - {{$dr->user_revised->username}} - {{ $dr->dmr_review_status->name }}
                                                                </option>
                                                            @endif
                                                    @endforeach
                                                 @endif
                                              </select>
                                          </div>

                                          <div>
                                             <button type="submit" class="btn btn-primary">
                                                 <span class="glyphicon glyphicon-search"> </span> cari
                                             </button>
                                          </div>
                                      </form>
                                      <br>
                                  </div>
                              </div>
                          </div>
                          @if(isset($dmr_review))
                          <div class="row">
                              <div class="col-md-12">
                                  <table class="table table-bordered table-hover" style="table-layout: fixed">
                                      <tr>
                                          <th>Review Oleh</th>
                                          <td>
                                                @if($dmr['dmr_review_phase_id']=='3' && $dmr['dmr_review_status_id']=='4')
                                                    {{$dmr_review->grupdiv->name}}
                                                @else
                                                    {{$dmr_review->dmr_review_phase->role->name}}
                                                @endif
                                          </td>
                                      </tr>
                                      <tr>
                                          <th>Review Status</th>
                                          <td>
                                                @if($dmr['dmr_review_phase_id']=='3' && $dmr['dmr_review_status_id']=='4')
                                                    {{$dmr_review->status_appr->name}}
                                                @else
                                                    {{$dmr_review->dmr_review_status->name}}
                                                @endif
                                          </td>
                                      </tr>
                                      <tr>
                                          <th>Alasan</th>
                                          <td>{{$dmr_review->alasan}}</td>
                                      </tr>
                                      <!-- <tr>
                                          <th>Alasan Latar Belakang Masalah</th>
                                          <td>{{$dmr_review->alasan_latar_belakang}}</td>
                                      </tr> -->
                                      <!-- <tr>
                                          <th>Alasan Sasaran Tujuan Kegiatan</th>
                                          <td>{{$dmr_review->alasan_sasaran_tujuan}}</td>
                                      </tr>
                                      <tr>
                                          <th>Alasan Permasalahan</th>
                                          <td>{{$dmr_review->alasan_permasalahan}}</td>
                                      </tr>
                                      <tr>
                                          <th>Alasan Alternatif Cara Pencapaian Sasaran</th>
                                          <td>{{$dmr_review->alasan_alternatif_pencapaian}}</td>
                                      </tr>
                                      <tr>
                                          <th>Alasan Benefit Operasional</th>
                                          <td>{{$dmr_review->alasan_benefit_operasional}}</td>
                                      </tr>
                                      <tr>
                                          <th>Alasan Benefit Finansial</th>
                                          <td>{{$dmr_review->alasan_benefit_finansial}}</td>
                                      </tr> -->
                                      <tr>
                                          <th>Lampiran Review</th>
                                          <td>
                                              <ul>
                                                @foreach($dmr_review->dmr_review_attachments as $da)
                                                  <li>
                                                  @if($da['filepath'] == '') -
                                                  @else <a href="{{ url('kkp/review_attachment') .'/'. $da['id'] }}">{{ basename($da['filepath']) }}</a>
                                                  @endif
                                                  </li>
                                                @endforeach
                                              </ul>
                                          </td>
                                      </tr>
                                  </table>
                              </div>
                          </div>
                          @endif
                      </div>
                    </div>
                    @endif

                  <div class="col-lg-12">
                      @if($role_id == ROLE_ID_STAFF OR $role_id == ROLE_ID_MANAGER_RISK OR $role_id == ROLE_ID_KABID OR $role_id == ROLE_ID_KADIV_RISK OR $role_id == ROLE_ID_MANAGER_UNIT_DMR OR $role_id == ROLE_ID_GM)
                          <a href="{{ url('/approval_kkp/daftar?tahun_anggaran='.$dmr->tahun_anggaran.'&strategi_bisnis='.$dmr->lokasi->distrik->strategi_bisnis_id.'&distrik='.$dmr->lokasi->distrik_id.'&lokasi='.$dmr->lokasi_id) }}" class="btn btn-success pull-left" type="reset">Kembali</a>
                      @else
                          <a href="{{ url('/kkp/daftar?tahun_anggaran='.$dmr->tahun_anggaran.'&strategi_bisnis='.$dmr->lokasi->distrik->strategi_bisnis_id.'&distrik='.$dmr->lokasi->distrik_id.'&lokasi='.$dmr->lokasi_id) }}" class="btn btn-success pull-left" type="reset">Kembali</a>
                      @endif
                  </div>

                </div><!-- /detail row -->
            </div>

        </div> <!-- page-title -->

        <!-- </div> --> <!-- main -->

    </div> <!-- col-md-12 col-sm-12 col-xs-12 -->
</div> <!-- row -->

<script src="{{ asset('js/jquery-1.11.2.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/datepicker/bootstrap-datepicker.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/jquery-ui.min.js') }}" type="text/javascript"></script>

<script type="text/javascript">
    var cekkondisi= $("#cekkondisi").val();
    var cekstatus= $("#cekstatus").val();

    if((cekkondisi=='2' || cekkondisi=='3') && (cekstatus!='')) 
    {
        var rowCount1 = $('.body_value_data_approval1 tr').length;
        if(rowCount1 === 0){
            $("#semua_data_approval").show('hide');
        }
    }
    else
    {
        var rowCount = $('.body_value_data_approval tr').length;
        if(rowCount === 0){
            $("#semua_data_approval").hide('hide');
        }
    }
    

    var birthdateInput = $( "#birth-date" ).datepicker({
            clearButton: true,
            dateFormat: "dd/mm/yy",
            autoclose: true
    });

    $(".add_grupdiv").click(function (e) {
        $("#semua_data_approval").show('hide');

        e.preventDefault();

        var cekkondisi= $("#cekkondisi").val();
        var cekstatus= $("#cekstatus").val();

        inputke='';

        // option='<option value="checker">Checker</option><option value="approval">Approval</option>';        

        if ((cekkondisi=='2' || cekkondisi=='3') && cekstatus=='') 
        {
            inputke= '1';
            option='<option value="checker">Checker</option>';
        }
        else if((cekkondisi=='2' || cekkondisi=='3') && cekstatus=='4')
        {
            inputke= '2';
            option='<option value="approval">Approval</option>';
        }
        else
        {
            option='<option value="approval">Approval</option>';
        }

        var user_ids = $(".grupdiv_values").val();
        var grupdiv_id = $(".grupdiv_values option:selected").val();
        var grupdiv_name = $(".grupdiv_values option:selected").attr('data-name');
        // var strategi_bisnis = $(".grupdiv_values option:selected").attr('data-sb-id');
        // var distrik_code = $(".grupdiv_values option:selected").attr('data-code');

        if (grupdiv_id=='' || grupdiv_id=='undifined') 
        {
            alert("Mohon pilih Grup Divisi terlebih dahulu !");return false;
        }
        else
        {
            $(".body_value_data_approval").append('<tr class="filterable">'+
                                    '<td class="text-left" ><input class="hidden" type="text" value="'+ inputke +'" name="data_approval_inputke[]" /><input class="hidden" type="text" value="'+ grupdiv_id+'" name="data_approval_grup[]" />'+ grupdiv_name +'</td>'+
                                    '<td class="text-left" ><input type="text" class="form-control" name="data_approval_urut[]" /></td>'+
                                    '<td class="text-left" >'+
                                        '<select class="form-control" name="data_approval_peran[]" required>'+option+
                                        '</select></td>'+
                                    '<td class="text-center">'+
                                    '<a href="#" onClick="return confirm(\'Apakah Anda yakin untuk menghapus data Gruop '+ grupdiv_name +'  ?\')" id="close_add" class="btn btn-xs btn-danger"><i class="fa fa-times" /></i></a></td>'+
                                '</tr>');
        }

        
        //$(".action_role_btn").show('hidden');

    });

    // DELETEING FUNCTION JAVASCRIPT
    $(document).on('click', '#close_add' ,function (e) {
        e.preventDefault();
        $(this).parent().parent().remove();

        var rowCount = $('.body_value_data_approval tr').length;
        if(rowCount === 0){
            $("#semua_data_approval").hide('hide');
        }
    });

    function submitappr(value = 1){

        // var messageLength = CKEDITOR.instances['latar_belakang'].getData().replace(/<[^>]*>/gi, '').length;
        // if( !messageLength ) {
        //     alert( 'anda belum mengisi Latar Belakang' );
        //     e.preventDefault();
        // }

        // var messageLength = CKEDITOR.instances['sasaran_tujuan'].getData().replace(/<[^>]*>/gi, '').length;
        // if( !messageLength ) {
        //     alert( 'anda belum mengisi Sasaran dan tujuan' );
        //     e.preventDefault();
        // }

        // var messageLength = CKEDITOR.instances['permasalahan'].getData().replace(/<[^>]*>/gi, '').length;
        // if( !messageLength ) {
        //     alert( 'anda belum mengisi Permasalahan' );
        //     e.preventDefault();
        // }

        // var messageLength = CKEDITOR.instances['alternatif_pencapaian'].getData().replace(/<[^>]*>/gi, '').length;
        // if( !messageLength ) {
        //     alert( 'anda belum mengisi Alternatif Pencapaian' );
        //     e.preventDefault();
        // }

        // var messageLength = CKEDITOR.instances['benefit_operasional'].getData().replace(/<[^>]*>/gi, '').length;
        // if( !messageLength ) {
        //     alert( 'anda belum mengisi Benefit Operasional' );
        //     e.preventDefault();
        //     valid = 0;
        // }

        document.getElementById("is_submitted").value = value;
        // document.forms["form"].submit();
    }

</script>
@endsection
