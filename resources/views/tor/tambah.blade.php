@extends('layouts.app')

@section('css_page')
    <!-- CKeditor -->
    <!-- <script src="{{ asset('vendors/ckeditor/ckeditor.js') }}"></script> -->

    <!-- include libraries(jQuery, bootstrap) --><!--
    <link href="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet">
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script>  -->



    <!-- Datatables -->
    <link href="{{ asset('vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}" rel="stylesheet">
@endsection

@section('js_page')
    <!-- include summernote css/js -->
    <!-- <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.css" rel="stylesheet"> -->
    <link href="{{ asset('vendors/summernote/css/summernote.css') }}" rel="stylesheet">
    <!-- <script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js"></script> -->
    <script src="{{ asset('vendors/summernote/css/summernote.js') }}"></script>


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
          <div>
            <div class="page-title">
              <h3> Tambah TOR</h3>

                <div class="row">
                    <div class="col-md-12">
                         <form method="post" role="form" enctype="multipart/form-data" name="form" id="form" class="disable-on-submit" onsubmit="return checkForm(this)">
                            <input type="hidden" name="is_submitted" id="is_submitted" value="0">
                            <div>
                            {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-2 {{ $errors->has('tahun_anggaran_id') ? 'has-error' : '' }}"><label>Tahun Anggaran</label></div>
                                    <div class="col-md-3">
                                        <select class="form-control" name="tahun_anggaran_id" required>
                                            <option value="" disabled selected>-- Pilih Tahun Anggaran --</option>
                                            <?php
                                              $th_skrg = date('Y');
                                              for($i=($th_skrg-5);$i<=($th_skrg+20);$i++) {
                                            ?>
                                            <option value="{{ $i }}" {{ old('tahun_anggaran_id') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div><br>
                                <div class="row">
                                    <div class="col-md-2"><label>Strategi Bisnis</label></div>
                                    <div class="col-md-3">
                                        <select class="form-control" name="strategi_bisnis_id" disabled="">
                                            <option>{{$distrik->strategi_bisnis->name}}</option>
                                        </select>
                                    </div>
                                </div><br>
                                <div class="row">
                                    <div class="col-md-2"><label>Distrik</label></div>
                                    <div class="col-md-3">
                                        <select class="form-control" name="distrik_id" disabled="">
                                            <option>{{$distrik->name}}</option>
                                        </select>
                                    </div>
                                </div><br>
                                <!-- Lokasi -->
                                <div class="row {{ $errors->has('lokasi_id') ? 'has-error' : '' }}">
                                    <div class="col-md-2"><label>Lokasi</label></div>
                                    <div class="col-md-3">
                                        <select required class="form-control" name="lokasi_id">
                                            <option value="" disabled selected>-- Pilih Lokasi --</option>
                                            @foreach($lokasi as $l)
                                                <option value="{{$l->id}}" {{ old('lokasi_id') == $l->id ? 'selected' : '' }}>{{$l->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div><br>
                                <!-- id dokumen dmr -->
                                <div class="row {{ $errors->has('no_dokumen_dmr') ? 'has-error' : '' }}">
                                    <div class="col-md-2"><label>ID Dokumen (DMR)</label></div>
                                    <div class="col-md-3">
                                        <select required class="form-control select2" name="no_dokumen_dmr">
                                            <option value="" disabled selected>-- Tidak ada data DMR --</option>
                                            @if($dmr != NULL)
                                                @foreach($dmr as $i)
                                                    <option value="{{$i->no_dokumen}}" {{ old('no_dokumen_dmr') == $i->no_dokumen ? 'selected' : '' }}>{{$i->no_dokumen}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @if($errors->has('no_dokumen_dmr'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('no_dokumen_dmr') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div><br>
                                <!-- id dokumen tor -->
                                <div class="row {{ $errors->has('no_dokumen') ? 'has-error' : '' }}">
                                    <div class="col-md-2"><label>ID Dokumen (TOR)</label></div>
                                    <div class="col-md-3">
                                        <input id="no_dokumen" required class="form-control" type="text" name="no_dokumen" value="{{ old('no_dokumen') }}">
                                        @if($errors->has('no_dokumen'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('no_dokumen') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div><br>
                                <div class="row {{ $errors->has('judul_dokumen') ? 'has-error' : '' }}">
                                    <div class="col-md-2"><label>Judul TOR</label></div>
                                    <div class="col-md-3">
                                        <input id="judul_dokumen" disabled="" class="form-control" type="text" name="judul_dokumen" value="{{ old('judul_dokumen') }}">
                                        @if($errors->has('judul_dokumen'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('judul_dokumen') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div><br>

                                <!-- dokumen tor -->
                                <div class="row hidden {{ $errors->has('tor_filepath') ? 'has-error' : '' }}">
                                    <div class="col-md-2"><label>Dokumen TOR</label></div>
                                    <div class="col-md-3">
                                    <input type="file" name="tor_filepath" id="tor_filepath" value="{{ old('tor_filepath') }}">
                                        @if($errors->has('tor_filepath'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('tor_filepath') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div><br>

                                <!-- reviewer role -->
                                <div class="row">
                                    <div class="col-md-2"><label>Reviewer TOR</label></div>
                                    <div class="col-md-3">
                                        <input type="text" id="manager_role_id" class="form-control" name="manager_role_id" disabled="" value="{{ $current_tor_review_phase->nextPhase()->role->name }}">
                                    </div>
                                </div><br>

                                <!-- summary tor -->
                                <div class="col-md-12">
                                    <div class="row"><label>Summary TOR</label></div>

                                    <div class="form-group hidden">
                                       <label>1.1 Pendahuluan</label>

                                        <!-- <form method="post"> -->
                                          <textarea id="pendahuluan" name="pendahuluan" class="summary-text">{{ old('pendahuluan') }}</textarea>
                                        <!-- </form> -->

                                        <script>
                                          $(document).ready(function() {
                                              $('#pendahuluan').summernote();
                                          });
                                        </script>
                                    </div>
                                    <br>

                                    <div class="form-group">
                                       <label>1.2 Data teknis / Referensi Teknis</label>
                                        <!-- <form method="post"> -->
                                          <textarea id="data_teknis" class="summary-text required" name="data_teknis">{{ old('data_teknis') }}</textarea>
                                        <!-- </form> -->

                                        <!-- <div id="sasaran_tujuan"></div> -->
                                        <script>
                                          $(document).ready(function() {
                                              $('#data_teknis').summernote();
                                          });
                                        </script>
                                    </div>
                                    <br>

                                    <div class="form-group">
                                        <label>1.3 Lingkup Pekerjaan / Scope of Work</label>

                                          <textarea id="lingkup_pekerjaan" class="summary-text required" name="lingkup_pekerjaan">{{ old('lingkup_pekerjaan') }}</textarea>

                                        <script>
                                          $(document).ready(function() {
                                              $('#lingkup_pekerjaan').summernote();
                                          });
                                        </script>

                                    </div>
                                    <br>

                                    <div class="form-group">
                                        <label>1.4 Performance Design</label>

                                          <textarea id="performance_desain" class="summary-text required" name="performance_desain">{{ old('performance_desain') }}</textarea>

                                        <script>
                                          $(document).ready(function() {
                                              $('#performance_desain').summernote();
                                          });
                                        </script>

                                    </div>
                                    <br>

                                    <div class="form-group">
                                       <label>1.5 Kualifikasi Calon Pelaksanaan Pekerjaan</label>

                                          <textarea id="kualifikasi_calon_pelaksanaan_pekerjaan" class="summary-text required" name="kualifikasi_calon_pelaksanaan_pekerjaan">{{ old('kualifikasi_calon_pelaksanaan_pekerjaan') }}</textarea>

                                        <script>
                                          $(document).ready(function() {
                                              $('#kualifikasi_calon_pelaksanaan_pekerjaan').summernote();
                                          });
                                        </script>

                                    </div>
                                    <br>

                                    <div class="form-group hidden">
                                       <label>1.6 Detail Pelaksanaan Pekerjaan</label>

                                          <textarea id="detail_pelaksanaan_pekerjaan" class="summary-text" name="detail_pelaksanaan_pekerjaan">{{ old('detail_pelaksanaan_pekerjaan') }}</textarea>

                                        <script>
                                          $(document).ready(function() {
                                              $('#detail_pelaksanaan_pekerjaan').summernote();
                                          });
                                        </script>
                                    </div>

                                    <div class="form-group hidden">
                                       <label>1.7 Kelengkapan Pelaksanaan Pekerjaan</label>

                                          <textarea id="kelengkapan_pelaksanaan_pekerjaan" class="summary-text" name="kelengkapan_pelaksanaan_pekerjaan">{{ old('kelengkapan_pelaksanaan_pekerjaan') }}</textarea>

                                        <script>
                                          $(document).ready(function() {
                                              $('#kelengkapan_pelaksanaan_pekerjaan').summernote();
                                          });
                                        </script>
                                    </div>

                                    <div class="form-group hidden">
                                       <label>1.8 Aspek Keamanan dan K3</label>

                                       <!-- <form method="post"> -->
                                          <textarea id="aspek_keamanan_k3" class="summary-text" name="aspek_keamanan_k3">{{ old('aspek_keamanan_k3') }}</textarea>
                                        <!-- </form> -->

                                        <!-- <div id="benefit_finansial"></div> -->
                                        <script>
                                          $(document).ready(function() {
                                              $('#aspek_keamanan_k3').summernote();
                                          });
                                        </script>
                                    </div>

                                    <div class="form-group hidden">
                                       <label>1.9 Laporan Hasil Pekerjaan</label>

                                       <!-- <form method="post"> -->
                                          <textarea id="laporan_hasil_pekerjaan" class="summary-text" name="laporan_hasil_pekerjaan">{{ old('laporan_hasil_pekerjaan') }}</textarea>
                                        <!-- </form> -->

                                        <!-- <div id="benefit_finansial"></div> -->
                                        <script>
                                          $(document).ready(function() {
                                              $('#laporan_hasil_pekerjaan').summernote();
                                          });
                                        </script>
                                    </div>
                                    <div class="form-group hidden">
                                       <label>2.0 Material Sisa atau Limbah</label>

                                       <!-- <form method="post"> -->
                                          <textarea id="material_sisa_limbah" class="summary-text" name="material_sisa_limbah">{{ old('material_sisa_limbah') }}</textarea>
                                        <!-- </form> -->

                                        <!-- <div id="benefit_finansial"></div> -->
                                        <script>
                                          $(document).ready(function() {
                                              $('#material_sisa_limbah').summernote();
                                          });
                                        </script>
                                    </div>

                                    <div class="form-group">
                                       <label>2.1 Quality Acceptance</label>

                                       <!-- <form method="post"> -->
                                          <textarea id="quality_acceptance" class="summary-text required" name="quality_acceptance">{{ old('quality_acceptance') }}</textarea>
                                        <!-- </form> -->

                                        <!-- <div id="benefit_finansial"></div> -->
                                        <script>
                                          $(document).ready(function() {
                                              $('#quality_acceptance').summernote();
                                          });
                                        </script>
                                    </div>

                                    <div class="form-group hidden">
                                       <label>2.2 Delivery</label>

                                       <!-- <form method="post"> -->
                                          <textarea id="delivery" class="summary-text" name="delivery">{{ old('delivery') }}</textarea>
                                        <!-- </form> -->

                                        <!-- <div id="benefit_finansial"></div> -->
                                        <script>
                                          $(document).ready(function() {
                                              $('#delivery').summernote();
                                          });
                                        </script>

                                    </div>

                                    <div class="form-group hidden">
                                       <label>2.3 Garansi</label>

                                       <!-- <form method="post"> -->
                                          <textarea id="garansi" class="summary-text" name="garansi">{{ old('garansi') }}</textarea>
                                        <!-- </form> -->

                                        <!-- <div id="benefit_finansial"></div> -->
                                        <script>
                                          $(document).ready(function() {
                                              $('#garansi').summernote();
                                          });
                                        </script>
                                    </div>
                                    <div class="form-group hidden">
                                       <label>2.4 Lain-lain</label>

                                       <!-- <form method="post"> -->
                                          <textarea id="lain_lain" class="summary-text" name="lain_lain">{{ old('lain_lain') }}</textarea>
                                        <!-- </form> -->

                                        <!-- <div id="benefit_finansial"></div> -->
                                        <script>
                                          $(document).ready(function() {
                                              $('#lain_lain').summernote();
                                          });
                                        </script>
                                    </div>
                                </div>
                                <!-- lampiran -->

                                <br>
                                <div class="row">
                                    <div class="control-label col-md-2"><label>Dokumen TOR</label></div>
                                    <div class="col-md-10">
                                      <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                          <tr>
                                             <td>No</td>
                                             <td>Berkas</td>
                                             <td>No</td>
                                             <td>Berkas</td>
                                          </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>
                                                <input type="file" name="filepath[]" multiple id="filepath"><br>
                                            </td>
                                            <td>6</td>
                                            <td>
                                                <input type="file" name="filepath[]" multiple id="filepath"><br>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>
                                                <input type="file" name="filepath[]" multiple id="filepath"><br>
                                            </td>
                                            <td>7</td>
                                            <td>
                                                <input type="file" name="filepath[]" multiple id="filepath"><br>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>
                                                <input type="file" name="filepath[]" multiple id="filepath"><br>
                                            </td>
                                            <td>8</td>
                                            <td>
                                                <input type="file" name="filepath[]" multiple id="filepath"><br>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>
                                                <input type="file" name="filepath[]" multiple id="filepath"><br>
                                            </td>
                                            <td>9</td>
                                            <td>
                                                <input type="file" name="filepath[]" multiple id="filepath"><br>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>
                                                <input type="file" name="filepath[]" multiple id="filepath"><br>
                                            </td>
                                            <td>10</td>
                                            <td>
                                                <input type="file" name="filepath[]" multiple id="filepath"><br>
                                            </td>
                                        </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                </div>
                                <!-- <div class="col-md-12">
                                    <div class="col-md-offset-2">
                                        <input type="file" name="filepath" id="filepath2"><br>
                                        <input type="file" name="filepath" id="filepath3"><br>
                                        <input type="file" name="filepath" id="filepath3"><br>
                                        <input type="file" name="filepath" id="filepath4"><br>
                                        <input type="file" name="filepath" id="filepath5"><br>
                                        <input type="file" name="filepath" id="filepath6"><br>
                                        <input type="file" name="filepath" id="filepath7"><br>
                                        <input type="file" name="filepath" id="filepath8"><br>
                                        <input type="file" name="filepath" id="filepath9"><br>
                                    </div>
                                </div> -->

                                <!-- button -->
                                <div class="col-md-12">
                                    <div class="col-md-offset-2">
                                      <button class="btn btn-danger" type="submit" onclick="submittor(0)">Simpan sebagai Draft</button>
                                      <button class="btn btn-success" type="submit" onclick="submittor()">Submit</button>
                                      {{-- <a href="javascript: submittor()" class="btn btn-success">Submit</a> --}}
                                      <a href="{{ url('/tor/daftar?tahun_anggaran='.(date('Y')).'&strategi_bisnis='.$distrik->strategi_bisnis_id.'&distrik='.$distrik->id) }}" class="btn btn-primary" type="reset">Kembali</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                        </div> <!-- col-md-12 -->
                    </div> <!-- row -->
            </div> <!-- page-title -->
          </div>
        <!-- </div>  --><!-- main -->

    </div> <!-- col-md-12 col-sm-12 col-xs-12 -->
</div><!-- row -->
<script type="text/javascript">
    function submittor(value = 1){
        var valid = 1;

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

        if(valid == 1){
            document.getElementById("is_submitted").value = value;
            // document.forms["form"].submit();
        }
    }

    function checkForm(form) {
        let $summary_text = $('.summary-text.required');
        let msg = '';
        let is_valid = true;
        for (let i=0; i < $summary_text.length; i++) {
            let $textarea = $summary_text.eq(i);
            // console.log('val ==>', $( $textarea.val() ).text().trim());
            // cek teks kosong
            if ($( $textarea.val() ).text().trim() == '') {
                // cek tag gambar
                if ($textarea.val().indexOf('img') < 0) {
                    msg += $textarea.prev('label').text() + '\n';
                    is_valid = false;
                }
            }
        }
        if (! is_valid) {
            // if (document.getElementById("is_submitted").value == 1) {
            //     alert('Mohon isian berikut dilengkapi terlebih dahulu.\n' + msg);
            // } else {
            //     is_valid = true;
            // }
            alert('Mohon isian berikut dilengkapi terlebih dahulu.\n' + msg);
        }

        if (is_valid) {
            $('button[type="submit"]', form).prop('disabled', true);
        }
        return is_valid;
    }
</script>

<script>
    window.cache_data = {};
    window.cache_data['dmr_list'] = {};

    function update_no_dokumen_dmr_list() {
        var id_lokasi = $('select[name="lokasi_id"]').val();
        var tahun_anggaran_id = $('select[name="tahun_anggaran_id"]').val();
        // console.log(input_concentration_id + ' ' + input_year);
        $('select[name="no_dokumen_dmr"]').empty();
        $('select[name="no_dokumen_dmr"]').prepend($('<option></option>').html('Loading...'));

        if(id_lokasi && tahun_anggaran_id) {
          $.ajax({
              url: '{{URL::to("output/dmr.ajax")}}',
              type: "POST",
              data: { id_lokasi:id_lokasi, tahun_anggaran_id:tahun_anggaran_id, _token: '{!! csrf_token() !!}' },
              dataType: "json",
              // async: false,
              success:function(data) {
                //console.log(data);
                $('select[name="no_dokumen_dmr"]').empty();
                // console.log('masuk');
                if(data.length) {
                    $('select[name="no_dokumen_dmr"]').append('<option disabled selected>-- Pilih DMR --</option>');
                    cache_data['dmr_list'] = {};

                    $.each(data, function(sb, value) {
                        $('select[name="no_dokumen_dmr"]').append('<option value="'+ value["no_dokumen"] +'">' + value["no_dokumen"] + '</option>');
                        cache_data['dmr_list'][value["no_dokumen"]] = value;
                    });
                }
                else {
                    $('select[name="no_dokumen_dmr"]').append('<option value="">-- Tidak ada data DMR --</option>');
                }

              }
          });
        }else{
           $('select[name="no_dokumen_dmr"]').empty();
           $('select[name="no_dokumen_dmr"]').append('<option disabled selected>-- Tidak ada data DMR --</option>');
        }
    }

    function update_judul_dokumen() {
        let no_dokumen_dmr = $('select[name="no_dokumen_dmr"]').val();
        if (no_dokumen_dmr != '') {
            let dmr_value = cache_data['dmr_list'][no_dokumen_dmr];
            $('#judul_dokumen').val(dmr_value ? dmr_value['judul_dokumen'] : '-- Kosong --');
        } else {
            $('#judul_dokumen').val('-- Kosong --');
        }
    }

    $(document).ready(function() {
        $('select[name="lokasi_id"]').on('change', function() {
            update_no_dokumen_dmr_list();
        });

        $('select[name="tahun_anggaran_id"]').on('change', function() {
            update_no_dokumen_dmr_list();
        });

        $('select[name="no_dokumen_dmr"]').on('change', function() {
            update_judul_dokumen();
        });

        update_no_dokumen_dmr_list();
        update_judul_dokumen();
    });
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $('.select2').select2();

        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
</script>

@endsection
