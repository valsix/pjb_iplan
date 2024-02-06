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

        .text-td {
              text-align: -webkit-left;
        }

        th{
            text-align: center;
        }
        td, th {
            padding: 0;
            text-align: center;
        }
        .form-horizontal .form-group
        {
            margin-right: 0;
            margin-left: 0;
            margin-top: -12px;
        }
    </style>

@endsection

@section('content')
    <h3> RISK PROFILE </h3>

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
          <form  class="form-horizontal form-label-left">

            <div class="form-group">
              <label class="control-label col-md-2 col-sm-3 col-xs-12">Tahun Anggaran</label>
              <div class="col-md-4 col-sm-4 col-xs-12">
                <!-- <select class="form-control col-md-7 col-xs-12" name="tahun_anggaran">
                  <option value="">- Pilih Tahun -</option>
                    @for($i=2017;$i<=(date('Y-m-d')+1);$i++)
                      <option value="{{$i}}"  @isset($input_tahun) @if($input_tahun == $i) selected @endif @endisset>{{$i}}</option>
                    @endfor
                </select> -->
                <input type="text" name="tahun_anggaran" class="form-control col-md-7 col-xs-12" value="{{isset($input_tahun) ? $input_tahun : ''}}" readonly="readonly" />
              </div>

              <div class="form-group">
              <label class="control-label col-md-2 col-sm-3 col-xs-12">Struktur Bisnis</label>
              <div class="col-md-4 col-sm-4 col-xs-12">
             <!--    <select class="form-control col-md-7 col-xs-12" name="strategi_bisnis">
                  <option value="">- Pilih Strategi Bisnis -</option>
                    @foreach ($sb as $sbs => $value)
                      <option value="{{ $value->id }}" @isset($input_sb) @if($input_sb->name == $value->name) selected @endif @endisset> {{ $value->name }} </option>
                    @endforeach
                </select> -->
                <input type="text" name="strategi_bisnis" class="form-control col-md-7 col-xs-12" value="{{isset($input_sb)? $input_sb->name : '' }}" readonly="readonly" />
              </div>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2 col-sm-3 col-xs-12" >Distrik</label>
              <div class="col-md-4 col-sm-4 col-xs-12">
               <!--  <select class="form-control col-md-7 col-xs-12" name="distrik">
                  <option value="">- Pilih Distrik -</option>
                    @isset($input_distrik)
                      <option value="{{$idistrik}}" selected> {{$input_distrik->name}}</option>
                    @endisset
                </select> -->
                <input type="text" name="distrik" class="form-control col-md-7 col-xs-12" value="{{isset($input_distrik)? $input_distrik->name : '' }}" readonly="readonly" />
              </div>

              <script type="text/javascript">
                  $(document).ready(function() {
                      $('select[name="strategi_bisnis"]').on('change', function() {
                          var strategi_bisnisID = $(this).val();
                          $('select[name="distrik"]').empty();
                          $('select[name="lokasi"]').empty();
                          $('select[name="lokasi"]').append('<option value="">- Pilih Lokasi -</option>');

                          if(strategi_bisnisID) {
                              $.ajax({
                              url: "{{url('/output/risk-profile/ajax/')}}/"+strategi_bisnisID,
                              type: "GET",
                              dataType: "json",
                              success:function(data) {
                              // console.log(data);
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

              <div class="form-group">
              <label class="control-label col-md-2 col-sm-3 col-xs-12">Lokasi</label>
              <div class="col-md-4 col-sm-4 col-xs-12">
               <!--  <select class="form-control col-md-7 col-xs-12" name="lokasi">
                  <option value="">- Pilih Lokasi -</option>
                    @isset($input_lokasi)
                      <option value="{{$ilokasi}}" selected> {{$input_lokasi->name}}</option>
                    @endisset
                </select> -->
                <input type="text" name="lokasi" class="form-control col-md-7 col-xs-12" value="{{isset($input_lokasi)? $input_lokasi->name : ''}}" readonly="readonly" />
              </div>
            </div>
            </div>

            <script type="text/javascript">
                $(document).ready(function() {
                    $('select[name="distrik"]').on('change', function() {
                        var lokasiID = $(this).val();
                        $('select[name="lokasi"]').empty();

                        if(lokasiID) {
                            $.ajax({
                            url: "{{url('/output/rincian-biaya-har/ajax2/')}}/"+lokasiID,
                            type: "GET",
                            dataType: "json",
                            success:function(data) {

                              $('select[name="lokasi"]').empty();
                              console.log(data);
                                $('select[name="lokasi"]').append('<option value="">- Pilih Lokasi -</option>');
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

            <div class="form-group">
              <label for="middle-name" class="control-label col-md-2 col-sm-3 col-xs-12">Fase</label>
              <div class="col-md-4 col-sm-4 col-xs-12">
             <!--  <select class="form-control col-md-7 col-xs-12" name="fase">
                <option value="">- Pilih Fase -</option>
                  @isset($fase)
                    @foreach($fase as $f)
                      <option value="{{$f->id}}" @isset($input_fase) @if($input_fase->name == $f->name) selected @endif @endisset>{{$f->name}}</option>
                    @endforeach
                  @endisset
              </select> -->
              <input type="text" name="fase" class="form-control col-md-7 col-xs-12" value="{{isset($input_fase) ? $input_fase->name : '' }}" readonly="readonly" />
              </div>

              <div class="form-group">
              <label class="control-label col-md-2 col-sm-3 col-xs-12">Draft</label>
              <div class="col-md-4 col-sm-4 col-xs-12">
               <!--  <select class="form-control col-md-7 col-xs-12" name="draft" required>
                  <option value="">- Pilih Draft -</option>
                    @isset($idraft)
                      <option value="{{$idraft}}" selected> {{$versi->draft_versi}}</option>
                    @endisset
                </select> -->
                <input type="text" name="draft" class="form-control col-md-12 col-xs-12" value="{{isset($idraft) ? $versi->draft_versi.' - '.$versi->name : ''}}" readonly="readonly" />
              </div>
              </div>
            </div>

            <script type="text/javascript">
              $(document).ready(function() {
                  $('select[name="fase"]').on('change', function() {
                      var jenisID = 8;
                      var lokasiID= $('select[name="lokasi"]').val();
                      var tahun= $('select[name="tahun_anggaran"]').val();

                      $('select[name="draft"]').empty();
                      console.log(jenisID);
                      console.log(lokasiID);
                      console.log(tahun);

                      if(jenisID) {
                          $.ajax({
                              url: "{{url('/output/risk-profile/ajax3/')}}/"+jenisID+'/'+lokasiID+'/'+tahun,
                              type: "GET",
                              dataType: "json",
                              success:function(data) {

                                $('select[name="draft"]').empty();
                                console.log(data);
                                $('select[name="draft"]').append('<option value="">- Pilih Draft -</option>');

                                $.each(data, function(ad , value) {
                                console.log(ad);
                                    $('select[name="draft"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +'</option>');
                                });

                              }
                          });
                      }else{
                          $('select[name="draft"]').empty();

                      }
                  });
              });
            </script>

           <!--  <div class="form-group">
              <div >
                <button type="submit" class="btn btn-primary pull-right">
                    <span class="glyphicon glyphicon-search"> </span> cari
                </button>
              </div>
            </div> -->

            </form>
          </div>
        </div>
      </div>
    </div>


       <div class="row">
          <div class="col-md-8  col-sm-12 col-xs-12 top">
            <div style="display: table;">
             <div class="x_panel">
               <div class="x_title">
                 <h2 style="font-size: 18px;">GRAFIK RISK PROFILE</h2>
                 <div class="clearfix"></div>
               </div>
              <div class="x_content">
               <table class="gant" border="1" style="width:100%; color: black;"> <!-- cellspacing="10" cellpadding="20" -->

                 <tbody >

                    <?php $i=1; ?>

                    @foreach($tingkat_kemungkinan as $tk)
                      <?php echo '<tr>' ?>
                        <?php if($i==1) { ?>
                        <td rowspan="5" width="30px" style="background:#2A3F54;"><span  class="vertical_Text" title="vertical text" style="font-size: 15px;color:white;">Kemungkinan</span></td>
                        <?php } ?>
                          <?php echo '<td width="100px">'.$tk->nama_tingkat_kemungkinan.'</td>'; ?>
                          <?php echo '<td width="27px">'.$tk->no_tingkat_kemungkinan.'</td>'; ?>

                              <?php foreach($tingkat_dampak as $td) { ?>



                                    <?php foreach($level_resiko as $lr) { ?>


                                      <?php if($lr->tingkat_kemungkinan_id==$tk->id
                                      && $lr->tingkat_dampak_id==$td->id)
                                          { ?>

                                            <?php echo '<td style="background-color:#'.$lr->warna_level_resiko.'";>'; ?>
                                              @isset($combineall)
                                                @foreach($combineall as $c1)
                                                  @if($c1['E'] == $tk->nama_tingkat_kemungkinan && $c1['F'] == $td->nama_tingkat_dampak)
                                                    <div class="badge bg-white" style="color: black;">
                                                      {{$c1['A']}}
                                                    </div>
                                                  @endif
                                                @endforeach
                                              @endisset
                                            <?php echo $lr->nama_level_resiko; ?>
                                            <?php echo '</td>'; ?>

                                         <?php } ?>

                                      <?php } ?>

                              <?php } ?>

                        <?php echo '</tr>' ?>
                        <?php $i++; ?>
                    @endforeach

                    <tr>
                      <td colspan="3"></td>
                        @foreach($tingkat_dampak as $td)
                            <?php echo '<td width="100px">'.$td->no_tingkat_dampak.'</td>'; ?>
                        @endforeach
                    </tr>

                    <tr>
                      <td colspan="3"></td>
                      @foreach($tingkat_dampak as $td)
                          <?php echo '<td>'.$td->nama_tingkat_dampak.'</td>'; ?>
                      @endforeach
                    </tr>

                    <tr>
                      <td colspan="3"></td>
                      <td colspan="5" height="40px" style="font-size: 15px;background:#2A3F54;color:white;">Tingkat Dampak</td>
                    </tr>

                </tbody>
              </table>
             </div>
            </div>
           </div>
       </div>
      </div>

      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2 style="font-size: 18px;">RISK PROFILE</h2>
              <div class="clearfix"></div>
            </div>

            <a href="{{ Request::fullUrl() }}&download=risk_profile&type=excel" id="get-excel1" class="btn btn-success pull-right"><i class="fa fa-download"></i> Download Excel</a>
            <a href="{{ Request::fullUrl() }}&download=risk_profile&type=pdf"  class="btn btn-primary pull-right"><i class="fa fa-download"></i> Download PDF</a>

              <div class="x_content">
                <table id="datatable" class="table table-striped table-bordered" style="font-size:11px;">
                  <thead style="background:#2A3F54;color:white;">
                    <tr>
                      <th>No</th>
                      <th>Risk Tag</th>
                      <th>Risk Event</th>
                    </tr>
                  </thead>
                  <body>
                    <?php $baris = 0; ?>
                    @isset($combineall)
                    @foreach($combineall as $c1)
                    <?php $baris = $baris+1; $warna=($baris % 2 == 0) ? "#ffffff" : "#E8EDEF"; ?>
                      <tr style="background-color: <?= $warna?>">
                        <td style="text-align: left;">{{$c1['A']}}</td>
                        <td style="text-align: left;">{{$c1['B']}}</td>
                        <td style="text-align: left;">{{$c1['C']}}</td>
                      </tr>
                    @endforeach
                    @endisset
                  </body>
                </table>
              </div>
            </div>
          </div>
      </div>

@endsection
