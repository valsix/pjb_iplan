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
    <h1> Dashboard Perencanaan </h1>
<!-- <div class="row">
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
                                    <select name="tahun_anggaran" class="form-control" required>
                                        <option value="">- Pilih Tahun -</option>
                                        @foreach($tahun as $th)
                                            <option value="{{$th->tahun}}" @if($input_tahun == $th->tahun) {{'selected'}} @endif>{{$th->tahun}}</option>
                                        @endforeach
                                    </select>
                                </div>
                              <div class="col-md-2"><label>Struktur Bisnis</label></div>
                              <div class="col-md-4">
                                  <select class="form-control" name="strategi_bisnis" required>
                                        <option value="">- Pilih Struktur Bisnis -</option>
                                        @foreach ($sb as $sbs => $value)
                                            <option value="{{ $value->id }}" <?php if($input_sb!=null) echo($input_sb->id == $value->id ? 'selected' : '')?> > {{ $value->name }} </option>
                                        @endforeach
                                    </select>
                              </div>
                             
                              <br>
                              <br>
                              <div class="col-md-2"><label>Distrik</label></div>
                              <div class="col-md-4">
                                  <select class="form-control" name="distrik" required>
                                        <option value="">- Pilih Distrik -</option>
                                        @if($input_sb!=null && $input_distrik!=null)
                                            @foreach($distrik as $d)
                                            <option value="{{$d->id}}" <?php echo($d->id == $input_distrik->id ? 'selected' : '')?>>{{$d->name}}</option>
                                            @endforeach
                                        @endif   
                                    </select>
                              </div>

                              <div class="col-md-2"><label> Lokasi</label></div>
                              <div class="col-md-4">
                                  <select class="form-control" name="lokasi" required>
                                        <option value="">- Pilih Lokasi -</option>
                                        @if($input_sb!=null && $input_distrik!=null && $input_lokasi!=null)
                                            @foreach($lokasi as $l)
                                            <option value="{{$l->id}}" <?php echo($l->id == $input_lokasi->id ? 'selected' : '')?>>{{$l->name}}</option>
                                            @endforeach
                                        @endif   
                                    </select>
                              </div><br><br>

                            <hr>
                            <div class="row">
                              <div class="col-lg-12">
                               <div class="col-md-2"><label>Fase</label></div>
                                <div class="col-md-4">
                                    <select class="form-control" name="fase" required>
                                        <option value="">- Pilih Fase -</option>
                                          @foreach ($fase as $fases => $value)
                                            <option value="{{ $value->id }}" <?php if($input_fase!= null) echo($input_fase->id == $value->id ? 'selected' : '')?>> {{ $value->name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                                </div>
                              </div>
                              <hr>
                              <div class="row" style="margin-top: 5px;">
                                <div class="col-lg-12">
                                  <div class="col-md-2"><label>RKAU</label></div>
                                    <div class="col-md-4">
                                      <select class="form-control" name="draft_rkau">
                                          <option value="" disabled="">-- Pilih RKAU --</option>
                                          @if($input_draft_rkau!= null)
                                              @foreach($draft_form_rkau as $draft)
                                                  <option value="{{$draft->id}}" {{($input_draft_rkau->id == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}} - {{$draft->name}}</option>
                                              @endforeach
                                          @endif
                                      </select>
                                    </div>
                                </div>
                              </div>
                              <hr>
                              <div class="row">
                                <div class="col-lg-12">
                                  <div class="col-md-2"><label>Form 6 - Reimburse</label></div>
                                  <div class="col-md-4">
                                      <select class="form-control" name="draft_form_6_reimburse">
                                          <option value="" disabled="">-- Pilih Form 6 Reimburse --</option>
                                          @if($input_draft_form_6_reimburse!= null)
                                              @foreach($draft_form_6_reimburse as $draft)
                                                  <option value="{{$draft->id}}" {{($input_draft_form_6_reimburse->id == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}} - {{$draft->name}}</option>
                                              @endforeach
                                          @endif
                                      <img class="loading" src="{{ asset('images/ajax-loader.gif') }}" style="display:none"/>
                                      </select>
                                  </div>
                                  <div class="col-md-2"><label>Form 6 - Rutin</label></div>
                                  <div class="col-md-4">
                                      <select class="form-control" name="draft_form_6_rutin" >
                                        <option value="" disabled="">-- Pilih Form 6 Rutin --</option>
                                          @if($input_draft_form_6_rutin!= null)
                                              @foreach($draft_form_6_rutin as $draft)
                                                  <option value="{{$draft->id}}" {{($input_draft_form_6_rutin->id == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}} - {{$draft->name}}</option>
                                              @endforeach
                                          @endif
                                      </select>
                                  </div>
                                </div>
                              </div>
                              <hr>
                              <div class="row">
                                <div class="col-lg-12">
                                  <div class="col-md-2"><label>Form 10 Pengembangan Usaha</label></div>
                                  <div class="col-md-4">
                                    <select class="form-control" name="draft_form_10_pu" >
                                        <option value="" disabled="">-- Pilih Form 10 Pengembangan Usaha --</option>
                                        @if($input_draft_form_10_pu!= null)
                                            @foreach($draft_form_10_pu as $draft)
                                                <option value="{{$draft->id}}" {{($input_draft_form_10_pu->id == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}} - {{$draft->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                  </div>

                                  <div class="col-md-2"><label>Form 10 Penguatan KIT</label></div>
                                  <div class="col-md-4">
                                    <select class="form-control" name="draft_form_10_pk" >
                                        <option value="" disabled="">-- Pilih Form 10 Penguatan Kit --</option>
                                        @if($input_draft_form_10_pk!= null)
                                            @foreach($draft_form_10_pk as $draft)
                                                <option value="{{$draft->id}}" {{($input_draft_form_10_pk->id == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}} - {{$draft->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                  </div>
                                </div>
                              </div>
                              <div class="row" style="margin-top: 5px;">
                                <div class="col-md-12">
                                  <div class="col-md-2"><label>Form 10 PLN </label></div>
                                  <div class="col-md-4">
                                    <select class="form-control" name="draft_form_10_pln" >
                                        <option value="" disabled="">-- Pilih Form 10 PLN --</option>
                                        @if($input_draft_form_10_pln!= null)
                                            @foreach($draft_form_10_pln as $draft)
                                                <option value="{{$draft->id}}" {{($input_draft_form_10_pln->id == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}} - {{$draft->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                  </div>
                                </div>
                              </div>
                              <hr>
                              <div class="row" style="margin-top: 5px;">
                                <div class="col-md-12">
                                  <div class="col-md-2"><label>Form Bahan Bakar </label></div>
                                  <div class="col-md-4">
                                    <select class="form-control" name="draft_form_bahan_bakar">
                                        <option value="" disabled="">-- Pilih Form Bahan Bakar --</option>
                                        @if($input_draft_form_bahan_bakar!= null)
                                            @foreach($draft_form_bahan_bakar as $draft)
                                                <option value="{{$draft->id}}" {{($input_draft_form_bahan_bakar->id == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}} - {{$draft->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                  </div>

                                  <div class="col-md-2"><label>s.d Bulan</label></div>
                                  <div class="col-md-4">
                                      <select class="form-control" name="bulan">
                                          <option>- Pilih Bulan -</option>
                                          @foreach ($months as $key => $value)
                                              <option value="{{ $key }}" <?php if($input_bulan != null) echo($input_bulan == $key ? 'selected' : '')?>> {{ $value }} </option>
                                          @endforeach
                                      </select>
                                  </div>
                                </div>
                              </div>
                              <hr>
                              <div class="row" style="margin-top: 5px;">
                                <div class="col-md-12">
                                  <div class="col-md-2"><label>Risk Profile</label></div>
                                  <div class="col-md-4">
                                      <select class="form-control" name="draft_risk_profile">
                                         <option value="" disabled="">- Pilih Risk Profile -</option>
                                         @if($input_draft_risk_profile!= null)
                                            @foreach($draft_form_risk_profile as $draft)
                                                <option value="{{$draft->id}}" {{($input_draft_risk_profile->id == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}} - {{$draft->name}}</option>
                                            @endforeach
                                        @endif
                                      </select>
                                  </div>

                                  <div class="col-md-2"><label>Form Penyusutan</label></div>
                                  <div class="col-md-4">
                                    <select class="form-control" name="draft_form_penyusutan" >
                                      <option value="" disabled="">- Pilih Form Penyusutan -</option>
                                        @if($input_draft_form_penyusutan!= null)
                                            @foreach($draft_form_penyusutan as $draft)
                                                <option value="{{$draft->id}}" {{($input_draft_form_penyusutan->id == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}} - {{$draft->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                  </div>
                                  
                                </div>
                              </div>

                              <div class="row" style="margin-top: 15px;">
                              <div class="col-lg-12">
                                   <button type="submit" class="btn btn-primary pull-right" style="margin-right: 10px;"> 
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
</div> -->

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        Pencarian     
      </div>
        <div class="panel-default">
        <br/>
        <form class="form-horizontal form-label-left">

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
                      <option value="{{ $value->id }}" <?php if($input_sb!=null) echo($input_sb->id == $value->id ? 'selected' : '')?> > {{ $value->name }} </option>
                    @endforeach
              </select>
            </div>
            </div>
          </div>

          <div class="form-group">
            <label class="col-md-2 col-sm-3 col-xs-12" >Distrik</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <select class="form-control col-md-7 col-xs-12" name="distrik" required>
                  <option value="">- Pilih Distrik -</option>
                    @if($input_sb!=null && $input_distrik!=null)
                      @foreach($distrik as $d)
                        <option value="{{$d->id}}" <?php echo($d->id == $input_distrik->id ? 'selected' : '')?>>{{$d->name}}</option>
                      @endforeach
                    @endif   
              </select>
            </div>

            <div class="form-group">
            <label class="col-md-2 col-sm-3 col-xs-12">Lokasi</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <select class="form-control col-md-7 col-xs-12" name="lokasi" required>
                  <option value="">- Pilih Lokasi -</option>
                    @if($input_sb!=null && $input_distrik!=null && $input_lokasi!=null)
                      @foreach($lokasi as $l)
                        <option value="{{$l->id}}" <?php echo($l->id == $input_lokasi->id ? 'selected' : '')?>>{{$l->name}}</option>
                      @endforeach
                    @endif   
              </select>
            </div>
          </div>
          </div>

          <div class="ln_solid"></div>

          <div class="form-group">
            <label class="col-md-2 col-sm-3 col-xs-12">Fase</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <select class="form-control col-md-7 col-xs-12" name="fase" required>
                  <option value="">- Pilih Fase -</option>
                      @foreach ($fase as $fases => $value)
                        <option value="{{ $value->id }}" <?php if($input_fase!= null) echo($input_fase->id == $value->id ? 'selected' : '')?>> {{ $value->name }} </option>
                      @endforeach
              </select>
            </div>
          </div>

          <div class="ln_solid"></div>

          <div class="form-group">
            <label class="col-md-2 col-sm-3 col-xs-12">RKAU</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <select class="form-control col-md-7 col-xs-12" name="draft_rkau">
                <option value="" disabled="">-- Pilih RKAU --</option>
                  @if($input_draft_rkau!= null)
                    @foreach($draft_form_rkau as $draft)
                      <option value="{{$draft->id}}" {{($input_draft_rkau->id == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}} - {{$draft->name}}</option>
                    @endforeach
                  @endif
              </select>
            </div>
          </div>

          <div class="ln_solid"></div>

          <div class="form-group">
            <label class="col-md-2 col-sm-3 col-xs-12">Draft Form 6 Reimburse</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <select class="form-control col-md-7 col-xs-12" name="draft_form_6_reimburse">
                <option value="" disabled="">-- Pilih Form 6 Reimburse --</option>
                  @if($input_draft_form_6_reimburse!= null)
                    @foreach($draft_form_6_reimburse as $draft)
                      <option value="{{$draft->id}}" {{($input_draft_form_6_reimburse->id == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}} - {{$draft->name}}</option>
                    @endforeach
                  @endif
                <img class="loading" src="{{ asset('images/ajax-loader.gif') }}" style="display:none"/>
              </select>
            </div>

            <div class="form-group">
            <label class="col-md-2 col-sm-3 col-xs-12">Draft Form 6 Rutin</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <select class="form-control col-md-7 col-xs-12" name="draft_form_6_rutin">
                <option value="" disabled="">-- Pilih Form 6 Rutin --</option>
                  @if($input_draft_form_6_rutin!= null)
                    @foreach($draft_form_6_rutin as $draft)
                      <option value="{{$draft->id}}" {{($input_draft_form_6_rutin->id == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}} - {{$draft->name}}</option>
                    @endforeach
                  @endif
              </select>
            </div>
            </div>
          </div>

          <div class="ln_solid"></div>

          <div class="form-group">
            <label class="col-md-2 col-sm-3 col-xs-12">Draft Form 10 Pengembangan Usaha</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <select class="form-control col-md-7 col-xs-12" name="draft_form_10_pu">
                <option value="" disabled="">-- Pilih Form 10 Pengembangan Usaha --</option>
                  @if($input_draft_form_10_pu!= null)
                    @foreach($draft_form_10_pu as $draft)
                      <option value="{{$draft->id}}" {{($input_draft_form_10_pu->id == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}} - {{$draft->name}}</option>
                    @endforeach
                  @endif
              </select>
            </div>

            <div class="form-group">
            <label class="col-md-2 col-sm-3 col-xs-12">Draft Form 10 Penguatan KIT</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <select class="form-control col-md-7 col-xs-12" name="draft_form_10_pk">
                <option value="" disabled="">-- Pilih Form 10 Penguatan Kit --</option>
                  @if($input_draft_form_10_pk!= null)
                    @foreach($draft_form_10_pk as $draft)
                      <option value="{{$draft->id}}" {{($input_draft_form_10_pk->id == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}} - {{$draft->name}}</option>
                    @endforeach
                  @endif
              </select>
            </div>
            </div>
          </div>


          <div class="form-group">
            <label class="col-md-2 col-sm-3 col-xs-12">Draft Form 10 PLN</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <select class="form-control col-md-7 col-xs-12" name="draft_form_10_pln">
                <option value="" disabled="">-- Pilih Form 10 PLN --</option>
                  @if($input_draft_form_10_pln!= null)
                    @foreach($draft_form_10_pln as $draft)
                      <option value="{{$draft->id}}" {{($input_draft_form_10_pln->id == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}} - {{$draft->name}}</option>
                    @endforeach
                  @endif
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
            <label class="col-md-2 col-sm-3 col-xs-12">Draft Form Bahan Bakar</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <select class="form-control col-md-7 col-xs-12" name="draft_form_bahan_bakar">
                <option value="" disabled="">-- Pilih Form Bahan Bakar --</option>
                  @if($input_draft_form_bahan_bakar!= null)
                    @foreach($draft_form_bahan_bakar as $draft)
                      <option value="{{$draft->id}}" {{($input_draft_form_bahan_bakar->id == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}} - {{$draft->name}}</option>
                    @endforeach
                  @endif
              </select>
            </div>

            <div class="form-group">
            <label class="col-md-2 col-sm-3 col-xs-12">s.d Bulan</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <select class="form-control col-md-7 col-xs-12" name="bulan">
                <!-- <option>- Pilih Bulan -</option> -->
                  @foreach ($months as $key => $value)
                    <option value="{{ $key }}" <?php if($input_bulan != null) echo($input_bulan == $key ? 'selected' : '')?>> {{ $value }} </option>
                  @endforeach
              </select>
            </div>
            </div>
          </div>

          <div class="ln_solid"></div>

          <div class="form-group">
            <label class="col-md-2 col-sm-3 col-xs-12">Risk Profile</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <select class="form-control col-md-7 col-xs-12" name="draft_risk_profile">
                <option value="" disabled="">- Pilih Risk Profile -</option>
                  @if($input_draft_risk_profile!= null)
                    @foreach($draft_form_risk_profile as $draft)
                      <option value="{{$draft->id}}" {{($input_draft_risk_profile->id == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}} - {{$draft->name}}</option>
                    @endforeach
                  @endif
              </select>
            </div>

            <div class="form-group">
            <label class="col-md-2 col-sm-3 col-xs-12">Draft Form Penyusutan</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
              <select class="form-control col-md-7 col-xs-12" name="draft_form_penyusutan">
                <option value="" disabled="">- Pilih Form Penyusutan -</option>
                  @if($input_draft_form_penyusutan!= null)
                    @foreach($draft_form_penyusutan as $draft)
                      <option value="{{$draft->id}}" {{($input_draft_form_penyusutan->id == $draft->id ? "selected" : '')}} >{{$draft->draft_versi}} - {{$draft->name}}</option>
                    @endforeach
                  @endif
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
        <div class="panel-heading">Report Dasbhoard</div>
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
                          href="{{ url('/output/risk-profile?tahun_anggaran='.$input_tahun.'&strategi_bisnis='.$input_sb->id.'&distrik='.$input_distrik->id.'&lokasi='.$input_lokasi->id.'&fase='.$input_fase->id.'&draft='.$input_draft_request_risk_profile.'') }}" 
                          @else
                          href="{{ url('/output/risk-profile') }}"
                          @endif
                          class="btn btn-primary btn-block" style="margin-left:10px;" target="_blank"> 
                          1.1 Risk Profile
                        </a>
                      </td>
                      <td class="col-md-4 col-sm-3 col-xs-12">
                        <a 
                          @if($input_fase!= null)
                          href="{{ url('/output/status-dmr?tahun_anggaran='.$input_tahun.'&strategi_bisnis='.$input_sb->id.'&distrik='.$input_distrik->id.'&lokasi='.$input_lokasi->id.'&fase='.$input_fase->id.'&form_6_reimburse='.$input_draft_request_form_6_reimburse.'&form_6_rutin='.$input_draft_request_form_6_rutin.'&form_10_pu='.$input_draft_request_form_10_pu.'&form_10_pk='.$input_draft_request_form_10_pk.'&form_10_pln='.$input_draft_request_form_10_pln.'') }}" 
                          @else
                          href="{{ url('/output/status-dmr') }}"
                          @endif
                          class="btn btn-primary btn-block" style="margin-left:10px;" target="_blank"> 
                          6.0 Status DMR
                        </a>
                      </td>
                      <td class="col-md-4 col-sm-3 col-xs-12">
                        <a 
                          @if($input_fase!= null)
                          href="{{ url('/output/rincian-biaya-pegawai?tahun_anggaran='.$input_tahun.'&strategi_bisnis='.$input_sb->id.'&distrik='.$input_distrik->id.'&lokasi='.$input_lokasi->id.'&fase='.$input_fase->id.'&draft_rkau='.$input_draft_request_rkau.'') }}" 
                          @else
                          href="{{ url('/output/rincian-biaya-pegawai') }}"
                          @endif
                          class="btn btn-primary btn-block" style="margin-left:10px;" target="_blank"> 
                          10 Rincian Biaya Pegawai
                        </a>
                      </td>
                    </tr>
                    <tr>
                      <td class="col-md-4 col-sm-3 col-xs-12">
                        <a 
                          @if($input_fase!= null)
                          href="{{ url('/output/mitigasi-risiko?tahun='.$input_tahun.'&strategi_bisnis='.$input_sb->id.'&distrik='.$input_distrik->id.'&lokasi='.$input_lokasi->id.'&fase='.$input_fase->id.'&reimburse='.$input_draft_request_form_6_reimburse.'&rutin='.$input_draft_request_form_6_rutin.'&usaha='.$input_draft_request_form_10_pu.'&kit='.$input_draft_request_form_10_pk.'&pln='.$input_draft_request_form_10_pln.'&register='.$input_draft_request_risk_profile.'') }}" 
                          @else
                          href="{{ url('/output/mitigasi-risiko') }}"
                          @endif
                          class="btn btn-primary btn-block" style="margin-left:10px;" target="_blank"> 
                          1.2 Mitigasi Resiko
                        </a>
                      </td>
                      <td class="col-md-4 col-sm-3 col-xs-12">
                        <a 
                          @if($input_fase!= null)
                          href="{{ url('/output/rincian-biaya-har?tahun_anggaran='.$input_tahun.'&strategi_bisnis='.$input_sb->id.'&distrik='.$input_distrik->id.'&lokasi='.$input_lokasi->id.'&fase1='.$input_fase->id.'&draft1='.$input_draft_request_form_6_rutin.'') }}" 
                          @else
                          href="{{ url('/output/rincian-biaya-har') }}"
                          @endif
                          class="btn btn-primary btn-block" style="margin-left:10px;" target="_blank"> 
                          7.0 Rincian Biaya HAR Rutin
                        </a>
                      </td>
                      <td class="col-md-4 col-sm-3 col-xs-12">
                        <a 
                          @if($input_fase!= null)
                          href="{{ url('/output/rincian-biaya-administrasi?tahun_anggaran='.$input_tahun.'&strategi_bisnis='.$input_sb->id.'&distrik='.$input_distrik->id.'&lokasi='.$input_lokasi->id.'&fase='.$input_fase->id.'&draft_rkau='.$input_draft_request_rkau.'') }}" 
                          @else
                          href="{{ url('/output/rincian-biaya-administrasi') }}"
                          @endif
                          class="btn btn-primary btn-block" style="margin-left:10px;" target="_blank"> 
                          11 Rincian Biaya Administrasi
                        </a>
                      </td>
                    </tr>
                    <tr>
                      <td class="col-md-4 col-sm-3 col-xs-12">
                        <a 
                          @if($input_fase!= null)
                          href="{{ url('/output/rencana-kinerja?tahun_anggaran='.$input_tahun.'&strategi_bisnis='.$input_sb->id.'&distrik='.$input_distrik->id.'&lokasi='.$input_lokasi->id.'&fase='.$input_fase->id.'&draft_rkau='.$input_draft_request_rkau.'') }}" 
                          @else
                          href="{{ url('/output/rencana-kinerja') }}"
                          @endif
                          class="btn btn-primary btn-block" style="margin-left:10px;" target="_blank"> 
                          2.0 Rencana Kerja
                        </a>
                      </td>
                      <td class="col-md-4 col-sm-3 col-xs-12">
                        <a 
                          @if($input_fase!= null)
                          href="{{ url('/output/rincian-biaya-har-reimburse?tahun_anggaran='.$input_tahun.'&strategi_bisnis='.$input_sb->id.'&distrik='.$input_distrik->id.'&lokasi='.$input_lokasi->id.'&fase1='.$input_fase->id.'&draft1='.$input_draft_request_form_6_reimburse.'') }}" 
                          @else
                          href="{{ url('/output/rincian-biaya-har-reimburse') }}"
                          @endif
                          class="btn btn-primary btn-block" style="margin-left:10px;" target="_blank"> 
                          8.0 Rincian Biaya HAR Reimburse
                        </a>
                      </td>
                      <td class="col-md-4 col-sm-3 col-xs-12">
                        <a 
                          @if($input_fase!= null)
                            <?php //OM: ambil dari RKAU
                              if($input_sb->id==1) { ?>
                              href="{{ url('/output/rincian-energi-primer?tahun_anggaran='.$input_tahun.'&strategi_bisnis='.$input_sb->id.'&distrik='.$input_distrik->id.'&lokasi='.$input_lokasi->id.'&fase='.$input_fase->id.'&draft_id='.$input_draft_request_rkau.'&bulan='.$input_bulan.'') }}" 
                            <?php //UP: ambil dari Bahan Bakar
                            } elseif($input_sb->id==2) { ?>
                              href="{{ url('/output/rincian-energi-primer?tahun_anggaran='.$input_tahun.'&strategi_bisnis='.$input_sb->id.'&distrik='.$input_distrik->id.'&lokasi='.$input_lokasi->id.'&fase='.$input_fase->id.'&draft_id='.$input_draft_request_form_bahan_bakar.'&bulan='.$input_bulan.'') }}" 
                            <?php } ?>
                          @else
                          href="{{ url('/output/rincian-energi-primer') }}"
                          @endif
                          class="btn btn-primary btn-block" style="margin-left:10px;" target="_blank"> 
                          12 Rincian Energi Primer
                        </a>
                      </td>
                    </tr>
                    <tr>
                      <td class="col-md-4 col-sm-3 col-xs-12">
                        <a 
                          @if($input_fase!= null)
                          href="{{ url('/output/program-strategis?tahun_anggaran='.$input_tahun.'&strategi_bisnis='.$input_sb->id.'&distrik='.$input_distrik->id.'&lokasi='.$input_lokasi->id.'&fase='.$input_fase->id.'&draft_form_6_reimburse='.$input_draft_request_form_6_reimburse.'&draft_form_6_rutin='.$input_draft_request_form_6_rutin.'&draft_form_10_pu='.$input_draft_request_form_10_pu.'&draft_form_10_pk='.$input_draft_request_form_10_pk.'&draft_form_10_pln='.$input_draft_request_form_10_pln.'') }}" 
                          @else
                          href="{{ url('/output/program-strategis') }}"
                          @endif
                          class="btn btn-primary btn-block" style="margin-left:10px;" target="_blank"> 
                          3.0 Program Strategis
                        </a>
                      </td>
                      <td class="col-md-4 col-sm-3 col-xs-12">
                        <a 
                          @if($input_fase!= null)
                          href="{{ url('/output/rincian-penetapan-ai?tahun_anggaran='.$input_tahun.'&strategi_bisnis='.$input_sb->id.'&distrik='.$input_distrik->id.'&lokasi='.$input_lokasi->id.'&fase='.$input_fase->id.'&draft1='.$input_draft_request_form_10_pk.'') }}" 
                          @else
                          href="{{ url('/output/rincian-penetapan-ai') }}"
                          @endif
                          class="btn btn-primary btn-block" style="margin-left:10px;" target="_blank"> 
                          9.1 Rincian AI Pembangkit
                        </a>
                      </td>
                      <td class="col-md-4 col-sm-3 col-xs-12">
                        <a 
                          @if($input_fase!= null)
                          href="{{ url('/output/form-luar-operasi?tahun_anggaran='.$input_tahun.'&strategi_bisnis='.$input_sb->id.'&distrik='.$input_distrik->id.'&lokasi='.$input_lokasi->id.'&fase='.$input_fase->id.'&draft_rkau='.$input_draft_request_rkau.'') }}" 
                          @else
                          href="{{ url('/output/form-luar-operasi') }}"
                          @endif
                          class="btn btn-primary btn-block" style="margin-left:10px;" target="_blank"> 
                          13 Rincian Biaya Luar Operasi
                        </a>
                      </td>
                    </tr>
                    <tr>
                      <td class="col-md-4 col-sm-3 col-xs-12">
                        <a 
                          @if($input_fase!= null && $input_draft_request_rkau!=null)
                          href="{{ url('/output/laba-rugi?tahun1='.$input_tahun.'&strategi_bisnis1='.$input_sb->id.'&distrik1='.$input_distrik->id.'&lokasi1='.$input_lokasi->id.'&fase1='.$input_fase->id.'&draft1='.$input_draft_request_rkau.'') }}" 
                          @else
                          href="{{ url('/output/laba-rugi') }}"
                          @endif
                          class="btn btn-primary btn-block" style="margin-left:10px;" target="_blank"> 
                          4.0 Laba Rugi
                        </a>
                      </td>
                      <td class="col-md-4 col-sm-3 col-xs-12">
                        <a 
                          @if($input_fase!= null)
                          href="{{ url('/output/rincian-pengembangan-usaha?tahun_anggaran='.$input_tahun.'&strategi_bisnis='.$input_sb->id.'&distrik='.$input_distrik->id.'&lokasi='.$input_lokasi->id.'&fase='.$input_fase->id.'&draft1='.$input_draft_request_form_10_pu.'') }}" 
                          @else
                          href="{{ url('/output/rincian-pengembangan-usaha') }}"
                          @endif
                          class="btn btn-primary btn-block" style="margin-left:10px;" target="_blank"> 
                          9.2 Rincian AI Pengembangan Usaha
                        </a>
                      </td>
                      <td class="col-md-4 col-sm-3 col-xs-12">
                        <a 
                          @if($input_fase!= null)
                          href="{{ url('/output/loader-ellipse?tahun_anggaran='.$input_tahun.'&strategi_bisnis='.$input_sb->id.'&distrik='.$input_distrik->id.'&lokasi='.$input_lokasi->id.'&fase='.$input_fase->id.'&draft_rkau='.$input_draft_request_rkau.'&draft_form_6_reimburse='.$input_draft_request_form_6_reimburse.'&draft_form_6_rutin='.$input_draft_request_form_6_rutin.'&draft_form_10_pu='.$input_draft_request_form_10_pu.'&draft_form_10_pk='.$input_draft_request_form_10_pk.'&draft_form_10_pln='.$input_draft_request_form_10_pln.'&draft_form_bahan_bakar='.$input_draft_request_form_bahan_bakar.'&draft_form_penyusutan='.$input_draft_request_form_penyusutan.'') }}" 
                          @else
                          href="{{ url('/output/loader-ellipse') }}"
                          @endif
                          class="btn btn-primary btn-block" style="margin-left:10px;" target="_blank"> 
                          14 Loader Ellipse 
                        </a>
                      </td>
                    </tr>
                    <tr>
                      <td class="col-md-4 col-sm-3 col-xs-12">
                        <a 
                          @if($input_fase!= null)
                          href="{{ url('/output/biaya-pemeliharaan-rutin?tahun='.$input_tahun.'&strategi_bisnis='.$input_sb->id.'&distrik='.$input_distrik->id.'&lokasi='.$input_lokasi->id.'&fase='.$input_fase->id.'&reimburse='.$input_draft_request_form_6_reimburse.'&rutin='.$input_draft_request_form_6_rutin.'') }}" 
                          @else
                          href="{{ url('/output/biaya-pemeliharaan-rutin') }}"
                          @endif
                          class="btn btn-primary btn-block" style="margin-left:10px;" target="_blank"> 
                          5.1 Biaya Pemeliharaan Rutin
                        </a>
                      </td>
                      <td class="col-md-4 col-sm-3 col-xs-12">
                        <a 
                          @if($input_fase!= null)
                          href="{{ url('/output/rincian-penetapan-pln?tahun_anggaran='.$input_tahun.'&strategi_bisnis='.$input_sb->id.'&distrik='.$input_distrik->id.'&lokasi='.$input_lokasi->id.'&fase='.$input_fase->id.'&draft1='.$input_draft_request_form_10_pln.'') }}" 
                          @else
                          href="{{ url('/output/rincian-penetapan-pln') }}"
                          @endif
                          class="btn btn-primary btn-block" style="margin-left:10px;" target="_blank"> 
                          9.3 Rincian AI PLN
                        </a>
                      </td>
                      <td class="col-md-4 col-sm-3 col-xs-12">
                        <a 
                          @if($input_fase!= null)
                          href="{{ url('/output/list-prk?tahun_anggaran='.$input_tahun.'&strategi_bisnis='.$input_sb->id.'&distrik='.$input_distrik->id.'&lokasi='.$input_lokasi->id.'&fase='.$input_fase->id.'&draft_rkau='.$input_draft_request_rkau.'&draft_form_6_reimburse='.$input_draft_request_form_6_reimburse.'&draft_form_6_rutin='.$input_draft_request_form_6_rutin.'&draft_form_10_pu='.$input_draft_request_form_10_pu.'&draft_form_10_pk='.$input_draft_request_form_10_pk.'&draft_form_10_pln='.$input_draft_request_form_10_pln.'&draft_form_bahan_bakar='.$input_draft_request_form_bahan_bakar.'&draft_form_penyusutan='.$input_draft_request_form_penyusutan.'') }}" 
                          @else
                          href="{{ url('/output/list-prk') }}"
                          @endif
                          class="btn btn-primary btn-block" style="margin-left:10px;" target="_blank"> 
                          15 List PRK
                        </a>
                    </tr>
                    <tr>
                      <td class="col-md-4 col-sm-3 col-xs-12">
                        <a 
                          @if($input_fase!= null)
                          href="{{ url('/output/biaya-pemeliharaan-reimburse?tahun='.$input_tahun.'&strategi_bisnis='.$input_sb->id.'&distrik='.$input_distrik->id.'&lokasi='.$input_lokasi->id.'&fase='.$input_fase->id.'&reimburse='.$input_draft_request_form_6_reimburse.'&rutin='.$input_draft_request_form_6_rutin.'') }}" 
                          @else
                          href="{{ url('/output/biaya-pemeliharaan-reimburse') }}"
                          @endif
                          class="btn btn-primary btn-block" style="margin-left:10px;" target="_blank"> 
                          5.2 Biaya Pemeliharaan Reimburse
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
      $('select[name="strategi_bisnis"]').on('change', function() {
          var strategi_bisnisID = $(this).val();
          $('select[name="distrik"]').empty();
          $('select[name="lokasi"]').empty();

          if(strategi_bisnisID) {
              $.ajax({
                  url: "{{ url('/output/pencarian/ajax/') }}/"+strategi_bisnisID,
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
                url: "{{ url('output/pencarian/ajax2/') }}/"+lokasiID,
                type: "GET",
                dataType: "json",
                success:function(data) {

                  $('select[name="lokasi"]').empty();
                  $('select[name="lokasi"]').append('<option selected="" value="" disabled="">Pilih</option>');
                  // $('select[name="lokasi"]').append('<option value="">==Silahkan Pilih Lokasi==</option>');
                  $.each(data, function(ad , value) {
                      $('select[name="lokasi"]').append('<option value="'+ value["id"] +'">'+ value["name"] +'</option>');
                  });

                }
            });
        }else{
            $('select[name="lokasi"]').empty();
            if(lokasiID) {
                $.ajax({
                    url: '/output/pencarian/ajax2/'+lokasiID,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {

                      $('select[name="lokasi"]').empty();
                      // $('select[name="lokasi"]').append('<option value="">==Silahkan Pilih Lokasi==</option>');
                      $.each(data, function(ad , value) {
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

<script type="text/javascript">
  $(document).ready(function() {
    $('select[name="lokasi"]').on('change', function() {
      var id_lokasi = $('select[name="lokasi"]').val();
      var id_tahun = $('select[name="tahun_anggaran"]').val();

      $('select[name="fase"]').empty();

      if(id_lokasi && id_tahun) {
          $.ajax({
              url: "{{ url('/output/pencarian/ajax_fase/') }}",
              type: "GET",
              dataType: "json",
              success:function(data) {

                $('select[name="fase"]').append('<option value="">- Pilih Fase -</option>');

                $.each(data, function(ad , value) {
                    $('select[name="fase"]').append('<option value="'+ value["id"] +'">'+ value["name"] +'</option>');
                });

              }
          });
      }else{
          $('select[name="fase"]').empty();

      }
    })
  })
</script>

<script type="text/javascript">
  $(document).ready(function() {
    $('select[name="fase"]').on('change', function() {
      var id_lokasi = $('select[name="lokasi"]').val();
      var id_tahun = $('select[name="tahun_anggaran"]').val();
      var id_fase = $('select[name="fase"]').val();

      $('select[name="draft_rkau"]').empty();
      $('select[name="draft_rkau"]').prepend($('<option></option>').html('Loading...'));

      if(id_lokasi && id_tahun && id_fase) {
          $.ajax({
              url: "{{ url('/output/pencarian/ajax3/') }}/"+id_lokasi+"/"+id_tahun+"/"+id_fase,
              type: "GET",
              dataType: "json",
              success:function(data) {
                $('select[name="draft_rkau"]').empty();

                if(data.length) {
                  $.each(data, function(ad , value) {
                      $('select[name="draft_rkau"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +' - '+ value["name"] +'</option>');
                  });
                }
                else {
                  $('select[name="draft_rkau"]').prepend($('<option value=""></option>').html('- Tidak ada data -'));
                }
              }
          });
      }else{
          $('select[name="draft_rkau"]').empty();
      }
    })
  })
</script>

<script type="text/javascript">
  $(document).ready(function() {
    $('select[name="fase"]').on('change', function() {
      var id_lokasi = $('select[name="lokasi"]').val();
      var id_tahun = $('select[name="tahun_anggaran"]').val();
      var id_fase = $('select[name="fase"]').val();

      $('select[name="draft_form_6_reimburse"]').empty();
      $('select[name="draft_form_6_reimburse"]').prepend($('<option></option>').html('Loading...'));

      if(id_lokasi && id_tahun && id_fase) {
          $.ajax({
              url: "{{ url('/output/pencarian/ajax4/') }}/"+id_lokasi+"/"+id_tahun+"/"+id_fase,
              type: "GET",
              dataType: "json",
              success:function(data) {
                $('select[name="draft_form_6_reimburse"]').empty();

                if(data.length) {
                  $.each(data, function(ad , value) {
                      $('select[name="draft_form_6_reimburse"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +' - '+ value["name"] +'</option>');
                  });
                }
                else {
                  $('select[name="draft_form_6_reimburse"]').prepend($('<option value=""></option>').html('- Tidak ada data -'));
                }
              }
          });
      }else{
          $('select[name="draft_form_6_reimburse"]').empty();

      }
    })
  })
</script>

<script type="text/javascript">
  $(document).ready(function() {
    $('select[name="fase"]').on('change', function() {
      var id_lokasi = $('select[name="lokasi"]').val();
      var id_tahun = $('select[name="tahun_anggaran"]').val();
      var id_fase = $('select[name="fase"]').val();

      $('select[name="draft_form_6_rutin"]').empty();
      $('select[name="draft_form_6_rutin"]').prepend($('<option></option>').html('Loading...'));

      if(id_lokasi && id_tahun && id_fase) {
          $.ajax({
              url: "{{ url('/output/pencarian/ajax5/') }}/"+id_lokasi+"/"+id_tahun+"/"+id_fase,
              type: "GET",
              dataType: "json",
              success:function(data) {
                $('select[name="draft_form_6_rutin"]').empty();

                if(data.length) {
                  $.each(data, function(ad , value) {
                      console.log(ad, value);
                      $('select[name="draft_form_6_rutin"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +' - '+ value["name"] +'</option>');
                  });
                }
                else {
                  $('select[name="draft_form_6_rutin"]').prepend($('<option value=""></option>').html('- Tidak ada data -'));
                }

              }
          });
      }else{
          $('select[name="draft_form_6_rutin"]').empty();

      }
    })
  })
</script>

<script type="text/javascript">
  $(document).ready(function() {
    $('select[name="fase"]').on('change', function() {
      var id_lokasi = $('select[name="lokasi"]').val();
      var id_tahun = $('select[name="tahun_anggaran"]').val();
      var id_fase = $('select[name="fase"]').val();

      $('select[name="draft_form_10_pu"]').empty();
      $('select[name="draft_form_10_pu"]').prepend($('<option></option>').html('Loading...'));

      if(id_lokasi && id_tahun && id_fase) {
          $.ajax({
              url: "{{ url('/output/pencarian/ajax6/') }}/"+id_lokasi+"/"+id_tahun+"/"+id_fase,
              type: "GET",
              dataType: "json",
              success:function(data) {

              $('select[name="draft_form_10_pu"]').empty();

              if(data.length) {
                $.each(data, function(ad , value) {
                    $('select[name="draft_form_10_pu"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +' - '+ value["name"] +'</option>');
                });
              }
              else {
                $('select[name="draft_form_10_pu"]').prepend($('<option value=""></option>').html('- Tidak ada data -'));
              }

              }
          });
      }else{
          $('select[name="draft_form_10_pu"]').empty();

      }
    })
  })
</script>

<script type="text/javascript">
  $(document).ready(function() {
    $('select[name="fase"]').on('change', function() {
      var id_lokasi = $('select[name="lokasi"]').val();
      var id_tahun = $('select[name="tahun_anggaran"]').val();
      var id_fase = $('select[name="fase"]').val();

      $('select[name="draft_form_10_pk"]').empty();
      $('select[name="draft_form_10_pk"]').prepend($('<option></option>').html('Loading...'));

      if(id_lokasi && id_tahun && id_fase) {
          $.ajax({
              url: "{{ url('/output/pencarian/ajax7/') }}/"+id_lokasi+"/"+id_tahun+"/"+id_fase,
              type: "GET",
              dataType: "json",
              success:function(data) {
                $('select[name="draft_form_10_pk"]').empty();

                if(data.length) {
                  $.each(data, function(ad , value) {
                      $('select[name="draft_form_10_pk"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +' - '+ value["name"] +'</option>');
                  });
                }
                else {
                  $('select[name="draft_form_10_pk"]').prepend($('<option value=""></option>').html('- Tidak ada data -'));
                }

              }
          });
      }else{
          $('select[name="draft_form_10_pk"]').empty();

      }
    })
  })
</script>

<script type="text/javascript">
  $(document).ready(function() {
    $('select[name="fase"]').on('change', function() {
      var id_lokasi = $('select[name="lokasi"]').val();
      var id_tahun = $('select[name="tahun_anggaran"]').val();
      var id_fase = $('select[name="fase"]').val();

      $('select[name="draft_form_10_pln"]').empty();
      $('select[name="draft_form_10_pln"]').prepend($('<option></option>').html('Loading...'));

      if(id_lokasi && id_tahun && id_fase) {
          $.ajax({
              url: "{{ url('/output/pencarian/ajax8/') }}/"+id_lokasi+"/"+id_tahun+"/"+id_fase,
              type: "GET",
              dataType: "json",
              success:function(data) {
                $('select[name="draft_form_10_pln"]').empty();

                if(data.length) {
                  $.each(data, function(ad , value) {
                      $('select[name="draft_form_10_pln"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +' - '+ value["name"] +'</option>');
                  });
                }
                else {
                  $('select[name="draft_form_10_pln"]').prepend($('<option value=""></option>').html('- Tidak ada data -'));
                }

              }
          });
      }else{
          $('select[name="draft_form_10_pln"]').empty();

      }
    })
  })
</script>
<script type="text/javascript">
  $(document).ready(function() {
    $('select[name="fase"]').on('change', function() {
      var id_lokasi = $('select[name="lokasi"]').val();
      var id_tahun = $('select[name="tahun_anggaran"]').val();
      var id_fase = $('select[name="fase"]').val();

      $('select[name="draft_form_bahan_bakar"]').empty();
      $('select[name="draft_form_bahan_bakar"]').prepend($('<option></option>').html('Loading...'));

      if(id_lokasi && id_tahun && id_fase) {
          $.ajax({
              url: "{{ url('/output/pencarian/ajax9/') }}/"+id_lokasi+"/"+id_tahun+"/"+id_fase,
              type: "GET",
              dataType: "json",
              success:function(data) {
                $('select[name="draft_form_bahan_bakar"]').empty();

                if(data.length) {
                  $.each(data, function(ad , value) {
                      $('select[name="draft_form_bahan_bakar"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +' - '+ value["name"] +'</option>');
                  });
                }
                else {
                  $('select[name="draft_form_bahan_bakar"]').prepend($('<option value=""></option>').html('- Tidak ada data -'));
                }

              }
          });
      }else{
          $('select[name="draft_form_bahan_bakar"]').empty();

      }
    })
  })
</script>
<script type="text/javascript">
  $(document).ready(function() {
    $('select[name="fase"]').on('change', function() {
      var id_lokasi = $('select[name="lokasi"]').val();
      var id_tahun = $('select[name="tahun_anggaran"]').val();
      var id_fase = $('select[name="fase"]').val();

      $('select[name="draft_form_penyusutan"]').empty();
      $('select[name="draft_form_penyusutan"]').prepend($('<option></option>').html('Loading...'));

      if(id_lokasi && id_tahun && id_fase) {
          $.ajax({
              url: "{{ url('/output/pencarian/ajax10/') }}/"+id_lokasi+"/"+id_tahun+"/"+id_fase,
              type: "GET",
              dataType: "json",
              success:function(data) {
                $('select[name="draft_form_penyusutan"]').empty();

                if(data.length) {
                  $.each(data, function(ad , value) {
                      $('select[name="draft_form_penyusutan"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +' - '+ value["name"] +'</option>');
                  });
                }
                else {
                  $('select[name="draft_form_penyusutan"]').prepend($('<option value=""></option>').html('- Tidak ada data -'));
                }

              }
          });
      }else{
          $('select[name="draft_form_penyusutan"]').empty();

      }
    })
  })
</script>
<script type="text/javascript">
  $(document).ready(function() {
      $('select[name="fase"]').on('change', function() {
          var lokasiID= $('select[name="lokasi"]').val();
          var tahun= $('select[name="tahun_anggaran"]').val();
          var id_fase = $('select[name="fase"]').val();

          $('select[name="draft_risk_profile"]').empty();
          $('select[name="draft_risk_profile"]').prepend($('<option></option>').html('Loading...'));

          if(lokasiID && tahun && id_fase) {
              $.ajax({
                  url: "{{url('/output/pencarian/ajax11/')}}/"+lokasiID+'/'+tahun+"/"+id_fase,
                  type: "GET",
                  dataType: "json",
                  success:function(data) {
                    $('select[name="draft_risk_profile"]').empty();

                    if(data.length) {
                      $.each(data, function(ad , value) {
                          $('select[name="draft_risk_profile"]').append('<option value="'+ value["id"] +'">'+ value["draft_versi"] +' - '+ value["name"] +'</option>');
                      });
                    }
                    else {
                      $('select[name="draft_risk_profile"]').prepend($('<option value=""></option>').html('- Tidak ada data -'));
                    }

                  }
              });
          }else{
              $('select[name="draft_risk_profile"]').empty();
          }
      });
  });
</script>

@endsection

