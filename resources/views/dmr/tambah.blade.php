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
        <!-- <div role="main"> -->
          <div>
            <div class="page-title">
              <h3> Tambah DMR</h3>

        <!--  <div class="col-lg-12">
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
                               <input type="text " id="tahun "  class="form-control" readonly="">
                             </div>
                           </div>

                           <div class="form-group ">
                             <label class="col-md-2 col-md-4 " >Struktur Bisnis</label>
                             <div class="col-md-6 col-sm-6 col-xs-12 ">
                               <input type="text " id="struktur-bisnis "  class="form-control col-md-7" readonly="">
                             </div>
                           </div>

                           <div class="form-group ">
                             <label class="col-md-2 col-md-4">Distrik</label>
                             <div class="col-md-6 col-sm-6 col-xs-12 ">
                               <input id="distrik " class="form-control col-md-7 col-xs-12 " type="text" readonly="">
                             </div>
                           </div>

                           <div class="form-group ">
                             <label class="col-md-2 col-md-4">Lokasi</label>
                             <div class="col-md-6 col-sm-6 col-xs-12">
                                 <input id="lokasi" class="form-control col-md-7 " type="text" readonly="">
                             </div>
                         </div>

                         </form>
                        </div>
                      </div>
                   </div>
                </div>
            </div> -->

                <div class="row">
                    <div class="col-md-12">
                        <!-- <h4> Detail DMR</h4> -->
                         <form method="post" role="form" action="" enctype="multipart/form-data" name="form" id="form" class="disable-on-submit" onsubmit="return checkForm(this)">
                            <input type="hidden" name="_token" class="{{ csrf_token() }}">
                            <input type="hidden" name="is_submitted" id="is_submitted" value="0">
                            <div>
                            {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-2"><label>Tahun Anggaran</label></div>
                                    <div class="col-md-3">
                                        <select id="tahun_anggaran_id" class="form-control" name="tahun_anggaran_id" required>
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
                                        <select required class="form-control" id="lokasi_id" name="lokasi_id">
                                            <option value="" disabled selected>-- Pilih Lokasi --</option>
                                            @foreach($lokasi as $l)
                                                <option value="{{$l->id}}" {{ old('lokasi_id') == $l->id ? 'selected' : '' }}>{{$l->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div><br>
                                <!-- id dokumen -->
                                <div class="row {{ $errors->has('no_dokumen') ? 'has-error' : '' }}">
                                    <div class="col-md-2"><label>ID Dokumen</label></div>
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
                                    <div class="col-md-2"><label>Judul DMR</label></div>
                                    <div class="col-md-3">
                                        <input required class="form-control" type="text" name="judul_dokumen" value="{{ old('judul_dokumen') }}">
                                        @if($errors->has('judul_dokumen'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('judul_dokumen') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div><br>
                                <div class="row {{ $errors->has('no_prk_form') ? 'has-error' : '' }}" hidden>
                                    <div class="col-md-2"><label>No. PRK Form</label></div>
                                    <div class="col-md-3">
                                        <input id="no_prk_form" class="form-control" type="text" name="no_prk_form" readonly>
                                    </div>
                                </div><br>
                                <div class="row {{ $errors->has('anggaran_prk_form') ? 'has-error' : '' }}">
                                    <div class="col-md-2"><label>Anggaran PRK Form</label></div>
                                    <div class="col-md-3">
                                        <input id="anggaran_prk_form" required class="form-control" type="text" name="anggaran_prk_form" readonly>
                                    </div>
                                </div><br>
                                <!-- no prk -->
                                <!-- <div class="row {{ $errors->has('no_prk') ? 'has-error' : '' }}">
                                    <div class="col-md-2"><label>No PRK</label></div>
                                    <div class="col-md-3">
                                        <input class="form-control" type="text" name="no_prk" value="{{ old('no_prk') }}" readonly="readonly">
                                        @if($errors->has('no_prk'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('no_prk') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div> <br> -->
                                <!-- nama prk -->
                                <!-- <div class="row {{ $errors->has('nama_prk') ? 'has-error' : '' }}">
                                    <div class="col-md-2"><label>Nama PRK</label></div>
                                    <div class="col-md-3">
                                        <input class="form-control" type="text" name="nama_prk" value="{{ old('nama_prk') }}" readonly="readonly">
                                        @if($errors->has('nama_prk'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('nama_prk') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div><br> -->

                                <!-- anggaran prk -->
                                <div class="row {{ $errors->has('jumlah_anggaran') ? 'has-error' : '' }}">
                                    <div class="col-md-2"><label>Anggaran PRK Input</label></div>
                                    <div class="col-md-3 input-group input-group-sm" style="padding-right: 10px; padding-left: 10px;">
                                            <label class="input-group-addon" >Rp (dalam ribuan)</label>
                                            <input type="text" required class="form-control"  name="jumlah_anggaran" value="{{ old('jumlah_anggaran') }}">
                                        <!-- </div> -->
                                        <!-- <label>Rp </label><input required class="form-control" type="text" name="jumlah_anggaran" value="{{ old('jumlah_anggaran') }}">-->
                                        @if($errors->has('jumlah_anggaran'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('jumlah_anggaran') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <br>

                                <!-- dokumen dmr -->
                                <div class="row" {{ $errors->has('dmr_filepath') ? 'has-error' : '' }}>
                                    <div class="col-md-2"><label>Dokumen DMR</label></div>
                                    <div class="col-md-3">
                                    <input type="file" required="required" name="dmr_filepath" id="dmr_filepath" value="{{ old('dmr_filepath') }}">
                                        @if($errors->has('dmr_filepath'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('dmr_filepath') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div><br>

                                <!-- summary DMR -->
                                <div class="col-md-12">
                                    <div class="row"><label>Summary DMR</label></div>

                                    <div class="form-group">
                                       <label>1.1 Latar Belakang</label>

                                        <!-- <form method="post"> -->
                                          <textarea id="latar_belakang" name="latar_belakang" class="form-control summary-text">{{ old('latar_belakang') }}</textarea>
                                        <!-- </form> -->

                                        <!-- <div id="latar_belakang"></div> -->
                                        <script>
                                          $(document).ready(function() {
                                              $('#latar_belakang').summernote();
                                          });
                                        </script>
                                       <!-- <textarea value="{{ old('latar_belakang') }}" class="form-control" name="latar_belakang" id="latar_belakang" required="required" >
                                       </textarea>

                                        <script>
                                            CKEDITOR.replace( 'latar_belakang' );
                                            $("form").submit( function(e) {
                                                var messageLength = CKEDITOR.instances['latar_belakang'].getData().replace(/<[^>]*>/gi, '').length;
                                                if( !messageLength ) {
                                                    alert( 'anda belum mengisi Latar Belakang' );
                                                    e.preventDefault();
                                                }
                                            });
                                        </script> -->
                                    </div>
                                    <br>

                                    <div class="form-group">
                                       <label>1.2 Sasaran dan Tujuan Kegiatan</label>
                                        <!-- <form method="post"> -->
                                          <textarea id="sasaran_tujuan" class="form-control summary-text" name="sasaran_tujuan">{{ old('sasaran_tujuan') }}</textarea>
                                        <!-- </form> -->

                                        <!-- <div id="sasaran_tujuan"></div> -->
                                        <script>
                                          $(document).ready(function() {
                                              $('#sasaran_tujuan').summernote();
                                          });
                                        </script>

                                       <!-- <textarea required value="{{ old('sasaran_tujuan') }}" class="form-control" name="sasaran_tujuan" id="sasaran_tujuan" required="required" >

                                       </textarea>
                                       <script>
                                            CKEDITOR.replace( 'sasaran_tujuan' );
                                            $("form").submit( function(e) {
                                                var messageLength = CKEDITOR.instances['sasaran_tujuan'].getData().replace(/<[^>]*>/gi, '').length;
                                                if( !messageLength ) {
                                                    alert( 'anda belum mengisi Sasaran dan tujuan' );
                                                    e.preventDefault();
                                                }
                                            });
                                        </script> -->
                                    </div>
                                    <br>

                                    <div class="form-group">
                                        <label>1.3 Permasalahan</label>

                                        <!-- <form method="post"> -->
                                          <textarea id="permasalahan" class="form-control summary-text" name="permasalahan">{{ old('permasalahan') }}</textarea>
                                        <!-- </form> -->

                                        <!-- <div id="permasalahan"></div> -->
                                        <script>
                                          $(document).ready(function() {
                                              $('#permasalahan').summernote();
                                          });
                                        </script>

                                       <!-- <textarea required value="{{ old('permasalahan') }}" class="form-control" name="permasalahan" id="permasalahan" required="required">

                                       </textarea>
                                       <script>
                                            CKEDITOR.replace( 'permasalahan' );
                                            $("form").submit( function(e) {
                                                var messageLength = CKEDITOR.instances['permasalahan'].getData().replace(/<[^>]*>/gi, '').length;
                                                if( !messageLength ) {
                                                    alert( 'anda belum mengisi Permasalahan' );
                                                    e.preventDefault();
                                                }
                                            });
                                        </script> -->
                                    </div>
                                    <br>

                                    <div class="form-group">
                                        <label>1.4 Alternatif Cara Pencarian Sasaran</label>

                                        <!-- <form method="post"> -->
                                          <textarea id="alternatif_pencapaian" class="form-control summary-text" name="alternatif_pencapaian">{{ old('alternatif_pencapaian') }}</textarea>
                                        <!-- </form> -->

                                        <!-- <div id="alternatif_pencapaian"></div> -->
                                        <script>
                                          $(document).ready(function() {
                                              $('#alternatif_pencapaian').summernote();
                                          });
                                        </script>

                                       <!-- <textarea required value="{{ old('alternatif_pencapaian') }}" class="form-control" name="alternatif_pencapaian" id="alternatif_pencapaian" required="required">

                                       </textarea>
                                       <script>
                                            CKEDITOR.replace( 'alternatif_pencapaian' );
                                            $("form").submit( function(e) {
                                                var messageLength = CKEDITOR.instances['alternatif_pencapaian'].getData().replace(/<[^>]*>/gi, '').length;
                                                if( !messageLength ) {
                                                    alert( 'anda belum mengisi Alternatif Pencapaian' );
                                                    e.preventDefault();
                                                }
                                            });
                                        </script> -->
                                    </div>
                                    <br>

                                    <div class="form-group">
                                       <label>1.5 Benefit Operasional</label>

                                       <!-- <form method="post"> -->
                                          <textarea id="benefit_operasional" class="form-control summary-text" name="benefit_operasional">{{ old('benefit_operasional') }}</textarea>
                                        <!-- </form> -->

                                        <!-- <div id="benefit_operasional"></div> -->
                                        <script>
                                          $(document).ready(function() {
                                              $('#benefit_operasional').summernote();
                                          });
                                        </script>

                                       <!-- <textarea required value="{{ old('benefit_operasional') }}" class="form-control" name="benefit_operasional" id="benefit_operasional" required="required">

                                       </textarea>
                                       <script>
                                            CKEDITOR.replace( 'benefit_operasional' );
                                            $("form").submit( function(e) {
                                                var messageLength = CKEDITOR.instances['benefit_operasional'].getData().replace(/<[^>]*>/gi, '').length;
                                                if( !messageLength ) {
                                                    alert( 'anda belum mengisi Benefit Operasional' );
                                                    e.preventDefault();
                                                }
                                            });
                                        </script> -->
                                    </div>
                                    <br>

                                    <div class="form-group">
                                       <label>1.6 Benefit Finansial</label>

                                       <!-- <form method="post"> -->
                                          <textarea id="benefit_finansial" class="form-control summary-text" name="benefit_finansial">{{ old('benefit_finansial') }}</textarea>
                                        <!-- </form> -->

                                        <!-- <div id="benefit_finansial"></div> -->
                                        <script>
                                          $(document).ready(function() {
                                              $('#benefit_finansial').summernote();
                                          });
                                        </script>

                                       <!-- <textarea required value="{{ old('benefit_finansial') }}" class="form-control" name="benefit_finansial" id="benefit_finansial" required="required">

                                       </textarea>
                                       <script>
                                            CKEDITOR.replace( 'benefit_finansial' );
                                            $("form").submit( function(e) {
                                                var messageLength = CKEDITOR.instances['benefit_finansial'].getData().replace(/<[^>]*>/gi, '').length;
                                                if( !messageLength ) {
                                                    alert( 'anda belum mengisi Benefit Finansial' );
                                                    e.preventDefault();
                                                }
                                            });
                                        </script> -->
                                    </div>
                                </div>
                                <!-- lampiran -->

                                <br>
                                <div class="row">
                                    <div class="control-label col-md-2"><label>Lampiran</label></div>
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
                                        <button class="btn btn-danger" type="submit" onclick="submitdmr(0)">Simpan sebagai Draft</button>
                                        <button class="btn btn-success" type="submit" onclick="submitdmr()">Submit</button>
                                      <a href="{{ url('/dmr/daftar?tahun_anggaran='.(date('Y')+1).'&strategi_bisnis='.$distrik->strategi_bisnis_id.'&distrik='.$distrik->id) }}" class="btn btn-primary" type="reset">Kembali</a>
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
  function submitdmr(value = 1){
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
    let $summary_text = $('.summary-text');
    // console.log($summary_text);
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
    // console.log(is_valid);
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

<script type="text/javascript">
  $(document).ready(function(){
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
});
</script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<script>
$(document).ready(function() {

   $("#no_dokumen").keyup(function(){

       var id_dokumen = $("#no_dokumen").val().trim();
       var tahun = $('select[name="tahun_anggaran_id"]').val();
       var id_lokasi = $('select[name="lokasi_id"]').val();

       if (id_dokumen && id_lokasi) {
           var url;
           url =  '{{URL::to("output/anggaran_no_prk.ajax")}}';

           $.ajax({
               url : url,
               type: "POST",
               data: { tahun:tahun, id_dokumen:id_dokumen, id_lokasi:id_lokasi, _token: '{!! csrf_token() !!}' },
               dataType: "JSON",
               async: false,

               success: function(data) {
                   // console.log('hai');

                   // $('#no_prk_form').val(data.data_prk);
                   $('#anggaran_prk_form').val(data.data_anggaran);
               }
           });
        }
    });
 });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $('select[name="lokasi_id"]').on('change', function() {

        var id_dokumen = $("#no_dokumen").val().trim();
        var tahun = $('select[name="tahun_anggaran_id"]').val();
        var id_lokasi = $('select[name="lokasi_id"]').val();

        if (id_dokumen && id_lokasi) {
            var url;
            url =  '{{URL::to("output/anggaran_no_prk.ajax")}}';

            $.ajax({
                url : url,
                type: "POST",
                data: { tahun:tahun, id_dokumen:id_dokumen, id_lokasi:id_lokasi, _token: '{!! csrf_token() !!}' },
                dataType: "JSON",
                // async: false,

                success: function(data) {
                    // console.log('hai');

                    // $('#no_prk_form').val(data.data_prk);
                    $('#anggaran_prk_form').val(data.data_anggaran);
                }
            });
         }
    });
});
</script>

@endsection
