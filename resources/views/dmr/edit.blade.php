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
              <h3> Edit DMR</h3>

            <!-- <div class="col-lg-12">
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
                            <form method="post" role="form" id="form" name="form" enctype="multipart/form-data" class="disable-on-submit" onsubmit="return checkForm(this)">
                                <input type="hidden" name="is_submitted" id="is_submitted" value="0">
                                <input type="hidden" name="_token" class="{{ csrf_token() }}">
                            <div>
                            {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-2"><label>Tahun Anggaran</label></div>
                                    <div class="col-md-3">
                                        <select class="form-control" name="tahun_anggaran_id" disabled="">
                                            <option>{{ $dmr['tahun_anggaran'] }}</option>
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
                                            <option disabled selected>{{$lokasi['name']}}</option>
                                        </select>
                                    </div>
                                </div><br>
                                <!-- id dokumen -->
                                <div class="row {{ $errors->has('no_dokumen') ? 'has-error' : '' }}">
                                    <div class="col-md-2"><label>ID Dokumen</label></div>
                                    <div class="col-md-3">
                                        <input required class="form-control" type="text" name="no_dokumen" value="{{ $dmr['no_dokumen'] }}" readonly="readonly">
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
                                        <input required class="form-control" type="text" name="judul_dokumen" value="{{ $dmr['judul_dokumen'] }}">
                                        @if($errors->has('judul_dokumen'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('judul_dokumen') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div><br>
                                <!-- no prk -->
                                <!-- <div class="row {{ $errors->has('no_prk') ? 'has-error' : '' }}">
                                    <div class="col-md-2"><label>No PRK</label></div>
                                    <div class="col-md-3">
                                        <input class="form-control" type="text" name="no_prk" value="{{ $dmr['no_prk'] }}" readonly="readonly">
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
                                        <input class="form-control" type="text" name="nama_prk" value="{{ $dmr['nama_prk'] }}" readonly="readonly">
                                         @if($errors->has('nama_prk'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('nama_prk') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div><br> -->

                                <!-- anggaran prk -->

                                <!-- <div class="row">
                                    <div class="col-md-2"><label>Anggaran PRK</label></div>
                                    <div class="col-md-3"><input required class="form-control" type="text" name="jumlah_anggaran" value="{{ $dmr['jumlah_anggaran'] }}"></div>
                                </div><br>
 -->                            
                                <div class="row {{ $errors->has('jumlah_anggaran') ? 'has-error' : '' }}">
                                    <div class="col-md-2"><label>Anggaran PRK</label></div>
                                    <div class="col-md-3 input-group input-group-sm" style="padding-right: 10px; padding-left: 10px;">
                                            <label class="input-group-addon" >Rp (dalam ribu)</label>
                                            <input type="text" required class="form-control"  name="jumlah_anggaran" value="{{ $dmr['jumlah_anggaran'] }}">
                                            <!-- <input required class="form-control" type="text" name="jumlah_anggaran" value="{{ $dmr['jumlah_anggaran'] }}"> -->
                                         @if($errors->has('jumlah_anggaran'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('jumlah_anggaran') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <br>
                                

                                <!-- dokumen dmr -->
                                <div class="row">
                                    <div class="col-md-2"><label>Dokumen DMR</label></div>
                                    <div class="col-md-8">
                                    <table class="table table-striped table-bordered table-hover">
                                        <tr>
                                            <td>Aksi</td>
                                            <td>Berkas</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="file" name="dmr_filepath" value="{{ $dmr['dmr_filepath'] }}">
                                            </td>
                                            <td>
                                                <!-- <a href="{{ asset($dmr['dmr_filepath']) }}"> {{ basename($dmr['dmr_filepath']) }}</a> -->
                                                <a href="{{ url('dmr/download_attachment') .'/'. $dmr['id'] }}"> {{ basename($dmr['dmr_filepath']) }}</a>
                                            </td>
                                        </tr>
                                    </table>
                                    </div>
                                </div>
                                <br>

                                <!-- summary DMR -->
                                <div class="col-md-12">
                                    <div class="row"><label>Summary DMR</label></div>

                                    <div class="form-group">
                                       <label>1.1 Latar Belakang</label>

                                       <textarea value="{{ $dmr['latar_belakang'] }} class="form-control summary-text" name="latar_belakang"  id="latar_belakang" >{{ $dmr['latar_belakang'] }}
                                       </textarea>
                                       <script>
                                         // Replace the <textarea id="editor1"> with a CKEditor
                                         // instance, using default configuration.
                                        // CKEDITOR.replace( 'latar_belakang' );

                                        $(document).ready(function() {
                                            $('#latar_belakang').summernote();
                                        });
                                       </script>
                                    </div>
                                    <br>

                                    <div class="form-group">
                                       <label>1.2 Sasaran dan Tujuan Kegiatan</label>

                                       <textarea value="{{ $dmr['sasaran_tujuan'] }}" class="form-control summary-text" name="sasaran_tujuan" id="sasaran_tujuan" >{{ $dmr['sasaran_tujuan'] }}
                                       </textarea>
                                       <script>
                                         // Replace the <textarea id="editor1"> with a CKEditor
                                         // instance, using default configuration.
                                        // CKEDITOR.replace( 'sasaran_tujuan' );

                                        $(document).ready(function() {
                                            $('#sasaran_tujuan').summernote();
                                        });
                                       </script>
                                    </div>
                                    <br>

                                    <div class="form-group">
                                        <label>1.3 Permasalahan</label>

                                       <textarea value="{{ $dmr['permasalahan'] }}" class="form-control summary-text" name="permasalahan" id="permasalahan" >{{ $dmr['permasalahan'] }}
                                       </textarea>
                                       <script>
                                         // Replace the <textarea id="editor1"> with a CKEditor
                                         // instance, using default configuration.
                                        // CKEDITOR.replace( 'permasalahan' );

                                        $(document).ready(function() {
                                            $('#permasalahan').summernote();
                                        });
                                       </script>
                                    </div>
                                    <br>

                                    <div class="form-group">
                                        <label>1.4 Alternatif Cara Pencarian Sasaran</label>

                                       <textarea value="{{ $dmr['alternatif_pencapaian'] }}" class="form-control summary-text" name="alternatif_pencapaian" id="alternatif_pencapaian" >{{ $dmr['alternatif_pencapaian'] }}
                                       </textarea>
                                       <script>
                                         // Replace the <textarea id="editor1"> with a CKEditor
                                         // instance, using default configuration.
                                        // CKEDITOR.replace( 'alternatif_pencapaian' );

                                        $(document).ready(function() {
                                            $('#alternatif_pencapaian').summernote();
                                        });
                                       </script>
                                    </div>
                                    <br>

                                    <div class="form-group">
                                       <label>1.5 Benefit Operasional</label>

                                       <textarea value="{{ $dmr['benefit_operasional'] }}" class="form-control summary-text" name="benefit_operasional" id="benefit_operasional">{{ $dmr['benefit_operasional'] }}
                                       </textarea>
                                       <script>
                                         // Replace the <textarea id="editor1"> with a CKEditor
                                         // instance, using default configuration.
                                        // CKEDITOR.replace( 'benefit_operasional' );

                                        $(document).ready(function() {
                                            $('#benefit_operasional').summernote();
                                        });
                                       </script>
                                    </div>
                                    <br>

                                    <div class="form-group">
                                       <label>1.6 Benefit Finansial</label>

                                       <textarea value="{{ $dmr['benefit_finansial'] }}" class="form-control summary-text" name="benefit_finansial" id="benefit_finansial" >{{ $dmr['benefit_finansial'] }}
                                       </textarea>
                                       <script>
                                         // Replace the <textarea id="editor1"> with a CKEditor
                                         // instance, using default configuration.
                                        // CKEDITOR.replace( 'benefit_finansial' );

                                        $(document).ready(function() {
                                            $('#benefit_finansial').summernote();
                                        });
                                       </script>
                                    </div>
                                    <br>

                                </div>
                                <!-- lampiran -->
                                <div class="form-group">
                                    <div class="col-md-2"><label> Lampiran </label></div>
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
                                         @foreach($dmrattachment as $da)
                                        <tr>
                                            <td>
                                                <input type="file" name="filepath[]" value="{{ $da['filepath'] }}">
                                                <input type="hidden" name="dmr_attachment_id[]" value="{{ $da['id'] }}">
                                            </td>
                                            <td>
                                                <a href="{{ url('dmr/dmr_attachment') .'/'. $da['id'] }}"> {{ basename($da['filepath']) }}</a>
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
                                    @if($dmr['dmr_review_phase_id'] != 4)
                                    <button class="btn btn-danger" type="submit" onclick="submitdmr(0)">Update Draft</button>
                                    @endif
                                    <button class="btn btn-success" type="submit" onclick="submitdmr()">Submit</button>
                                    {{--
                                    <a href="javascript: submitdmr()" class="btn btn-success" onclick="submitdmr()">Submit</a>
                                    --}}
                                    <a href="{{ url('/dmr/daftar?tahun_anggaran='.(date('Y')+1).'&strategi_bisnis='.$lokasi->distrik->strategi_bisnis_id.'&distrik='.$lokasi->distrik_id.'&lokasi='.$lokasi->id) }}" class="btn btn-primary" type="reset">Kembali</a>
                                    </div>
                                </div>
                            </div>
                            </form>
                            </table>
                            @if($dmr_review)
                            <br />
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
                            @endif
                        </div> <!-- col-md-12 -->
                    </div> <!-- row -->
            </div> <!-- page-title -->
          </div>
        </div> <!-- main -->

    </div> <!-- col-md-12 col-sm-12 col-xs-12 -->
</div> <!-- row -->
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
        $('button[type="reset"]', form).prop('disabled', true);
    }
    return is_valid;
}
</script>
@endsection
