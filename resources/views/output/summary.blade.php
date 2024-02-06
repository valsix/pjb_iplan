@extends('layouts.app')

@section('css_page')

    <!-- searching -->
    <!-- <script src="{{ asset('js/jquery-1.11.2.min.js') }}" type="text/javascript"></script> -->
    
    <!-- Datatables -->
    <link href="{{ asset('vendors/datatables.net-bs/css/dataTables-khusus-summary.bootstrap.min.css') }}" rel="stylesheet">
    <!-- <link href="{{ asset('vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet"> -->
    <!-- <link href="{{ asset('vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css') }}" rel="stylesheet"> -->
    <!-- <link href="{{ asset('vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet"> -->
    <!-- <link href="{{ asset('vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}" rel="stylesheet"> -->

    <style type="text/css">
        .table-container
        {
            width: 100%;
            overflow-x: auto;
            overflow: auto;
            margin: 0 0 1em;
        }
        thead th{
            text-align: center;
        }

        /*Update line height & font-size*/
        .table thead tr th{
          line-height: 1;
        }
        .table tbody tr td{
          /*Untuk data yang deskripsi panjang*/
          line-height: 1; 

          /*Untuk data yang tidak ada deskripsi panjang*/
          /*line-height: 0.5; */
        }
        .table {
          font-size: 11px;
        }

        .form-horizontal .form-group
        {
            margin-right: 0;
            margin-left: 0;
            margin-top: -13px;
        }

    </style>
@endsection

