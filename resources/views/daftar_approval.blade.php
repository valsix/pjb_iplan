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
            "searching": true
        } );
    </script>


@endsection

@section('content')
     <div role="main">
          <div class="">
              <div>
                <h3> Daftar Approval {{ $jenis->name }}</h3>

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
                                    <select name="tahun_anggaran" class="form-control" required>
                                       <option value="">- Pilih Tahun Anggaran -</option>
                                       @foreach($tahun as $th)
                                            <option value="{{$th->tahun}}" @if($tahun_anggaran == $th->tahun) {{'selected'}} @endif>{{$th->tahun}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2"><label>Struktur Bisnis</label></div>
                                <div class="col-md-3">
                                    <select class="form-control" name="strategi_bisnis" required>
                                       <option value="">- Pilih Struktur Bisnis -</option>
                                        @foreach ($Sb as $sbs => $value)
                                            <option value="{{ $value->id }}" <?php if($input_sb!=null) echo($input_sb->id == $value->id ? 'selected' : '')?> > {{ $value->name }} </option>
                                        @endforeach
                                    </select>
                                </div>

                                <br>
                                <br>
                                <div class="col-md-2"><label>Distrik</label></div>
                                <div class="col-md-3">
                                    <select class="form-control" name="distrik" required>
                                        <option value="">- Pilih Distrik -</option>
                                        @if($input_sb!=null && $input_distrik!=null)
                                            @foreach($distrik as $d)
                                            <option value="{{$d->id}}" <?php echo($d->id == $input_distrik->id ? 'selected' : '')?>>{{$d->name}}</option>
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
                                                    url: "{{ url('/approval/daftar/ajax/') }}/"+strategi_bisnisID,
                                                    type: "GET",
                                                    dataType: "json",
                                                    success:function(data) {
                                                      $('select[name="distrik"]').empty();
                                                      $('select[name="distrik"]').append('<option value="">- Pilih Distrik -</option>');
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
                                    <select class="form-control" name="lokasi" required>
                                        <option value="">- Pilih Lokasi -</option>
                                        @if($input_sb!=null && $input_distrik!=null && $input_lokasi!=null)
                                            @foreach($lokasi as $l)
                                            <option value="{{$l->id}}" <?php echo($l->id == $input_lokasi->id ? 'selected' : '')?>>{{$l->name}}</option>
                                            @endforeach
                                        @endif   
                                    </select>
                                </div>

                                <script type="text/javascript">
                                    $(document).ready(function() {
                                        $('select[name="distrik"]').on('change', function() {
                                            var distrikID = $(this).val();
                                            $('select[name="lokasi"]').empty();

                                            if(distrikID) {
                                                $.ajax({
                                                    url: "{{ url('/approval/daftar/ajax2/') }}/"+distrikID,
                                                    type: "GET",
                                                    dataType: "json",
                                                    success:function(data) {

                                                      $('select[name="lokasi"]').empty();
                                                      // console.log(data);
                                                      // $('select[name="lokasi"]').append('<option value="">==Silahkan Pilih Lokasi==</option>');
                                                      $.each(data, function(ad , value) {
                                                      // console.log(ad);
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
                                       <span class="glyphicon glyphicon-search"> </span> Cari
                                   </button>
                                   <a href="{{ url('assignment') }}"" class="btn btn-warning">
                                       <span class="glyphicon glyphicon-refresh"> </span> Refresh  
                                   </a>
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
                  <table id="datatable" class="table table-striped table-bordered table-hover">
                     <thead  style="background:#282865;color: white">
                        <tr>
                          <th rowspan="2" style="vertical-align: middle;text-align: center;">No.</th>
                          <th rowspan="2" style="vertical-align: middle;text-align: center;">Nama Form</th>
                          <th rowspan="2" style="vertical-align: middle;text-align: center;">Draft</th>
                          <th rowspan="2" style="vertical-align: middle;text-align: center;">Tahun Anggaran</th>
                          <th rowspan="2" style="vertical-align: middle;text-align: center;">Struktur Bisnis</th>
                          <th rowspan="2" style="vertical-align: middle;text-align: center;">Distrik</th>
                          <th rowspan="2" style="vertical-align: middle;text-align: center;">Lokasi</th>
                          <th rowspan="2" style="vertical-align: middle;text-align: center;">Posisi Terakhir</th>
                          <th rowspan="2" style="vertical-align: middle;text-align: center;">Fase Terakhir</th>
                          <th colspan="3" style="border-right: 0px; text-align: center;">Fase</th>
                          <!-- <th>Status Approval</th> -->
						  @if($role->is_kantor_pusat)
						<th rowspan="2" style="vertical-align: middle;text-align: center;">Interchange</th>
                          @endif
                          <th rowspan="2" style="border-left: 1px solid #FFFFFF;text-align: center;">Aksi</th>
                        </tr>
                        <tr>
                          <th>1</th>
                          <th>2</th>
                          <th>3</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $no=1;?>
                      @foreach ($fileapproval as $item)
                        <tr <?php if ($no%2==1): ?>
                            style="background:#E8EDEF"
                        <?php else: ?>
                            style="background:#FFFFFF"
                        <?php endif ?>
                        >
                          <!-- <td>{{-- $no++ --}} id: {{ $item->id }}, file import id: {{ $item->file_import_id }}, lokasi: {{ $item->lokasi_id }}, jenis: {{ $item->jenis_id }}</td> -->
                          <td>{{ $no++ }}</td>
                          <td><a href="{{ route('approval.daftar_per_jenis', $item->jenis->id) }}" style="text-decoration:underline;">{{ $item->jenis->name }}</a></td>
                          <td>{{ $item->fileImport->draft_versi->format('d F Y H:i:s') }} - {{ $item->fileImport->name }}</td>
                          <td>{{ $item->tahun_anggaran }}</td>
                          <?php if ($item->lokasi_id): ?>
                          <td>{{ $item->lokasi->distrik->strategi_bisnis->name }}</td>
                          <td>{{ $item->lokasi->distrik->name }}</td>
                          <td>{{ $item->lokasi->name }}</td>
                          <?php else: ?>
                          <td>{{ '-' }}</td>
                          <td>{{ '-' }}</td>
                          <td>{{ '-' }}</td>
                          <?php endif ?>
                          <td>{{ $item->lokasiterakhir($item->id) }}</td>
                          <td>
                             <?php if ($item->latest_approval_id): ?>
                              {{ $item->faseterakhir->fase->name }}     
                           <?php else: ?>
                                {{ $item->approval->fase->name }}                       
                            <?php endif ?>
                          </td>


                          <?php $cek1 = $item->cek_fase($item, $item->lokasi_id, '1', $item->jenis_id, $item->tahun_anggaran); ?>
                          <td style="text-align: center;" data-sort="image1{{$cek1}}">
                            @if($cek1=='centang')
                            <i class="fa fa-check-circle fa-lg" style="color: green;"></i>
                            @elseif($cek1=='silang')
                            <i class="fa fa-close fa-lg" style="color: red;"></i>
                            @elseif($cek1=='-a' || $cek1=='-b' || $cek1=='-c' || $cek1=='-d' || $cek1=='-e' || $cek1=='-f')
                            <i class="fa fa-minus fa-lg" style="color: black;"></i>
                            @elseif($cek1=='kotak')
                            <i class="fa fa-square fa-lg" style="color: gray;"></i>
                            @endif
                          </td>

                          <?php $cek2 = $item->cek_fase($item, $item->lokasi_id, '2', $item->jenis_id, $item->tahun_anggaran); ?>
                          <td style="text-align: center;" data-sort="image2{{$cek2}}">
                            @if($cek2=='centang')
                            <i class="fa fa-check-circle fa-lg" style="color: green;"></i>
                            @elseif($cek2=='silang')
                            <i class="fa fa-close fa-lg" style="color: red;"></i>
                            @elseif($cek2=='-a' || $cek2=='-b' || $cek2=='-c' || $cek2=='-d' || $cek2=='-e' || $cek2=='-f')
                            <i class="fa fa-minus fa-lg" style="color: black;"></i>
                            @elseif($cek2=='kotak')
                            <i class="fa fa-square fa-lg" style="color: gray;"></i>
                            @endif
                          </td>

                          <?php $cek3 = $item->cek_fase($item, $item->lokasi_id, '3', $item->jenis_id, $item->tahun_anggaran); ?>
                          <td style="text-align: center;" data-sort="image3{{$cek3}}">
                            @if($cek3=='centang')
                            <i class="fa fa-check-circle fa-lg" style="color: green;"></i>
                            @elseif($cek3=='silang')
                            <i class="fa fa-close fa-lg" style="color: red;"></i>
                            @elseif($cek3=='-a' || $cek3=='-b' || $cek3=='-c' || $cek3=='-d' || $cek3=='-e' || $cek3=='-f')
                            <i class="fa fa-minus fa-lg" style="color: black;"></i>
                            @endif
                          </td>
						  
						   @if($role->is_kantor_pusat)
                          <?php $cek4 = $item->cek_fase_interchange($item, $item->lokasi_id, '4', $item->jenis_id, $item->tahun_anggaran); ?>
                          <td style="text-align: center;" data-sort="image4{{$cek4}}">
                                @if($cek4=='centang')
                                    <i class="fa fa-check-circle fa-lg" style="color: green;"></i>
                                @elseif($cek4=='minus')
                                    <i class="fa fa-minus fa-lg" style="color: black;"></i>
                                @else
                                    &nbsp;
                                @endif
                          </td>
                          @endif

                          <td>
                          <!-- <a href="detail/{{ $item->id }}" class="btn btn-primary" data-toggle="tooltip" title="detail"> <span class="glyphicon glyphicon-eye-open"></span></a> -->
                          <a href="<?php echo url('approval/detail') ?>/{{ $item->tahun_anggaran }}/{{ $item->lokasi_id }}/{{ $item->jenis_id }}/{{ $item->approval->fase->id }}/{{ $item->id }}" class="btn btn-primary" data-toggle="tooltip" title="detail"> Detail</a>

                          </td>
                        </tr>

                          <!-- <script type="text/javascript" language="JavaScript">
                          function konfirmasi()
                          {
                            tanya = confirm("Are you sure you want to delete?");
                              if (tanya == true) return true;
                              else return false;
                          }
                          </script> -->

                      @endforeach

                      @if(!$role->is_kantor_pusat)
                        @foreach ($fileapproval_ketetapan_draft_selain_usulan_unit as $item)
                          <tr <?php if ($no%2==1): ?>
                              style="background:#E8EDEF"
                          <?php else: ?>
                              style="background:#FFFFFF"
                          <?php endif ?>
                          >
                            <!-- <td>{{-- $no++ --}} id: {{ $item->id }}, file import id: {{ $item->file_import_id }}, lokasi: {{ $item->lokasi_id }}, jenis: {{ $item->jenis_id }}</td> -->
                            <td>{{ $no++ }}</td>
                            <td><a href="{{ route('approval.daftar_per_jenis', $item->jenis->id) }}" style="text-decoration:underline;">{{ $item->jenis->name }}</a></td>
                            <td>{{ $item->tahun_anggaran }}</td>
                            <?php if ($item->lokasi_id): ?>
                            <td>{{ $item->lokasi->distrik->strategi_bisnis->name }}</td>
                            <td>{{ $item->lokasi->distrik->name }}</td>
                            <td>{{ $item->lokasi->name }}</td>
                            <?php else: ?>
                            <td>{{ '-' }}</td>
                            <td>{{ '-' }}</td>
                            <td>{{ '-' }}</td>
                            <?php endif ?>
                            <td>{{ $item->lokasiterakhir($item->id) }}</td>
                            <td>
                               <?php if ($item->latest_approval_id): ?>
                                {{ $item->faseterakhir->fase->name }}     
                             <?php else: ?>
                                  {{ $item->approval->fase->name }}                       
                              <?php endif ?>
                            </td>


                            <?php $cek1 = $item->cek_fase($item, $item->lokasi_id, '1', $item->jenis_id, $item->tahun_anggaran); ?>
                            <td style="text-align: center;" data-sort="image1{{$cek1}}">
                              @if($cek1=='centang')
                              <i class="fa fa-check-circle fa-lg" style="color: green;"></i>
                              @elseif($cek1=='silang')
                              <i class="fa fa-close fa-lg" style="color: red;"></i>
                              @elseif($cek1=='-a' || $cek1=='-b' || $cek1=='-c' || $cek1=='-d' || $cek1=='-e' || $cek1=='-f')
                              <i class="fa fa-minus fa-lg" style="color: black;"></i>
                              @elseif($cek1=='kotak')
                              <i class="fa fa-square fa-lg" style="color: gray;"></i>
                              @endif
                            </td>

                            <?php $cek2 = $item->cek_fase($item, $item->lokasi_id, '2', $item->jenis_id, $item->tahun_anggaran); ?>
                            <td style="text-align: center;" data-sort="image2{{$cek2}}">
                              @if($cek2=='centang')
                              <i class="fa fa-check-circle fa-lg" style="color: green;"></i>
                              @elseif($cek2=='silang')
                              <i class="fa fa-close fa-lg" style="color: red;"></i>
                              @elseif($cek2=='-a' || $cek2=='-b' || $cek2=='-c' || $cek2=='-d' || $cek2=='-e' || $cek2=='-f')
                              <i class="fa fa-minus fa-lg" style="color: black;"></i>
                              @elseif($cek2=='kotak')
                              <i class="fa fa-square fa-lg" style="color: gray;"></i>
                              @endif
                            </td>

                            <?php $cek3 = $item->cek_fase($item, $item->lokasi_id, '3', $item->jenis_id, $item->tahun_anggaran); ?>
                            <td style="text-align: center;" data-sort="image3{{$cek3}}">
                              @if($cek3=='centang')
                              <i class="fa fa-check-circle fa-lg" style="color: green;"></i>
                              @elseif($cek3=='silang')
                              <i class="fa fa-close fa-lg" style="color: red;"></i>
                              @elseif($cek3=='-a' || $cek3=='-b' || $cek3=='-c' || $cek3=='-d' || $cek3=='-e' || $cek3=='-f')
                              <i class="fa fa-minus fa-lg" style="color: black;"></i>
                              @endif
                            </td>

							@if($role->is_kantor_pusat)
                            <?php $cek4 = $item->cek_fase_interchange($item, $item->lokasi_id, '4', $item->jenis_id, $item->tahun_anggaran); ?>
                            <td style="text-align: center;" data-sort="image4{{$cek4}}">
                                    @if($cek4=='centang')
                                        <i class="fa fa-check-circle fa-lg" style="color: green;"></i>
                                    @elseif($cek4=='minus')
                                        <i class="fa fa-minus fa-lg" style="color: black;"></i>
                                    @else
                                        &nbsp;
                                    @endif
                            </td>
                            @endif


                            <td>
                            <!-- <a href="detail/{{ $item->id }}" class="btn btn-primary" data-toggle="tooltip" title="detail"> <span class="glyphicon glyphicon-eye-open"></span></a> -->
                            <a href="<?php echo url('approval/detail_ketetapan_selain_usulan_unit') ?>/{{ $item->tahun_anggaran }}/{{ $item->lokasi_id }}/{{ $item->jenis_id }}/{{ $item->approval->fase->id }}/{{ $item->id }}" class="btn btn-primary" data-toggle="tooltip" title="detail"> Detail</a>

                            </td>
                          </tr>

                            <!-- <script type="text/javascript" language="JavaScript">
                            function konfirmasi()
                            {
                              tanya = confirm("Are you sure you want to delete?");
                                if (tanya == true) return true;
                                else return false;
                            }
                            </script> -->

                        @endforeach
                      @endif
                    </tbody>
                </table>
            </div>
          </div>
        </div>
@endsection
