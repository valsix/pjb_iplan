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
        <h3> Detail TOR</h3>
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
                          <input type="text " id="tahun "  class="form-control" readonly="" value="{{$tor->tahun_anggaran}}">
                        </div>
                      </div>

                      <div class="form-group ">
                        <label class="col-md-2 col-md-4 " >Struktur Bisnis</label>
                        <div class="col-md-6 col-sm-6 col-xs-12 ">
                          <input type="text " id="struktur-bisnis "  class="form-control col-md-7" readonly="" value="{{$tor->lokasi->distrik->strategi_bisnis->name}}">
                        </div>
                      </div>

                      <div class="form-group ">
                        <label class="col-md-2 col-md-4">Distrik</label>
                        <div class="col-md-6 col-sm-6 col-xs-12 ">
                          <input id="distrik " class="form-control col-md-7 col-xs-12 " type="text" readonly="" value="{{$tor->lokasi->distrik->name}}">
                        </div>
                      </div>

                      <div class="form-group ">
                        <label class="col-md-2 col-md-4">Lokasi</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="lokasi" class="form-control col-md-7 " type="text" readonly="" value="{{$tor->lokasi->name}}">
                        </div>
                    </div>

                  </form>

                      </div>
                   </div>
                </div>
            </div>
        </div>
      </div>

            <h4> Detail TOR</h4>
                <div class="row">
                   <div class="col-lg-12">
                    <div class="panel panel-default">
                     <div class="panel-heading">
                        Detail
                     </div>
                    <div class="panel-default">
                    <br>
                    <form method="post" class="form">
                        <input type="hidden" name="_token" class="{{ csrf_token() }}">

                            {{ csrf_field() }}
                            <table class="table table-bordered table-hover" style="table-layout: fixed">
                            <tr>
                                <th width="25%">ID Dokumen (DMR)</th>
                                @if ($dmr['id'])
                                    <td width="75%">
                                        <a href="{{ URL::to('dmr/detail/'.$dmr['id'])  }}" title="Klik untuk lihat detail DMR">
                                            {{ $dmr['no_dokumen'] }}
                                        </a>
                                    </td>
                                @else
                                    <td width="75%">-</td>
                                @endif
                            </tr>
                            <tr>
                                <th width="25%">ID Dokumen (TOR)</th>
                                <td width="75%">{{ $tor['no_dokumen'] }}</td>
                            </tr>
                            <tr>
                                <th>Judul TOR</th>
                                <td>{{ $dmr['judul_dokumen']  ?? '-- DMR tidak ditemukan --' }}</td>
                            </tr>
                            <tr class="hidden">
                                <td>Dokumen TOR</td>
                                <td><a href="{{ asset($tor->tor_filepath) }}">{{ basename($tor['tor_filepath']) }}</a></td>
                            </tr>
                            <tr>
                                <th colspan="2">Summary</th>
                                <!-- <td></td> -->
                            </tr>
                            <tr class="hidden">
                                <th>1.1 Pendahuluan</th>
                                <td class="td_text">
                                   {!! $tor['pendahuluan'] !!}
                                </td>
                            </tr>
                            <tr>
                                <th>1.2 Data teknis / Referensi Teknis</th>
                                <td class="td_text">
                                    {!! $tor['data_teknis'] !!}
                                </td>
                            </tr>
                            <tr>
                                <th>1.3 Lingkup Pekerjaan / Scope of Work</th>
                                <td class="td_text">
                                    {!! $tor['lingkup_pekerjaan'] !!}
                                </td>
                            </tr>
                            <tr>
                                <th>1.4 Performance Design</th>
                                <td class="td_text">
                                    {!! $tor['performance_desain'] !!}
                                </td>
                            </tr>
                            <tr>
                                <th>1.5 Kualifikasi Calon Pelaksanaan Pekerjaan</th>
                                <td class="td_text">
                                    {!! $tor['kualifikasi_calon_pelaksanaan_pekerjaan'] !!}
                                </td>
                            </tr>
                            <tr class="hidden">
                                <th>1.6 Detail Pelaksanaan Pekerjaan</th>
                                <td class="td_text">
                                    {!! $tor['detail_pelaksanaan_pekerjaan'] !!}
                                </td>
                            </tr>
                            <tr class="hidden">
                                <th>1.7 Kelengkapan Pelaksanaan Pekerjaan</th>
                                <td class="td_text">
                                    {!! $tor['kelengkapan_pelaksanaan_pekerjaan'] !!}
                                </td>
                            </tr>
                            <tr class="hidden">
                                <th>1.8 Aspek Keamanan dan K3</th>
                                <td class="td_text">
                                    {!! $tor['aspek_keamanan_k3'] !!}
                                </td>
                            </tr>
                            <tr class="hidden">
                                <th>1.9 Laporan Hasil Pekerjaan</th>
                                <td class="td_text">
                                    {!! $tor['laporan_hasil_pekerjaan'] !!}
                                </td>
                            </tr>
                            <tr class="hidden">
                                <th>2.0 Material Sisa atau Limbah</th>
                                <td class="td_text">
                                    {!! $tor['material_sisa_limbah'] !!}
                                </td>
                            </tr>
                            <tr>
                                <th>2.1 Quality Acceptance</th>
                                <td class="td_text">
                                    {!! $tor['quality_acceptance'] !!}
                                </td>
                            </tr>
                            <tr class="hidden">
                                <th>2.2 Delivery</th>
                                <td class="td_text">
                                    {!! $tor['delivery'] !!}
                                </td>
                            </tr>
                            <tr class="hidden">
                                <th>2.3 Garansi</th>
                                <td class="td_text">
                                    {!! $tor['garansi'] !!}
                                </td>
                            </tr>
                            <tr class="hidden">
                                <th>2.4 Lain-lain</th>
                                <td class="td_text">
                                    {!! $tor['lain_lain'] !!}
                                </td>
                            </tr>
                            <tr>
                                <th>Dokumen TOR</th>
                                <td>
                                    <ul>
                                      @foreach($torattachment as $da)
                                        <li>
                                        @if($da['filepath'] == '') -
                                        @else <a href="{{ asset($da['filepath']) }}">{{ basename($da['filepath']) }}</a>
                                        @endif
                                        </li>
                                      @endforeach
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <th>TOR Status</th>
                                <td>@if($tor->is_submitted == 1) Submitted
                                    @else N/A
                                    @endif
                                </td>
                            </tr>
                        </table>

                        </form>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Pencarian TOR Review
                            </div>
                            @php
                                $tor_reviews = json_decode($tor->review_list, true) ?: [];
                                $tor_selected_review = [];
                                $tor_selected_review_id = null;
                                if (old('review_id') !== null) {
                                    // Tampilkan review dari pilihan dropdown list review
                                    $tor_selected_review_id = old('review_id');
                                    $tor_selected_review[] = $tor_reviews[old('review_id')];
                                } else if ($count = count($tor_reviews)) {
                                    // Default tampilkan review terakhir
                                    $tor_selected_review_id = 0;
                                    $tor_selected_review[] = $tor_reviews[$tor_selected_review_id];
                                }
                            @endphp
                            <div class="panel-default">
                                <div class="row">
                                    <form method="post" class="form col-md-12">
                                        {{ csrf_field() }}
                                        <div class="col-md-2"><label>TOR Review</label></div>
                                        <div class="col-md-8">
                                            <select class="form-control" name="review_id" required>
                                                <option selected="" disabled="" value="">-- Pilih TOR Review --</option>
                                                @if(is_array($tor_reviews) && !empty($tor_reviews))
                                                    @foreach($tor_reviews as $key => $tr)
                                                        <option value="{{ $tr['id'] }}" {{ $tor_selected_review_id == $key ? 'selected=""' : ''  }} > {{ $tr['reviewed_at'] }} - {{$tr['review_role_name'] }} - {{ $tr['review_status_name'] }}</option>
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
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-hover" style="table-layout: fixed">
                                        @foreach($tor_selected_review as $tsr)
                                            <tr>
                                                <th>Review Oleh</th>
                                                <td>
                                                    {{$tsr['review_role_name']}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Review Status</th>
                                                <td>
                                                    {{$tsr['review_status_name']}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Alasan</th>
                                                <td>{{$tsr['alasan']}}</td>
                                            </tr>

                                            <tr>
                                                <th>Alasan Pendahuluan</th>
                                                <td>{{$tsr['alasan_pendahuluan']}}</td>
                                            </tr>

                                            <tr>
                                                <th>Alasan Data teknis / Referensi Teknis</th>
                                                <td>{{$tsr['alasan_data_teknis']}}</td>
                                            </tr>

                                            <tr>
                                                <th>Alasan Lingkup Pekerjaan / Scope of Work</th>
                                                <td>{{$tsr['alasan_lingkup_pekerjaan']}}</td>
                                            </tr>

                                            <tr>
                                                <th>Alasan Performance Design</th>
                                                <td>{{$tsr['alasan_performance_desain']}}</td>
                                            </tr>

                                            <tr>
                                                <th>Alasan Kualifikasi Calon Pelaksanaan Pekerjaan</th>
                                                <td>{{$tsr['alasan_kualifikasi_calon_pelaksanaan_pekerjaan']}}</td>
                                            </tr>

                                            <tr>
                                                <th>Alasan Detail Pelaksanaan Pekerjaan</th>
                                                <td>{{$tsr['alasan_detail_pelaksanaan_pekerjaan']}}</td>
                                            </tr>

                                            <tr>
                                                <th>Alasan Kelengkapan Pelaksanaan Pekerjaan</th>
                                                <td>{{$tsr['alasan_kelengkapan_pelaksanaan_pekerjaan']}}</td>
                                            </tr>

                                            <tr>
                                                <th>Alasan Aspek Keamanan dan K3</th>
                                                <td>{{$tsr['alasan_aspek_keamanan_k3']}}</td>
                                            </tr>

                                            <tr>
                                                <th>Alasan Laporan Hasil Pekerjaan</th>
                                                <td>{{$tsr['alasan_laporan_hasil_pekerjaan']}}</td>
                                            </tr>

                                            <tr>
                                                <th>Alasan Material Sisa atau Limbah</th>
                                                <td>{{$tsr['alasan_material_sisa_limbah']}}</td>
                                            </tr>

                                            <tr>
                                                <th>Alasan Quality Acceptance</th>
                                                <td>{{$tsr['alasan_quality_acceptance']}}</td>
                                            </tr>

                                            <tr>
                                                <th>Alasan Delivery</th>
                                                <td>{{$tsr['alasan_delivery']}}</td>
                                            </tr>

                                            <tr>
                                                <th>Alasan Garansi</th>
                                                <td>{{$tsr['alasan_garansi']}}</td>
                                            </tr>

                                            <tr>
                                                <th>Alasan Lain-lain</th>
                                                <td>{{$tsr['alasan_lain_lain']}}</td>
                                            </tr>
                                            <tr>
                                                <th>Lampiran Review</th>
                                                <td>
                                                    <ul>
                                                      @php
                                                        if (! is_array($tsr['review_attachment'])) {
                                                            $tsr['review_attachment'] = array_fill(0, 9, ['filepath' => '']);
                                                        }
                                                      @endphp
                                                      @foreach($tsr['review_attachment'] as $f)
                                                        <li>
                                                        @if($f['filepath'] == '') -
                                                        @else <a href="{{ asset($f['filepath']) }}">{{ basename($f['filepath']) }}</a>
                                                        @endif
                                                        </li>
                                                      @endforeach
                                                    </ul>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <button class="btn btn-success pull-left" type="reset" onclick="return history.back()">Kembali</button>
                    {{-- @if (URL::previous() !== '')
                        <a href="{{ URL::previous() }}" class="btn btn-success pull-left" type="reset">Kembali</a>
                    @endif --}}
            </div><!-- /detail row -->
           </div>
          </div> <!-- page-title -->

        <!-- </div> --> <!-- main -->

    </div> <!-- col-md-12 col-sm-12 col-xs-12 -->
</div> <!-- row -->
@endsection
