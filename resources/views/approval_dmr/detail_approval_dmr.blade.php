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
    @if (\Session::has('msg'))
      <div class="alert alert-danger">
          <ul>
              <li>{!! \Session::get('msg') !!}</li>
          </ul>
      </div>
    @endif
    <div role="main">
        <div class="">
            <div>
                <h3> Approval DMR</h3>
            </div>
            @if($status_dokumen == FALSE)
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                      {{ 'Dokumen dengan nomer '.$dmr->no_dokumen.' belum diupload di Form. Sesuai arahan terbaru pembina proses DIVANG, proses approval DMR dapat tetap dilanjutkan.' }}
                </div>
            @endif
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
                        <td width="75%"> {{ $dmr['tahun_anggaran'] }} </td>
                      </tr>
                      <tr>
                        <th> Strategi Bisnis</th>
                        <td> {{ $dmr->lokasi->distrik->strategi_bisnis->name }} </td>
                      </tr>
                      <tr>
                        <th> Distrik</th>
                        <td> {{ $dmr->lokasi->distrik->name }} </td>
                      </tr>
                      <tr>
                        <th> Lokasi</th>
                        <td> {{ $dmr->lokasi->name }} </td>
                      </tr>
                      <tr>
                        <th> ID Dokumen</th>
                        <td> {{ $dmr['no_dokumen'] }} </td>
                      </tr>
                      <tr>
                        <th> Judul DMR</th>
                        <td> {{ $dmr['judul_dokumen'] ? $dmr['judul_dokumen'] : '-' }} </td>
                      </tr>
                      <tr>
                        <th> No PRK Form</th>
                        <td> {{ $dmr['no_prk_form'] ? $dmr['no_prk_form'] : '-' }} </td>
                      </tr>
                      <tr>
                        <th> Anggaran PRK Form</th>
                        <td> {{ $dmr['anggaran_prk_form'] ? $dmr['anggaran_prk_form'] : '-' }} </td>
                      </tr>
					  <tr>
                        <th>Anggaran PRK Input (dalam ribuan)</th>
                        <td>Rp. {{ number_format($dmr['jumlah_anggaran'],0,',','.') }}</td>
                       </tr>
                     <!--  <tr>
                        <th>No PRK</th>
                        <td> {{ $dmr['no_prk'] }} </td>
                      </tr>
                      <tr>
                        <th>Nama PRK</th>
                        <td> {{ $dmr['nama_prk'] }} </td>
                      </tr>
                      <tr>
                        <th>Anggaran PRK Input (dalam ribuan)</th>
                        <td>Rp. {{ $dmr['jumlah_anggaran'] }} </td>
                      </tr>
                      -->
                      <tr>
                        <th>Dokumen DMR</th>
                       <!--  <td><a href="#" > <span>{{ $dmr['dmr_filepath'] }}</span></a></td> -->
                        <td><a href="{{ url('dmr/download_attachment') .'/'. $dmr->id }}">{{ basename($dmr['dmr_filepath']) }}</a></td>
                      </tr>
                      <tr>
                          <th>Summary</th>
                          <td></td>
                      </tr>
                      <tr>
                          <td>Latar Belakang Masalah</td>
                          <td>
                             <?php //echo strip_tags($dmr['latar_belakang']);  ?>
                             {!! $dmr['latar_belakang'] !!}
                          </td>
                      </tr>
                      <tr>
                          <td>1.2 Sasaran Tujuan Kegiatan</td>
                           <td>
                              <?php  //echo strip_tags($dmr['sasaran_tujuan']); ?>
                             {!! $dmr['sasaran_tujuan'] !!}
                          </td>
                      </tr>
                      <tr>
                          <td>1.3 Permasalahan</td>
                          <td>
                              <?php  //echo strip_tags($dmr['permasalahan']); ?>
                              {!! $dmr['permasalahan'] !!}
                          </td>
                      </tr>
                      <tr>
                          <td>1.4 Alternatif Cara Pencapaian Sasaran</td>
                          <td>
                              <?php //echo strip_tags($dmr['alternatif_pencapaian']); ?>
                              {!! $dmr['alternatif_pencapaian'] !!}
                          </td>
                      </tr>
                      <tr>
                          <td>1.5 Benefit Operasional</td>
                          <td>
                              <?php //echo strip_tags($dmr['benefit_operasional']); ?>
                              {!! $dmr['benefit_operasional'] !!}
                          </td>
                      </tr>
                      <tr>
                          <td>1.6 Benefit Finansial</td>
                          <td>
                              <?php //echo strip_tags($dmr['benefit_finansial']); ?>
                              {!! $dmr['benefit_finansial'] !!}
                          </td>
                      </tr>
                      <tr>
                        <th>Lampiran</th>
                          <td>
                            <ul>
                               @foreach($dmr_attachment as $da)
                                  <li>
                                  @if($da['filepath'] == '') -
                                  @else <a href="{{ url('dmr/dmr_attachment') .'/'. $da['id'] }}">{{ basename($da['filepath']) }}</a>
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
              <form method="post" role="form" enctype="multipart/form-data" name="form" id="form">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" name="id" value="{{ $dmr['id'] }}">
              <div class="row">
                <div class="col-md-2">
                  <label > Status Approval </label>
                  {{ csrf_field() }}
                </div>
                  <div class="col-md-2" id="status-approval">
                    <select class="form-control" name="dmr_review_status_id" id="dmr_review_status_id" required="" <?php if($dmr->dmr_review_phase->role_id != $current_dmr_review_phase->role_id || ($dmr->dmr_review_phase->role_id == $current_dmr_review_phase->role_id && $dmr->dmr_review_status_id != DMR_STATUS_QUEUE)) echo "disabled=''"?>>
                      @if($dmr_review->dmr_review_status_id == DMR_STATUS_QUEUE)
                      <option value="" disabled selected></option>
                      @endif
                      @foreach($dmr_review_status as $sp)
                        @if($sp->id != DMR_STATUS_QUEUE)
                         <option value="{{ $sp->id }}" <?php echo($dmr->dmr_review_status_id == $sp->id ? 'selected' : '')?>> {{ $sp->name }} </option>
                        @endif
                      @endforeach
                    </select>
                </div>
              </div>
              <br>
              <div id="field_alasan">
              <div class="row {{ $errors->has('alasan') ? 'has-error' : '' }}" id="row_alasan">
                <div class="col-md-2" >
                <label> Alasan Umum </label>
                </div>
                <div class="col-md-9">
                    <textarea name="alasan" style="resize:vertical;" class="form-control" <?php if($dmr->dmr_review_phase->role_id != $current_dmr_review_phase->role_id || ($dmr->dmr_review_phase->role_id == $current_dmr_review_phase->role_id && $dmr->dmr_review_status_id != DMR_STATUS_QUEUE)) echo "disabled=''"?>>{{ ($dmr->dmr_review_phase->role_id != $current_dmr_review_phase->role_id || ($dmr->dmr_review_phase->role_id == $current_dmr_review_phase->role_id && $dmr->dmr_review_status_id != DMR_STATUS_QUEUE) ? $dmr_review['alasan'] : '')}}</textarea>
                    @if($errors->has('alasan'))
                      <span>
                        <strong>{{ $errors->first('alasan') }}</strong>
                      </span>
                    @endif
                </div>
              </div>
              <br>

              <div class="row" >
                <div class="col-md-2" >
                <label> Alasan Latar Belakang Masalah</label>
                </div>
                <div class="col-md-9">
                    <textarea name="alasan_latar_belakang" style="resize:vertical;"  class="form-control" <?php if($dmr->dmr_review_phase->role_id != $current_dmr_review_phase->role_id || ($dmr->dmr_review_phase->role_id == $current_dmr_review_phase->role_id && $dmr->dmr_review_status_id != DMR_STATUS_QUEUE)) echo "disabled=''"?>>{{($dmr->dmr_review_phase->role_id != $current_dmr_review_phase->role_id || ($dmr->dmr_review_phase->role_id == $current_dmr_review_phase->role_id && $dmr->dmr_review_status_id != DMR_STATUS_QUEUE) ? $dmr_review->alasan_latar_belakang : '')}}</textarea>
                </div>
              </div>
              <br>

              <div class="row">
                <div class="col-md-2" >
                <label> Alasan Sasaran Tujuan Kegiatan</label>
                </div>
                <div class="col-md-9">
                    <textarea name="alasan_sasaran_tujuan" style="resize:vertical;"  class="form-control" <?php if($dmr->dmr_review_phase->role_id != $current_dmr_review_phase->role_id || ($dmr->dmr_review_phase->role_id == $current_dmr_review_phase->role_id && $dmr->dmr_review_status_id != DMR_STATUS_QUEUE)) echo "disabled=''"?>>{{($dmr->dmr_review_phase->role_id != $current_dmr_review_phase->role_id || ($dmr->dmr_review_phase->role_id == $current_dmr_review_phase->role_id && $dmr->dmr_review_status_id != DMR_STATUS_QUEUE) ? $dmr_review['alasan_sasaran_tujuan'] : '')}}</textarea>
                </div>
              </div>
              <br>

              <div class="row">
                <div class="col-md-2" >
                <label> Alasan Permasalahan</label>
                </div>
                <div class="col-md-9">
                    <textarea name="alasan_permasalahan" style="resize:vertical;"  class="form-control" <?php if($dmr->dmr_review_phase->role_id != $current_dmr_review_phase->role_id || ($dmr->dmr_review_phase->role_id == $current_dmr_review_phase->role_id && $dmr->dmr_review_status_id != DMR_STATUS_QUEUE)) echo "disabled=''"?>>{{($dmr->dmr_review_phase->role_id != $current_dmr_review_phase->role_id || ($dmr->dmr_review_phase->role_id == $current_dmr_review_phase->role_id && $dmr->dmr_review_status_id != DMR_STATUS_QUEUE) ? $dmr_review['alasan_permasalahan'] : '')}}</textarea>
                </div>
              </div>
              <br>

              <div class="row">
                <div class="col-md-2" >
                <label> Alasan Alternatif Cara Pencapaian Sasaran</label>
                </div>
                <div class="col-md-9">
                    <textarea name="alasan_alternatif_pencapaian" style="resize:vertical;"  class="form-control" <?php if($dmr->dmr_review_phase->role_id != $current_dmr_review_phase->role_id || ($dmr->dmr_review_phase->role_id == $current_dmr_review_phase->role_id && $dmr->dmr_review_status_id != DMR_STATUS_QUEUE)) echo "disabled=''"?>>{{($dmr->dmr_review_phase->role_id != $current_dmr_review_phase->role_id || ($dmr->dmr_review_phase->role_id == $current_dmr_review_phase->role_id && $dmr->dmr_review_status_id != DMR_STATUS_QUEUE) ? $dmr_review['alasan_alternatif_pencapaian'] : '')}}</textarea>
                </div>
              </div>
              <br>

              <div class="row">
                <div class="col-md-2" >
                <label> Alasan Benefit Operasional</label>
                </div>
                <div class="col-md-9">
                    <textarea name="alasan_benefit_operasional" style="resize:vertical;"  class="form-control" <?php if($dmr->dmr_review_phase->role_id != $current_dmr_review_phase->role_id || ($dmr->dmr_review_phase->role_id == $current_dmr_review_phase->role_id && $dmr->dmr_review_status_id != DMR_STATUS_QUEUE)) echo "disabled=''"?>>{{($dmr->dmr_review_phase->role_id != $current_dmr_review_phase->role_id || ($dmr->dmr_review_phase->role_id == $current_dmr_review_phase->role_id && $dmr->dmr_review_status_id != DMR_STATUS_QUEUE) ? $dmr_review['alasan_benefit_operasional'] : '')}}</textarea>
                </div>
              </div>
              <br>

              <div class="row">
                <div class="col-md-2" >
                <label> Alasan Benefit Finansial</label>
                </div>
                <div class="col-md-9">
                    <textarea name="alasan_benefit_finansial" style="resize:vertical;"  class="form-control" <?php if($dmr->dmr_review_phase->role_id != $current_dmr_review_phase->role_id || ($dmr->dmr_review_phase->role_id == $current_dmr_review_phase->role_id && $dmr->dmr_review_status_id != DMR_STATUS_QUEUE)) echo "disabled=''"?>>{{($dmr->dmr_review_phase->role_id != $current_dmr_review_phase->role_id || ($dmr->dmr_review_phase->role_id == $current_dmr_review_phase->role_id && $dmr->dmr_review_status_id != DMR_STATUS_QUEUE) ? $dmr_review['alasan_benefit_finansial'] : '')}}</textarea>
                </div>
              </div>
                <br>

                <div class="row">
                    <div class="control-label col-md-2"><label>Lampiran Review</label></div>
                    <div class="col-md-9">
                      <table class="table table-striped table-bordered table-hover">
                        @if($dmr->dmr_review_phase->role_id != $current_dmr_review_phase->role_id || ($dmr->dmr_review_phase->role_id == $current_dmr_review_phase->role_id && $dmr->dmr_review_status_id != DMR_STATUS_QUEUE))
                            <thead>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <ul>
                                        @foreach($dmr_review->dmr_review_attachments as $da)
                                          <li>
                                          @if($da['filepath'] == '') -
                                          @else <a href="{{ asset($da['filepath']) }}">{{ basename($da['filepath']) }}</a>
                                          @endif
                                          </li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                            </tbody>
                        @else
                        <thead>
                          <tr>
                             <td>No</td>
                             <td>Upload</td>
                             <!-- <td>Berkas</td> -->
                          </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>1</td>
                            <td>
                                <input type="file" name="filepath_review[]" multiple id="filepath_review"><br>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>
                                <input type="file" name="filepath_review[]" multiple id="filepath_review"><br>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>
                                <input type="file" name="filepath_review[]" multiple id="filepath_review"><br>
                            </td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>
                                <input type="file" name="filepath_review[]" multiple id="filepath_review"><br>
                            </td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>
                                <input type="file" name="filepath_review[]" multiple id="filepath_review"><br>
                            </td>
                        </tr>
                        </tbody>
                        @endif

                      </table>
                    </div>
                </div>
                </div>
                  <div class=" col-xs-10 col-md-offset-2">
                      <a href="{{ url('approval_dmr/daftar?tahun_anggaran='.$dmr->tahun_anggaran.'&strategi_bisnis='.$dmr->lokasi->distrik->strategi_bisnis_id.'&distrik='.$dmr->lokasi->distrik_id.'&lokasi='.$dmr->lokasi_id) }}" class="btn btn-success" >Kembali</a>
                      <button class="btn btn-primary pull-left" type="submit" onClick="confirm_rejected(event)" <?php if($dmr->dmr_review_phase->role_id != $current_dmr_review_phase->role_id || ($dmr->dmr_review_phase->role_id == $current_dmr_review_phase->role_id && $dmr->dmr_review_status_id != DMR_STATUS_QUEUE)) echo "disabled"?> >Simpan</button>
                  </div>
                 <br />&nbsp;
              </form>
            </div>
        </div>
  </div>
  </div>
