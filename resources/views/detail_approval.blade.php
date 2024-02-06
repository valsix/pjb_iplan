@extends('layouts.app')

@section('css_page')
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
     <div role="main">
          <div>
            <div class="page-title">
              <div>
                  <div class="col-md-10">
                  <h3> Approval {{ $jenis->name }}</h3>
                            <table id="datatables" class="table table-striped table-bordered table-hover">
                              <tr>
                                <td> Tahun Anggaran </td>
                                <td>{{ $tahun_anggaran }}</td>
                              </tr>
                              <tr>
                                <td> Struktur Bisnis</td>
                                <td>{{ $lokasi->distrik->strategi_bisnis->name }}</td>
                              </tr>
                              <tr>
                                <td>Distrik</td>
                                <td>{{ $lokasi->distrik->name }}</td>
                              </tr>
                              <tr>
                                <td>Lokasi</td>
                                <td>{{ $lokasi->name }}</td>
                              </tr>
                            </table>
                        <h3> Daftar Draft/Versi </h3>
                          <table id="datatables" class="table table-striped table-bordered table-hover">
                            <thead>
                              <tr>
                                <th> No. </th>
                                <th> Fase </th>
                                <th> Draft/Versi </th>
                                <th> Konseptor </th>
                                <th> Status </th>
                                <th> Aksi </th>
                              </tr>
                            </thead>
                              <tbody>
                              <?php $no=1;?>
                              @foreach ($fileapproval as $item)
                                <tr>
                                  <!-- <td>{{-- $no++ --}} id: {{ $item->id }}, file import id: {{ $item->file_import_id }}, lokasi: {{ $item->lokasi_id }}, jenis: {{ $item->jenis_id }}</td> -->
                                  <td>{{ $no++ }}</td>
                                  <td>
                                      <?php if ($item->latest_approval_id): ?>
                                        {{ $item->faseterakhir->fase->name }}
                                      <?php else: ?>
                                        {{ $item->approval->fase->name }}
                                      <?php endif ?>
                                  </td>
                                  <td>{{ $item->fileImport->draft_versi }} - {{ $item->fileImport->name }}</td>
                                  <td>{{ $item->konseptor->name }} </td>
                                  <td>
                                  @if($item->file_approval_parent_id)
                                  {{ $item->parent->fileapprovalstatus->name }} {{ $item->parent->approvalby->name }} 
                                    @if($item->latest_approval_id==9)
                                      (Fase {{ $item->parent->approvalByOnFase->fase->name }}) 
                                    @else
                                      <?php 
                                        $latest_approval_id_minus_1 = $item->latest_approval_id-1;
                                        $approval_minus_1 = \App\Entities\Approval::where('id', $latest_approval_id_minus_1)->first();

                                        $urutan_terakhir_fase_1 = \App\Entities\Approval::where('fase_id', '1')->orderBy('id', 'desc')->first()->id;
                                        $urutan_terakhir_fase_2 = \App\Entities\Approval::where('fase_id', '2')->orderBy('id', 'desc')->first()->id;
                                      ?>
                                      @if($latest_approval_id_minus_1 == $urutan_terakhir_fase_1 || $latest_approval_id_minus_1 == $urutan_terakhir_fase_2)
                                        (Fase {{ $approval_minus_1->fase->name }}) 
                                      @else
                                        (Fase {{ $item->faseterakhir->fase->name }}) 
                                      @endif
                                    @endif
                                  @else
                                    {{ $item->fileapprovalstatus->name }} {{ $item->approvalby->name }} 
                                    @if($item->latest_approval_id==9)
                                      (Fase {{ $item->approvalByOnFase->fase->name }}) 
                                    @else
                                      @if($item->latest_approval_id!=NULL)
                                        <?php 
                                          $latest_approval_id_minus_1 = $item->latest_approval_id-1;
                                          $approval_minus_1 = \App\Entities\Approval::where('id', $latest_approval_id_minus_1)->first();

                                          $urutan_terakhir_fase_1 = \App\Entities\Approval::where('fase_id', '1')->orderBy('id', 'desc')->first()->id;
                                          $urutan_terakhir_fase_2 = \App\Entities\Approval::where('fase_id', '2')->orderBy('id', 'desc')->first()->id;
                                        ?>
                                        @if($latest_approval_id_minus_1 == $urutan_terakhir_fase_1 || $latest_approval_id_minus_1 == $urutan_terakhir_fase_2)
                                        (Fase {{ $approval_minus_1->fase->name }}) 
                                        @else
                                        (Fase {{ $item->faseterakhir->fase->name }}) 
                                        @endif
                                      @else
                                        (Fase {{ $item->approval->fase->name }}) 
                                      @endif
                                    @endif
                                  @endif
                                  </td>
                                  <td>

                                  <?php 
                                    if($item->latest_approval_id==NULL) $fase_selected = 1;
                                    else $fase_selected = $item->faseterakhir->fase_id;
                                  ?>
                                  <!-- rkau -->
                                  @if($item->jenis_id == 1)
                                  <a target="_blank" href="{{ url('/output/laba-rugi?tahun1='.$item->tahun_anggaran.'&strategi_bisnis1='.$item->lokasi->distrik->strategi_bisnis->id.'&distrik1='.$item->lokasi->distrik->id.'&lokasi1='.$item->lokasi_id.'&fase1='.$fase_selected.'&draft1='.$item->file_import_id.'') }}" class="btn btn-primary" data-toggle="tooltip" title="detail"> 
                                  Detail Dashboard</a>
                                  <!-- form 6 reimburse -->
                                  @elseif($item->jenis_id == 2)
                                  <a target="_blank" href="{{ url('/output/rincian-biaya-har-reimburse?tahun_anggaran='.$item->tahun_anggaran.'&strategi_bisnis='.$item->lokasi->distrik->strategi_bisnis->id.'&distrik='.$item->lokasi->distrik->id.'&lokasi='.$item->lokasi_id.'&fase1='.$fase_selected.'&draft1='.$item->file_import_id.'') }}" class="btn btn-primary" data-toggle="tooltip" title="detail"> 
                                  Detail Dashboard</a>
                                  <!-- form 6 rutin -->
                                  @elseif($item->jenis_id == 3)
                                  <a target="_blank" href="{{ url('/output/rincian-biaya-har?tahun_anggaran='.$item->tahun_anggaran.'&strategi_bisnis='.$item->lokasi->distrik->strategi_bisnis->id.'&distrik='.$item->lokasi->distrik->id.'&lokasi='.$item->lokasi_id.'&fase1='.$fase_selected.'&draft1='.$item->file_import_id.'') }}" class="btn btn-primary" data-toggle="tooltip" title="detail"> 
                                  Detail Dashboard</a>
                                  <!-- form 10 pengembangan usaha -->
                                  @elseif($item->jenis_id == 4)
                                  <a target="_blank" href="{{ url('/output/rincian-pengembangan-usaha?tahun_anggaran='.$item->tahun_anggaran.'&strategi_bisnis='.$item->lokasi->distrik->strategi_bisnis->id.'&distrik='.$item->lokasi->distrik->id.'&lokasi='.$item->lokasi_id.'&fase='.$fase_selected.'&draft1='.$item->file_import_id.'') }}" class="btn btn-primary" data-toggle="tooltip" title="detail"> 
                                  Detail Dashboard</a>
                                  <!-- form 10 penguatan kit -->
                                  @elseif($item->jenis_id == 5)
                                  <a target="_blank" href="{{ url('/output/rincian-penetapan-ai?tahun_anggaran='.$item->tahun_anggaran.'&strategi_bisnis='.$item->lokasi->distrik->strategi_bisnis->id.'&distrik='.$item->lokasi->distrik->id.'&lokasi='.$item->lokasi_id.'&fase='.$fase_selected.'&draft1='.$item->file_import_id.'') }}" class="btn btn-primary" data-toggle="tooltip" title="detail"> 
                                  Detail Dashboard</a>
                                  <!-- form 10 pln -->
                                  @elseif($item->jenis_id == 6)
                                  <a target="_blank" href="{{ url('/output/rincian-penetapan-pln?tahun_anggaran='.$item->tahun_anggaran.'&strategi_bisnis='.$item->lokasi->distrik->strategi_bisnis->id.'&distrik='.$item->lokasi->distrik->id.'&lokasi='.$item->lokasi_id.'&fase='.$fase_selected.'&draft1='.$item->file_import_id.'') }}" class="btn btn-primary" data-toggle="tooltip" title="detail"> 
                                  Detail Dashboard</a>
                                  <!-- form bahan bakar -->
                                  @elseif($item->jenis_id == 7)
                                  <a target="_blank" href="{{ url('/output/rincian-energi-primer?tahun_anggaran='.$item->tahun_anggaran.'&strategi_bisnis='.$item->lokasi->distrik->strategi_bisnis->id.'&distrik='.$item->lokasi->distrik->id.'&lokasi='.$item->lokasi_id.'&fase='.$fase_selected.'&draft_id='.$item->file_import_id.'&bulan=12') }}" class="btn btn-primary" data-toggle="tooltip" title="detail"> 
                                  Detail Dashboard</a>
                                  <!-- form risk profile -->
                                  @elseif($item->jenis_id == 8)
                                  <a target="_blank" href="{{ url('/output/risk-profile?tahun_anggaran='.$item->tahun_anggaran.'&strategi_bisnis='.$item->lokasi->distrik->strategi_bisnis->id.'&distrik='.$item->lokasi->distrik->id.'&lokasi='.$item->lokasi_id.'&fase='.$fase_selected.'&draft='.$item->file_import_id.'') }}" class="btn btn-primary" data-toggle="tooltip" title="detail"> 
                                  Detail Dashboard</a>
                                  <!-- form penyusutan -->
                                  @elseif($item->jenis_id == 9)
                                  <a target="_blank" href="{{ url('/output/laba-rugi?tahun1='.$item->tahun_anggaran.'&strategi_bisnis1='.$item->lokasi->distrik->strategi_bisnis->id.'&distrik1='.$item->lokasi->distrik->id.'&lokasi1='.$item->lokasi_id.'&fase1='.$fase_selected.'&draft1='.$item->file_import_id.'') }}" class="btn btn-primary" data-toggle="tooltip" title="detail"> 
                                  Detail Dashboard</a>
                                  @endif
                                  <a href="{{ asset($item->fileImport->file) }}" class="btn btn-warning" data-toggle="tooltip" title="Download Original Excel">
                                  <span class="glyphicon glyphicon-download-alt"></span>
                                  </a>
                                    <a href="{{ route('fileimport.export.use', ['version_id' => $item->fileImport->version_id, 'id' => $item->file_import_id]) }}" class="btn btn-info" data-toggle="tooltip" title="Download Processed Excel">
                                  <span class="glyphicon glyphicon-download-alt"></span>
                                </a></td>
                                </tr>
                              @endforeach
                              </tbody>
                          </table>
                            <form method="post" role="form">
                            <?php if ($show_button): ?>
                            <h3> Approval</h3>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                {{ csrf_field() }}
                                <input type="hidden" name="jenis_id" value="<?php echo $jenis_id ?>">
                                <input type="hidden" name="fase_id" value="<?php echo $fase->id ?>">
                                <input type="hidden" name="tahun_anggaran" value="<?php echo $tahun_anggaran ?>">
                                <input type="hidden" name="lokasi_id" value="<?php echo $lokasi->id ?>" >
                                <!-- <div class="row">
                                <div class="col-md-5"> <label> Fase </label> </div>
                                <div class="col-md-4">
                                  <p>{{-- $fase->name --}}
                                </div>
                                </div>
                                <br> -->
                                <div class="row">
                                <div class="col-md-5"> <label> Draft/Versi yang digunakan </label> </div>
                                <div class="col-md-7">
                                  <select class="form-control" name="file_approval_selected_id">
                                    @foreach ($fileapproval as $item)
                                      <option value="{{ $item->id }}">{{ $item->fileImport->draft_versi }} - {{ $item->fileImport->name }}</option>
                                    @endforeach
                                  </select>
                                </div>
                                </div>
                                <br>
                                <div class="row">
                                <div class="col-md-5"> <label> Status Approval </label> </div>
                                <div class="col-md-7">
                                  <select class="form-control" name="file_approval_status_id">
                                  @foreach($file_approval_status as $sp)
                                    <option value="{{ $sp->id }}"> {{ $sp->name.' '.$role_name }} </option>
                                  @endforeach
                                  </select>
                                </div>
                                </div>
                                <br>
                                <!-- khusus Staff Unit -->
                                @if(session('role_id')==2)
                                <div class="row">
                                <div class="col-md-5"> <label> Manager Unit </label> </div>
                                <div class="col-md-7">
                                  <select class="form-control" name="manager_unit_user_id">
                                  @foreach($manager_unit as $mu)
                                    <option value="{{ $mu->id }}"> {{ $mu->name }} </option>
                                  @endforeach
                                  </select>
                                </div>
                                </div>
                                <br>
                                @endif
                                <!-- <div class="row {{ $errors->has('keterangan') ? ' has-error' : '' }}">
                                <div class="col-md-5"> <label> Alasan Revisi/Ditolak </label> </div>
                                <div class="col-md-7">
                                  <textarea name="keterangan" type="text" value="{{ old('keterangan') }}" required="required"></textarea>
                                  @if($errors->has('keterangan'))
                                    <span class="help-block">
                                      <strong>{{ $errors->first('keterangan') }}</strong>
                                    </span>
                                  @endif
                                </div>
                                </div> -->
                                <br>
                                <div class=" col-xs-12 col-md-offset-5">
                                
                                  <button type="submit" class="btn btn-primary">Simpan</button>
                                  <?php endif ?>
                                  <a href="{{ url()->previous() }}" class="btn btn-default" >Kembali</a>
                                </div>
                                
                              </form>
                         </div>
                     </div>
                  </div>
                </div>
                </div>
  </div>
</div>

@endsection
