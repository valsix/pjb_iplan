<div class="row">
    <div class="col-md-12">
        <thead>
            <tr>
                <th>Nomor Project /PRK</th>
                <th>Deskripsi Project /PRK [40 karakter]</th>
                <th>Ext.Description Line 1 [60 Karakter]</th>
                <th>Ext.Description Line 2 [60 Karakter]</th>
                <th>Parent Project</th>
                <th>Beban (MAT)</th>
                <th>Cash (OTH)</th>
                <th>Ijin Proses (LAB)</th>
                <th>Spread Code</th>
                <th>Raised Date (yyyymmdd)</th>
                <th>Originator</th>
                <th>Account Code</th>
                <th>Authorize Employee</th>
                <th>Authorize Date (yyyymmdd)</th>
                <th>Rumah PRK Number</th>
                <th>Years</th>
                <th>Version</th>
                <th>PRK Type</th>
                <th>Plan Start Date (yyyymmdd)</th>
                <th>Plan Finish Date (yyyymmdd)</th>
                <th>Category Code</th>
                <th>Total Year Estimate</th>
                <th>Classification</th>
                <th>Estimator</th>
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
                <th>Tahun Disburse</th>
                <th>UPLOAD STATUS PROJECT</th>
                <th>UPLOAD STATUS PROJECT ESTIMATE</th>
                <th>UPLOAD STATUS PERIOD PROJECT ESTIMATE</th>
                <th>JUMLAH SUBMIT (KALI)</th>
            </tr>
        </thead>
        <tbody>
                    @foreach($dataparent as $key_form => $parent_per_form)
                        @foreach($parent_per_form as $key_parent => $parent)
                        <!-- parent -->
                            @if($key_parent!= '')
                            <tr>
                                @if(strlen($key_parent)!=4)
                                <td>{{ preg_match('/E/', $key_parent) ? "'".substr($key_parent,2,4) : substr($key_parent,2,4) }}</td><!-- <th>Nomor Project /PRK</th> -->
                                @else
                                <td>{{ preg_match('/E/', $key_parent) ? "'".$key_parent : $key_parent}}</td><!-- <th>Nomor Project /PRK</th> -->
                                @endif
                                <td>{{substr($parent['desc_prk_parent'],0,40)}}</td><!-- <th>Nomor Project /PRK</th> -->
                                <td>{{substr($parent['desc_prk_parent'],40,60)}}</td><!-- <th>Deskripsi Project /PRK [40 karakter]</th> -->
                                <td>{{substr($parent['desc_prk_parent'],100,60)}}</td><!-- <th>Ext.Description Line 1 [60 Karakter]</th> -->
                                <td></td><!-- <th>Parent Project</th> -->
                                <td style="text-align: right;">{{ round(1000 * $parent['beban_mat']) }}</td><!-- <th>Beban (MAT)</th> -->
                                <td style="text-align: right;">{{ round(1000 * $parent['cash_oth']) }}</td><!-- <th>Cash (OTH)</th> -->
                                <td style="text-align: right;">{{ round(1000 * $parent['ijin_proses']) }}</td><!-- <th>Ijin Proses (LAB)</th> -->
                                <td></td><!-- <th>Spread Code</th> -->
                                <td></td><!-- <th>Raised Date (yyyymmdd)</th> -->
                                <td></td><!-- <th>Originator</th> -->
                                <td>K00000100</td><!-- <th>Account Code</th> -->
                                <td></td><!-- <th>Authorize Employee</th> -->
                                <td></td><!-- <th>Authorize Date (yyyymmdd)</th> -->
                                <td></td><!-- <th>Rumah PRK Number</th> -->
                                <td>{{ $input_tahun }}</td><!-- <th>Years</th> -->
                                <td>001</td><!-- <th>Version</th> -->
                                <td>PP</td><!-- <th>PRK Type</th> -->
                                <td>{{$input_tahun}}0101</td><!-- <th>Plan Start Date (yyyymmdd)</th> -->
                                <td>{{$input_tahun}}1231</td><!-- <th>Plan Finish Date (yyyymmdd)</th> -->
                                <td></td><!-- <th>Category Code</th> -->
                                <td style="text-align: right;">{{ round(1000 * $parent['total_year_estimate']) }}</td><!-- <th>Total Year Estimate</th> -->
                                <td></td><!-- <th>Classification</th> -->
                                <td></td><!-- <th>Estimator</th> -->
                                @for($bulan=1; $bulan<=12; $bulan++)
                                    <td style="text-align: right;">{{ round(1000 * $parent['disburse'][$bulan]) }}</td><!-- <th>disburse bulan ke {{$bulan}}</th> -->
                                @endfor
                                <td>{{ $input_tahun }}</td><!-- <th>Tahun Disburse</th> -->
                                <td></td><!-- <th>UPLOAD STATUS PROJECT</th> -->
                                <td></td><!-- <th>UPLOAD STATUS PROJECT ESTIMATE</th> -->
                                <td></td><!-- <th>UPLOAD STATUS PERIOD PROJECT ESTIMATE</th> -->
                                <td></td><!-- <th>JUMLAH SUBMIT (KALI)</th> -->
                            </tr>

                            <!-- inti -->
                            @foreach($datainti[$key_form] as $key_inti=>$inti)
                                @if($inti['prk_parent'] == $key_parent)
                                <tr>
                                    @if(strlen($key_inti)!=6)
                                    <td>{{ preg_match('/E/', $key_inti) ? "'".substr($key_inti,2,6) : substr($key_inti,2,6) }}</td><!-- <th>Nomor Project /PRK</th> -->
                                    @else
                                    <td>{{ preg_match('/E/', $key_inti) ? "'".$key_inti : $key_inti }}</td><!-- <th>Nomor Project /PRK</th> -->
                                    @endif
                                    <td>{{substr($inti['desc_prk_inti'],0,40)}}</td><!-- <th>Nomor Project /PRK</th> -->
                                    <td>{{substr($inti['desc_prk_inti'],40,60)}}</td><!-- <th>Deskripsi Project /PRK [40 karakter]</th> -->
                                    <td>{{substr($inti['desc_prk_inti'],100,60)}}</td><!-- <th>Ext.Description Line 1 [60 Karakter]</th> -->
                                    @if(strlen($inti['prk_parent'])!=4)
                                    <td>{{ preg_match('/E/', $inti['prk_parent']) ? "'".substr($inti['prk_parent'],2,4) : substr($inti['prk_parent'],2,4) }}</td><!-- <th>Parent Project</th> -->
                                    @else
                                    <td>{{ preg_match('/E/', $inti['prk_parent']) ? "'".$inti['prk_parent'] : $inti['prk_parent'] }}</td><!-- <th>Parent Project</th> -->
                                    @endif
                                    <td style="text-align: right;">{{ round(1000 * $inti['beban_mat']) }}</td><!-- <th>Beban (MAT)</th> -->
                                    <td style="text-align: right;">{{ round(1000 * $inti['cash_oth']) }}</td><!-- <th>Cash (OTH)</th> -->
                                    <td style="text-align: right;">{{ round(1000 * $inti['ijin_proses']) }}</td><!-- <th>Ijin Proses (LAB)</th> -->
                                    <td></td><!-- <th>Spread Code</th> -->
                                    <td></td><!-- <th>Raised Date (yyyymmdd)</th> -->
                                    <td></td><!-- <th>Originator</th> -->
                                    <td>K00000100</td><!-- <th>Account Code</th> -->
                                    <td></td><!-- <th>Authorize Employee</th> -->
                                    <td></td><!-- <th>Authorize Date (yyyymmdd)</th> -->
                                    <td></td><!-- <th>Rumah PRK Number</th> -->
                                    <td>{{ $input_tahun }}</td><!-- <th>Years</th> -->
                                    <td>001</td><!-- <th>Version</th> -->
                                    <td>PI</td><!-- <th>PRK Type</th> -->
                                    <td>{{$input_tahun}}0101</td><!-- <th>Plan Start Date (yyyymmdd)</th> -->
                                    <td>{{$input_tahun}}1231</td><!-- <th>Plan Finish Date (yyyymmdd)</th> -->
                                    <td></td><!-- <th>Category Code</th> -->
                                    <td style="text-align: right;">{{ round(1000 * $inti['total_year_estimate']) }}</td><!-- <th>Total Year Estimate</th> -->
                                    <td></td><!-- <th>Classification</th> -->
                                    <td></td><!-- <th>Estimator</th> -->
                                    @for($bulan=1; $bulan<=12; $bulan++)
                                        <td style="text-align: right;">{{ round(1000 * $inti['disburse'][$bulan]) }}</td><!-- <th>disburse bulan ke {{$bulan}}</th> -->
                                    @endfor
                                    <td>{{ $input_tahun }}</td><!-- <th>Tahun Disburse</th> -->
                                    <td></td><!-- <th>UPLOAD STATUS PROJECT</th> -->
                                    <td></td><!-- <th>UPLOAD STATUS PROJECT ESTIMATE</th> -->
                                    <td></td><!-- <th>UPLOAD STATUS PERIOD PROJECT ESTIMATE</th> -->
                                    <td></td><!-- <th>JUMLAH SUBMIT (KALI)</th> -->
                                </tr>

                                <!-- kegiatan -->
                                @foreach($datakegiatan[$key_form] as $key_kegiatan => $kegiatan)
                                    @if($kegiatan['prk_inti'] == $key_inti)
                                    <tr>
                                        @if(strlen($kegiatan['prk_kegiatan'])!=8)
                                        <td>{{ preg_match('/E/', $kegiatan['prk_kegiatan']) ? "'".substr($kegiatan['prk_kegiatan'],2,8) : substr($kegiatan['prk_kegiatan'],2,8)}}</td><!-- <th>Nomor Project /PRK</th> -->
                                        @else
                                        <td>{{ preg_match('/E/', $kegiatan['prk_kegiatan']) ? "'".$kegiatan['prk_kegiatan'] : $kegiatan['prk_kegiatan'] }}</td><!-- <th>Nomor Project /PRK</th> -->
                                        @endif
                                        <td>{{substr($kegiatan['desc_prk_kegiatan'],0,40)}}</td><!-- <th>Nomor Project /PRK</th> -->
                                        <td>{{substr($kegiatan['desc_prk_kegiatan'],40,60)}}</td><!-- <th>Deskripsi Project /PRK [40 karakter]</th> -->
                                        <td>{{substr($kegiatan['desc_prk_kegiatan'],100,60)}}</td><!-- <th>Ext.Description Line 1 [60 Karakter]</th> -->
                                        @if(strlen($kegiatan['prk_inti'])!=6)
                                        <td>{{ preg_match('/E/', $kegiatan['prk_inti']) ? "'".substr($kegiatan['prk_inti'],2,6) : substr($kegiatan['prk_inti'],2,6) }}</td><!-- <th>Parent Project</th> -->
                                        @else
                                        <td>{{ preg_match('/E/', $kegiatan['prk_inti']) ? "'".$kegiatan['prk_inti']: $kegiatan['prk_inti'] }}</td><!-- <th>Parent Project</th> -->
                                        @endif
                                        <td style="text-align: right;">{{ round(1000 * $kegiatan['beban_mat']) }}</td><!-- <th>Beban (MAT)</th> -->
                                        <td style="text-align: right;">{{ round(1000 * $kegiatan['cash_oth']) }}</td><!-- <th>Cash (OTH)</th> -->
                                        <td style="text-align: right;">{{ round(1000 * $kegiatan['ijin_proses']) }}</td><!-- <th>Ijin Proses (LAB)</th> -->
                                        <td></td><!-- <th>Spread Code</th> -->
                                        <td></td><!-- <th>Raised Date (yyyymmdd)</th> -->
                                        <td></td><!-- <th>Originator</th> -->
                                        <td>K00000100</td><!-- <th>Account Code</th> -->
                                        <td></td><!-- <th>Authorize Employee</th> -->
                                        <td></td><!-- <th>Authorize Date (yyyymmdd)</th> -->
                                        <td></td><!-- <th>Rumah PRK Number</th> -->
                                        <td>{{ $input_tahun }}</td><!-- <th>Years</th> -->
                                        <td>001</td><!-- <th>Version</th> -->
                                        <td>PK</td><!-- <th>PRK Type</th> -->
                                        <td>{{$input_tahun}}0101</td><!-- <th>Plan Start Date (yyyymmdd)</th> -->
                                        <td>{{$input_tahun}}1231</td><!-- <th>Plan Finish Date (yyyymmdd)</th> -->
                                        <td></td><!-- <th>Category Code</th> -->
                                        <td style="text-align: right;">{{ round(1000 * $kegiatan['total_year_estimate']) }}</td><!-- <th>Total Year Estimate</th> -->
                                        <td></td><!-- <th>Classification</th> -->
                                        <td></td><!-- <th>Estimator</th> -->
                                        @for($bulan=1; $bulan<=12; $bulan++)
                                            <td style="text-align: right;">{{ round(1000 * $kegiatan['disburse'][$bulan]) }}</td><!-- <th>disburse bulan ke {{$bulan}}</th> -->
                                        @endfor
                                        <td>{{ $input_tahun }}</td><!-- <th>Tahun Disburse</th> -->
                                        <td></td><!-- <th>UPLOAD STATUS PROJECT</th> -->
                                        <td></td><!-- <th>UPLOAD STATUS PROJECT ESTIMATE</th> -->
                                        <td></td><!-- <th>UPLOAD STATUS PERIOD PROJECT ESTIMATE</th> -->
                                        <td></td><!-- <th>JUMLAH SUBMIT (KALI)</th> -->
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
