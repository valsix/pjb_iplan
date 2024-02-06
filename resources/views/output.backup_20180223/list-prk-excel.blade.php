<div class="row">
    <div class="col-md-12">
        <thead>
            <tr>
                <th>No PRK</th>
                <th>PRK Parent</th>
                <th>PRK Inti</th>
                <th>PRK Kegiatan</th>
                <th>Anggaran</th>
                <th>Disburse</th>
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
                                <td>{{ $parent['desc_prk_parent'] }}</td>
                                <td></td>
                                <td></td>
                                <td style="text-align: right;">{{ round(1000 * $parent['beban_mat']) }}</td><!-- <th>Beban (MAT)</th> -->
                                <td style="text-align: right;">{{ round(1000 * $parent['cash_oth']) }}</td><!-- <th>Cash (OTH)</th> -->
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
                                    <td></td>
                                    <td>{{ $inti['desc_prk_inti'] }}</td><!-- <th>Nomor Project /PRK</th> -->
                                    <td></td>
                                    <td style="text-align: right;">{{ round(1000 * $inti['beban_mat']) }}</td><!-- <th>Beban (MAT)</th> -->
                                    <td style="text-align: right;">{{ round(1000 * $inti['cash_oth']) }}</td><!-- <th>Cash (OTH)</th> -->
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
                                        <td></td>
                                        <td></td>
                                        <td>{{$kegiatan['desc_prk_kegiatan']}}</td><!-- <th>Nomor Project /PRK</th> -->
                                        <td style="text-align: right;">{{ round(1000 * $kegiatan['beban_mat']) }}</td><!-- <th>Beban (MAT)</th> -->
                                        <td style="text-align: right;">{{ round(1000 * $kegiatan['cash_oth']) }}</td><!-- <th>Cash (OTH)</th> -->
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
