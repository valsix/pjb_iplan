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
        .form-horizontal .form-group
        {
            margin-right: 0;
            margin-left: 0;
            margin-top: -13px;
        }

    </style>

@endsection

@section('content')
    <h1> Dashboard Pengendalian </h1>
   
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        Pencarian
      </div>
        <div class="panel-default">
        <br/>
        <form id="form_pencarian" class="form-horizontal form-label-left">
          @php
            $alt_fields = [
              'draft_form_rkau' => $input_draft_form_rkau,
              'draft_form_penyusutan' => $input_draft_form_penyusutan,
              'draft_form_10_pln' => $input_draft_form_10_pln,
              'draft_form_10_pu' => $input_draft_form_10_pu,
              'draft_form_10_pk' => $input_draft_form_10_pk,
              'draft_form_6_reimburse' => $input_draft_form_6_reimburse,
              'draft_form_6_rutin' => $input_draft_form_6_rutin,
              'draft_form_bahan_bakar' => $input_draft_form_bahan_bakar,
              'draft_form_risk_profile' => $input_draft_risk_profile,
            ];
          @endphp

          @foreach($alt_fields as $field_name => $field_value)
            @if (isset($field_value))
            <input type="hidden" class="alt_field" name="{{$field_name}}" value="{{$field_value}}"/>
            @endif
          @endforeach
          <div class="form-group">
            <label class="col-md-2 col-sm-3 col-xs-12">Tahun Anggaran</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <select class="form-control col-md-7 col-xs-12" name="tahun_anggaran" required>
                  <option value="">- Pilih Tahun -</option>
                    @foreach($tahun as $th)
                      <option value="{{$th->tahun}}" @if($input_tahun == $th->tahun) {{'selected'}} @endif>{{$th->tahun}}</option>
                    @endforeach
              </select>
            </div>

            <div class="form-group">
            <label class="col-md-2 col-sm-3 col-xs-12">Struktur Bisnis</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <select class="form-control col-md-7 col-xs-12" name="strategi_bisnis" required>
                  <option value="">- Pilih Struktur Bisnis -</option>
                    @foreach ($sb as $sbs => $value)
                      <option value="{{ $value->id }}" @if($input_sb!=null) {{ $input_sb->id == $value->id ? 'selected' : '' }} @endif > {{ $value->name }} </option>
                    @endforeach
              </select>
            </div>
            </div>
          </div>

          <div class="form-group">
            <label class="col-md-2 col-sm-3 col-xs-12" >Distrik</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <select class="form-control col-md-7 col-xs-12" name="distrik" required>
                  <option value="3">- Pilih Distrik -</option>
                    @if($input_sb!=null && $input_distrik!=null)
                      @foreach($distrik as $d)
                        <option value="{{$d->id}}" {{ $d->id == $input_distrik->id ? 'selected' : '' }}>{{$d->name}}</option>
                      @endforeach
                    @endif
              </select>
            </div>


            <div class="form-group">

            <label class="col-md-2 col-sm-3 col-xs-12">Lokasi</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <input class="form-control col-md-7 col-xs-12" name="lokasi" value ="{{ $input_lokasi }}" required readonly="true">

              </input>
            </div>

          </div>

          </div>

          <div class="ln_solid"></div>
          <div class="form-group">
            <label class="col-md-2 col-sm-3 col-xs-12">Fase</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <select class="form-control col-md-7 col-xs-12" name="fase" required>
                  <option value="">- Pilih Fase -</option>
                    @if($input_sb!=null && $input_distrik!=null)
                      @foreach($fase as $fase)
                        {{-- 3 = Ketetapan dan 4 = intechange --}}
                        <option value="{{$fase->id}}" {{ $fase->id == $input_fase->id ? 'selected' : '' }}>{{$fase->name}}</option>
                      @endforeach
                    @endif
              </select>

            </div>
          
          <div class="form-group">
            <label class="col-md-2 col-sm-3 col-xs-12">s.d Bulan</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <select class="form-control col-md-7 col-xs-12" name="bulan">
                <option value = "12">- Pilih Bulan -</option>
                  @foreach ($months as $key => $value)
                    <option value="{{ $key }}" @if($input_bulan != null) {{ $input_bulan == $key ? 'selected' : '' }} @endif> {{ $value }} </option>
                  @endforeach
              </select>
            </div>
            </div>
          </div>

          <div class="ln_solid"></div>

          <div class="form-group">
            <label class="col-md-2 col-sm-3 col-xs-12">RKAU</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <select class="form-control col-md-7 col-xs-12" id="draft_form_rkau" name="draft_form_rkau">
                @forelse($draft_form_rkau as $draft)
                  <option value="{{$draft->id}}" {{($input_draft_form_rkau == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}} - {{$draft->name}}</option>
                @empty
                  <option value="" disabled="" selected="">-- Tidak ada data --</option>
                @endforelse
              </select>
            </div>
          </div>

          <div class="ln_solid"></div>

          <div class="form-group">
            <label class="col-md-2 col-sm-3 col-xs-12">Form 6 Reimburse</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <select class="form-control col-md-7 col-xs-12" id="draft_form_6_reimburse" name="draft_form_6_reimburse">
                @forelse($draft_form_6_reimburse as $draft)
                  <option value="{{$draft->id}}" {{($input_draft_form_6_reimburse == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}} - {{$draft->name}}</option>
                @empty
                  <option value="" disabled="" selected="">-- Tidak ada data --</option>
                @endforelse
              </select>
            </div>

            <div class="form-group">
            <label class="col-md-2 col-sm-3 col-xs-12">Form 6 Rutin</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <select class="form-control col-md-7 col-xs-12" id="draft_form_6_rutin" name="draft_form_6_rutin">
                @forelse($draft_form_6_rutin as $draft)
                  <option value="{{$draft->id}}" {{($input_draft_form_6_rutin == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}} - {{$draft->name}}</option>
                @empty
                  <option value="" disabled="" selected="">-- Tidak ada data --</option>
                @endforelse
              </select>
            </div>
            </div>
          </div>

          <div class="ln_solid"></div>

          <div class="form-group">
            <label class="col-md-2 col-sm-3 col-xs-12">Form 10 Pengembangan Usaha</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <select class="form-control col-md-7 col-xs-12" id="draft_form_10_pu" name="draft_form_10_pu">
                @forelse($draft_form_10_pu as $draft)
                  <option value="{{$draft->id}}" {{($input_draft_form_10_pu == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}} - {{$draft->name}}</option>
                @empty
                  <option value="" disabled="" selected="">-- Tidak ada data --</option>
                @endforelse
              </select>
            </div>

            <div class="form-group">
            <label class="col-md-2 col-sm-3 col-xs-12">Form 10 Penguatan KIT</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <select class="form-control col-md-7 col-xs-12" id="draft_form_10_pk" name="draft_form_10_pk">
                @forelse($draft_form_10_pk as $draft)
                  <option value="{{$draft->id}}" {{($input_draft_form_10_pk == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}} - {{$draft->name}}</option>
                @empty
                  <option value="" disabled="" selected="">-- Tidak ada data --</option>
                @endforelse
              </select>
            </div>
            </div>
          </div>


          <div class="form-group">
            <label class="col-md-2 col-sm-3 col-xs-12">Form 10 PLN</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <select class="form-control col-md-7 col-xs-12" id="draft_form_10_pln" name="draft_form_10_pln">
                @forelse($draft_form_10_pln as $draft)
                  <option value="{{$draft->id}}" {{($input_draft_form_10_pln == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}} - {{$draft->name}}</option>
                @empty
                  <option value="" disabled="" selected="">-- Tidak ada data --</option>
                @endforelse
              </select>
            </div>

            <div class="form-group">
            <label class="col-md-2 col-sm-3 col-xs-12"></label>
            <div class="col-md-3 col-sm-4 col-xs-12">

            </div>
            </div>
          </div>

          <div class="ln_solid"></div>

          <div class="form-group">
            <label class="col-md-2 col-sm-3 col-xs-12">Form Bahan Bakar</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <select class="form-control col-md-7 col-xs-12" id="draft_form_bahan_bakar" name="draft_form_bahan_bakar">
                @forelse($draft_form_bahan_bakar as $draft)
                  <option value="{{$draft->id}}" {{($input_draft_form_bahan_bakar == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}} - {{$draft->name}}</option>
                @empty
                  <option value="" disabled="" selected="">-- Tidak ada data --</option>
                @endforelse
              </select>
            </div>

          </div>

          <div class="ln_solid"></div>

          <div class="form-group">
            <label class="col-md-2 col-sm-3 col-xs-12">Risk Profile</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <select class="form-control col-md-7 col-xs-12" id="draft_form_risk_profile" name="draft_form_risk_profile">
                @forelse($draft_form_risk_profile as $draft)
                  <option value="{{$draft->id}}" {{($input_draft_risk_profile == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}} - {{$draft->name}}</option>
                @empty
                  <option value="" disabled="" selected="">-- Tidak ada data --</option>
                @endforelse
              </select>
            </div>

            <div class="form-group">
            <label class="col-md-2 col-sm-3 col-xs-12">Form Penyusutan</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <select class="form-control col-md-7 col-xs-12" id="draft_form_penyusutan" name="draft_form_penyusutan">
                @forelse($draft_form_penyusutan as $draft)
                  <option value="{{$draft->id}}" {{($input_draft_form_penyusutan == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}} - {{$draft->name}}</option>
                @empty
                  <option value="" disabled="" selected="">-- Tidak ada data --</option>
                @endforelse
              </select>
            </div>
            </div>
          </div>


          <div class="ln_solid"></div>

              <div class="form-group">
                <div >
                  <button type="submit" class="btn btn-primary pull-right">
                      <span class="glyphicon glyphicon-search"> </span> cari
                  </button>
                </div>
              </div>

          </form>
        </div>
      </div>
    </div>
</div>

@if($input_fase!=null)
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="col-lg-12">
      <div class="panel panel-default">
        
        <div class="panel-heading">Dashboard</div>
          <div class="panel-default">
              <br>
              <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <center>
                    <table>
                    <tr>
                      <tr>
                      <td class="col-md-4 col-sm-3 col-xs-12">
                        
                        <a
                          @if($input_fase!= null)
                          
                          <?php
                            $nama_bln[0] = ''; 
                            $nama_bln[1] = 'Januari'; 
                            $nama_bln[2] = 'Februari'; 
                            $nama_bln[3] = 'Maret'; 
                            $nama_bln[4] = 'April'; 
                            $nama_bln[5] = 'Mei'; 
                            $nama_bln[6] = 'Juni'; 
                            $nama_bln[7] = 'Juli'; 
                            $nama_bln[8] = 'Agustus'; 
                            $nama_bln[9] = 'September'; 
                            $nama_bln[10] = 'Oktober'; 
                            $nama_bln[11] = 'November'; 
                            $nama_bln[12] = 'Desember';
                          ?>

                          href="{{ url('/output/pengendalian/lr?tahun_anggaran='.$input_tahun.'&strategi_bisnis='.$input_sb->name.'&distrik='.$input_distrik->name.'&fase='.$input_fase->name.'&bulan='.$nama_bln[$input_bulan].'') }}" 
                          @else
                          
                          href="{{ url('/output/pengendalian/lr') }}"
                          @endif
                          class="btn btn-primary btn-block" style="margin-left:10px;" target="_blank">
                          
                          1. Laba Rugi
                        </a>
                      </td>
                      <td class="col-md-4 col-sm-3 col-xs-12">
                        <a
                          @if($input_fase!= null)
                          href="{{ url('/output/pengendalian/monitoring_prk_ai_pu_pk?tahun_anggaran='.$input_tahun.'&strategi_bisnis='.$input_sb->id.'&distrik='.$input_distrik->id.'&fase='.$input_fase->id.'&bulan='.$input_bulan.'') }}"
                          @else
                          href="{{ url('/output/pengendalian/monitoring_prk_ai_pu_pk') }}"
                          @endif
                          class="btn btn-primary btn-block" style="margin-left:10px;" target="_blank">
                          
                          2.A. Monitoring PRK AI Pengembangan Usaha & Penguatan Kit
                        </a>
                      </td>
                      <td class="col-md-4 col-sm-3 col-xs-12">
                        <a
                          @if($input_fase!= null)
                          href="{{ url('/output/pengendalian/monitoring_prk_ai_pln_rei?tahun_anggaran='.$input_tahun.'&strategi_bisnis='.$input_sb->id.'&distrik='.$input_distrik->id.'&fase='.$input_fase->id.'&bulan='.$input_bulan.'') }}" 
                          @else
                          href="{{ url('/output/pengendalian/monitoring_prk_ai_pln_rei') }}"
                          @endif
                          class="btn btn-primary btn-block" style="margin-left:10px;" target="_blank">
                          
                          2.B. Monitoring PRK AI PLN
                        </a>
                      </td>
                    </tr>
                    </table>
                  </center>
                </div>
              </div>
              <br>
          </div>
      </div>
    </div>
  </div>
</div>
@endif
@if($input_fase!=null)
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="col-lg-12">
      <div class="panel panel-default">
        <div class="panel-heading">Report</div>
          <div class="panel-default">
              <br>
              <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <center>
                    <table>
                    <tr>
                      <td class="col-md-4 col-sm-3 col-xs-12">
                        <a
                          @if($input_fase!= null)
                          
                          href="{{ url('/output/pengendalian/history_log/ai?tahun_anggaran='.$input_tahun.'&strategi_bisnis='.$input_sb->id.'&distrik='.$input_distrik->id.'&fase='.$input_fase->id.'&bulan='.$input_bulan.'') }}" 
                          @else
            
                          href="{{ url('/output/pengendalian/history_log') }}"
                          @endif
                          class="btn btn-primary btn-block" style="margin-left:10px;" target="_blank">
                          
                          3. History Log AI
                        </a>
                      </td>

                      <td class="col-md-4 col-sm-3 col-xs-12">
                        <a
                          @if($input_fase!= null)
                           
                          href="{{ url('/output/pengendalian/history_log/ao?tahun_anggaran='.$input_tahun.'&strategi_bisnis='.$input_sb->id.'&distrik='.$input_distrik->id.'&fase='.$input_fase->id.'&bulan='.$input_bulan.'') }}"

                          @else
                          
                          href="{{ url('/output/pengendalian/history_log') }}"
                          @endif
                          class="btn btn-primary btn-block" style="margin-left:10px;" target="_blank">
                          
                          4. History Log AO
                        </a>
                      </td>

                      <td class="col-md-4 col-sm-3 col-xs-12">
                        <a
                          @if($input_fase!= null)
                          
                          <?php
                            $nama_bln[0] = ''; 
                            $nama_bln[1] = 'Januari'; 
                            $nama_bln[2] = 'Februari'; 
                            $nama_bln[3] = 'Maret'; 
                            $nama_bln[4] = 'April'; 
                            $nama_bln[5] = 'Mei'; 
                            $nama_bln[6] = 'Juni'; 
                            $nama_bln[7] = 'Juli'; 
                            $nama_bln[8] = 'Agustus'; 
                            $nama_bln[9] = 'September'; 
                            $nama_bln[10] = 'Oktober'; 
                            $nama_bln[11] = 'November'; 
                            $nama_bln[12] = 'Desember';
                          ?>

                          href="{{ url('/output/pengendalian/rekap_lr?tahun_anggaran='.$input_tahun.'&strategi_bisnis='.$input_sb->name.'&distrik='.$input_distrik->name.'&fase='.$input_fase->name.'&bulan='.$nama_bln[$input_bulan].'') }}" 
                          @else
                          
                          href="{{ url('/output/loader-ellipse-pgdl') }}"
                          @endif
                          class="btn btn-primary btn-block" style="margin-left:10px;" target="_blank">
                          
                          5. Rekap LR
                        </a>
                      </td>
                      <td class="col-md-4 col-sm-3 col-xs-12">
                        <a
                          @if($input_fase!= null)

                          href="{{ url('/output/monitoring-prk-ao?tahun_anggaran='.$input_tahun.'&strategi_bisnis='.$input_sb->id.'&distrik='.$input_distrik->id.'&fase='.$input_fase->id.'&bulan='.$input_bulan.'') }}"
                          @else
                          
                          href="{{ url('/output/monitoring-prk-ao') }}"
                          @endif
                          class="btn btn-primary btn-block" style="margin-left:10px;" target="_blank">
                          
                          6. Monitoring PRK AO
                        </a>
                      </td>
                    </tr>
                    
                    <tr>
                      
                      <td class="col-md-4 col-sm-3 col-xs-12">
                        <a
                          @if($input_fase!= null)
                      
                          href="{{ url('/output/pengendalian/ai_pjb?tahun_anggaran='.$input_tahun.'&strategi_bisnis='.$input_sb->id.'&distrik='.$input_distrik->id.'&fase='.$input_fase->id.'&bulan='.$input_bulan.'') }}" 
                          @else
                      
                          href="{{ url('/output/pengendalian/ai_pjb') }}"
                          @endif
                          class="btn btn-primary btn-block" style="margin-left:10px;" target="_blank">

                          7. AI PJB
                        </a>
                      </td>
                      <td class="col-md-4 col-sm-3 col-xs-12">
                        <a
                          @if($input_fase!= null)

                          href="{{ url('/output/loader-ellipse-pgdl?tahun_anggaran='.$input_tahun.'&strategi_bisnis='.$input_sb->id.'&distrik='.$input_distrik->id.'&fase='.$input_fase->id.'&bulan='.$input_bulan.'') }}" 
                          @else

                          href="{{ url('/output/loader-ellipse-pgdl') }}"
                          @endif
                          class="btn btn-primary btn-block" style="margin-left:10px;" target="_blank">

                          8. Loader Ellipse Pengendalian
                        </a>
                      </td>
                    </tr>
                    </table>
                  </center>
                </div>
              </div>
              <br>
          </div>
      </div>
    </div>
  </div>
</div>

@endif   

<script type="text/javascript">
  $(document).ready(function() {
    $('select[name="tahun_anggaran"]').on('change', function() {
        $('select[name="strategi_bisnis"]').val() = '';
        $('select[name="distrik"]').empty();
        $('select[name="lokasi"]').empty();
        $('select[name="fase"]').empty();
    })
  })

  $(document).ready(function() {
      $('select[name="strategi_bisnis"]').on('change', function() {
          var strategi_bisnisID = $(this).val();
          $('select[name="distrik"]').empty();
          $('select[name="lokasi"]').empty();

          if(strategi_bisnisID) {
              $.ajax({
                  url: "{{ url('/output/pencarian-pengendalian/ajax/') }}/"+strategi_bisnisID,
                  type: "GET",
                  dataType: "json",
                  success:function(data) {
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
  function check() {
        var lokasiID = $(this).val();
        $('select[name="lokasi"]').empty();
        if(lokasiID) {
            $.ajax({
                url: "{{ url('output/pencarian-pengendalian/ajax2/') }}/"+lokasiID,
                type: "GET",
                dataType: "json",
                success:function(data) {

                  var temp_name = [];
                  var temp_id = [];
                  
                  $.each(data, function(ad , value) {
                      temp_name.push(value["name"]);
                      temp_id.push(value["id"]);
                  });
                  
                  $('input[name="lokasi"]').val(temp_name);
                }
            });
        }else{
            $('select[name="lokasi"]').empty();
            if(lokasiID) {
                $.ajax({
                    url: '/output/pencarian-pengendalian/ajax2/'+lokasiID,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {
                      var temp_name = [];
                      var temp_id = [];
                      
                      $.each(data, function(ad , value) {draft_form_6_reimburse
                          temp_name.push(value["name"]);
                          temp_id.push(value["id"]);
                      });
                      
                      $('input[name="lokasi"]').val(temp_name);
                    }
                });
            }else{
                $('input[name="lokasi"]').empty();
            }
        }

        }

    $(document).ready(function() {
        $('select[name="distrik"]').on('change', check);
        $('select[name="distrik"]').on('click', check);
    });
</script>

<script type="text/javascript">
  $(document).ready(function() {
    $('select[name="distrik"]').on('click', function() {
      var id_distrik = $('select[name="distrik"]').val();
      
      var id_tahun = $('select[name="tahun_anggaran"]').val();

      $('select[name="fase"]').empty();
      if(id_distrik && id_tahun) {
          $.ajax({
              url: "{{ url('/output/pencarian-pengendalian/ajax_fase/') }}",
              type: "GET",
              dataType: "json",
              success:function(data) {

                if(id_tahun > 2021) {
                  $('select[name="fase"]').append(
                    '<option value="">-Pilih Fase-</option>', 
                    '<option value="3">Ketetapan</option>', 
                    '<option value="4">Interchange</option>'
                  );
                } else {
                  $('select[name="fase"]').append(
                    '<option value="">-Pilih Fase-</option>', 
                    '<option value="3">Ketetapan</option>', 
                  );
                }

              }
          });
      }else{
          $('select[name="fase"]').empty();

      }
    })
  })
</script>

<script type="text/javascript">
  var select_forms = $(`
          #draft_form_rkau,
          #draft_form_6_reimburse,
          #draft_form_6_rutin,
          #draft_form_10_pu,
          #draft_form_10_pk,
          #draft_form_10_pln,
          #draft_form_bahan_bakar,
          #draft_form_risk_profile,
          #draft_form_penyusutan
      `);

  function auto_disable_forms() {
    let id_fase = $('select[name="fase"]').val();

    if (id_fase == 4) {
      select_forms.prop('disabled', false);
    } else {
      select_forms.prop('disabled', true);
    }
  }

  // disable select forms untuk non-interchange
  auto_disable_forms();

  $(document).ready(function() {
      $('select[name="fase"]').on('click', function() {

          var id_fase = $('select[name="fase"]').val();
          var id_distrik = $('select[name="distrik"]').val();
          var id_tahun= $('select[name="tahun_anggaran"]').val();

          // disable select forms untuk non-interchange
          auto_disable_forms();

          const forms = [
            {
              name: 'draft_form_rkau',
              id_jenis: 1,
            },
            {
              name: 'draft_form_6_reimburse',
              id_jenis: 2,
            },
            {
              name: 'draft_form_6_rutin',
              id_jenis: 3,
            },
            {
              name: 'draft_form_10_pu',
              id_jenis: 4,
            },
            {
              name: 'draft_form_10_pk',
              id_jenis: 5,
            },
            {
              name: 'draft_form_10_pln',
              id_jenis: 6,
            },
            {
              name: 'draft_form_bahan_bakar',
              id_jenis: 7,
            },
            {
              name: 'draft_form_risk_profile',
              id_jenis: 8,
            },
            {
              name: 'draft_form_penyusutan',
              id_jenis: 9,
            }
          ]

          forms.map((form) => {
            // set loading msg
            $(`select[name=${form.name}]`).prepend($('<option></option>').html('Loading...'));
            // hapus alt field
            $('form#form_pencarian input.alt_field').remove();

            if(id_distrik && id_tahun && id_fase) {
              $.ajax({
                  url: "{{ url('/output/pencarian-pengendalian/ajax_pencarian/') }}/"+id_distrik+"/"+id_tahun+"/"+id_fase+"/"+form.id_jenis,
                  type: "GET",
                  dataType: "json",
                  success:function(data) {
                    $(`select[name=${form.name}]`).empty();
                    // console.log('data fetch',form.name, data);

                    // ------------------------------------------------------------------------------
                    // NOTE:
                    // untuk fase non-interchange (ketetapan) asumsi hanya ada 1 row data yg
                    // di-return oleh server
                    // ------------------------------------------------------------------------------

                    let field_name = form.name;
                    let field_value = '';

                    if(data.length) {
                      $.each(data, function(key , value) {
                        field_value = value["id"];

                        $(`select[name=${field_name}]`).append(`<option value="${value["id"]}">${value["draft_versi"]} - ${value["name"]}</option>`);
                      });
                    } else {
                      $(`select[name=${field_name}]`).prepend($('<option value="" disabled="" selected=""></option>').html('-- Tidak ada data --'));
                    }

                    if (id_fase != 4) {
                      // buat alt field untuk input yang di-disable
                      $('form#form_pencarian').prepend(`<input type="hidden" class="alt_field" name="${field_name}" value="${field_value}"/>`);
                    }
                  }
              });
            } else {
                $(`select[name=${form.name}]`).empty();
            }

          })
      });
  });
</script>


@endsection
