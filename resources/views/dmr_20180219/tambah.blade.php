@extends('layouts.app')

@section('css_page')
    <!-- CKeditor --> 
    <script src="{{ asset('vendors/ckeditor/ckeditor.js') }}"></script>

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
                         <form method="post" role="form" action="" onSubmit="return validationForm()" enctype="multipart/form-data" name="form" id="form">
                            <input type="hidden" name="_token" class="{{ csrf_token() }}">
                            <input type="hidden" name="is_submitted" id="is_submitted" value="0">
                            <div>
                            {{ csrf_field() }}
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
                                            <option disabled selected>-- Pilih Lokasi</option>
                                            @foreach($lokasi as $l)
                                                <option value="{{$l->id}}">{{$l->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div><br>
                                <!-- id dokumen -->                                
                                <div class="row {{ $errors->has('no_dokumen') ? 'has-error' : '' }}">
                                    <div class="col-md-2"><label>ID Dokumen</label></div>
                                    <div class="col-md-3">
                                        <input required class="form-control" type="text" name="no_dokumen" value="{{ old('no_dokumen') }}">
                                        @if($errors->has('no_dokumen'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('no_dokumen') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div><br>
                                <!-- no prk -->
                                <div class="row {{ $errors->has('no_prk') ? 'has-error' : '' }}">
                                    <div class="col-md-2"><label>No PRK</label></div>
                                    <div class="col-md-3">
                                        <input class="form-control" type="text" name="no_prk" value="{{ old('no_prk') }}" readonly="readonly">
                                        @if($errors->has('no_prk'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('no_prk') }}</strong>
                                            </span> 
                                        @endif
                                    </div>
                                </div> <br>
                                <!-- nama prk -->
                                <div class="row {{ $errors->has('nama_prk') ? 'has-error' : '' }}">
                                    <div class="col-md-2"><label>Nama PRK</label></div>
                                    <div class="col-md-3">
                                        <input class="form-control" type="text" name="nama_prk" value="{{ old('nama_prk') }}" readonly="readonly">
                                        @if($errors->has('nama_prk'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('nama_prk') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div><br>
                                <!-- anggaran prk -->
                            
                                <div class="row {{ $errors->has('jumlah_anggaran') ? 'has-error' : '' }}">
                                    <div class="col-md-2"><label>Anggaran PRK</label></div>
                                    <div class="col-md-3 input-group input-group-sm" style="padding-right: 10px; padding-left: 10px;">
                                            <label class="input-group-addon" >Rp (dalam ribu)</label>
                                            <input type="text" required class="form-control"  name="jumlah_anggaran" value="{{ old('jumlah_anggaran') }}">
                                        <!-- </div> -->
<!--                                         <label>Rp </label><input required class="form-control" type="text" name="jumlah_anggaran" value="{{ old('jumlah_anggaran') }}">
 -->                                        @if($errors->has('jumlah_anggaran'))
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

                                    <br>
                                    <div class="form-group">
                                       <label>1.1 Latar Belakang</label>
                                        
                                       <textarea value="{{ old('latar_belakang') }}" class="form-control" name="latar_belakang" id="latar_belakang" required="required" >
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
                                        </script>
                                    </div>
                                    <br>
                                
                                    <div class="form-group">
                                       <label>1.2 Sasaran dan Tujuan Kegiatan</label>

                                       <textarea required value="{{ old('sasaran_tujuan') }}" class="form-control" name="sasaran_tujuan" id="sasaran_tujuan" required="required" >
                                  
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
                                        </script>
                                    </div>
                                    <br>
                                 
                                    <div class="form-group">
                                        <label>1.3 Permasalahan</label>

                                       <textarea required value="{{ old('permasalahan') }}" class="form-control" name="permasalahan" id="permasalahan" required="required">
                                  
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
                                        </script>
                                    </div>
                                    <br>
                               
                                    <div class="form-group">
                                        <label>1.4 Alternatif Cara Pencarian Sasaran</label>

                                       <textarea required value="{{ old('alternatif_pencapaian') }}" class="form-control" name="alternatif_pencapaian" id="alternatif_pencapaian" required="required">
                                  
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
                                        </script>
                                    </div>
                                    <br>

                                    <div class="form-group">
                                       <label>1.5 Benefit Operasional</label>

                                       <textarea required value="{{ old('benefit_operasional') }}" class="form-control" name="benefit_operasional" id="benefit_operasional" required="required">
                                  
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
                                        </script>
                                    </div>
                                    <br>

                                    <div class="form-group">
                                       <label>1.6 Benefit Finansial</label>

                                       <textarea required value="{{ old('benefit_finansial') }}" class="form-control" name="benefit_finansial" id="benefit_finansial" required="required">
                                  
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
                                        </script>
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
                                      <button class="btn btn-danger" type="submit">Simpan sebagai Draft</button>
                                      <a href="javascript: submitdmr()" class="btn btn-success">Submit</a>
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
  function submitdmr(){
    var valid = 1;

    var messageLength = CKEDITOR.instances['latar_belakang'].getData().replace(/<[^>]*>/gi, '').length;
    if( !messageLength ) {
        alert( 'anda belum mengisi Latar Belakang' );
        e.preventDefault();
    }

    var messageLength = CKEDITOR.instances['sasaran_tujuan'].getData().replace(/<[^>]*>/gi, '').length;
    if( !messageLength ) {
        alert( 'anda belum mengisi Sasaran dan tujuan' );
        e.preventDefault();
    }

    var messageLength = CKEDITOR.instances['permasalahan'].getData().replace(/<[^>]*>/gi, '').length;
    if( !messageLength ) {
        alert( 'anda belum mengisi Permasalahan' );
        e.preventDefault();
    }

    var messageLength = CKEDITOR.instances['alternatif_pencapaian'].getData().replace(/<[^>]*>/gi, '').length;
    if( !messageLength ) {
        alert( 'anda belum mengisi Alternatif Pencapaian' );
        e.preventDefault();
    }

    var messageLength = CKEDITOR.instances['benefit_operasional'].getData().replace(/<[^>]*>/gi, '').length;
    if( !messageLength ) {
        alert( 'anda belum mengisi Benefit Operasional' );
        e.preventDefault();
        valid = 0;
    }

    if(valid == 1){
        document.getElementById("is_submitted").value = "1";
        document.forms["form"].submit();
    }
}
</script>
@endsection