</div>

<script type="text/javascript">
  function confirm_rejected(e) {

    var pilihan = $("#dmr_review_status_id").val();

    if (pilihan == 3) {
      if(confirm('Merejected DMR ini berarti membatalkan PRK atas DMR ini. Apakah Anda yakin melakukan rejected DMR ini?'))
        true;
      else {
        // alert('Cancelled !');
        e.preventDefault();
      }
    }
  }
</script>
<script type="text/javascript">
$(document).ready( function() {
    if($('#status-approval select').val() == 1){ //'.val()'
           $('#field_alasan').hide();
       } else {
           $('#field_alasan').show();
       }


    $('#status-approval select').change(function(){
       if($(this).val() == 1){
           $('#field_alasan').hide();
       } else {
            $('#field_alasan').show();
       }
    });
});
</script>
<!-- <script type="text/javascript">
  $(function() {
    var status_id = $('#dmr_review_status_id').val();
        if(status_id == '2' || status_id == '3') {
            $('#row_alasan').show();
        } else {
            $('#row_alasan').hide();
        }
    $('#dmr_review_status_id').change(function(){
        var status_id = $('#dmr_review_status_id').val();
        if(status_id == '2' || status_id == '3') {
            $('#row_alasan').show();
        } else {
            $('#row_alasan').hide();
        }
    });
  });
</script> -->
@endsection
