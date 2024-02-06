@extends('layouts.app')

@section('css_page')
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

@section('content')
    <h3>REPORT LOADER ELLIPSE PENGENDALIAN</h3>
    <div class="row"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel collapse">
            <!-- <div class="panel-heading"> -->
            <div class="x_title">
              <h2 style="font-size: 18px;">PENCARIAN</h2>
              <ul class="nav navbar-right panel_toolbox">
                <li>
                   <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
                <li>
                   <a class="close-link"><i class="fa fa-close"></i></a>
                </li>
              </ul>
              <div class="clearfix"></div>
            </div>
            <!-- <div class="panel-default"> -->
             <div class="x_content" style="display: none;">
            <br/>
            <form class="form-horizontal form-label-left">

            <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12">Tahun Anggaran</label>
              <div class="col-md-4 col-sm-4 col-xs-12">

                <input type="text" class="form-control col-md-7 col-xs-12" name="tahun_anggaran" readonly="readonly" value="{{$input_tahun}}">
              </div>

              <div class="form-group">
              <label class="col-md-2 col-sm-3 col-xs-12">Struktur Bisnis</label>
              <div class="col-md-3 col-sm-4 col-xs-12">

                <input type="text" class="form-control col-md-7 col-xs-12" name="strategi_bisnis" readonly="readonly" value="{{!empty($input_sb) ? $input_sb->name : ''}}">
              </div>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12" >Distrik</label>
              <div class="col-md-4 col-sm-4 col-xs-12">
                <input type="text" class="form-control col-md-7 col-xs-12" name="distrik" readonly="readonly" value="{{!empty($input_distrik) ? $input_distrik->name : ''}}">
              </div>

              <div class="form-group">
              <label class="col-md-2 col-sm-3 col-xs-12">Lokasi</label>
              <div class="col-md-3 col-sm-4 col-xs-12">
                <input type="text" class="form-control col-md-7 col-xs-12" name="lokasi" readonly="readonly"
                @if(!empty($input_lokasi))
                  <?php $val = null; ?>
                  <?php $ival = null; ?>
                  @foreach($input_lokasi as $l)
                  <?php
                    $ival++;
                    if($ival==1)
                      $val = $val.' '.$l->name;
                    else
                      $val = $val.', '.$l->name;
                  ?>
                  @endforeach
                  value="{{ $val }}"
                @else
                  value=""
                @endif
                >
              </div>
            </div>
            </div>

            <script type="text/javascript">
              function check() {
                  var lokasiID = $(this).val();
                  $('select[name="lokasi"]').empty();

                  if(lokasiID) {
                      $.ajax({
                          url: "{{ url('output/loader-ellipse/ajax2/') }}/"+lokasiID,
                          type: "GET",
                          dataType: "json",
                          success:function(data) {

                            $('select[name="lokasi"]').empty();
                            $('select[name="lokasi"]').append('<option selected="" value="" disabled="">Pilih</option>');
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
                      console.log(lokasiID);

                      if(lokasiID) {
                          $.ajax({
                              url: '/output/loader-ellipse/ajax2/'+lokasiID,
                              type: "GET",
                              dataType: "json",
                              success:function(data) {

                                $('select[name="lokasi"]').empty();
                                console.log("waw");
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
                  }

                  }

              $(document).ready(function() {
                  $('select[name="distrik"]').on('change', check);
                  $('select[name="distrik"]').on('click', check);

              });
            </script>

            <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12">Fase</label>
              <div class="col-md-4 col-sm-4 col-xs-12">

                <input type="text" name="fase" value="Ketetapan" class="form-control col-md-7 col-xs-12" readonly="readonly">
              </div>

              <div class="form-group">
                <label class="col-md-2 col-sm-3 col-xs-12">s.d Bulan</label>
                <div class="col-md-3 col-sm-4 col-xs-12">
                  <input type="text" name="fase" value="{{ $nama_bln_dipilih }}" class="form-control col-md-7 col-xs-12" readonly="readonly">
                </div>
              </div>
            </div>
            <hr>

            <div class="form-group" style="margin-top: 5px;">
              <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12">RKAU</label>
              <div class="col-md-6 col-sm-4 col-xs-12">
                <input type="text" name="draft_rkau" value="{{ !empty($name_draft_rkau) ? $name_draft_rkau : '' }}" class="form-control col-md-7 col-xs-12" readonly="readonly">
              </div>
              </div>
            </div>

            <div class="form-group" style="margin-top: 5px;">
              <!-- <label class="col-md-2 col-sm-3 col-xs-12"></label>
              <div class="col-md-4 col-sm-4 col-xs-12"></div> -->

              <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12">Form 6 Reimburse</label>
              <div class="col-md-6 col-sm-4 col-xs-12">

                <input type="text" name="draft_form_6_reimburse" value="{{ !empty($name_draft_form_6_reimburse) ? $name_draft_form_6_reimburse : '' }}" class="form-control col-md-7 col-xs-12" readonly="readonly">
              </div>
              </div>
            </div>


            <div class="form-group" style="margin-top: 5px;">
              <!-- <label class="col-md-2 col-sm-3 col-xs-12"></label>
              <div class="col-md-4 col-sm-4 col-xs-12"></div> -->

              <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12">Form 6 Rutin</label>
              <div class="col-md-6 col-sm-4 col-xs-12">
                <input type="text" name="draft_form_6_rutin" value="{{ !empty($name_draft_form_6_rutin) ? $name_draft_form_6_rutin : '' }}" class="form-control col-md-7 col-xs-12" readonly="readonly">
              </div>
              </div>
            </div>

            <div class="form-group" style="margin-top: 5px;">
              <!-- <label class="col-md-2 col-sm-3 col-xs-12"></label>
              <div class="col-md-4 col-sm-4 col-xs-12"></div> -->

              <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12">Form 10 Pengembangan Usaha</label>
              <div class="col-md-6 col-sm-4 col-xs-12">
                <input type="text" name="draft_form_10_pu" value="{{ !empty($name_draft_form_10_pu) ? $name_draft_form_10_pu : '' }}" class="form-control col-md-7 col-xs-12" readonly="readonly">
              </div>
              </div>
            </div>

            <div class="form-group" style="margin-top: 5px;">
              <!-- <label class="col-md-2 col-sm-3 col-xs-12"></label>
              <div class="col-md-4 col-sm-4 col-xs-12"></div> -->

              <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12">Form 10 Penguatan KIT</label>
              <div class="col-md-6 col-sm-4 col-xs-12">
                <input type="text" name="draft_form_10_pk" value="{{ !empty($name_draft_form_10_pk) ? $name_draft_form_10_pk : '' }}" class="form-control col-md-7 col-xs-12" readonly="readonly">
              </div>
              </div>
            </div>

            <div class="form-group" style="margin-top: 5px;">
              <!-- <label class="col-md-2 col-sm-3 col-xs-12"></label>
              <div class="col-md-4 col-sm-4 col-xs-12"></div> -->

              <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12">Form 10 PLN</label>
              <div class="col-md-6 col-sm-4 col-xs-12">

                <input type="text" name="draft_form_10_pln" value="{{ !empty($name_draft_form_10_pln) ? $name_draft_form_10_pln : '' }}" class="form-control col-md-7 col-xs-12" readonly="readonly">
              </div>
              </div>
            </div>

            <div class="form-group" style="margin-top: 5px;">
              <!-- <label class="col-md-2 col-sm-3 col-xs-12"></label>
              <div class="col-md-4 col-sm-4 col-xs-12"></div> -->

              <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12">Form Bahan Bakar</label>
              <div class="col-md-6 col-sm-4 col-xs-12">

                <input type="text" name="draft_form_bahan_bakar" value="{{ !empty($name_draft_form_bahan_bakar) ? $name_draft_form_bahan_bakar : '' }}" class="form-control col-md-7 col-xs-12" readonly="readonly">
              </div>
              </div>
            </div>

            <div class="form-group" style="margin-top: 5px;">
              <!-- <label class="col-md-2 col-sm-3 col-xs-12"></label>
              <div class="col-md-4 col-sm-4 col-xs-12"></div> -->

              <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12">Form Penyusutan</label>
              <div class="col-md-6 col-sm-4 col-xs-12">

                <input type="text" name="draft_form_penyusutan" value="{{ !empty($name_draft_form_penyusutan) ? $name_draft_form_penyusutan : ''}}" class="form-control col-md-7 col-xs-12" readonly="readonly">
              </div>
              </div>
            </div>

            </form>
          </div>
        </div>
      </div>
    </div>
    @if(!empty($notification_failed))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ $notification_failed }}
        </div>
    @endif
    @if(!empty($input_lokasi))
    <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <a href="{{ Request::fullUrl() }}&download=rincian-biaya-administrasi&type=excel" id="get-excel" class="btn btn-success pull-right"><i class="fa fa-download"></i> Download Excel</a>
                    <h2 style="font-size: 18px;">REPORT LOADER ELLIPSE PENGENDALIAN</h2>
                    <div class="clearfix"></div>

                  </div>


                  <div style="overflow-x:auto;">
                    @php
                        $columnMappings = [
                            // 'LABEL' => 'COLUMN',
                            'Nomor Project /PRK' => 'Nomor Project / PRK',
                            'Deskripsi Project /PRK [40 karakter]' => 'Deskripsi Project /PRK [40 karakter]',
                            'Ext.Description Line 1 [60 Karakter]' => 'Ext.Description Line 1 [60 Karakter]',
                            'Ext.Description Line 2 [60 Karakter]' => 'Ext.Description Line 2 [60 Karakter]',
                            'Parent Project' => 'Parent Project',

                            'Raised Date (yyyymmdd)' => 'Raised Date (yyyymmdd)',
                            'Originator' => 'Originator',
                            'Account Code' => 'Account Code',
                            'Authorize Employee' => 'Authorize Employee',
                            'Authorize Date (yyyymmdd)' => 'Authorize Date (yyyymmdd)',
                            'Nomer Rumah PRK' => 'Rumah PRK Number',
                            'Years' => 'Years',
                            'Version' => 'Version',
                            'PRK Type' => 'PRK Type',
                            'Plan Start Date (yyyymmdd)' => 'Plan Start Date (yyyymmdd)',
                            'Plan Finish Date (yyyymmdd)' => 'Plan Finish Date (yyyymmdd)',

                            'Schedule Start Date' => false,
                            'Schedule Finish Date' => false,
                            'Actual Start Date' => false,
                            'Actual Finish Date' => false,
                            'Build Method (T/B)' => false,
                            'Budget Code' => false,
                            'Direct Est Cost/Revenue' => false,
                            'Category Code' => false,
                            'Category Value' => false,

                            // '*Beban (MAT)' => 'Beban (MAT)',
                            // '*Cash (OTH)' => 'Cash (OTH)',
                            // '*Ijin Proses (LAB)' => 'Ijin Proses (LAB)',

                            'Classification' => 'Classification',
                            'Estimator' => 'Estimator',
                            'Years Estimate' => false,
                            'Total Year Estimate' => 'Total Year Estimate',
                            'Jan' => 'Jan',
                            'Feb' => 'Feb',
                            'Mar' => 'Mar',
                            'Apr' => 'Apr',
                            'Mei' => 'Mei',
                            'Jun' => 'Jun',
                            'Jul' => 'Jul',
                            'Agt' => 'Agt',
                            'Sep' => 'Sep',
                            'Okt' => 'Okt',
                            'Nov' => 'Nov',
                            'Des' => 'Des',
                            'UPLOAD STATUS' => false,

                            // '*Spread Code' => 'Spread Code',
                            // '*Tahun Disburse' => 'Tahun Disburse',
                            // '*UPLOAD STATUS PROJECT' => 'UPLOAD STATUS PROJECT',
                            // '*UPLOAD STATUS PROJECT ESTIMATE' => 'UPLOAD STATUS PROJECT ESTIMATE',
                            // '*UPLOAD STATUS PERIOD PROJECT ESTIMATE' => 'UPLOAD STATUS PERIOD PROJECT ESTIMATE',
                            // '*JUMLAH SUBMIT (KALI)' => 'JUMLAH SUBMIT (KALI)',
                        ];
                    @endphp
                    <table id="table-loader-ellipse" class="table table-striped table-bordered" style="font-size:11px;">
                       <thead style="background:#2A3F54;color:white;">
                         <tr>
                           @foreach ($columnMappings as $label => $column)
                              <th>{{ $label }}</th>
                           @endforeach
                         </tr>
                       </thead>
                       <body>
                        @foreach($prk_parent_result as $key_parent => $parent)
                            @if($key_parent!= '')
                                {{-- Buat row sendiri untuk tiap MAT, OTH, LAB --}}
                                @foreach(['Beban (MAT)'] as $k => $catcode)
                                <tr>
                                    @foreach($columnMappings as $label => $column)
                                        <td>
                                          {{ loaderEllipsePgdlVal('parent', $catcode, $label, $column, $parent) }}
                                        </td>
                                    @endforeach
                                </tr>
                                @endforeach

                                @foreach($prk_inti_result as $key_inti => $inti)
                                    @if($key_inti!= '' && substr($key_inti,0,-2) == $key_parent)
                                    {{-- Buat row sendiri untuk tiap MAT, OTH, LAB --}}
                                    @foreach(['Beban (MAT)'] as $k => $catcode)
                                    <tr>
                                        @foreach($columnMappings as $label => $column)
                                            <td>
                                              {{ loaderEllipsePgdlVal('inti', $catcode, $label, $column, $inti) }}
                                            </td>
                                        @endforeach
                                    </tr>
                                    @endforeach

                                    @foreach($prk_kegiatan_result as $key_kegiatan => $kegiatan)
                                        @if(substr($kegiatan['Nomor Project / PRK'],0,6) == $key_inti || substr($kegiatan['Nomor Project / PRK'],0,-2) == $key_inti || substr($kegiatan['Nomor Project / PRK'],2, 6) == $key_inti)
                                            {{-- Buat row sendiri untuk tiap MAT, OTH, LAB --}}
                                            @foreach(['Beban (MAT)'] as $k => $catcode)
                                            <tr>
                                                @foreach($columnMappings as $label => $column)
                                                    <td>
                                                      {{ loaderEllipsePgdlVal('kegiatan', $catcode, $label, $column, $kegiatan, 8, 2) }}
                                                    </td>
                                                @endforeach
                                            </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                    @endif
                                @endforeach
                            @endif
                        @endforeach

                       </body>
                    </table>
                    </div>
                  </div>
                </div>
            <!-- </div> -->
    </div>
    @endif
          </div>

@endsection

@section('js_page')
<script type="text/javascript">
  $(document).ready(function() {
    var table = $('#table-loader-ellipse').DataTable( {
        "aLengthMenu": [[10, 25, 50, 100, -1],
        [10, 25, 50, 100, "All"]],
        "scrollY": "300px",
        "scrollX": "300px",
        "scrollCollapse": true,
        "paging": true,
        // pagingType: "full_numbers",
        fixedHeader: true,
        ordering: false
    } );
    $('#table-loader-ellipse_filter label input').on( 'keyup', function () {
    table
        .columns( 0 )
        .search( this.value )
        .draw();
    } );
} );
</script>
@endsection
