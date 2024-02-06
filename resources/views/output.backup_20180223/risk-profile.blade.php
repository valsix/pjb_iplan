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
    </style>

@endsection

@section('content')
    <h1> Risk Profile </h1>

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
                                    <select class="form-control" name="tahun_anggaran">
                                       <option value="">- Pilih Tahun -</option>
                                       @for($i=2017;$i<=(date('Y-m-d')+1);$i++)
                                        <option value="{{$i}}"  @isset($input_tahun) @if($input_tahun == $i) selected @endif @endisset>{{$i}}</option>
                                       @endfor

                                    </select>
                                </div>
                                <div class="col-md-2"><label>Struktur Bisnis</label></div>
                                <div class="col-md-3">
                                    <select class="form-control" name="strategi_bisnis">
                                       <option value="">- Pilih Strategi Bisnis -</option>
                                       @foreach ($sb as $sbs => $value)
                                         <option value="{{ $value->id }}" @isset($input_sb) @if($input_sb->name == $value->name) selected @endif @endisset> {{ $value->name }} </option>
                                       @endforeach
                                    </select>
                                </div>

                                <br>
                                <br>
                                <div class="col-md-2"><label>Distrik</label></div>
                                <div class="col-md-4">
                                    <select class="form-control" name="distrik">
                                      <option value="">- Pilih Distrik -</option>
                                      @isset($input_distrik) <option value="{{$idistrik}}" selected> {{$input_distrik->name}}</option> @endisset

                                    </select>
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


                                <div class="col-md-2"><label> Lokasi</label></div>
                                <div class="col-md-3">
                                    <select class="form-control" name="lokasi">
                                       <option value="">- Pilih Lokasi -</option>
                                       @isset($input_lokasi) <option value="{{$ilokasi}}" selected> {{$input_lokasi->name}}</option> @endisset
                                    </select>
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

                                <br>
                                <br>
                                <div class="col-md-2"><label>Fase</label></div>
                                <div class="col-md-4">
                                    <select class="form-control" name="fase">
                                       <option value="">- Pilih Fase -</option>
                                       @isset($fase)
                                        @foreach($fase as $f)
                                          <option value="{{$f->id}}" @isset($input_fase) @if($input_fase->name == $f->name) selected @endif @endisset>{{$f->name}}</option>
                                        @endforeach
                                       @endisset
                                    </select>
                                </div>

                                <div class="col-md-2"><label>Draft</label></div>
                                <div class="col-md-3">
                                    <select class="form-control" name="draft" required>
                                       <option value="">- Pilih Draft -</option>
                                       @isset($idraft)
                                       <option value="{{$idraft}}" selected> {{$versi->draft_versi}}</option>
                                       @endisset
                                    </select>
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
        <div class="row">
           <div class="col-md-12 col-sm-12 col-xs-12">
             <div class="x_panel">
               <div class="x_title">
                 <h2>Risk Profile Graph</h2>
                 <div class="clearfix"></div>
               </div>
              <div class="x_content">
               <table class="gant" border="1"  style="width:100%; color: black;"> <!-- cellspacing="10" cellpadding="20" -->
                 <tbody >

                    <?php $i=1; ?>

                    @foreach($tingkat_kemungkinan as $tk)
                      <?php echo '<tr>' ?>
                        <?php if($i==1) { ?>
                        <td rowspan="5" width="1px"><span  class="vertical_Text" title="vertical text">Kemungkinan</span></td>
                        <?php } ?>
                          <?php echo '<td width="25px">'.$tk->nama_tingkat_kemungkinan.'</td>'; ?>
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
                      <td colspan="5" >Tingkat Dampak</td>
                    </tr>

                </tbody>
              </table>
             </div>
            </div>
           </div>
          </div>

        <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                      <div class="x_title">
                        <h2>Risk Profile</h2>
                        <div class="clearfix"></div>
                      </div>

                      <a href="{{ Request::fullUrl() }}&download=risk_profile&type=excel" id="get-excel1" class="btn btn-success pull-right"><i class="fa fa-download"></i> Download Excel</a>
                      <a href="{{ Request::fullUrl() }}&download=risk_profile&type=pdf"  class="btn btn-primary pull-right"><i class="fa fa-download"></i> Download PDF</a>


                      <div class="x_content">
                        <table id="datatable" class="table table-striped table-bordered">
                          <thead style="background:#2A3F54;color:white;">
                            <tr>
                              <th>No</th>
                              <th>Risk Tag</th>
                              <th>Risk Event</th>
                            </tr>
                          </thead>
                          <body>
                            @isset($combineall)
                            @foreach($combineall as $c1)
                              <tr>
                                <td>{{$c1['A']}}</td>
                                <td>{{$c1['B']}}</td>
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
