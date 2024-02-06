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
<!--         <div role="main"> -->
        @if (\Session::has('msg'))
          <div class="alert alert-danger">
              <ul>
                  <li>{!! \Session::get('msg') !!}</li>
              </ul>
          </div>
        @endif
        <div>
            <div class="page-title">
            <!-- <div> -->
              <h3> Daftar KKP</h3>


            </div> <!-- page-title -->
            <!-- </div> -->
              @if(session('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  {{ session('success') }}
                </div>
              @endif
              @if(session('fail'))
                <div class="alert alert-danger alert-dismissible" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  {{ session('fail') }}
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
                                    <select class="form-control" name="tahun_anggaran" required>
                                       <option selected="" disabled="" value="">-- Pilih Tahun Anggaran--</option>
                                       @for($i=date('Y')-5; $i <= date('Y')+20; $i++)
                                          <option value="{{$i}}" <?php if($input_tahun != null)echo( $input_tahun == $i? 'selected=""' : '' )?> >{{$i}}</option>
                                       @endfor
                                    </select>
                                </div>
                                <div class="col-md-2"><label>Struktur Bisnis</label></div>
                                <div class="col-md-3">

                                    <select class="form-control" name="strategi_bisnis" {{$required}}>
                                       <option selected="" disabled="" value="">-- Pilih Struktur Bisnis --</option>
                                        @foreach ($Sb as $sbs => $value)
                                         <option value="{{ $value->id }}" <?php if($input_sb != null) echo( $input_sb == $value->id ? 'selected=""' : '' )?> > {{ $value->name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                               <br>
                               <br>
                                <div class="col-md-2"><label>Distrik</label></div>
                                <div class="col-md-4">
                                    <select class="form-control" name="distrik" {{$required}}>
                                        <option selected="" disabled="" value="">-- Pilih Distrik --</option>
                                       @if(count($distrik)>0)
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
                                                      $('select[name="distrik"]').append('<option value="">-- Pilih Distrik --</option>');
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
                                    <select class="form-control" name="lokasi" {{$required}}>
                                       <option selected="" disabled="" value="">-- Pilih Lokasi --</option>
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


                                <div class="col-md-2 bagianclass"><label>Bagian</label></div>
                                <div class="col-md-4 bagianclass">
                                    <select class="form-control" name="bagian" {{$required}}>
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

        <style media="screen">
          thead th {
            text-align: center;
          }
        </style>

          <div class="x_content">
                  <div>
                    <a href="create" class="btn btn-primary"><span class="glyphicon glyphicon-plus"> </span> Tambah KKP</a>
                  </div>
                  <br>
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="datatable" style="width: 100%"> <!-- id="datatable" -->
                      <thead style="background:#2A3F54;color:white">

                        <tr>
                          <th rowspan="2" style="vertical-align: middle;">No</th>
                          <!-- <th rowspan="2" style="vertical-align: middle;">Strategi Bisnis</th> -->
                          <!-- <th rowspan="2" style="vertical-align: middle;">Distrik</th> -->
                          <!-- <th rowspan="2" style="vertical-align: middle;">Lokasi</th> -->
                          <th rowspan="2" style="vertical-align: middle;">Judul KKP</th>
                          <th rowspan="2" style="vertical-align: middle;">No KKP</th>
                          <!-- <th rowspan="2" style="vertical-align: middle;">No PRK Form</th> -->
                          <th rowspan="2" style="vertical-align: middle;">AI Cluster</th>
                          <th rowspan="2" style="vertical-align: middle;">AKI Cluster</th>
                         <!--  <th>No PRK</th>
                          <th>Nama PRK</th> -->
                          <th rowspan="2" style="vertical-align: middle;">Status KKP</th>
                          <!-- <th rowspan="2" style="vertical-align: middle;">Status Submit</th> -->
                          <th colspan="2" style="vertical-align: middle;">Review</th>
                          <th rowspan="2" style="vertical-align: middle;">Reviewer<br>Sekarang</th>
                          <th rowspan="2" style="vertical-align: middle;">Dokumen</th>
                          <th rowspan="2" style="vertical-align: middle;">Aksi</th>
                        </tr>
                        <tr>
                          <th style="vertical-align: middle;">Reviewer</th>
                          <th style="vertical-align: middle;">Status</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php $no=1; ?>
                      @if($dmr!= null)
                      @foreach($dmr as $item)
                        <tr>
                          <td>{{ $no++ }}</td>
                          <!-- <td>{{ $item->lokasi->distrik->strategi_bisnis->name }}</td> -->
                          <!-- <td>{{ $item->lokasi->distrik->name }}</td> -->
                          <!-- <td>{{ $item->lokasi->name }}</td> -->
                          <!-- <td>{{ $item->no_dokumen }}</td>
                          <td>{{ $item->no_prk }}</td> -->
                          <!-- <td>{{ $item->nama_prk }}</td> -->
                          <td>{{ $item->judul_dokumen ? $item->judul_dokumen : '-' }}</td>
                          <td>{{ $item->no_dokumen }}</td>
                          <!-- <td>{{ $item->no_prk_form }}</td> -->
                          <td>{{ number_format($item->jumlah_anggaran,0,',','.') }}</td>
                          <td>{{ number_format($item->anggaran_percluster,0,',','.') }}</td>
                          <!-- <td>{{ $item->no_prk }}</td> -->
                          <!-- <td>{{ $item->nama_prk }}</td> -->
                          <td>
                              @if($item->is_submitted == 0)
                                  Draft
                              @else
                                  Submitted - {{ $item->submitted_at}}
                              @endif
                          </td>
                          <td>
                                @if($item->dmr_review_phase_id=='3' && $item->dmr_review_status_id=='4')
                                    @if($item->status_appr_id=='3' || $item->status_appr_id=='4' || $item->status_appr_id=='6' || $item->status_appr_id=='7')
                                        @if($datrb != null)
                                            @for($az=0; $az < count($datrb); $az++)
                                                @if($item->id == $datrb[$az]['kkp_id'])
                                                    ({{ $datrb[$az]['grupdivname'] }})
                                                @endif
                                            @endfor
                                        @endif
                                    @elseif($item->status_appr_id=='8')
                                        @if($datrevb != null)
                                            @for($az=0; $az < count($datrevb); $az++)
                                                @if($item->id == $datrevb[$az]['kkp_id'])
                                                    ({{ $datrevb[$az]['grupdivname'] }})
                                                @endif
                                            @endfor
                                        @endif
                                    @else
                                        -
                                    @endif
                                @else
                                    @if($item->is_submitted == 0)
                                        -
                                    @else
                                        {{ $item->dmr_review_phase->role->name }}
                                    @endif
                                @endif
                          </td>
                          <td>
                                @if($item->dmr_review_phase_id=='3' && $item->dmr_review_status_id=='4')
                                    @if($item->status_appr_id=='')
                                        Draft Set Approval
                                    @else
                                        {{ $item->status_appr->name }}
                                    @endif
                                @else
                                    @if($item->is_submitted == 0)
                                        -
                                    @else
                                        {{ $item->dmr_review_status->name }}
                                    @endif
                                @endif
                          </td>
                          <!-- Reviewer Sekarang -->
                          <td>
                                @if($item->is_submitted == 0)
                                    {{ $role_spv_unit_dmr_tor->name }}
                                @else
                                    @if($item->dmr_review_phase_id=='3' && $item->dmr_review_status_id=='4')
                                        @if($item->status_appr_id=='' || ($item->status_appr_id=='4' && ($item->kondisi_aicluster_id=='2' || $item->kondisi_aicluster_id=='3')) )
                                            Set Approval BIDFIN
                                        @else
                                            @if($item->status_appr_id=='3' || $item->status_appr_id=='4' || $item->status_appr_id=='6' || $item->status_appr_id=='7')
                                                @if($datrs != null)
                                                    @for($az=0; $az < count($datrs); $az++)
                                                        @if($item->id == $datrs[$az]['kkp_id'])
                                                            ({{ $datrs[$az]['grupdivname'] }})
                                                        @endif
                                                    @endfor
                                                @endif
                                            @elseif($item->status_appr_id=='8')
                                                BIDFIN
                                            @else
                                                -
                                            @endif
                                        @endif
                                    @else
                                        @if ($item->dmr_review_status_id == DMR_STATUS_REVISED || $item->dmr_review_status_id == DMR_STATUS_REJECTED)
                                          {{ $role_spv_unit_dmr_tor->name }}
                                        @else
                                          {{ $item->dmr_review_phase->role->name }}
                                        @endif
                                    @endif
                                @endif
                          </td>
                          <td class="text-center"> <!-- dokumen upload -->
                            <a href="{{ url('dmr/download_attachment') .'/'. $item->id }}" class="btn btn-info btn-xs" data-toggle="tooltip" title="Download">
                            <!-- <a href="{{ asset($item->dmr_filepath) }}" class="btn btn-info btn-xs" data-toggle="tooltip" title="Download"> -->
                              <span class="glyphicon glyphicon-download-alt"></span>
                            </a>
                          </td>
                          <td> <!-- aksi -->
                            <a href="detail/{{ $item['id'] }}" class="btn btn-primary btn-xs" data-toggle="tooltip" title="detail">
                              <span class="glyphicon glyphicon-eye-open "></span>
                            </a>
                            @if( ($item->dmr_review_phase_id=='3' && $item->dmr_review_status_id=='4') && ($role_id=='5' && $grupdiv_id=='4'))
                                @if($item->status_appr_id=='' || ($item->status_appr_id=='4' && ($item->kondisi_aicluster_id=='2' || $item->kondisi_aicluster_id=='3')))
                                    <a href="set_appr/{{ $item['id'] }}" class="btn btn-info btn-xs" data-toggle="tooltip" title="Set Approval">
                                        <span class="glyphicon glyphicon-edit "></span>
                                    </a>
                                @else

                                @endif
                            @endif

                            @if(($item->is_submitted == 0 OR $item->dmr_review_status_id == DMR_STATUS_REVISED) || ($item->dmr_review_phase_id=='3' && $item->dmr_review_status_id=='4' && ($item->status_appr_id=='8') ))
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

<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();

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
