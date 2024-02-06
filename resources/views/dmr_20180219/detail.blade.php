@extends('layouts.app')

@section('css_page')
    <!-- Datatables -->
    <link href="{{ asset('vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}" rel="stylesheet">
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
   
    <div class="page-title">
        <h3> Detail DMR</h3>
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
                        <label class="col-md-2 col-md-4" " >Tahun Anggaran</label>
                        <div class="col-md-6 col-sm-6 col-xs-12 ">
                          <input type="text " id="tahun "  class="form-control" readonly="" value="{{$dmr->tahun_anggaran}}">
                        </div>
                      </div>

                      <div class="form-group ">
                        <label class="col-md-2 col-md-4 " >Struktur Bisnis</label>
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
                
                      <div class="form-group "> 
                        <label class="col-md-2 col-md-4">Lokasi</label>
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

            <h4> Detail DMR</h4>
            
                <div class="row">
                    <div class="col-md-12">
                        <form method="post" class="form">
                        <input type="hidden" name="_token" class="{{ csrf_token() }}">
                            
                            {{ csrf_field() }}
                            <table class="table table-striped table-bordered table-hover">
                            <tr>
                                <th width="25%">ID Dokumen</th>
                                <td width="75%">{{ $dmr['no_dokumen'] }}</td>
                            </tr>
                            <tr>
                                <th>No PRK</th>
                                <td>{{ $dmr['no_prk'] }}</td>
                            </tr>
                            <tr>
                                <th>Nama PRK</th>
                                <td>{{ $dmr['nama_prk'] }}</td>
                            </tr>
                            <tr>
                                <th>Anggaran PRK</th>
                                <td>{{ $dmr['jumlah_anggaran'] }}</td>
                            </tr>
                            <tr>

                                <td>Dokumen DMR</td>
                                <td><a href="{{ asset($dmr->dmr_filepath) }}">{{ $dmr['dmr_filepath'] }}</a></td>
                            </tr>
                            <tr>
                                <th colspan="2">Summary</th>
                                <!-- <td></td> -->
                            </tr>
                            <tr>
                                <th>Latar Belakang Masalah</th>
                                <td>
                                   <?php //echo strip_tags($dmr['latar_belakang']);  ?>
                                   {!! $dmr['latar_belakang'] !!}
                                </td>
                            </tr>
                            <tr>
                                <th>1.2 Sasaran Tujuan Kegiatan</th>
                                 <td>
                                    <?php  //echo strip_tags($dmr['sasaran_tujuan']); ?>
                                    {!! $dmr['sasaran_tujuan'] !!}
                                </td>
                            </tr>
                            <tr>
                                <th>1.3 Permasalahan</th>
                                <td>
                                    <?php  //echo strip_tags($dmr['permasalahan']); ?>
                                    {!! $dmr['permasalahan'] !!}
                                </td>
                            </tr>
                            <tr>
                                <th>1.4 Alternatif Cara Pencapaian Sasaran</th>
                                <td>
                                    <?php  //echo strip_tags($dmr['alternatif_pencapaian']); ?>
                                    {!! $dmr['alternatif_pencapaian'] !!}
                                </td>
                            </tr>
                            <tr>
                                <th>1.5 Benefit Operasional</th>
                                <td>
                                    <?php  //echo strip_tags($dmr['benefit_operasional']); ?>
                                    {!! $dmr['benefit_operasional'] !!}
                                </td>
                            </tr>
                            <tr>
                                <th>1.6 Benefit Finansial</th>
                                <td>
                                    <?php  //echo strip_tags($dmr['benefit_finansial']); ?>
                                    {!! $dmr['benefit_finansial'] !!}
                                </td>
                            </tr>
                            <tr>
                                <th>Lampiran</th>
                                <td>
                                    <ul>
                                      @foreach($dmrattachment as $da)
                                        <li>
                                        @if($da['filepath'] == '') -
                                        @else <a href="{{ asset($da['filepath']) }}">{{ $da['filepath'] }}</a>
                                        @endif
                                        </li>
                                      @endforeach
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <th>Status DMR</th>
                                <td>@if($dmr->is_submitted == 1) Submitted 
                                    @else N/A 
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Approval Oleh</th>
                                <td>@if($dmr->is_submitted == 0)
                                    -
                                    @else
                                    {{$dmr->dmr_review_phase->role->name}}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Status Approval</th>
                                <td>@if($dmr->is_submitted == 0)
                                    -
                                    @else
                                    {{$dmr->dmr_review_status->name}}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Alasan</th>
                                <td>{{$dmr->alasan}}</td>
                            </tr>
                            <tr>
                                <th>Alasan Latar Belakang Masalah</th>
                                <td>{{$dmr->alasan_latar_belakang}}</td>
                            </tr>
                            <tr>
                                <th>Alasan Sasaran Tujuan Kegiatan</th>
                                <td>{{$dmr->alasan_sasaran_tujuan}}</td>
                            </tr>
                            <tr>
                                <th>Alasan Permasalahan</th>
                                <td>{{$dmr->alasan_permasalahan}}</td>
                            </tr>
                            <tr>
                                <th>Alasan Alternatif Cara Pencapaian Sasaran</th>
                                <td>{{$dmr->alasan_alternatif_pencapaian}}</td>
                            </tr>
                            <tr>
                                <th>Alasan Benefit Operasional</th>
                                <td>{{$dmr->alasan_benefit_operasional}}</td>
                            </tr>
                            <tr>
                                <th>Alasan Benefit Finansial</th>
                                <td>{{$dmr->alasan_benefit_finansial}}</td>
                            </tr>
                        </table>
                      
                        </form>
                    </div>
                </div>
                    <a href="{{ url('/dmr/daftar?tahun_anggaran='.$dmr->tahun_anggaran.'&strategi_bisnis='.$dmr->lokasi->distrik->strategi_bisnis_id.'&distrik='.$dmr->lokasi->distrik_id.'&lokasi='.$dmr->lokasi_id) }}" class="btn btn-success pull-left" type="reset">Kembali</a>
            </div><!-- /detail row -->
          </div> <!-- page-title -->
        
        <!-- </div> --> <!-- main -->

    </div> <!-- col-md-12 col-sm-12 col-xs-12 -->
</div> <!-- row -->
@endsection