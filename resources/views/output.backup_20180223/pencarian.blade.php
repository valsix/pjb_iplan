@extends('layouts.app')

@section('css_page')
    <style type="text/css">
        .table-container
        {
            widtd: 100%;
            overflow-x: auto;
            overflow: auto;
            margin: 0 0 1em;
        }
        thead th{
          text-align: center;
        }

    </style>
    
@endsection

@section('content')
    <h1> Dashboard </h1>
    <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">

    <div class="col-lg-12">
          <div class="panel panel-default">
              <div class="panel-heading">
                   Pencarian
              </div>
              <div class="panel-default"> 
                  <br>
                  <div class="row">
                      <div class="col-lg-12">
                          <form method>
                              <div class="col-md-2"><label>Tahun Anggaran</label></div>
                              <div class="col-md-4">
                                    <select class="form-control" required="true" name="tahun">
                                      <option value="">- Pilih Tahun -</option>
                                       @for($i=2016;$i<=(date('Y')+1);$i++)
                                        <option value="{{$i}}"  @isset($tahun) @if($tahun == $i) selected @endif @endisset>{{$i}}</option>
                                       @endfor
                                    </select>
                                </div>
                              <div class="col-md-2"><label>Struktur Bisnis</label></div>
                              <div class="col-md-3">
                                  <select class="form-control" name="strategi_bisnis" required>
                                     <option value="">- Pilih Stuktur Bisnis -</option>
                                     @foreach ($Sbisnis as $sbs => $value)
                                      <option value="{{ $value->id }}" @isset($input_sb) @if($input_sb->name == $value->name) selected @endif @endisset> {{ $value->name }} </option>
                                     @endforeach
                                  </select>
                              </div>
                             
                              <br>
                              <br>
                              <div class="col-md-2"><label>Distrik</label></div>
                              <div class="col-md-4">
                                  <select class="form-control" name="distrik" required>
                                     <option value="">- Pilih Distrik -</option>
                                       @isset($distrik) <option value="{{$input_distrik}}" selected> {{$distrik->name}}</option> @endisset
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
                                                  url: '{{url("/output/biaya-pemeliharaan/ajax/")}}'+'/'+strategi_bisnisID,
                                                  type: "GET",
                                                  dataType: "json",
                                                  success:function(data) {
                                              // console.log(data);
                                                    var t = "";
                                                    $.each(data, function(sb, value) {
                                                        t += '<option value="'+ value["id"] +'">'+ value["name"] +'</option>';
                                                    });
                                                        $('select[name="distrik"]').append("<option value=''>Pilih Distrik</option>"+t);

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
                                  <select class="form-control" name="lokasi" id="lokasi" required>
                                     <option value="">- Pilih Lokasi -</option>
                                       @isset($input_lokasi) <option value="{{$lokasi}}" selected> {{$input_lokasi->name}}</option> @endisset
                                     
                                  </select>
                              </div><br><br>

                               <script type="text/javascript">
                                    $(document).ready(function() {
                                        $('select[name="distrik"]').on('change', function() {
                                            var lokasiID = $(this).val();
                                            $('select[name="lokasi"]').empty();

                                            if(lokasiID) {
                                                $.ajax({
                                                    url: "{{url('/output/program-strategis/ajax2/')}}"+'/'+lokasiID,
                                                    type: "GET",
                                                    dataType: "json",
                                                    success:function(data) {

                                                      $('select[name="lokasi"]').empty();
                                                       var l = "";
                                                      $.each(data, function(ad , value) {
                                                          l += "'<option value='"+ value["id"] +"'>"+ value["name"] +"</option>";
                                                      });
                                                          $('select[name="lokasi"]').append('<option value="">Pilih Lokasi</option>'+l);
                                                    }
                                                });
                                            }else{
                                                $('select[name="lokasi"]').empty();

                                            }
                                        });
                                    });
                                </script>
                            <hr>
                            <div class="row">
                            <div class="col-lg-12">
                             <div class="col-md-2"><label>Fase</label></div>
                              <div class="col-md-4">
                                  <select class="form-control" name="fase" required>
                                      <option value="">- Pilih Fase -</option>
                                        @foreach($fs as $f)
                                          <option value="{{$f->id}}" @isset($input_fase) @if($input_fase->name == $f->name) selected @endif @endisset>{{$f->name}}</option>
                                        @endforeach
                                  </select>
                              </div>
                                <script type="text/javascript">
                                  $(document).ready(function() {
                                    $('select[name="lokasi"]').on('change', function() {
                                      var id_lokasi = $(this).val();
                                      var id_tahun = $('select[name="tahun"]').val();

                                      $('select[name="reimburse"]').empty();

                                      if(id_lokasi && id_tahun) {
                                          $.ajax({
                                              url: "{{url('/output/biaya-pemeliharaan/ajax3/')}}"+'/'+id_lokasi+"/"+id_tahun,
                                              type: "GET",
                                              dataType: "json",
                                              success:function(data) {
                                                console.log(data);
                                                $.each(data, function(ad , value) {
                                                    $('select[name="reimburse"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +'</option>');
                                                });

                                              }
                                          });
                                      }else{
                                          $('select[name="reimburse"]').empty();

                                      }
                                    })
                                  })
                                </script>
                              </div>
                              </div>
                              <div class="row" style="margin-top: 5px;">
                              <div class="col-lg-12">
                              <div class="col-md-2"><label>Form 6 - Reimburse</label></div>
                              <div class="col-md-4">
                                  <select class="form-control" name="reimburse">
                                     <option value="">- Pilih Form6 - Reimburse -</option>
                                     @isset($input_reimburse) <option value="{{$reimburse}}" selected> {{$input_reimburse->draft_versi}}</option> @endisset
                                  </select>
                              </div><br><br>
                              </div>
                              </div>
                              <script type="text/javascript">
                                  $(document).ready(function() {
                                    $('select[name="lokasi"]').on('change', function() {
                                      console.log("masuk");
                                      var id_lokasi = $(this).val();
                                      var id_tahun = $('select[name="tahun"]').val();

                                      $('select[name="rutin"]').empty();

                                      if(id_lokasi && id_tahun) {
                                          $.ajax({
                                              url: "{{url('/output/biaya-pemeliharaan/ajax4/')}}"+'/'+id_lokasi+"/"+id_tahun,
                                              type: "GET",
                                              dataType: "json",
                                              success:function(data) {
                                                console.log(data);
                                                $.each(data, function(ad , value) {
                                                    $('select[name="rutin"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +'</option>');
                                                });

                                              }
                                          });
                                      }else{
                                          $('select[name="rutin"]').empty();

                                      }
                                    })
                                  })
                              </script>

                              <div class="row" style="margin-top: 5px;">
                              <div class="col-lg-12">
                              <div class="col-md-2"><label>Form 6 - Rutin</label></div>
                              <div class="col-md-4">
                                  <select class="form-control" name="rutin">
                                     <option value="">- Pilih Form6 - Rutin -</option>
                                    @isset($input_rutin)<option value="{{$rutin}}" selected>{{$input_rutin->draft_versi}}</option>@endisset
                                  </select>
                              </div>
                              </div>
                              </div>
                              <div class="row" style="margin-top: 5px;">
                              <div class="col-lg-12">
                                   <button type="submit" class="btn btn-primary" style="margin-left:10px;"> 
                                       <span class="glyphicon glyphicon-search"> </span> cari 
                                   </button>   
                              </div>
                              </div>
                          </form>
                          <br>
                      </div>
                  </div>
              </div>
          </div>
      </div>   
  </div>
</div>

<div class="row">
<div class="col-md-12 col-sm-12 col-xs-12">
<div class="col-lg-12">
  <div class="panel panel-default">
      <div class="panel-heading">
      Report Dasbhoard
      </div>
      <div class="panel-default"> 
          <br>
          <div class="row">
              <div class="col-lg-12">
              <table>
                <tr>
                  <td class="col-md-4">
                    <form method="GET" action="{{ url('/output/risk-profile') }}">
                    <button type="submit" class="btn btn-primary btn-block" style="margin-left:10px;"> 
                      1.1 Risk Profile
                    </button>
                    </form>
                  </td>
                  <td class="col-md-4">
                    <form method="GET" action="{{ url('/output/status-dmr') }}">
                      <button type="submit" class="btn btn-primary btn-block" style="margin-left:10px;"> 
                        6.0 Status DMR
                      </button>
                    </form>
                  </td>
                  <td class="col-md-4">
                    <form method="GET" action="{{ url('/output/rincian-biaya-pegawai') }}">
                      <button type="submit" class="btn btn-primary btn-block" style="margin-left:10px;"> 
                        10 Rincian Biaya Pegawai
                      </button>
                    </form>
                  </td>
                </tr>
                <tr>
                  <td class="col-md-4">
                    <form method="GET" action="{{ url('/output/mitigasi-risiko') }}">
                      <button type="submit" class="btn btn-primary btn-block" style="margin-left:10px;"> 
                        1.2 Mitigasi Resiko
                      </button>
                    </form>
                  </td>
                  <td class="col-md-4">
                    <form method="GET" action="{{ url('/output/rincian-biaya-har') }}">
                      <button type="submit" class="btn btn-primary btn-block" style="margin-left:10px;"> 
                      7.0 Rincian Biaya HAR
                      </button>
                    </form>
                  </td>
                  <td class="col-md-4">
                    <form method="GET" action="{{ url('/output/rincian-biaya-administrasi') }}">
                      <button type="submit" class="btn btn-primary btn-block" style="margin-left:10px;"> 
                      11 Rincian Biaya Administrasi
                      </button>
                    </form>
                  </td>
                </tr>
                <tr>
                  <td class="col-md-4">
                    <form method="GET" action="{{ url('/output/rencana-kinerja') }}">
                      <button type="submit" class="btn btn-primary btn-block" style="margin-left:10px;"> 
                        2.0 Rencana Kinerja
                      </button>
                    </form>
                  </td>
                  <td class="col-md-4">
                    <form method="GET" action="{{ url('/output/rincian-biaya-har-reimburse') }}">
                      <button type="submit" class="btn btn-primary btn-block" style="margin-left:10px;">
                      8.0 Rincian Biaya HAR Reimburse 
                      </button>
                    </form>
                  </td>
                  <td class="col-md-4">
                    <form method="GET" action="{{ url('/output/rincian-energi-primer') }}">
                      <button type="submit" class="btn btn-primary btn-block" style="margin-left:10px;"> 
                      12 Rincian Energi Primer
                      </button>
                    </form>
                  </td>
                </tr>
                <tr>
                  <td class="col-md-4">
                    <form method="GET" action="{{ url('/output/program-strategis') }}">
                      <button type="submit" class="btn btn-primary btn-block" style="margin-left:10px;"> 
                        3.0 Program Strategis
                      </button>
                    </form>
                  </td>
                  <td class="col-md-4">
                    <form method="GET" action="{{ url('/output/rincian-penetapan-ai') }}">
                      <button type="submit" class="btn btn-primary btn-block" style="margin-left:10px;"> 
                        9.1 Rincian Penetapan AI
                      </button>
                    </form>
                  </td>
                  <td class="col-md-4">
                    <form method="GET" action="{{ url('/output/form-luar-operasi') }}">
                      <button type="submit" class="btn btn-primary btn-block" style="margin-left:10px;"> 
                      13 Form Luar Operasi
                      </button>
                    </form>
                  </td>
                </tr>
                <tr>
                  <td class="col-md-4">
                    <form method="GET" action="{{ url('/output/laba-rugi') }}">
                      <button type="submit" class="btn btn-primary btn-block" style="margin-left:10px;"> 
                        4.0 Laba Rugi
                      </button>
                    </form>
                  </td>
                  <td class="col-md-4">
                    <form method="GET" action="{{ url('/output/rincian-pengembangan-usaha') }}">
                      <button type="submit" class="btn btn-primary btn-block" style="margin-left:10px;"> 
                        9.2 Rincian AI Pengembangan Usaha
                      </button>
                    </form>
                  </td>
                  <td class="col-md-4">
                    <form method="GET" action="{{ url('/output/loader-ellipse') }}">
                      <button type="submit" class="btn btn-primary btn-block" style="margin-left:10px;">
                      14 Loader Ellipse 
                      </button>
                    </form>
                  </td>
                </tr>
                <tr>
                  <td class="col-md-4">
                    <form method="GET" action="{{ url('/output/biaya-pemeliharaan') }}">
                      <button type="submit" class="btn btn-primary btn-block" style="margin-left:10px;"> 
                        5.0 Biaya Pemeliharaan
                      </button>
                    </form>
                  </td>
                  <td class="col-md-4">
                    <form method="GET" action="{{ url('/output/rincian-penetapan-pln') }}">
                      <button type="submit" class="btn btn-primary btn-block" style="margin-left:10px;"> 
                        9.3 Rincian AI Penetapan PLN
                      </button>
                    </form>
                  </td>
                  <td class="col-md-4">
                    <form method="GET" action="{{ url('/output/list-prk') }}">
                      <button type="submit" class="btn btn-primary btn-block" style="margin-left:10px;"> 
                      15 List PRK
                      </button>
                    </form>
                  </td>
                </tr>
              </table>
              </div>
          </div>
      </div>
  </div>
</div>
</div>
</div>

@endsection

