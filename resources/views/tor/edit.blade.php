@extends('layouts.app')

@section('css_page')

    <!-- CKeditor -->
    <!-- <script src="{{ asset('vendors/ckeditor/ckeditor.js') }}"></script> -->

    <!-- Datatables -->
    <link href="{{ asset('vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}" rel="stylesheet">
@endsection

@section('js_page')
    <!-- include summernote css/js -->
    <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.css" rel="stylesheet">
    <script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js"></script>

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
          <div>
            <div class="page-title">
              <h3> Edit TOR</h3>

                    <div class="row">
                        <div class="col-md-12">
                        <!-- <h4> Detail TOR</h4> -->
                            <form method="post" role="form" id="form" class="disable-on-submit" name="form" enctype="multipart/form-data" onsubmit="return checkForm(this)">
                                <input type="hidden" name="is_submitted" id="is_submitted" value="0">
                                <input type="hidden" name="_token" class="{{ csrf_token() }}">
                            <div>
                            {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-2"><label>Tahun Anggaran</label></div>
                                    <div class="col-md-3">
                                        <select class="form-control" name="tahun_anggaran_id" disabled="">
                                            <option>{{ $tor['tahun_anggaran'] }}</option>
                                        </select>
                                    </div>
                                </div><br>
                                <div class="row">
                                    <div class="col-md-2"><label>Strategi Bisnis</label></div>
                                    <div class="col-md-3">
                                        <select class="form-control" name="strategi_bisnis_id" disabled="">
                                            <option>{{$lokasi->distrik->strategi_bisnis->name}}</option>
                                        </select>
                                    </div>
                                </div><br>
                                <div class="row">
                                    <div class="col-md-2"><label>Distrik</label></div>
                                    <div class="col-md-3">
                                        <select class="form-control" name="distrik_id" disabled="">
                                            <option>{{$lokasi->distrik->name}}</option>
                                        </select>
                                    </div>
                                </div><br>
                                <!-- Lokasi -->
                                <div class="row {{ $errors->has('lokasi_id') ? 'has-error' : '' }}">
                                    <div class="col-md-2"><label>Lokasi</label></div>
                                    <div class="col-md-3">
                                        <select class="form-control" name="lokasi_id" disabled>
                                            <option selected>{{$lokasi['name']}}</option>
                                        </select>
                                    </div>
                                </div><br>
                                <!-- id dokumen dmr -->
                                <div class="row {{ $errors->has('no_dokumen_dmr') ? 'has-error' : '' }}">
                                    <div class="col-md-2"><label>ID Dokumen (DMR)</label></div>
                                    <div class="col-md-3">
                                        <select class="form-control" name="no_dokumen_dmr" disabled>
                                            <option selected>{{ $tor['no_dokumen_dmr'] }}</option>
                                        </select>
                                    </div>
                                </div><br>
                                <!-- id dokumen -->
                                <div class="row {{ $errors->has('no_dokumen') ? 'has-error' : '' }}">
                                    <div class="col-md-2"><label>ID Dokumen (TOR)</label></div>
                                    <div class="col-md-3">
                                        <input class="form-control" type="text" name="no_dokumen" value="{{ $tor['no_dokumen'] }}" readonly="readonly" disabled="">
                                        @if($errors->has('no_dokumen'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('no_dokumen') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div><br>

                                <!-- judul tor -->
                                <div class="row {{ $errors->has('judul_dokumen') ? 'has-error' : '' }}">
                                    <div class="col-md-2"><label>Judul TOR</label></div>
                                    <div class="col-md-3">
                                        <input disabled="" class="form-control" type="text" name="judul_dokumen" value="{{ $dmr['judul_dokumen'] ?? '-- DMR tidak ditemukan --' }}">
                                    </div>
                                </div><br>

                                <!-- dokumen tor -->
                                <div class="row hidden">
                                    <div class="col-md-2"><label>Dokumen TOR</label></div>
                                    <div class="col-md-8">
                                    <table class="table table-striped table-bordered table-hover">
                                        <tr>
                                            <td>Aksi</td>
                                            <td>Berkas</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="file" name="tor_filepath" value="{{ $tor['tor_filepath'] }}">
                                            </td>
                                            <td>
                                                <a href="{{ asset($tor['tor_filepath']) }}"> {{ basename($tor['tor_filepath']) }}</a>
                                            </td>
                                        </tr>
                                    </table>
                                    </div>
                                </div>
                                <br>

                                <!-- reviewer role -->
                                <div class="row">
                                    <div class="col-md-2"><label>Reviewer TOR</label></div>
                                    <div class="col-md-3">
                                        <input type="text" id="manager_role_id" class="form-control" name="manager_role_id" disabled="" value="{{ $current_tor_review_phase->nextPhase()->role->name }}">
                                    </div>
                                </div><br>

                                <!-- summary TOR -->
                                <div class="col-md-12">
                                    <div class="row"><label>Summary TOR</label></div>

                                    <div class="form-group hidden">
                                        <label>1.1 Pendahuluan</label>

                                       <textarea class="form-control summary-text"  name="pendahuluan"  id="pendahuluan" >{{ $tor['pendahuluan'] }}
                                       </textarea>
                                       <script>
                                         // Replace the <textarea id="editor1"> with a CKEditor
                                         // instance, using default configuration.
                                        // CKEDITOR.replace( 'pendahuluan' );

                                        $(document).ready(function() {
                                            $('#pendahuluan').summernote();
                                        });
                                       </script>
                                    </div>

                                    <div class="form-group">
                                       <label>1.2 Data teknis / Referensi Teknis</label>

                                       <textarea class="form-control summary-text required"  name="data_teknis"  id="data_teknis" >{{ $tor['data_teknis'] }}
                                       </textarea>
                                       <script>
                                         // Replace the <textarea id="editor1"> with a CKEditor
                                         // instance, using default configuration.
                                        // CKEDITOR.replace( 'data_teknis' );

                                        $(document).ready(function() {
                                            $('#data_teknis').summernote();
                                        });
                                       </script>
                                    </div>

                                    <div class="form-group">
                                       <label>1.3 Lingkup Pekerjaan / Scope of Work</label>

                                       <textarea class="form-control summary-text required"  name="lingkup_pekerjaan"  id="lingkup_pekerjaan" >{{ $tor['lingkup_pekerjaan'] }}
                                       </textarea>
                                       <script>
                                         // Replace the <textarea id="editor1"> with a CKEditor
                                         // instance, using default configuration.
                                        // CKEDITOR.replace( 'lingkup_pekerjaan' );

                                        $(document).ready(function() {
                                            $('#lingkup_pekerjaan').summernote();
                                        });
                                       </script>
                                    </div>

                                    <div class="form-group">
                                       <label>1.4 Performance Design</label>

                                       <textarea class="form-control summary-text required"  name="performance_desain"  id="performance_desain" >{{ $tor['performance_desain'] }}
                                       </textarea>
                                       <script>
                                         // Replace the <textarea id="editor1"> with a CKEditor
                                         // instance, using default configuration.
                                        // CKEDITOR.replace( 'performance_desain' );

                                        $(document).ready(function() {
                                            $('#performance_desain').summernote();
                                        });
                                       </script>
                                    </div>

                                    <div class="form-group">
                                       <label>1.5 Kualifikasi Calon Pelaksanaan Pekerjaan</label>

                                       <textarea class="form-control summary-text required"  name="kualifikasi_calon_pelaksanaan_pekerjaan"  id="kualifikasi_calon_pelaksanaan_pekerjaan" >{{ $tor['kualifikasi_calon_pelaksanaan_pekerjaan'] }}
                                       </textarea>
                                       <script>
                                         // Replace the <textarea id="editor1"> with a CKEditor
                                         // instance, using default configuration.
                                        // CKEDITOR.replace( 'kualifikasi_calon_pelaksanaan_pekerjaan' );

                                        $(document).ready(function() {
                                            $('#kualifikasi_calon_pelaksanaan_pekerjaan').summernote();
                                        });
                                       </script>
                                    </div>

                                    <div class="form-group hidden">
                                       <label>1.6 Detail Pelaksanaan Pekerjaan</label>

                                       <textarea class="form-control summary-text"  name="detail_pelaksanaan_pekerjaan"  id="detail_pelaksanaan_pekerjaan" >{{ $tor['detail_pelaksanaan_pekerjaan'] }}
                                       </textarea>
                                       <script>
                                         // Replace the <textarea id="editor1"> with a CKEditor
                                         // instance, using default configuration.
                                        // CKEDITOR.replace( 'detail_pelaksanaan_pekerjaan' );

                                        $(document).ready(function() {
                                            $('#detail_pelaksanaan_pekerjaan').summernote();
                                        });
                                       </script>
                                    </div>

                                    <div class="form-group hidden">
                                       <label>1.7 Kelengkapan Pelaksanaan Pekerjaan</label>

                                       <textarea class="form-control summary-text"  name="kelengkapan_pelaksanaan_pekerjaan"  id="kelengkapan_pelaksanaan_pekerjaan" >{{ $tor['kelengkapan_pelaksanaan_pekerjaan'] }}
                                       </textarea>
                                       <script>
                                         // Replace the <textarea id="editor1"> with a CKEditor
                                         // instance, using default configuration.
                                        // CKEDITOR.replace( 'kelengkapan_pelaksanaan_pekerjaan' );

                                        $(document).ready(function() {
                                            $('#kelengkapan_pelaksanaan_pekerjaan').summernote();
                                        });
                                       </script>
                                    </div>

                                    <div class="form-group hidden">
                                       <label>1.8 Aspek Keamanan dan K3</label>

                                       <textarea class="form-control summary-text"  name="aspek_keamanan_k3"  id="aspek_keamanan_k3" >{{ $tor['aspek_keamanan_k3'] }}
                                       </textarea>
                                       <script>
                                         // Replace the <textarea id="editor1"> with a CKEditor
                                         // instance, using default configuration.
                                        // CKEDITOR.replace( 'aspek_keamanan_k3' );

                                        $(document).ready(function() {
                                            $('#aspek_keamanan_k3').summernote();
                                        });
                                       </script>
                                    </div>

                                    <div class="form-group hidden">
                                       <label>1.9 Laporan Hasil Pekerjaan</label>

                                       <textarea class="form-control summary-text"  name="laporan_hasil_pekerjaan"  id="laporan_hasil_pekerjaan" >{{ $tor['laporan_hasil_pekerjaan'] }}
                                       </textarea>
                                       <script>
                                         // Replace the <textarea id="editor1"> with a CKEditor
                                         // instance, using default configuration.
                                        // CKEDITOR.replace( 'laporan_hasil_pekerjaan' );

                                        $(document).ready(function() {
                                            $('#laporan_hasil_pekerjaan').summernote();
                                        });
                                       </script>
                                    </div>

                                    <div class="form-group hidden">
                                       <label>2.0 Material Sisa atau Limbah</label>

                                       <textarea class="form-control summary-text"  name="material_sisa_limbah"  id="material_sisa_limbah" >{{ $tor['material_sisa_limbah'] }}
                                       </textarea>
                                       <script>
                                         // Replace the <textarea id="editor1"> with a CKEditor
                                         // instance, using default configuration.
                                        // CKEDITOR.replace( 'material_sisa_limbah' );

                                        $(document).ready(function() {
                                            $('#material_sisa_limbah').summernote();
                                        });
                                       </script>
                                    </div>

                                    <div class="form-group">
                                       <label>2.1 Quality Acceptance</label>

                                       <textarea class="form-control summary-text required"  name="quality_acceptance"  id="quality_acceptance" >{{ $tor['quality_acceptance'] }}
                                       </textarea>
                                       <script>
                                         // Replace the <textarea id="editor1"> with a CKEditor
                                         // instance, using default configuration.
                                        // CKEDITOR.replace( 'quality_acceptance' );

                                        $(document).ready(function() {
                                            $('#quality_acceptance').summernote();
                                        });
                                       </script>
                                    </div>

                                    <div class="form-group hidden">
                                       <label>2.2 Delivery</label>

                                       <textarea class="form-control summary-text"  name="delivery"  id="delivery" >{{ $tor['delivery'] }}
                                       </textarea>
                                       <script>
                                         // Replace the <textarea id="editor1"> with a CKEditor
                                         // instance, using default configuration.
                                        // CKEDITOR.replace( 'delivery' );

                                        $(document).ready(function() {
                                            $('#delivery').summernote();
                                        });
                                       </script>
                                    </div>

                                    <div class="form-group hidden">
                                       <label>2.3 Garansi</label>

                                       <textarea class="form-control summary-text"  name="garansi"  id="garansi" >{{ $tor['garansi'] }}
                                       </textarea>
                                       <script>
                                         // Replace the <textarea id="editor1"> with a CKEditor
                                         // instance, using default configuration.
                                        // CKEDITOR.replace( 'garansi' );

                                        $(document).ready(function() {
                                            $('#garansi').summernote();
                                        });
                                       </script>
                                    </div>

                                    <div class="form-group hidden">
                                       <label>2.4 Lain-lain</label>

                                       <textarea class="form-control summary-text"  name="lain_lain"  id="lain_lain" >{{ $tor['lain_lain'] }}
                                       </textarea>
                                       <script>
                                         // Replace the <textarea id="editor1"> with a CKEditor
                                         // instance, using default configuration.
                                        // CKEDITOR.replace( 'lain_lain' );

                                        $(document).ready(function() {
                                            $('#lain_lain').summernote();
                                        });
                                       </script>
                                    </div>

                                </div>
                                <!-- lampiran -->
                                <div class="row form-group">
                                    <div class="col-md-2"><label> Dokumen TOR </label></div>
                                    <div class="col-md-8">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                          <tr>
                                             <td>Aksi</td>
                                             <td>Berkas</td>
                                             <td>Hapus</td>
                                          </tr>
                                        </thead>
                                        <tbody>
                                         @foreach($torattachment as $da)
                                        <tr>
                                            <td>
                                                <input type="file" name="filepath[]" value="{{ $da['filepath'] }}">
                                                <input type="hidden" name="tor_attachment_id[]" value="{{ $da['id'] }}">
                                            </td>
                                            <td>
                                                <a href="{{ asset($da['filepath']) }}"> {{ basename($da['filepath']) }}</a>
                                            </td>
                                            <td>
                                            @if($da['filepath']!= null)
                                                <input type="checkbox" name="delete_attachments[]" value="{{$da['id']}}">
                                            @endif
                                            </td>
                                        </tr>
                                         @endforeach
                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                                <!-- button -->
                                <div class="col-md-12">
                                    <div class="col-md-offset-2">
                                    <button class="btn btn-danger" type="submit" onclick="submittor(0)">Update Draft</button>
                                    <button class="btn btn-success" type="submit" onclick="submittor()">Submit</button>
                                    {{-- <a href="javascript: submittor()" class="btn btn-success">Submit</a> --}}
                                    <a href="{{ url('/tor/daftar?tahun_anggaran='.(date('Y')).'&strategi_bisnis='.$lokasi->distrik->strategi_bisnis_id.'&distrik='.$lokasi->distrik_id.'&lokasi='.$lokasi->id) }}" class="btn btn-primary" type="reset">Kembali</a>
                                    </div>
                                </div>
                            </div>
                            </form>
                        </div> <!-- col-md-12 -->
                    </div> <!-- row -->
            </div> <!-- page-title -->
          </div>
        </div> <!-- main -->

    </div> <!-- col-md-12 col-sm-12 col-xs-12 -->
</div> <!-- row -->
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
@endsection