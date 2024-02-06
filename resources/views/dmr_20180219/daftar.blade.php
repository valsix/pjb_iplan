@extends('layouts.app')

@section('css_page')

    <!-- searching -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

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
<!--         <div role="main"> -->
        <div>
            <div class="page-title">
            <!-- <div> -->
              <h3> Daftar DMR</h3>

              
            </div> <!-- page-title -->
            <!-- </div> -->
              @if(session('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  {{ session('success') }}
                </div>
              @endif
          <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                     Pencarian
                </div>
                <div class="panel-default"> 
                    <br>
                    <div class="row">
                        <div class="col-lg-12">
                            <form>
                                <div class="col-md-2"><label>Tahun Anggaran</label></div>
                                <div class="col-md-4">
                                    <select class="form-control" name="tahun_anggaran" required="">
                                       <option selected="" disabled="">-- Pilih Tahun Anggaran--</option>
                                       @for($i=date('Y')+1; $i > date('Y'); $i--)
                                          <option value="{{$i}}" <?php if($input_tahun != null)echo( $input_tahun == $i? 'selected=""' : '' )?> >{{$i}}</option>
                                       @endfor
                                    </select>
                                </div>
                                <div class="col-md-2"><label>Struktur Bisnis</label></div>
                                <div class="col-md-3">

                                    <select class="form-control" name="strategi_bisnis" required="">
                                       <option>-- Pilih Struktur Bisnis --</option>
                                        @foreach ($Sb as $sbs => $value)
                                         <option value="{{ $value->id }}" <?php if($input_sb != null) echo( $input_sb == $value->id ? 'selected=""' : '' )?> > {{ $value->name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                               
                                <br>
                                <br>
                                <div class="col-md-2"><label>Distrik</label></div>
                                <div class="col-md-4">
                                    <select class="form-control" name="distrik" required="">
                                        <option>-- Pilih Distrik --</option>
                                       @if($input_distrik != null)
                                          @foreach($distrik as $value)
                                              <option value="{{$value->id}}" <?php if($input_distrik != null) echo( $input_distrik == $value->id ? 'selected=""' : '' )?> >{{$value->name}}</option>
                                          @endforeach
                                       @endif
                                    </select>
                                </div>
            
                                <script type="text/javascript">
                                    $(document).ready(function() {
                                        $('select[name="strategi_bisnis"]').on('change', function() {
                                            var strategi_bisnisID = $(this).val();
                                            $('select[name="distrik"]').empty();
                                            $('select[name="lokasi"]').empty();

                                            if(strategi_bisnisID) {
                                                $.ajax({
                                                    // url: '/dmr/daftar/ajax/'+strategi_bisnisID,
                                                    url: "{{ url('/dmr/daftar/ajax/') }}/"+strategi_bisnisID,
                                                    type: "GET",
                                                    dataType: "json",
                                                    success:function(data) {
                                                // console.log(data);
                                                      $('select[name="distrik"]').empty();
                                                      $.each(data, function(sb, value) {
                                                          $('select[name="distrik"]').append('<option value="'+ value["id"] +'">'+ value["name"] +'</option>');
                                                      });

                                                    }
                                                });
                                            }else{
                                                $('select[name="distrik"]').empty();
                                            }
                                        }); 
                                    });
                                </script>


                                <div class="col-md-2"><label> Lokasi</label></div>
                                <div class="col-md-3">
                                    <select class="form-control" name="lokasi">
                                       <option selected="" disabled="">-- Pilih Lokasi --</option>
                                       @if($input_distrik != null)
                                          @foreach($lokasi as $value)
                                              <option value="{{$value->id}}" <?php if($input_lokasi != null) echo( $input_lokasi == $value->id ? 'selected=""' : '' )?> >{{$value->name}}</option>
                                          @endforeach
                                       @endif
                                    </select>
                                </div>

                                <script type="text/javascript">
                                    $(document).ready(function() {
                                        $('select[name="distrik"]').on('change', function() {
                                            var lokasiID = $(this).val();
                                            $('select[name="lokasi"]').empty();
                                            
                                            if(lokasiID) {
                                                $.ajax({
                                                    // url: '/dmr/daftar/ajax2/'+lokasiID,
                                                    url: "{{ url('dmr/daftar/ajax2/') }}/"+lokasiID,
                                                    type: "GET",
                                                    dataType: "json",
                                                    success:function(data) {
                        
                                                      $('select[name="lokasi"]').empty();
                                                      console.log(data);
                                                      // $('select[name="lokasi"]').append('<option value="">==Silahkan Pilih Lokasi==</option>');
                                                      $.each(data, function(ad , value) {
                                                      console.log(ad);
                                                          $('select[name="lokasi"]').append('<option value="'+ value["id"] +'">'+ value["name"] +'</option>');
                                                      });

                                                    }
                                                });
                                            }else{
                                                $('select[name="lokasi"]').empty();

                                            }
                                        });
                                    });
                                </script>

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
            </div>
        </div>

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



          <div class="x_content">
                  <div>
                    <a href="create" class="btn btn-primary"><span class="glyphicon glyphicon-plus"> </span> Tambah DMR</a>
                  </div>
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="datatable"> <!-- id="datatable" -->
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Strategi Bisnis</th>
                          <th>Distrik</th>
                          <th>Lokasi</th>
                          <th>No Dokumen</th>
                          <th>No PRK</th>
                          <th>Nama PRK</th>
                          <th>DMR Status</th>
                          <th>Review Oleh</th>
                          <th>Review Status</th>
                          <th>Dokumen</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php $no=1; ?>
                      @if($dmr!= null)
                      @foreach($dmr as $item)
                        <tr>
                          <td>{{ $no++ }}</td>
                          <td>{{ $item->lokasi->distrik->strategi_bisnis->name }}</td>
                          <td>{{ $item->lokasi->distrik->name }}</td>
                          <td>{{ $item->lokasi->name }}</td>
                          <td>{{ $item->no_dokumen }}</td>
                          <td>{{ $item->no_prk }}</td>
                          <td>{{ $item->nama_prk }}</td>
                          <td>
                              @if($item->is_submitted == 0)
                                  N/A
                              @else
                                  Submitted
                              @endif  
                          </td>
                          <td>
                              @if($item->is_submitted == 0)
                              -
                              @else
                                  {{$item->dmr_review_phase->role->name}}
                              @endif
                          </td>
                          <td>
                              @if($item->is_submitted == 0)
                              -
                              @else
                                  {{$item->dmr_review_status->name}}
                              @endif
                          </td>
                          <td class="text-center"> <!-- dokumen upload -->
                            <a href="{{ asset($item->dmr_filepath) }}" class="btn btn-info btn-xs" data-toggle="tooltip" title="Download">
                              <span class="glyphicon glyphicon-download-alt"></span>
                            </a>
                          </td>
                          <td> <!-- aksi -->
                            <a href="detail/{{ $item['id'] }}" class="btn btn-primary btn-xs" data-toggle="tooltip" title="detail"> 
                              <span class="glyphicon glyphicon-eye-open "></span>
                            </a>

                            @if($item->is_submitted == 0 || $item->dmr_review_status->name == "Revised")      
                            <a href="update/{{ $item['id'] }}" class="btn btn-success btn-xs" data-toggle="tooltip" title="edit"> 
                             <span class="glyphicon glyphicon-edit"></span>
                            </a>

                            @endif
                            @if($item->is_submitted == 0)      
                            <a onclick="return konfirmasi()" href="delete/{{ $item['id'] }}" class="btn btn-danger btn-xs" data-toggle="tooltip" title="hapus"> 
                              <span class="glyphicon glyphicon-trash"></span> 
                            </a>
                            @endif
                          </td>
                        </tr>
                        @endforeach

                        <script type="text/javascript" language="JavaScript">
                          function konfirmasi()
                          {
                            tanya = confirm("Anda Yakin Akan Menghapus Data ?");
                            if (tanya == true) return true;
                            else return false;
                          }
                        </script>
                      @endif
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
    </div> 
</div> 

<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});
</script>

@endsection