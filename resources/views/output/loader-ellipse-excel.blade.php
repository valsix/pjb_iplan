<table>
    <tr>
        <td colspan="9">
            <h3>Report Rencana Anggaran Untuk Loader Ellipse</h3>    
        </td>
    </tr>
    <tr>
        <td>Tahun Anggaran</td>
        <td class="pull-right">{{$input_tahun}}</td>
    </tr>
    <tr>
        <td>Strategi Bisnis</td>
        <td>{{$input_sb->name}}</td>
    </tr>
    <tr>
        <td>Distrik</td>
        <td>{{$input_distrik->name}}</td>
    </tr>
    <tr>
        <td>Lokasi</td>
        <td>{{$input_lokasi->name}}</td>
    </tr>
    <tr>
        <td>Fase</td>
        <td>{{$input_fase->name}}</td>
    </tr>
    <tr>
        <td>Draft RKAU</td>
        <td>{{ ($input_draft_rkau!= null) ? $input_draft_rkau->draft_versi.' - '.$input_draft_rkau->name : '' }}</td>
    </tr>
    <tr>
        <td>Draft Form 6 Reimburse</td>
        <td>{{ ($input_draft_form_6_reimburse!= null) ? $input_draft_form_6_reimburse->draft_versi.' - '.$input_draft_form_6_reimburse->name : '' }}</td>
    </tr>
    <tr>
        <td>Draft Form 6 Rutin</td>
        <td>{{ ($input_draft_form_6_rutin!= null) ? $input_draft_form_6_rutin->draft_versi.' - '.$input_draft_form_6_rutin->name : '' }}</td>
    </tr>
    <tr>
        <td>Draft Form 10 Pengembangan Usaha</td>
        <td>{{ ($input_draft_form_10_pu!= null) ? $input_draft_form_10_pu->draft_versi.' - '.$input_draft_form_10_pu->name : '' }}</td>
    </tr>
    <tr>
        <td>Draft Form 10 Penguatan KIT</td>
        <td>{{ ($input_draft_form_10_pk!= null) ? $input_draft_form_10_pk->draft_versi.' - '.$input_draft_form_10_pk->name : '' }}</td>
    </tr>
    <tr>
        <td>Draft Form 10 PLN</td>
        <td>{{ ($input_draft_form_10_pln!= null) ? $input_draft_form_10_pln->draft_versi.' - '.$input_draft_form_10_pln->name : '' }}   </td>
    </tr>
    <tr>
        <td>Draft Form Bahan Bakar</td>
        <td>{{ ($input_draft_form_bahan_bakar!= null) ? $input_draft_form_bahan_bakar->draft_versi.' - '.$input_draft_form_bahan_bakar->name : '' }}</td>
    </tr>
    <tr>
        <td>Draft Form Penyusutan</td>
        <td>{{ ($input_draft_form_penyusutan!= null) ? $input_draft_form_penyusutan->draft_versi.' - '.$input_draft_form_penyusutan->name : '' }}</td>
    </tr>
</table>

