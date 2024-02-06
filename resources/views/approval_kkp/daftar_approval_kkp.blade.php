@extends('layouts.app')

@section('css_page')
    <!-- searching -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

    <!-- Datatables -->
    <!-- <link href="{{ asset('vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}" rel="stylesheet"> -->
@endsection

@section('js_page')
    <!-- <script src="{{ asset('vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
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
    <script src="{{ asset('vendors/pdfmake/build/vfs_fonts.js') }}"></script> -->

    <script type="text/javascript">
        $('#datatable').dataTable( {
            "searching": true,
            "aLengthMenu": [[10, 25, 50, 100, -1],
                         [10, 25, 50, 100, "All"]]
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
                <h3> Approval KKP</h3>
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {{ session('success') }}
                    </div>
                @endif
              </div>

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
                                <div class="col-md-3">
                                    <select class="form-control" name="tahun_anggaran" required>
                                       <option selected="" disabled="" value="">-- Pilih Tahun Anggaran--</option>
                                       @for($i=date('Y')-5; $i <= date('Y')+20; $i++)
                                          <option value="{{$i}}" <?php if($input_tahun != null)echo( $input_tahun == $i? 'selected=""' : '' )?> >{{$i}}</option>
                                       @endfor
                                    </select>
                                </div>
                                <div class="col-md-2"><label>Struktur Bisnis</label></div>
                                <div class="col-md-4">
                                    <select class="form-control" name="strategi_bisnis" required>
                                       <option disabled="" selected="" value="">-- Pilih Struktur Bisnis --</option>
                                        @foreach ($Sb as $sbs => $value)
                                         <option value="{{ $value->id }}" <?php if($input_sb != null) echo( $input_sb == $value->id ? 'selected=""' : '' )?> > {{ $value->name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <br /><br />
                                <div class="col-md-2"><label>Distrik</label></div>
                                <div class="col-md-3">
                                    <select class="form-control" name="distrik" required>
                                        <option disabled="" selected="" value="">-- Pilih Distrik --</option>
                                       @if($input_distrik != null)
                                          @foreach($distrik as $value)
                                              <option value="{{$value->id}}" <?php if($input_distrik != null) echo( $input_distrik == $value->id ? 'selected=""' : '' )?> >{{$value->name}}</option>
                                          @endforeach
                                       @endif
                                    </select>
                                </div>
                                <div class="col-md-2"><label>Lokasi</label></div>
                                <div class="col-md-4">
                                    <select class="form-control" name="lokasi" required>
                                       <option disabled="" selected="" value="">-- Pilih Lokasi --</option>
                                       @if($input_distrik != null)
                                          @foreach($lokasi as $value)
                                              <option value="{{$value->id}}" <?php if($input_lokasi != null) echo( $input_lokasi == $value->id ? 'selected=""' : '' )?> >{{$value->name}}</option>
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
                                                    // url: '/approval_dmr/daftar/ajax/'+strategi_bisnisID,
                                                    url: "{{ url('/approval_kkp/daftar/ajax/') }}/"+strategi_bisnisID,
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

                                <script type="text/javascript">
                                    $(document).ready(function() {
                                        $('select[name="distrik"]').on('change', function() {
                                            var lokasiID = $(this).val();
                                            $('select[name="lokasi"]').empty();

                                            if(lokasiID) {
                                                if (lokasiID==21 || lokasiID==28) 
                                                {
                                                    $('.bagianclass').show();
                                                    $('select[name="bagian"]').attr('required', '');
                                                    
                                                        // $.ajax({
                                                        //     // url: '/dmr/daftar/ajax/'+strategi_bisnisID,
                                                        //     url: "{{ url('/dmr/daftar/ajax/') }}/"+strategi_bisnisID,
                                                        //     type: "GET",
                                                        //     dataType: "json",
                                                        //     success:function(data) {
                                                        // // console.log(data);
                                                        //       $('select[name="bagian"]').empty();
                                                        //       $('select[name="bagian"]').append('<option value="">-- Pilih Bagian --</option>');
                                                        //       $.each(data, function(sb, value) {
                                                        //           $('select[name="distrik"]').append('<option value="'+ value["id"] +'">'+ value["name"] +'</option>');
                                                        //       });

                                                        //     }
                                                        // });
                                                    
                                                }
                                                else
                                                {
                                                    $('.bagianclass').hide();
                                                    $('select[name="bagian"]').removeAttr('required'); 
                                                    $('select[name="bagian"]').val('');
                                                }

                                                $.ajax({
                                                    // url: '/approval_dmr/daftar/ajax2/'+lokasiID,
                                                    url: "{{ url('/approval_kkp/daftar/ajax2/') }}/"+lokasiID,
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

                                <div class="col-md-2 bagianclass"><label>Bagian</label></div>
                                <div class="col-md-3 bagianclass">
                                    <select class="form-control" name="bagian" required>
                                        <option disabled="" value="">-- Pilih Bagian --</option>
                                       @if(count($bagian)>0)
                                          @foreach($bagian as $value)
                                              <option value="{{$value->id}}" <?php if($input_bagian != null) echo( $input_bagian == $value->id ? 'selected=""' : '' )?> >{{$value->name}}</option>
                                          @endforeach
                                       @endif
                                    </select>
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

              <br>
                <div class="x_content">
                  <table class="table table-striped table-bordered table-hover" id="datatable">
                    <thead style="background:#2A3F54;color:white">
                        <tr>
                          <th style="vertical-align: middle;">No</th>
                          <!-- <th style="vertical-align: middle;">Tahun Anggaran</th> -->
                          <!-- <th style="vertical-align: middle;">Strategi Bisnis</th> -->
                          <!-- <th style="vertical-align: middle;">Distrik</th> -->
                          <!-- <th style="vertical-align: middle;">Lokasi</th> -->
                          <th style="vertical-align: middle;">Judul KKP</th>
                          <th style="vertical-align: middle;">No KKP</th>
                          <!-- <th style="vertical-align: middle;">No PRK</th> -->
                          <th style="vertical-align: middle;">AI Cluster</th>
						  <th style="vertical-align: middle;">AKI Cluster</th>
                          <!-- <th>No PRK</th>
                          <th>Nama PRK</th> -->
                          <!-- <th style="vertical-align: middle;">Review Status</th> -->
                          <th>Posisi Review Sekarang</th>
                          <th>Review Status Sekarang</th>
                          <th style="vertical-align: middle;">Document</th>
                          <th style="vertical-align: middle;">Aksi</th>
                          <th style="vertical-align: middle;">Approval</th>
                        </tr>
                    </thead>

                    <?php $i=1; ?>
                    @if($approval_dmr != null)
                    @foreach($approval_dmr as $ap)
                        <tr>
                          <td>{{ $i }}</td>
                          <!-- <td>{{ $ap->tahun_anggaran }}</td> -->
                          <!-- <td>{{ $ap->lokasi->distrik->strategi_bisnis->name }}</td> -->
                          <!-- <td>{{ $ap->lokasi->distrik->name }}</td> -->
                          <!-- <td>{{ $ap->lokasi->name }}</td> -->
                          <td>{{ $ap->judul_dokumen ? $ap->judul_dokumen : '-' }}</td>
                          <td>{{ $ap->no_dokumen }}</td>
                          <!-- <td>{{ $ap->no_prk_form }}</td> -->
                          <td>{{ number_format($ap->jumlah_anggaran,0,',','.') }}</td>
						  <td>{{ number_format($ap->anggaran_percluster,0,',','.') }}</td>
						  <!-- <td>{{ $ap->no_prk }}</td>
                          <td>{{ $ap->nama_prk }}</td> -->
                          {{--
                          <td>
                              @if($ap->dmr_review_phase->role_id == $dmr_review_phase->role_id)
                                    {{ $ap->dmr_review_status->name }}
                              @else
                                    {{ 'Approved' }}
                              @endif
                          </td>
                          --}}
                          <td>
                                @if($ap->dmr_review_phase_id=='3' && $ap->dmr_review_status_id=='4')
                                    @if($ap->status_appr_id=='' || ($ap->status_appr_id=='4' && ($ap->kondisi_aicluster_id=='2' || $ap->kondisi_aicluster_id=='3')) )
                                        Set Approval BIDFIN
                                    @else
                                        @if($ap->status_appr_id=='3' || $ap->status_appr_id=='4' || $ap->status_appr_id=='6' || $ap->status_appr_id=='7')
                                            @if($datrs != null)
                                                @for($az=0; $az < count($datrs); $az++)
                                                    @if($ap->id == $datrs[$az]['kkp_id'])
                                                        {{ $datrs[$az]['grupdivname'] }}
                                                    @endif
                                                @endfor
                                            @endif
                                        @elseif($ap->status_appr_id=='8')
                                            BIDFIN
                                        @else
                                            -
                                        @endif
                                    @endif
                                @else
                                    @if ($ap->dmr_review_status_id == DMR_STATUS_REVISED || $ap->dmr_review_status_id == DMR_STATUS_REJECTED)
                                        {{ $role_spv_unit_dmr_tor->name }}
                                    @else
                                        {{ $ap->dmr_review_phase->role->name }}
                                    @endif
                                @endif
                          </td>
                          <td>
                                @if($ap->dmr_review_phase_id=='3' && $ap->dmr_review_status_id=='4')
                                    @if($ap->status_appr_id=='3' || $ap->status_appr_id=='4' || $ap->status_appr_id=='7')
                                        {{ $ap->status_appr->name }}
                                        @if($datrb != null)
                                            @for($az=0; $az < count($datrb); $az++)
                                                @if($ap->id == $datrb[$az]['kkp_id'])
                                                    ({{ $datrb[$az]['grupdivname'] }})
                                                @endif
                                            @endfor
                                        @endif
                                    @else
                                        @if($ap->status_appr_id!='')
                                            {{ $ap->status_appr->name }}
                                        @else
                                            Set Approval BIDFIN
                                        @endif
                                    @endif
                                    
                                @else
                                    {{ $ap->dmr_review_status->name }}
                                @endif
                          </td>

                          <td class="text-center">
                            <a href="{{ url('kkp/download_attachment') .'/'. $ap->id }}" class="btn btn-info"> <span class="glyphicon glyphicon-download-alt"></span></a>
                          </td>
                          <td>
                              <a href="{{ url('kkp/detail/'.$ap['id']) }}" class="btn btn-primary btn-xs" data-toggle="tooltip" title="detail">
                                <span class="glyphicon glyphicon-eye-open "></span>
                              </a>
                          </td>
                          <td>
                                @if(($role_id == ROLE_ID_MANAGER_RISK OR $role_id == ROLE_ID_KADIV_RISK) AND $ap->anggaran_prk_form < MINIMUM_ANGGARAN)
                                    <a class="btn btn-success" disabled>Approval</a>
                                @else
                                    @if($ap->dmr_review_phase_id=='3' && $ap->dmr_review_status_id=='4')
                                        @if($ap->status_appr_id!='8')
                                            @if($getappr != null)
                                                @foreach($getappr as $cekappr)
                                                    @if($ap->id==$cekappr->kkp_id)
                                                        <a href="approval/{{ $ap['id'] }} " class="btn btn-success">Approval</a>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endif
                                    @else
                                        @if($ap->dmr_review_phase->role_id == $dmr_review_phase->role_id AND $ap->dmr_review_status_id == DMR_STATUS_QUEUE)
                                            <a href="approval/{{ $ap['id'] }} " class="btn btn-success">Approval</a>
                                        @endif
                                    @endif
                                @endif
                          </td>
                        </tr>
                        <?php $i++; ?>
                    @endforeach
                    @endif

                    </table>
                 </div>
          </div>
          </div>
        </div>

  </div>
<script>
$(document).ready(function(){
    var distrik_id = $('select[name="distrik"]').val();
    // console.log(distrik_id);
    if (distrik_id==28 || distrik_id==21) 
    {
        $('.bagianclass').show();
        $('select[name="bagian"]').attr('required', '');
    } 
    else 
    {
        $('.bagianclass').hide();
        $('select[name="bagian"]').removeAttr('required'); 

        $('select[name="bagian"]').val('');

        // console.log($('select[name="bagian"]').val())
    }
});
</script>
@endsection