@section('js_page')

    <script type="text/javascript">
        $('#datatable').dataTable( {
            "searching": true,
            "aLengthMenu": [[10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"]],
            "scrollY": "800px",
            "scrollX": "300px",
            "scrollCollapse": true,
            "paging": true,
            // "ordering": false,
            // pagingType: "full_numbers",
            // fixedHeader: true,
        } );
    </script>


@endsection

@section('content')
     <div role="main">
          <div class="row">
            <div class="page-title">
              <div>
                <h3> Summary Form</h3>
              </div>
              <br>
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      Filter     
                      </div>
                        <div class="panel-default">
                        <br/>
                        <form method="post" class="form-horizontal form-label-left" action="{{ route('output/summary') }}">
                          {{ csrf_field() }}
                          <div class="form-group">
                            <label class="col-md-2 col-sm-3 col-xs-12">Tahun Anggaran</label>
                              <div class="col-md-4 col-sm-4 col-xs-12">
                                <select class="form-control col-md-7 col-xs-12" name="tahun_anggaran">
                                  @foreach($tahun as $thn)
                                    <option value="{{$thn}}" <?php if ($input_t == $thn) { echo 'selected'; } ?>>{{$thn}}</option>
                                  @endforeach
                                </select>
                              </div>
                            <div class="form-group">
                            <label class="col-md-2 col-sm-3 col-xs-12">Strategi Bisnis</label>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                              <select class="form-control col-md-7 col-xs-12" name="strategi_bisnis">
                                  <option value="">- Pilih Strategi Bisnis -</option>
                                    @foreach($Sbisnis as $sb)
                                      <option value="{{$sb->id}}" <?php if (isset($input_sb) && $input_sb == $sb->id) { echo 'selected'; } ?>>{{$sb->name}}</option>
                                    @endforeach
                              </select>
                            </div>
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-md-2 col-sm-3 col-xs-12" >Distrik</label>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                              <select class="form-control col-md-7 col-xs-12" name="distrik">
                                  <option value="">- Pilih Distrik -</option>
                                  @if(isset($input_d))
                                    @foreach($distrik as $dst)
                                      @if($dst->id == $input_d)
                                        <option value="{{$dst->id}}" selected>{{ $dst->name }}</option>
                                      @endif
                                    @endforeach
                                  @endif
                              </select>
                            </div>
                            <script type="text/javascript">
                              $(document).ready(function() {
                                  $('select[name="strategi_bisnis"]').on('change', function() {
                                      var strategi_bisnisID = $(this).val();
                                      $('select[name="distrik"]').empty();

                                      if(strategi_bisnisID) {
                                          $.ajax({
                                              // url: '/lokasi/create/ajax/'+strategi_bisnisID,
                                              url: "{{ url('/output/summary/ajax/') }}/"+strategi_bisnisID,
                                              type: "GET",
                                              dataType: "json",
                                              success:function(data) {
                                          // console.log(data);
                                                $('select[name="distrik"]').empty();
                                                $('select[name="lokasi"]').empty();
                                                $('select[name="distrik"]').append('<option value="">- Pilih Distrik -</option>')
                                                $('select[name="lokasi"]').append('<option value="">- Pilih Lokasi -</option>')
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
                            <div class="form-group">
                            <label class="col-md-2 col-sm-3 col-xs-12">Lokasi</label>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                              <select class="form-control col-md-7 col-xs-12" name="lokasi">
                                  <option value="">- Pilih Lokasi -</option>
                                  @if(isset($input_l))
                                    @foreach($lokasiall as $lall)
                                      @if($lall->id == $input_l)
                                        <option value="{{$lall->id}}" selected>{{ $lall->name }}</option>
                                        <?php break; ?>
                                      @endif
                                    @endforeach
                                  @endif
                              </select>
                            </div>
                          </div>
                          <script type="text/javascript">
                              $(document).ready(function() {
                                  $('select[name="distrik"]').on('change', function() {
                                      var distrikID = $(this).val();
                                      $('select[name="lokasi"]').empty();

                                      if(distrikID) {
                                          $.ajax({
                                              // url: '/lokasi/create/ajax/'+strategi_bisnisID,
                                              url: "{{ url('/output/summary/ajax2/') }}/"+distrikID,
                                              type: "GET",
                                              dataType: "json",
                                              success:function(data) {
                                                $('select[name="lokasi"]').append('<option value="">- Pilih Lokasi -</option>')
                                                $.each(data, function(sb, value) {
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
                          </div>
                          <div class="form-group">
                            <label class="col-md-2 col-sm-3 col-xs-12" >Filter Fase</label>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                              <select class="form-control col-md-7 col-xs-12" name="fase">
                                @foreach($fs as $f)
                                  <option value="{{$f->id}}" <?php if ($input_f == $f->id) { echo 'selected'; } ?>>{{$f->name}}</option>
                                @endforeach
                              </select>
                            </div>          
                          </div>

                          <div class="ln_solid"></div>

                              <div class="form-group">
                                <div >
                                  <button type="submit" class="btn btn-primary pull-right" >
                                      <span class="glyphicon glyphicon-search"> </span> Filter
                                  </button>
                                </div>
                              </div>        

                          </form>
                        </div>
                  </div>
                </div>
                <div class="x_content">
                  <table id="datatable" class="table table-striped table-bordered table-hover">
                    <thead style="background:#2A3F54;color:white;">
                        <tr>
                          <th>No.</th>
                          <th>Strategi Bisnis</th>
                          <th>Distrik</th>
                          <th>Lokasi</th>
                          <th>Tahun</th>
                          <th>RKAU</th>
                          <th>Form 6 - Reimburse</th>
                          <th>Form 6 - Rutin</th>
                          <th>Form 10 - Pengembangan Usaha</th>
                          <th>Form 10 - Penguatan KIT</th>
                          <th>Form 10 - PLN</th>
                          <th>Form Bahan Bakar</th>
                          <th>Risk Profile</th>
                          <th>Penyusutan</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $no=0;?>
                    @foreach($lokasi as $lok)
                    <?php
// print_r($lok->fileapproval[0]);exit();
// dd(fileapproval);
?>
                      @if(!empty($lok->fileapproval[0]))
                        @if($lok->fileapproval->contains('tahun_anggaran', $input_t))
                          <?php $no++;?>
                          <tr>
                            <td>{{ $no }}</td>
                            <td>{{ $lok->distrik->strategi_bisnis->name }}</td>
                            <td>{{ $lok->distrik->name }}</td>
                            <td>{{ $lok->name }}</td>
                            <td>{{ $input_t }}</td>
                            <?php $temp=0;?>
                            @for ($i = 1; $i <= 9; $i++)
                              @if(isset($lok->lokasijenis[$temp]))
                                @if($lok->lokasijenis[$temp]->jenis_id == $i)
                                  <?php $found=0;?>
                                  @foreach($lok->fileapproval as $fa)
                                    @if($fa->tahun_anggaran == $input_t)
                                      @if($fa->jenis_id == $i)
                                        @if(($fa->approval_id > $input_f * 3) || ($fa->approval_id == $input_f * 3 && $fa->file_approval_status_id == 4))
                                          <?php $found=1;?>
                                          <?php break;?>
                                        @elseif($fa->approval_id < $input_f * 3 && ($input_f * 3) - $fa->approval_id < 4)
                                          <?php $found=2;?>
                                        @elseif(($fa->approval_id < $input_f * 3 && ($input_f * 3) - $fa->approval_id >= 4) && $found == 0)
                                          <?php $found=3;?>
                                        @endif
                                      @endif
                                    @endif
                                  @endforeach
                                  @if($found == 1)
                                    <td>
                                      <a href="{{ asset($fa->fileImport->file) }}" data-toggle="tooltip" title="{{ $fa->fileImport->draft_versi.' - '.$fa->fileImport->name }}">
                                      <span class="glyphicon glyphicon-ok" style="color:green"></span>
                                      </a>
                                    </td>
                                  @elseif($found == 2)
                                    <td><span class="glyphicon glyphicon-time" style="color:orange"></span></td>
                                  @else
                                    <td><span class="glyphicon glyphicon-minus" style="color:blue"></span></td>
                                  @endif
                                  @if($temp+1 < count($lok->lokasijenis))
                                    <?php $temp++;?>
                                  @endif
                                @elseif($lok->lokasijenis[$temp]->jenis_id != $i)
                                  <td><span class="glyphicon glyphicon-stop" style="color:red"></span></td>
                                @endif
                              @else
                                <td></td>
                              @endif
                            @endfor
                          </tr>
                        @endif
                      @else
                        <?php $no++;?>
                        <tr>
                          <td>{{ $no }}</td>
                          <td>{{ $lok->distrik->strategi_bisnis->name }}</td>
                          <td>{{ $lok->distrik->name }}</td>
                          <td>{{ $lok->name }}</td>
                          <td>-</td>
                          <?php $temp=0;?>
                          @for ($i = 1; $i <= 9; $i++)
                            @if(isset($lok->lokasijenis[$temp]))
                              @if($lok->lokasijenis[$temp]->jenis_id == $i)
                                <td><span class="glyphicon glyphicon-minus" style="color:blue"></span></td>
                                @if($temp+1 < count($lok->lokasijenis))
                                  <?php $temp++;?>
                                @endif
                              @elseif($lok->lokasijenis[$temp]->jenis_id != $i)
                                <td><span class="glyphicon glyphicon-stop" style="color:red"></span></td>
                              @endif
                            @else
                              <td></td>
                            @endif
                          @endfor
                        </tr>
                      @endif
                    @endforeach
                        </tbody>
                      </table>
                 </div>
            </div>
          </div>
        </div>
@endsection