<div class="row">
    <div class="col-md-12">
        <thead>
            <tr>
                <tr>
                           <th>Nomor Project /PRK</th>
                           <th>Deskripsi Project /PRK [40 karakter]</th>
                           <th>Ext.Description Line 1 [60 Karakter]</th>
                           <th>Ext.Description Line 2 [60 Karakter]</th>
                           <th>Parent Project</th>
                           <th>Raised Date (yyyymmdd)</th>
                           <th>Originator</th>
                           <th>Account Code</th>
                           <th>Authorize Employee</th>
                           <th>Authorize Date (yyyymmdd)</th>
                           <th>Nomer Rumah PRK</th>
                           <th>Years</th>
                           <th>Version</th>
                           <th>PRK Type</th>
                           <th>Plan Start Date (yyyymmdd)</th>
                           <th>Plan Finish Date (yyyymmdd)</th>
                           <th>Schedule Start Date</th>
                           <th>Schedule Finish Date</th>
                           <th>Actual Start Date</th>
                           <th>Actual Finish Date</th>
                           <th>Build Method (T/B)</th>
                           <th>Budget Code</th>
                           <th>Direct Est Cost/Revenue</th>
                           <th>Category Code</th>
                           <th>Category Value</th>
                           <th>Classification</th>
                           <th>Estimator</th>
                           <th>Year Estimate</th>
                           <th>Total Year Estimate/Revenue</th>
                           <th>Jan</th>
                           <th>Feb</th>
                           <th>Mar</th>
                           <th>Apr</th>
                           <th>Mei</th>
                           <th>Jun</th>
                           <th>Jul</th>
                           <th>Agt</th>
                           <th>Sep</th>
                           <th>Okt</th>
                           <th>Nov</th>
                           <th>Des</th>
                           <th>UPLOAD STATUS</th>
                         </tr>
            </tr>
        </thead>
        <tbody>
                    @foreach($dataparent as $key_form => $parent_per_form)
                        @foreach($parent_per_form as $key_parent => $parent)
                        <!-- parent -->
                            @if($key_parent!= '')
                            <tr>
                                @if(strlen($key_parent)!=4)
                                <td>{{substr($key_parent,2,4)}}</td><!-- <th>Nomor Project /PRK</th> -->
                                @else
                                <td>{{$key_parent}}</td><!-- <th>Nomor Project /PRK</th> -->
                                @endif
                                <td>{{substr($parent['desc_prk_parent'],0,40)}}</td><!-- <th>Deskripsi Project /PRK</th> -->
                                <td>{{substr($parent['desc_prk_parent'],40,60)}}</td><!-- <th>Deskripsi Project /PRK [40 karakter]</th> -->
                                <td>{{substr($parent['desc_prk_parent'],100,60)}}</td><!-- <th>Ext.Description Line 1 [60 Karakter]</th> -->
                                <td></td><!-- <th>Parent Project</th> -->
                                <td></td><!-- <th>Raised Date (yyyymmdd)</th> -->
                                <td></td><!-- <th>Originator</th> -->
                                <td>K00000100</td><!-- <th>Account Code</th> -->
                                <td></td><!-- <th>Authorize Employee</th> -->
                                <td></td><!-- <th>Authorize Date (yyyymmdd)</th> -->
                                <td></td><!-- <th>Nomor RUmah PRK</th> -->
                                <td>{{ $input_tahun }}</td><!-- <th>Years</th> -->
                                <td>001</td><!-- <th>Version</th> -->
                                <td>PP</td><!-- <th>PRK Type</th> -->
                                <td>{{$input_tahun}}0101</td><!-- <th>Plan Start Date (yyyymmdd)</th> -->
                                <td>{{$input_tahun}}1231</td><!-- <th>Plan Finish Date (yyyymmdd)</th> -->
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>{{ number_format(1000 * $parent['direct_est_cost'] ,0,",","")}}</td> <!-- <th>Direct Est Cost/Revenue</th> -->
                                <td>{{ $parent['category_code'] }}</td><!-- <th>Category Code</th> -->
                                <td>{{ number_format(1000 * $parent['category_value'],0,",","") }}</td><!-- <th>Category Value</th> -->
                                <td></td><!-- <th>Classification</th> -->
                                <td></td><!-- <th>Estimator</th> -->
                                <td></td><!-- <th>Years Estimate</th> -->
                                <td style="text-align: right;">{{ number_format(1000 * $parent['total_year_estimate'],0,",","") }}</td><!-- <th>Total Year Estimate</th> -->
                                @for($bulan=1; $bulan<=12; $bulan++)
                                    <td style="text-align: right;">{{ number_format(1000 * $parent['disburse'][$bulan],0,",","") }}</td><!-- <th>disburse bulan ke {{$bulan}}</th> -->
                                @endfor
                                <td></td><!-- <th>UPLOAD STATUS</th> -->
                            </tr>

                            <!-- inti -->
                            @foreach($datainti[$key_form] as $key_inti=>$inti)
                                @if($inti['prk_parent'] == $key_parent)
                                <tr>
                                    @if(strlen($key_inti)!=6)
                                    <td>{{ preg_match('/E/', substr($key_inti,2,6)) ? "'".substr($key_inti,2,6) : substr($key_inti,2,6) }}</td><!-- <th>Nomor Project /PRK</th> -->
                                    @else
                                    <td>{{ preg_match('/E/', $key_inti) ? "'".$key_inti : $key_inti }}</td><!-- <th>Nomor Project /PRK</th> -->
                                    @endif
                                    <td>{{substr($inti['desc_prk_inti'],0,40)}}</td><!-- <th>Deskripsi Project /PRK</th> -->
                                    <td>{{substr($inti['desc_prk_inti'],40,60)}}</td><!-- <th>Deskripsi Project /PRK [40 karakter]</th> -->
                                    <td>{{substr($inti['desc_prk_inti'],100,60)}}</td><!-- <th>Ext.Description Line 1 [60 Karakter]</th> -->
                                    @if(strlen($inti['prk_parent'])!=4)
                                    <td>{{ substr($inti['prk_parent'],2,4) }}</td><!-- <th>Parent Project</th> -->
                                    @else
                                    <td>{{ preg_match('/E/', $inti['prk_parent']) ? "'".$inti['prk_parent'] : $inti['prk_parent'] }}</td><!-- <th>Parent Project</th> -->
                                    @endif
                                    <td></td><!-- <th>Raised Date (yyyymmdd)</th> -->
                                    <td></td><!-- <th>Originator</th> -->
                                    <td>K00000100</td><!-- <th>Account Code</th> -->
                                    <td></td><!-- <th>Authorize Employee</th> -->
                                    <td></td><!-- <th>Authorize Date (yyyymmdd)</th> -->
                                    <td></td><!-- <th>Nomer Rumah PRK</th> -->
                                    <td>{{ $input_tahun }}</td><!-- <th>Years</th> -->
                                    <td>001</td><!-- <th>Version</th> -->
                                    <td>PI</td><!-- <th>PRK Type</th> -->
                                    <td>{{$input_tahun}}0101</td><!-- <th>Plan Start Date (yyyymmdd)</th> -->
                                    <td>{{$input_tahun}}1231</td><!-- <th>Plan Finish Date (yyyymmdd)</th> -->
                                    <td></td><!-- <th>Schedule Start Date (yyyymmdd)</th> -->
                                    <td></td><!-- <th>Schedule Finish Date (yyyymmdd)</th> -->
                                    <td></td><!-- <th>Actual Start Date (yyyymmdd)</th> -->
                                    <td></td><!-- <th>Actual Finish Date (yyyymmdd)</th> -->
                                    <td></td><!-- <th>Build Method (T/B)</th> -->
                                    <td></td><!-- <th>Budget Code</th> -->
                                    <td>{{ number_format(1000 * $inti['direct_est_cost'],0,",","")}}</td><!-- <th>Direct East Code/Revenue</th> -->
                                    <td>{{ $inti['category_code'] }}</td><!-- <th>Category Code</th> -->
                                    <td>{{ number_format(1000 * $inti['category_value'],0,",","") }}</td><!-- <th>Category Value</th> -->
                                    <td></td><!-- <th>Classification</th> -->
                                    <td></td><!-- <th>Estimator</th> -->
                                    <td></td><!-- <th>Years Estimate</th> -->
                                    <td style="text-align: right;">{{ number_format(1000 * $inti['total_year_estimate'],0,",","") }}</td><!-- <th>Total Year Estimate</th> -->
                                    @for($bulan=1; $bulan<=12; $bulan++)
                                        <td style="text-align: right;">{{ number_format(1000 * $inti['disburse'][$bulan],0,",","") }}</td><!-- <th>disburse bulan ke {{$bulan}}</th> -->
                                    @endfor
                                    <td></td><!-- <th>UPLOAD STATUS</th> -->
                                </tr>

                                <!-- kegiatan -->
                                @foreach($datakegiatan[$key_form] as $key_kegiatan => $kegiatan)
                                    @if($kegiatan['prk_inti'] == $key_inti)
                                   <tr> 
                                      @if(strlen($kegiatan['prk_kegiatan'])!=8) 
                                      <td>{{ preg_match('/E/', substr($kegiatan['prk_kegiatan'],2,8)) ? "'".substr($kegiatan['prk_kegiatan'],2,8) : substr($kegiatan['prk_kegiatan'],2,8) }}</td><!-- <th>Nomor Project /PRK</th> -->
                                      @else
                                      <td>{{ preg_match('/E/', $kegiatan['prk_kegiatan']) ? "'".$kegiatan['prk_kegiatan'] : $kegiatan['prk_kegiatan'] }}</td><!-- <th>Nomor Project /PRK</th> -->
                                      @endif
                                      <td>{{substr($kegiatan['desc_prk_kegiatan'],0,40)}}</td><!-- <th>Deskripsi Project /PRK</th> -->
                                      <td>{{substr($kegiatan['desc_prk_kegiatan'],40,60)}}</td><!-- <th>Deskripsi Project /PRK [40 karakter]</th> -->
                                      <td>{{substr($kegiatan['desc_prk_kegiatan'],100,60)}}</td><!-- <th>Ext.Description Line 1 [60 Karakter]</th> -->
                                      @if(strlen($kegiatan['prk_inti'])!=6)
                                      <td>{{ substr($kegiatan['prk_inti'],2,6) }}</td><!-- <th>Parent Project</th> -->
                                      @else
                                      <td>{{ preg_match('/E/', $kegiatan['prk_inti']) ? "'".$kegiatan['prk_inti'] : $kegiatan['prk_inti'] }}</td><!-- <th>Parent Project</th> -->
                                      @endif
                                      <td></td><!-- <th>Raised Date (yyyymmdd)</th> -->
                                      <td></td><!-- <th>Originator</th> -->
                                      <td>K00000100</td><!-- <th>Account Code</th> -->
                                      <td></td><!-- <th>Authorize Employee</th> -->
                                      <td></td><!-- <th>Authorize Date (yyyymmdd)</th> -->
                                      <td></td><!-- <th>Nomer Rumah PRK</th> -->
                                      <td>{{ $input_tahun }}</td><!-- <th>Years</th> -->
                                      <td>001</td><!-- <th>Version</th> -->
                                      <td>PK</td><!-- <th>PRK Type</th> -->
                                      <td>{{$input_tahun}}0101</td><!-- <th>Plan Start Date (yyyymmdd)</th> -->
                                      <td>{{$input_tahun}}1231</td><!-- <th>Plan Finish Date (yyyymmdd)</th> -->
                                      <td></td><!-- <th>Schedule Start Date (yyyymmdd)</th> -->
                                      <td></td><!-- <th>Schedule Finish Date (yyyymmdd)</th> -->
                                      <td></td><!-- <th>Actual Start Date (yyyymmdd)</th> -->
                                      <td></td><!-- <th>Actual Finish Date (yyyymmdd)</th> -->
                                      <td></td><!-- <th>Build Methor (T/B)</th> -->
                                      <td></td><!-- <th>Budget Code</th> -->
                                      <td>{{ number_format(1000 *  $kegiatan['direct_est_cost'],0,",","")}}</td><!-- <th>Direct Est Cost/Revenue</th> -->
                                      <td>{{ $kegiatan['category_code'] }}</td><!-- <th>Category Code</th> -->
                                      <td>{{ number_format(1000 * $kegiatan['category_value'],0,",","") }}</td><!-- <th>Category Value</th> -->
                                      <td></td><!-- <th>Classification</th> -->
                                      <td></td><!-- <th>Estimator</th> -->
                                      <td></td><!-- <th>Year Estimate</th> -->
                                      <td style="text-align: right;">{{ number_format(1000 * $kegiatan['total_year_estimate'],0,",","") }}</td><!-- <th>Total Year Estimate</th> -->
                                      @for($bulan=1; $bulan<=12; $bulan++)
                                          <td style="text-align: right;">{{ number_format(1000 * $kegiatan['disburse'][$bulan],0,",","") }}</td><!-- <th>disburse bulan ke {{$bulan}}</th> -->
                                      @endfor
                                      <td></td><!-- <th>UPLOAD STATUS</th> -->
                                    </tr>
                                    @endif
                                @endforeach <!-- end of inti -->
                                @endif
                            @endforeach <!-- end of inti -->
                            @endif
                        @endforeach <!-- end of parent -->
                        @endforeach
                        <!-- end of form rkau, 6 dan 10 -->

        </tbody>
    </div>
</div>
