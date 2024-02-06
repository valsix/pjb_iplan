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
                        <label class="col-md-2 col-md-4" >Tahun Anggaran</label>
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
                   <div class="col-lg-12">
                    <div class="panel panel-default">
                     <div class="panel-heading">
                        Detail
                     </div>
                    <div class="panel-default">
                    <br>
                            <table class="table table-bordered table-hover" style="table-layout: fixed">
                            <tr>
                                <th width="25%">ID Dokumen</th>
                                <td width="75%">{{ $dmr['no_dokumen'] }}</td>
                            </tr>
                            <tr>
                                <th width="25%">Judul DMR</th>
                                <td width="75%">{{ $dmr['judul_dokumen'] ? $dmr['judul_dokumen'] : "-" }}</td>
                            </tr>
                            <tr>
                                <th>No PRK Form</th>
                                <td>{{ $dmr['no_prk_form'] ? $dmr['no_prk_form'] : "-" }}</td>
                            </tr>
                            <tr>
                                <th>Anggaran PRK Form</th>
                                <td>{{ $dmr['anggaran_prk_form'] ? $dmr['anggaran_prk_form'] : "-" }}</td>
                            </tr>
                            <tr>
                                <th>Anggaran PRK Input (dalam ribuan)</th>
                                <td>Rp. {{ number_format($dmr['jumlah_anggaran'],0,',','.') }}</td>
                            </tr>
                            <tr>

                                <td>Dokumen DMR</td>
                                <td><a href="{{ url('dmr/download_attachment') .'/'. $dmr->id }}">{{ basename($dmr['dmr_filepath']) }}</a></td>
                                <!-- <td><a href="{{ asset($dmr->dmr_filepath) }}">{{ basename($dmr['dmr_filepath']) }}</a></td> -->
                            </tr>
                            <tr>
                                <th colspan="2">Summary</th>
                                <!-- <td></td> -->
                            </tr>
                            <tr>
                                <th>1.1 Latar Belakang Masalah</th>
                                <td class="td_text">
                                   <?php //echo strip_tags($dmr['latar_belakang']);  ?>
                                   {!! $dmr['latar_belakang'] !!}
                                </td>
                            </tr>
                            <tr>
                                <th>1.2 Sasaran Tujuan Kegiatan</th>
                                <td class="td_text">
                                    <?php  //echo strip_tags($dmr['sasaran_tujuan']); ?>
                                    {!! $dmr['sasaran_tujuan'] !!}
                                </td>
                            </tr>
                            <tr>
                                <th>1.3 Permasalahan</th>
                                <td class="td_text">
                                    <?php  //echo strip_tags($dmr['permasalahan']); ?>
                                    {!! $dmr['permasalahan'] !!}
                                </td>
                            </tr>
                            <tr>
                                <th>1.4 Alternatif Cara Pencapaian Sasaran</th>
                                <td class="td_text">
                                    <?php  //echo strip_tags($dmr['alternatif_pencapaian']); ?>
                                    {!! $dmr['alternatif_pencapaian'] !!}
                                </td>
                            </tr>
                            <tr>
                                <th>1.5 Benefit Operasional</th>
                                <td class="td_text">
                                    <?php  //echo strip_tags($dmr['benefit_operasional']); ?>
                                    {!! $dmr['benefit_operasional'] !!}
                                </td>
                            </tr>
                            <tr>
                                <th>1.6 Benefit Finansial</th>
                                <td class="td_text">
                                    <?php  //echo strip_tags($dmr['benefit_finansial']); ?>
                                    {!! $dmr['benefit_finansial'] !!}
                                </td>
                            </tr>
                            <tr>
                                <th>Lampiran DMR</th>
                                <td>
                                    <ul>
                                      @foreach($dmrattachment as $da)
                                        <li>
                                        @if($da['filepath'] == '') -
                                        @else <a href="{{ url('dmr/dmr_attachment') .'/'. $da['id'] }}">{{ basename($da['filepath']) }}</a>
                                        @endif
                                        </li>
                                      @endforeach
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <th>DMR Status</th>
                                <td>@if($dmr->is_submitted == 1) Submitted
                                    @else N/A
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Lampiran TOR</th>
                                @if(!empty($tor_attachments))
                                    <td>
                                        <ul>
                                          @foreach($tor_attachments as $ta)
                                            <li>
                                            @if($ta['filepath'] == '') -
                                            @else <a href="{{ asset($ta['filepath']) }}">{{ basename($ta['filepath']) }}</a>
                                            @endif
                                            </li>
                                          @endforeach
                                        </ul>
                                    </td>
                                @else
                                    <td>
                                        <ul>
                                            <li>-</li><li>-</li><li>-</li><li>-</li><li>-</li><li>-</li><li>-</li><li>-</li><li>-</li><li>-</li>
                                        </ul>
                                    </td>
                                @endif

                            </tr>
                            </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                      <div class="panel panel-default">
                          <div class="panel-heading">
                               Pencarian DMR Review
                          </div>
                          <div class="panel-default">
                              <br>
                              <div class="row">
                                  <div class="col-lg-12">
                                      <form method="post" class="form">
                                          <input type="hidden" name="_token" class="{{ csrf_token() }}">
                                          {{ csrf_field() }}
                                          <div class="col-md-2"><label>DMR Review</label></div>
                                          <div class="col-md-8">
                                              <select class="form-control" name="dmr_review_id" required>
                                                  <option selected="" disabled="" value="">-- Pilih DMR Review --</option>
                                                 @if(!empty($dmr_reviews))
                                                    @foreach($dmr_reviews as $dr)
                                                        <option value="{{ $dr->id }}" <?php echo( $input_dmr_review == $dr->id ? 'selected=""' : '' )?> > {{ $dr->created_at }} - {{$dr->dmr_review_phase->role->name }} - {{$dr->user_revised->username}} - {{ $dr->dmr_review_status->name }}</option>
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
                                              {{$dmr_review->dmr_review_phase->role->name}}
                                          </td>
                                      </tr>
                                      <tr>
                                          <th>Review Status</th>
                                          <td>
                                              {{$dmr_review->dmr_review_status->name}}
                                          </td>
                                      </tr>
                                      <tr>
                                          <th>Alasan</th>
                                          <td>{{$dmr_review->alasan}}</td>
                                      </tr>
                                      <tr>
                                          <th>Alasan Latar Belakang Masalah</th>
                                          <td>{{$dmr_review->alasan_latar_belakang}}</td>
                                      </tr>
                                      <tr>
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
                                      </tr>
                                      <tr>
                                          <th>Lampiran Review</th>
                                          <td>
                                              <ul>
                                                @foreach($dmr_review->dmr_review_attachments as $da)
                                                  <li>
                                                  @if($da['filepath'] == '') -
                                                  @else <a href="{{ url('dmr/review_attachment') .'/'. $da['id'] }}">{{ basename($da['filepath']) }}</a>
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

                  <div class="col-lg-12">
                      @if($role_id == ROLE_ID_STAFF OR $role_id == ROLE_ID_MANAGER_RISK OR $role_id == ROLE_ID_KABID OR $role_id == ROLE_ID_KADIV_RISK OR $role_id == ROLE_ID_MANAGER_UNIT_DMR OR $role_id == ROLE_ID_GM)
                          <a href="{{ url('/approval_dmr/daftar?tahun_anggaran='.$dmr->tahun_anggaran.'&strategi_bisnis='.$dmr->lokasi->distrik->strategi_bisnis_id.'&distrik='.$dmr->lokasi->distrik_id.'&lokasi='.$dmr->lokasi_id) }}" class="btn btn-success pull-left" type="reset">Kembali</a>
                      @else
                          <a href="{{ url('/dmr/daftar?tahun_anggaran='.$dmr->tahun_anggaran.'&strategi_bisnis='.$dmr->lokasi->distrik->strategi_bisnis_id.'&distrik='.$dmr->lokasi->distrik_id.'&lokasi='.$dmr->lokasi_id) }}" class="btn btn-success pull-left" type="reset">Kembali</a>
                      @endif
                  </div>

            </div><!-- /detail row -->
           </div>

          </div> <!-- page-title -->

        <!-- </div> --> <!-- main -->

    </div> <!-- col-md-12 col-sm-12 col-xs-12 -->
</div> <!-- row -->
@endsection
