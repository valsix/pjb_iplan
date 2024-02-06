@extends('layouts.app')

@section('css_page')
    <!-- Datatables -->
    <link href="{{ asset('vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}" rel="stylesheet">

    <style type="text/css">
      label {
        margin-left: 15px;
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
        <div class="">
            <div>
                <h3> Approval TOR</h3>
            </div>
            @if($errors->has('alasan'))
              <div class="alert alert-danger alert-dismissible" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    {{ $errors->first('alasan') }}
              </div>
            @endif
            {{-- @if(session()->has('message'))
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    {{ session()->get('message') }}
                </div>
            @endif --}}
                <div class="col-lg-12">
            <div class="panel panel-default">
                     <div class="panel-heading">
                        Detail
                     </div>
                    <div class="panel-default">
                    <br>

              <div>
                <div class="x_content">
                  <!-- <h4>Detail Approval</h4> -->
                  <table  class="table table-bordered table-hover">
                      <tr>
                        <th width="25%"> Tahun Anggaran</th>
                        <td width="75%"> {{ $tor['tahun_anggaran'] }} </td>
                      </tr>
                      <tr>
                        <th> Strategi Bisnis</th>
                        <td> {{ $tor->lokasi->distrik->strategi_bisnis->name }} </td>
                      </tr>
                      <tr>
                        <th> Distrik</th>
                        <td> {{ $tor->lokasi->distrik->name }} </td>
                      </tr>
                      <tr>
                        <th> Lokasi</th>
                        <td> {{ $tor->lokasi->name }} </td>
                      </tr>
                      <tr>
                        <th> ID Dokumen (DMR)</th>
                        {{-- <td> {{ $tor['no_dokumen_dmr'] }} </td> --}}
                        <td>
                          <a href="{{ URL::to('dmr/detail/'. ($tor->dmr ? $tor->dmr->id : '') )  }}" title="Klik untuk lihat detail DMR">
                            {{ $tor['no_dokumen_dmr'] ?? '-' }}
                          </a>
                        </td>
                      </tr>
                      <tr>
                        <th> ID Dokumen (TOR)</th>
                        <td> {{ $tor['no_dokumen'] }} </td>
                      </tr>
                      <tr>
                        <th>Judul TOR</th>
                        <td> {{ $tor->dmr->judul_dokumen ??  '-- DMR tidak ditemukan --' }} </td>
                      </tr>
                      <tr class="hidden">
                        <th>Dokumen TOR</th>
                        <td><a href="{{ asset($tor->tor_filepath) }}">{{ basename($tor['tor_filepath']) }}</a></td>
                      </tr>
                      <tr>
                          <th>Summary</th>
                          <td></td>
                      </tr>
                      <tr class="hidden">
                          <td>1.1 Pendahuluan</td>
                          <td>
                             {!! $tor['pendahuluan'] !!}
                          </td>
                      </tr>
                      <tr>
                          <td>1.2 Data teknis / Referensi Teknis</td>
                           <td>
                             {!! $tor['data_teknis'] !!}
                          </td>
                      </tr>
                      <tr>
                          <td>1.3 Lingkup Pekerjaan / Scope of Work</td>
                          <td>
                              {!! $tor['lingkup_pekerjaan'] !!}
                          </td>
                      </tr>
                      <tr>
                          <td>1.4 Performance Design</td>
                          <td>
                              {!! $tor['performance_desain'] !!}
                          </td>
                      </tr>
                      <tr>
                          <td>1.5 Kualifikasi Calon Pelaksanaan Pekerjaan</td>
                          <td>
                              {!! $tor['kualifikasi_calon_pelaksanaan_pekerjaan'] !!}
                          </td>
                      </tr>
                      <tr class="hidden">
                          <td>1.6 Detail Pelaksanaan Pekerjaan</td>
                          <td>
                              {!! $tor['detail_pelaksanaan_pekerjaan'] !!}
                          </td>
                      </tr>
                      <tr class="hidden">
                          <td>1.7 Kelengkapan Pelaksanaan Pekerjaan</td>
                          <td>
                              {!! $tor['kelengkapan_pelaksanaan_pekerjaan'] !!}
                          </td>
                      </tr>
                      <tr class="hidden">
                          <td>1.8 Aspek Keamanan dan K3</td>
                          <td>
                              {!! $tor['aspek_keamanan_k3'] !!}
                          </td>
                      </tr>
                      <tr class="hidden">
                          <td>1.9 Laporan Hasil Pekerjaan</td>
                          <td>
                              {!! $tor['laporan_hasil_pekerjaan'] !!}
                          </td>
                      <tr class="hidden">
                          <td>2.0 Material Sisa atau Limbah</td>
                          <td>
                              {!! $tor['material_sisa_limbah'] !!}
                          </td>
                      </tr>
                      <tr>
                          <td>2.1 Quality Acceptance</td>
                          <td>
                              {!! $tor['quality_acceptance'] !!}
                          </td>
                      </tr>
                      <tr class="hidden">
                          <td>2.2 Delivery</td>
                          <td>
                              {!! $tor['delivery'] !!}
                          </td>
                      </tr>
                      <tr class="hidden">
                          <td>2.3 Garansi</td>
                          <td>
                              {!! $tor['garansi'] !!}
                          </td>
                      </tr>
                      <tr class="hidden">
                          <td>2.4 Lain-lain</td>
                          <td>
                              {!! $tor['lain_lain'] !!}
                          </td>
                      </tr>
                      <tr>
                        <th>Dokumen TOR</th>
                          <td>
                            <ul>
                               @foreach($tor_attachment as $da)
                                  <li>
                                  @if($da['filepath'] == '') -
                                  @else <a href="{{ asset($da['filepath']) }}">{{ basename($da['filepath']) }}</a>
                                  @endif
                                  </li>
                               @endforeach
                            </ul>
                          </td>
                      </tr>

                  </table>
                </div>
              </div>
            </div>
              <br>

              <form method="post" role="form" enctype="multipart/form-data" onsubmit="return checkForm(this)">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" name="id" value="{{ $tor['id'] }}">
              <div class="row">
                <div class="col-md-2">
                  <label > Status Approval </label>
                  {{ csrf_field() }}
                </div>
                  <div class="col-md-2">
                    <select class="form-control" name="tor_review_status_id" id="tor_review_status_id" required="" <?php if($tor->tor_review_phase->role_id != $current_tor_review_phase->role_id || ($tor->tor_review_phase->role_id == $current_tor_review_phase->role_id && $tor->tor_review_status_id != 4)) echo "disabled=''"?>>
                      @foreach($tor_review_status as $sp)\
                        @if($sp->id!=4)
                         <option value="{{ $sp->id }}" {{ ($tor->tor_review_phase->role_id != $current_tor_review_phase->role_id ? ($sp->id == 1 ? 'selected' : '') : ($tor->tor_review_status_id == $sp->id ? 'selected' : '')) }}> {{ $sp->name }} </option>
                        @endif
                      @endforeach
                    </select>
                </div>
              </div>

              @if ($current_tor_review_phase->urutan < 4)
                <br>
                <div id="approved_to" class="row">
                  <div class="col-md-2">
                    <label > Approved To </label>
                  </div>
                  <div class="col-md-9">
                    <input type="text" id="manager_role_id" class="form-control" name="manager_role_id" disabled="" value="{{ $current_tor_review_phase->nextPhase()->role->name }}">
                  </div>
                </div>
              @endif

              <br>
              <div class="row {{ $errors->has('alasan') ? 'has-error' : '' }}" id="row_alasan">
                <div class="col-md-2" >
                <label> Alasan Umum </label>
                </div>
                <div class="col-md-9">
                    <textarea name="alasan" class="form-control" <?php if($tor->tor_review_phase->role_id != $current_tor_review_phase->role_id || ($tor->tor_review_phase->role_id == $current_tor_review_phase->role_id && $tor->tor_review_status_id != 4)) echo "disabled=''"?>>{{ $tor['alasan']}}</textarea>
                    @if($errors->has('alasan'))
                      <span>
                        <strong>{{ $errors->first('alasan') }}</strong>
                      </span>
                    @endif
                </div>
              </div>

                            <br>
              <div class="row {{ $errors->has('alasan_pendahuluan') ? 'has-error' : '' }}" id="row_alasan_pendahuluan">
                <div class="col-md-2" >
                <label>Alasan Pendahuluan</label>
                </div>
                <div class="col-md-9">
                    <textarea name="alasan_pendahuluan" class="form-control" <?php if($tor->tor_review_phase->role_id != $current_tor_review_phase->role_id || ($tor->tor_review_phase->role_id == $current_tor_review_phase->role_id && $tor->tor_review_status_id != 4)) echo "disabled=''"?>>{{ $tor['alasan_pendahuluan']}}</textarea>
                    @if($errors->has('alasan_pendahuluan'))
                      <span>
                        <strong>{{ $errors->first('alasan_pendahuluan') }}</strong>
                      </span>
                    @endif
                </div>
              </div>

              <br>
              <div class="row {{ $errors->has('alasan_data_teknis') ? 'has-error' : '' }}" id="row_alasan_data_teknis">
                <div class="col-md-2" >
                <label>Alasan Data teknis / Referensi Teknis</label>
                </div>
                <div class="col-md-9">
                    <textarea name="alasan_data_teknis" class="form-control" <?php if($tor->tor_review_phase->role_id != $current_tor_review_phase->role_id || ($tor->tor_review_phase->role_id == $current_tor_review_phase->role_id && $tor->tor_review_status_id != 4)) echo "disabled=''"?>>{{ $tor['alasan_data_teknis']}}</textarea>
                    @if($errors->has('alasan_data_teknis'))
                      <span>
                        <strong>{{ $errors->first('alasan_data_teknis') }}</strong>
                      </span>
                    @endif
                </div>
              </div>

              <br>
              <div class="row {{ $errors->has('alasan_lingkup_pekerjaan') ? 'has-error' : '' }}" id="row_alasan_lingkup_pekerjaan">
                <div class="col-md-2" >
                <label>Alasan Lingkup Pekerjaan / Scope of Work  </label>
                </div>
                <div class="col-md-9">
                    <textarea name="alasan_lingkup_pekerjaan" class="form-control" <?php if($tor->tor_review_phase->role_id != $current_tor_review_phase->role_id || ($tor->tor_review_phase->role_id == $current_tor_review_phase->role_id && $tor->tor_review_status_id != 4)) echo "disabled=''"?>>{{ $tor['alasan_lingkup_pekerjaan']}}</textarea>
                    @if($errors->has('alasan_lingkup_pekerjaan'))
                      <span>
                        <strong>{{ $errors->first('alasan_lingkup_pekerjaan') }}</strong>
                      </span>
                    @endif
                </div>
              </div>

              <br>
              <div class="row {{ $errors->has('alasan_performance_desain') ? 'has-error' : '' }}" id="row_alasan_performance_desain">
                <div class="col-md-2" >
                <label>Alasan Performance Design </label>
                </div>
                <div class="col-md-9">
                    <textarea name="alasan_performance_desain" class="form-control" <?php if($tor->tor_review_phase->role_id != $current_tor_review_phase->role_id || ($tor->tor_review_phase->role_id == $current_tor_review_phase->role_id && $tor->tor_review_status_id != 4)) echo "disabled=''"?>>{{ $tor['alasan_performance_desain']}}</textarea>
                    @if($errors->has('alasan_performance_desain'))
                      <span>
                        <strong>{{ $errors->first('alasan_performance_desain') }}</strong>
                      </span>
                    @endif
                </div>
              </div>

              <br>
              <div class="row {{ $errors->has('alasan_kualifikasi_calon_pelaksanaan_pekerjaan') ? 'has-error' : '' }}" id="row_alasan_kualifikasi_calon_pelaksanaan_pekerjaan">
                <div class="col-md-2" >
                <label>Alasan Kualifikasi Calon Pelaksanaan Pekerjaan</label>
                </div>
                <div class="col-md-9">
                    <textarea name="alasan_kualifikasi_calon_pelaksanaan_pekerjaan" class="form-control" <?php if($tor->tor_review_phase->role_id != $current_tor_review_phase->role_id || ($tor->tor_review_phase->role_id == $current_tor_review_phase->role_id && $tor->tor_review_status_id != 4)) echo "disabled=''"?>>{{ $tor['alasan_kualifikasi_calon_pelaksanaan_pekerjaan']}}</textarea>
                    @if($errors->has('alasan_kualifikasi_calon_pelaksanaan_pekerjaan'))
                      <span>
                        <strong>{{ $errors->first('alasan_kualifikasi_calon_pelaksanaan_pekerjaan') }}</strong>
                      </span>
                    @endif
                </div>
              </div>

              <br>
              <div class="row {{ $errors->has('alasan_detail_pelaksanaan_pekerjaan') ? 'has-error' : '' }}" id="row_alasan_detail_pelaksanaan_pekerjaan">
                <div class="col-md-2" >
                <label>Alasan Detail Pelaksanaan Pekerjaan   </label>
                </div>
                <div class="col-md-9">
                    <textarea name="alasan_detail_pelaksanaan_pekerjaan" class="form-control" <?php if($tor->tor_review_phase->role_id != $current_tor_review_phase->role_id || ($tor->tor_review_phase->role_id == $current_tor_review_phase->role_id && $tor->tor_review_status_id != 4)) echo "disabled=''"?>>{{ $tor['alasan_detail_pelaksanaan_pekerjaan']}}</textarea>
                    @if($errors->has('alasan_detail_pelaksanaan_pekerjaan'))
                      <span>
                        <strong>{{ $errors->first('alasan_detail_pelaksanaan_pekerjaan') }}</strong>
                      </span>
                    @endif
                </div>
              </div>

              <br>
              <div class="row {{ $errors->has('alasan_kelengkapan_pelaksanaan_pekerjaan') ? 'has-error' : '' }}" id="row_alasan_kelengkapan_pelaksanaan_pekerjaan">
                <div class="col-md-2" >
                <label>Alasan Kelengkapan Pelaksanaan Pekerjaan  </label>
                </div>
                <div class="col-md-9">
                    <textarea name="alasan_kelengkapan_pelaksanaan_pekerjaan" class="form-control" <?php if($tor->tor_review_phase->role_id != $current_tor_review_phase->role_id || ($tor->tor_review_phase->role_id == $current_tor_review_phase->role_id && $tor->tor_review_status_id != 4)) echo "disabled=''"?>>{{ $tor['alasan_kelengkapan_pelaksanaan_pekerjaan']}}</textarea>
                    @if($errors->has('alasan_kelengkapan_pelaksanaan_pekerjaan'))
                      <span>
                        <strong>{{ $errors->first('alasan_kelengkapan_pelaksanaan_pekerjaan') }}</strong>
                      </span>
                    @endif
                </div>
              </div>

              <br>
              <div class="row {{ $errors->has('alasan_aspek_keamanan_k3') ? 'has-error' : '' }}" id="row_alasan_aspek_keamanan_k3">
                <div class="col-md-2" >
                <label>Alasan Aspek Keamanan dan K3  </label>
                </div>
                <div class="col-md-9">
                    <textarea name="alasan_aspek_keamanan_k3" class="form-control" <?php if($tor->tor_review_phase->role_id != $current_tor_review_phase->role_id || ($tor->tor_review_phase->role_id == $current_tor_review_phase->role_id && $tor->tor_review_status_id != 4)) echo "disabled=''"?>>{{ $tor['alasan_aspek_keamanan_k3']}}</textarea>
                    @if($errors->has('alasan_aspek_keamanan_k3'))
                      <span>
                        <strong>{{ $errors->first('alasan_aspek_keamanan_k3') }}</strong>
                      </span>
                    @endif
                </div>
              </div>

              <br>
              <div class="row {{ $errors->has('alasan_laporan_hasil_pekerjaan') ? 'has-error' : '' }}" id="row_alasan_laporan_hasil_pekerjaan">
                <div class="col-md-2" >
                <label>Alasan Laporan Hasil Pekerjaan  </label>
                </div>
                <div class="col-md-9">
                    <textarea name="alasan_laporan_hasil_pekerjaan" class="form-control" <?php if($tor->tor_review_phase->role_id != $current_tor_review_phase->role_id || ($tor->tor_review_phase->role_id == $current_tor_review_phase->role_id && $tor->tor_review_status_id != 4)) echo "disabled=''"?>>{{ $tor['alasan_laporan_hasil_pekerjaan']}}</textarea>
                    @if($errors->has('alasan_laporan_hasil_pekerjaan'))
                      <span>
                        <strong>{{ $errors->first('alasan_laporan_hasil_pekerjaan') }}</strong>
                      </span>
                    @endif
                </div>
              </div>

              <br>
              <div class="row {{ $errors->has('alasan_material_sisa_limbah') ? 'has-error' : '' }}" id="row_alasan_material_sisa_limbah">
                <div class="col-md-2" >
                <label>Alasan Material Sisa atau Limbah  </label>
                </div>
                <div class="col-md-9">
                    <textarea name="alasan_material_sisa_limbah" class="form-control" <?php if($tor->tor_review_phase->role_id != $current_tor_review_phase->role_id || ($tor->tor_review_phase->role_id == $current_tor_review_phase->role_id && $tor->tor_review_status_id != 4)) echo "disabled=''"?>>{{ $tor['alasan_material_sisa_limbah']}}</textarea>
                    @if($errors->has('alasan_material_sisa_limbah'))
                      <span>
                        <strong>{{ $errors->first('alasan_material_sisa_limbah') }}</strong>
                      </span>
                    @endif
                </div>
              </div>

              <br>
              <div class="row {{ $errors->has('alasan_quality_acceptance') ? 'has-error' : '' }}" id="row_alasan_quality_acceptance">
                <div class="col-md-2" >
                <label>Alasan Quality Acceptance </label>
                </div>
                <div class="col-md-9">
                    <textarea name="alasan_quality_acceptance" class="form-control" <?php if($tor->tor_review_phase->role_id != $current_tor_review_phase->role_id || ($tor->tor_review_phase->role_id == $current_tor_review_phase->role_id && $tor->tor_review_status_id != 4)) echo "disabled=''"?>>{{ $tor['alasan_quality_acceptance']}}</textarea>
                    @if($errors->has('alasan_quality_acceptance'))
                      <span>
                        <strong>{{ $errors->first('alasan_quality_acceptance') }}</strong>
                      </span>
                    @endif
                </div>
              </div>

              <br>
              <div class="row {{ $errors->has('alasan_delivery') ? 'has-error' : '' }}" id="row_alasan_delivery">
                <div class="col-md-2" >
                <label>Alasan Delivery   </label>
                </div>
                <div class="col-md-9">
                    <textarea name="alasan_delivery" class="form-control" <?php if($tor->tor_review_phase->role_id != $current_tor_review_phase->role_id || ($tor->tor_review_phase->role_id == $current_tor_review_phase->role_id && $tor->tor_review_status_id != 4)) echo "disabled=''"?>>{{ $tor['alasan_delivery']}}</textarea>
                    @if($errors->has('alasan_delivery'))
                      <span>
                        <strong>{{ $errors->first('alasan_delivery') }}</strong>
                      </span>
                    @endif
                </div>
              </div>

              <br>
              <div class="row {{ $errors->has('alasan_garansi') ? 'has-error' : '' }}" id="row_alasan_garansi">
                <div class="col-md-2" >
                <label>Alasan Garansi</label>
                </div>
                <div class="col-md-9">
                    <textarea name="alasan_garansi" class="form-control" <?php if($tor->tor_review_phase->role_id != $current_tor_review_phase->role_id || ($tor->tor_review_phase->role_id == $current_tor_review_phase->role_id && $tor->tor_review_status_id != 4)) echo "disabled=''"?>>{{ $tor['alasan_garansi']}}</textarea>
                    @if($errors->has('alasan_garansi'))
                      <span>
                        <strong>{{ $errors->first('alasan_garansi') }}</strong>
                      </span>
                    @endif
                </div>
              </div>

              <br>
              <div class="row {{ $errors->has('alasan_lain_lain') ? 'has-error' : '' }}" id="row_alasan_lain_lain">
                <div class="col-md-2" >
                <label>Alasan Lain-lain  </label>
                </div>
                <div class="col-md-9">
                    <textarea name="alasan_lain_lain" class="form-control" <?php if($tor->tor_review_phase->role_id != $current_tor_review_phase->role_id || ($tor->tor_review_phase->role_id == $current_tor_review_phase->role_id && $tor->tor_review_status_id != 4)) echo "disabled=''"?>>{{ $tor['alasan_lain_lain']}}</textarea>
                    @if($errors->has('alasan_lain_lain'))
                      <span>
                        <strong>{{ $errors->first('alasan_lain_lain') }}</strong>
                      </span>
                    @endif
                </div>
              </div>

              <br>
              <div class="row {{ $errors->has('alasan_lain_lain') ? 'has-error' : '' }}" id="row_alasan_lain_lain">
                <div class="col-md-2" >
                <label>Lampiran reviewer</label>
                </div>
                <div class="col-md-9">
                    <table class="table table-striped table-bordered table-hover">
                      <thead>
                        <tr>
                          <td>Aksi</td>
                          <td>Berkas</td>
                          <td>Hapus</td>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($tor_review_attachment as $tra)
                          <tr>
                            <td>
                              <input type="file" name="review_filepath[]" value="{{ $tra['filepath'] }}">
                              <input type="hidden" name="review_attachment_id[]" value="{{ $tra['id'] }}">
                            </td>
                            <td>
                              <a href="{{ asset($tra['filepath']) }}"> {{ basename($tra['filepath']) }}</a>
                            </td>
                            <td>
                              @if($tra['filepath']!= null)
                                <input type="checkbox" name="delete_review_attachments_id[]" value="{{$tra['id']}}">
                              @endif
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                </div>
              </div>

              <br>
                <div class=" col-xs-10 col-md-offset-2">
                    <input type="hidden" name="is_submit_review" id="is_submit_review" value="0">
                    <a href="{{ url('approval_tor/daftar?tahun_anggaran='.$tor->tahun_anggaran.'&strategi_bisnis='.$tor->lokasi->distrik->strategi_bisnis_id.'&distrik='.$tor->lokasi->distrik_id.'&lokasi='.$tor->lokasi_id) }}" class="btn btn-success" >Kembali</a>
                    <button id="draft-btn" class="btn btn-danger pull-left" type="submit" onclick="submittor(0)">Simpan sebagai Draft</button>
                    <button id="submit-btn" class="btn btn-primary pull-left" type="submit" onclick="confirm_rejected(event) && submittor(1)" <?php if($tor->tor_review_phase->role_id != $current_tor_review_phase->role_id || ($tor->tor_review_phase->role_id == $current_tor_review_phase->role_id && $tor->tor_review_status_id != 4)) echo "disabled"?> >Simpan</button>
                </div>
              </form>
            </div>
        </div>
  </div>
  </div>
</div>

<script type="text/javascript">
  function submittor(value = 1){
    document.getElementById("is_submit_review").value = value;
  }

  function confirm_rejected(e) {

    var pilihan = $("#tor_review_status_id").val();

    if (pilihan == 3) {
      if(confirm('Merejected TOR ini berarti membatalkan TOR ini. Apakah Anda yakin melakukan rejected TOR ini?'))
        true;
      else {
        // alert('Cancelled !');
        e.preventDefault();
        return false;
      }
    }
    console.log('Klik something');

    return true;
  }

  function checkForm(form) {
    let is_valid = true;

    if (is_valid) {
        $('button[type="submit"]', form).prop('disabled', true);
    }
    return is_valid;
  }

  $(function(){
    function trigger_approved_to() {
      let val = $("#tor_review_status_id").val();
      let $approved_to = $('#approved_to');
      if ($approved_to.length > 0) {
        if (val == "1") {
          $approved_to.removeClass('hidden');
          $('#manager_role_id').prop('required', true);
        } else {
          $approved_to.addClass('hidden');
          $('#manager_role_id').prop('required', false);
        }
      }
    }

    $($("#tor_review_status_id").on('change', function() {
      trigger_approved_to();
    }));

    trigger_approved_to();
  });
</script>
<!-- <script type="text/javascript">
  $(function() {
    var status_id = $('#tor_review_status_id').val();
        if(status_id == '2' || status_id == '3') {
            $('#row_alasan').show();
        } else {
            $('#row_alasan').hide();
        }
    $('#tor_review_status_id').change(function(){
        var status_id = $('#tor_review_status_id').val();
        if(status_id == '2' || status_id == '3') {
            $('#row_alasan').show();
        } else {
            $('#row_alasan').hide();
        }
    });
  });
</script> -->
@endsection
