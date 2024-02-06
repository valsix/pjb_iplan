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


     <div role="main">
          <div class="">
              <div>
                <h3> Approval TOR</h3>
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
                                <div class="col-md-4">
                                    <select class="form-control" name="tahun_anggaran" required>
                                      <option disabled="" selected="" value="">-- Pilih Tahun Anggaran --</option>
                                      @for ( $y=date('Y'), $i=($y-5); $i<=($y+20); $i++)
                                        <option value="{{ $i }}" {{ $input_tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                                      @endfor
                                    </select>
                                </div>

                                <div class="col-md-2"><label>Struktur Bisnis</label></div>
                                <div class="col-md-3">
                                    <select class="form-control" name="strategi_bisnis" required>
                                       <option disabled="" selected="" value="">-- Pilih Struktur Bisnis --</option>
                                        @foreach ($Sb as $sbs => $value)
                                         <option value="{{ $value->id }}" {{ $input_sb == $value->id ? 'selected' : '' }} > {{ $value->name }} </option>
                                        @endforeach
                                    </select>
                                </div>

                                <br>
                                <br>
                                <div class="col-md-2"><label>Distrik</label></div>
                                <div class="col-md-4">
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
                                <div class="col-md-3">
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
                                                    // url: '/approval_tor/daftar/ajax/'+strategi_bisnisID,
                                                    url: "{{ url('/approval_tor/daftar/ajax/') }}/"+strategi_bisnisID,
                                                    type: "GET",
                                                    dataType: "json",
                                                    success:function(data) {
                                                      $('select[name="distrik"]').empty();
                                                      $('select[name="distrik"]').append(
                                                        '<option disabled="" selected="" value="">-- Pilih Distrik --</option>'
                                                      );
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
                                                $.ajax({
                                                    // url: '/approval_tor/daftar/ajax2/'+lokasiID,
                                                    url: "{{ url('/approval_tor/daftar/ajax2/') }}/"+lokasiID,
                                                    type: "GET",
                                                    dataType: "json",
                                                    success:function(data) {

                                                      $('select[name="lokasi"]').empty();
                                                      $('select[name="lokasi"]').append(
                                                        '<option disabled="" selected="" value="">-- Pilih Lokasi --</option>'
                                                      );
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

              <br>
                <div class="x_content">
                  <table class="table table-striped table-bordered table-hover" id="datatable">
                    <thead style="background:#2A3F54;color:white">
                        <tr>
                          <th style="vertical-align: middle;">No.</th>
                          <th style="vertical-align: middle;">Distrik</th>
                          <th style="vertical-align: middle;">Judul TOR</th>
                          <th style="vertical-align: middle;">No Document</th>
                          <th style="vertical-align: middle;">Review Status</th>
                          <th>Posisi Review Sekarang</th>
                          <th>Review Status Sekarang</th>
                          <th style="vertical-align: middle;">Document</th>
                          <th style="vertical-align: middle;">Aksi</th>
                        </tr>
                    </thead>

                    <?php $i=1; ?>
                    @if($approval_tor != null)
                    @foreach($approval_tor as $ap)
                        <tr>
                          <td>{{ $i }}</td>
                          <td>{{ $ap->lokasi->distrik->name }}</td>
                          <td>{{ $ap->dmr->judul_dokumen  ?? '-- DMR tidak ditemukan --' }}</td>
                          <td>{{ $ap->no_dokumen }}</td>
                          <td>
                              {{ ($ap->tor_review_phase->role_id == $tor_review_phase->role_id ? $ap->tor_review_status->name : "Approved") }}
                          </td>
                          <td>
                            @if ($ap->tor_review_status->id == TOR_STATUS_REVISED || $ap->tor_review_status->id == TOR_STATUS_REJECTED)
                                {{ 'Supervisor Unit (TOR)' }}
                            @else
                                {{$ap->tor_review_phase->role->name}}
                            @endif
                          </td>
                          <td>
                            {{$ap->tor_review_status->name}}
                          </td>
                          <td class="text-center">
                            <a href="{{ url('tor/download_attachment') .'/'. $ap->id }}" class="btn btn-info btn-xs"> <span class="glyphicon glyphicon-download-alt"></span></a>
                          </td>
                          <td>
                            <a href="{{ url('tor/detail')}}/{{ $ap->id }}" class="btn btn-primary btn-xs" data-toggle="tooltip" title="detail">
                              <span class="glyphicon glyphicon-eye-open "></span>
                            </a>
                            @if ($ap->tor_review_phase_id == $tor_review_phase->id && $ap->tor_review_status_id == TOR_STATUS_QUEUE)
                              <a href="approval/{{ $ap->id }} " class="btn btn-success btn-xs">Approval</a>
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

@endsection
