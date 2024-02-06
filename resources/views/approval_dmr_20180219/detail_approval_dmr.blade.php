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
        <div class="">
            <div>
                <h3> Approval DMR</h3>   
            </div>
                <div class="col-lg-12">
            </div>
        </div>
        <br>

          
              <div>
                <div class="x_content">
                  <!-- <h4>Detail Approval</h4> -->
                  <table  class="table table-striped table-bordered table-hover">
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
                        <th>No PRK</th>
                        <td> {{ $dmr['no_prk'] }} </td>
                      </tr>
                      <tr>
                        <th>Nama PRK</th>
                        <td> {{ $dmr['nama_prk'] }} </td>
                      </tr>
                      <tr>
                        <th>Anggaran PRK </th>
                        <td> {{ $dmr['jumlah_anggaran'] }} </td>
                      </tr>
                      <tr>
                        <th>Dokumen DMR</th>
                       <!--  <td><a href="#" > <span>{{ $dmr['dmr_filepath'] }}</span></a></td> -->
                        <td><a href="{{ asset($dmr->dmr_filepath) }}">{{ $dmr['dmr_filepath'] }}</a></td>
                      </tr>
                      <tr>
                          <th>Summary</th>
                          <td></td>
                      </tr>
                      <tr>
                          <td>Latar Belakang Masalah</td>
                          <td>
                             <?php echo strip_tags($dmr['latar_belakang']);  ?>
                          </td>
                      </tr>
                      <tr>
                          <td>1.2 Sasaran Tujuan Kegiatan</td>
                           <td>
                              <?php  echo strip_tags($dmr['sasaran_tujuan']); ?>
                          </td>
                      </tr>
                      <tr>
                          <td>1.3 Permasalahan</td>
                          <td>
                              <?php  echo strip_tags($dmr['permasalahan']); ?>
                          </td>
                      </tr>
                      <tr>
                          <td>1.4 Alternatif Cara Pencapaian Sasaran</td>
                          <td>
                              <?php  echo strip_tags($dmr['alternatif_pencapaian']); ?>
                          </td>
                      </tr>
                      <tr>
                          <td>1.5 Benefit Operasional</td>
                          <td>
                              <?php  echo strip_tags($dmr['benefit_operasional']); ?>
                          </td>
                      </tr>
                      <tr>
                          <td>1.6 Benefit Finansial</td>
                          <td>
                              <?php  echo strip_tags($dmr['benefit_finansial']); ?>
                          </td>
                      </tr>
                      <tr>
                        <th>Lampiran</th>
                          <td>
                            <ul>
                               @foreach($dmr_attachment as $da)
                                  <li>
                                  @if($da['filepath'] == '') -
                                  @else <a href="{{ asset($da['filepath']) }}">{{ $da['filepath'] }}</a>
                                  @endif
                                  </li>
                               @endforeach
                            </ul>
                          </td>
                      </tr>
                    
                  </table>
                </div>
              <br>

              <form method="post" role="form">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" name="id" value="{{ $dmr['id'] }}">
              <div class="row">
                <div class="col-md-2"><label> Status Approval </label> 
                {{ csrf_field() }}</div>
                  <div class="col-md-2"> 
                    <select class="form-control" name="dmr_review_status_id" id="dmr_review_status_id" required="" <?php if($dmr->dmr_review_phase->role_id != $current_dmr_review_phase->role_id || ($dmr->dmr_review_phase->role_id == $current_dmr_review_phase->role_id && $dmr->dmr_review_status_id != 4)) echo "disabled=''"?>>
                      @foreach($dmr_review_status as $sp)
                         <option value="{{ $sp->id }}" <?php echo($dmr->dmr_review_phase->role_id != $current_dmr_review_phase->role_id ? ($sp->id == 1 ? 'selected' : '') : ($dmr->dmr_review_status_id == $sp->id ? 'selected' : ''))?>> {{ $sp->name }} </option>
                      @endforeach
                    </select>
                </div>
              </div>

              <br>
              <div class="row {{ $errors->has('alasan') ? 'has-error' : '' }}" id="row_alasan">
                <div class="col-md-2" >
                <label> Alasan </label>
                </div>
                <div class="col-md-10"> 
                    <textarea name="alasan" class="form-control" <?php if($dmr->dmr_review_phase->role_id != $current_dmr_review_phase->role_id || ($dmr->dmr_review_phase->role_id == $current_dmr_review_phase->role_id && $dmr->dmr_review_status_id != 4)) echo "disabled=''"?>>{{ $dmr['alasan']}}</textarea>
                    @if($errors->has('alasan'))
                      <span>
                        <strong>{{ $errors->first('alasan') }}</strong>
                      </span>
                    @endif
                </div>
              </div>
                            <br>
              <div class="row">
                <div class="col-md-2" >
                <label> Alasan Latar Belakang Masalah</label>
                </div>
                <div class="col-md-10"> 
                    <textarea name="alasan_latar_belakang" class="form-control" <?php if($dmr->dmr_review_phase->role_id != $current_dmr_review_phase->role_id || ($dmr->dmr_review_phase->role_id == $current_dmr_review_phase->role_id && $dmr->dmr_review_status_id != 4)) echo "disabled=''"?>>{{($dmr->dmr_review_phase->role_id != $current_dmr_review_phase->role_id || ($dmr->dmr_review_phase->role_id == $current_dmr_review_phase->role_id && $dmr->dmr_review_status_id != 4) ? $dmr['alasan_latar_belakang'] : '')}}</textarea>
                </div>
              </div>

              <br>
              <div class="row">
                <div class="col-md-2" >
                <label> Alasan Sasaran Tujuan Kegiatan</label>
                </div>
                <div class="col-md-10"> 
                    <textarea name="alasan_sasaran_tujuan" class="form-control" <?php if($dmr->dmr_review_phase->role_id != $current_dmr_review_phase->role_id || ($dmr->dmr_review_phase->role_id == $current_dmr_review_phase->role_id && $dmr->dmr_review_status_id != 4)) echo "disabled=''"?>>{{($dmr->dmr_review_phase->role_id != $current_dmr_review_phase->role_id || ($dmr->dmr_review_phase->role_id == $current_dmr_review_phase->role_id && $dmr->dmr_review_status_id != 4) ? $dmr['alasan_sasaran_tujuan'] : '')}}</textarea>
                </div>
              </div>

              <br>
              <div class="row">
                <div class="col-md-2" >
                <label> Alasan Permasalahan</label>
                </div>
                <div class="col-md-10"> 
                    <textarea name="alasan_permasalahan" class="form-control" <?php if($dmr->dmr_review_phase->role_id != $current_dmr_review_phase->role_id || ($dmr->dmr_review_phase->role_id == $current_dmr_review_phase->role_id && $dmr->dmr_review_status_id != 4)) echo "disabled=''"?>>{{($dmr->dmr_review_phase->role_id != $current_dmr_review_phase->role_id || ($dmr->dmr_review_phase->role_id == $current_dmr_review_phase->role_id && $dmr->dmr_review_status_id != 4) ? $dmr['alasan_permasalahan'] : '')}}</textarea>
                </div>
              </div>

              <br>
              <div class="row">
                <div class="col-md-2" >
                <label> Alasan Alternatif Cara Pencapaian Sasaran</label>
                </div>
                <div class="col-md-10"> 
                    <textarea name="alasan_alternatif_pencapaian" class="form-control" <?php if($dmr->dmr_review_phase->role_id != $current_dmr_review_phase->role_id || ($dmr->dmr_review_phase->role_id == $current_dmr_review_phase->role_id && $dmr->dmr_review_status_id != 4)) echo "disabled=''"?>>{{($dmr->dmr_review_phase->role_id != $current_dmr_review_phase->role_id || ($dmr->dmr_review_phase->role_id == $current_dmr_review_phase->role_id && $dmr->dmr_review_status_id != 4) ? $dmr['alasan_alternatif_pencapaian'] : '')}}</textarea>
                </div>
              </div>

              <br>
              <div class="row">
                <div class="col-md-2" >
                <label> Alasan Benefit Operasional</label>
                </div>
                <div class="col-md-10"> 
                    <textarea name="alasan_benefit_operasional" class="form-control" <?php if($dmr->dmr_review_phase->role_id != $current_dmr_review_phase->role_id || ($dmr->dmr_review_phase->role_id == $current_dmr_review_phase->role_id && $dmr->dmr_review_status_id != 4)) echo "disabled=''"?>>{{($dmr->dmr_review_phase->role_id != $current_dmr_review_phase->role_id || ($dmr->dmr_review_phase->role_id == $current_dmr_review_phase->role_id && $dmr->dmr_review_status_id != 4) ? $dmr['alasan_benefit_operasional'] : '')}}</textarea>
                </div>
              </div>

              <br>
              <div class="row">
                <div class="col-md-2" >
                <label> Alasan Benefit Finansial</label>
                </div>
                <div class="col-md-10"> 
                    <textarea name="alasan_benefit_finansial" class="form-control" <?php if($dmr->dmr_review_phase->role_id != $current_dmr_review_phase->role_id || ($dmr->dmr_review_phase->role_id == $current_dmr_review_phase->role_id && $dmr->dmr_review_status_id != 4)) echo "disabled=''"?>>{{($dmr->dmr_review_phase->role_id != $current_dmr_review_phase->role_id || ($dmr->dmr_review_phase->role_id == $current_dmr_review_phase->role_id && $dmr->dmr_review_status_id != 4) ? $dmr['alasan_benefit_finansial'] : '')}}</textarea>
                </div>
              </div>

              <br>
                <div class=" col-xs-10 col-md-offset-2">
                    <a href="{{ url('approval_dmr/daftar?tahun_anggaran='.$dmr->tahun_anggaran.'&strategi_bisnis='.$dmr->lokasi->distrik->strategi_bisnis_id.'&distrik='.$dmr->lokasi->distrik_id.'&lokasi='.$dmr->lokasi_id) }}" class="btn btn-success" >Kembali</a>
                    <button class="btn btn-primary pull-left" type="submit" <?php if($dmr->dmr_review_phase->role_id != $current_dmr_review_phase->role_id || ($dmr->dmr_review_phase->role_id == $current_dmr_review_phase->role_id && $dmr->dmr_review_status_id != 4)) echo "disabled"?> >Simpan</button>
                </div>
              </form>
            </div>
        </div>
  </div>
  </div>
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