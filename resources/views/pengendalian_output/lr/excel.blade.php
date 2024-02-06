<table>
    <tr>
        <td colspan="9">
            <h3>Report Laba Rugi</h3>    
        </td>
    </tr>
    <tr>
        <th>Tahun Anggaran</th>
        <td class="pull-right">{{$input_tahun}}</td>
    </tr>
    <tr>
        <th>Strategi Bisnis</th>
        <td>{{$distrik->strategi_bisnis->name}}</td>
    </tr>
    <tr>
        <th>Distrik</th>
        <td>{{$distrik->name}}</td>
    </tr>
    <tr>
        <th>Lokasi</th>
        <td>
        @if($input_lokasi)
            {{ $input_lokasi->name }}
        @elseif($lokasi)
          	<?php $val = null; ?>
          	<?php $ival = null; ?>
          	@foreach($lokasi as $l)
          	<?php
	            $ival++;
	            if($ival==1)  
	            	$val = $val.' '.$l->name; 
	            else
	            	$val = $val.', '.$l->name; 
	        ?>
          	@endforeach
          	{{ $val }}
        @else
          	{{ '' }}
        @endif
        </td>
    </tr>
    <tr>
        <th>Fase</th>
        <td>{{ 'Ketetapan' }}</td>
    </tr>
    <tr>
        <th>s.d Bulan</th>
        <td>{{$nama_bln_dipilih}}</td>
    </tr>
</table>

<div class="row">
    <div class="col-md-12">
    	<table>
	        <thead>
	            <tr>
                    <th rowspan="2" style="border: 1px solid #000">Keterangan</th>
                    <th rowspan="2" style="border: 1px solid #000">RKAP n</th>
                    <th rowspan="2" style="border: 1px solid #000">RKAP n update</th>
                    <th rowspan="2" style="border: 1px solid #000">RKAP s.d Bulan</th>
                    <th rowspan="2" style="border: 1px solid #000">REALISASI s.d Bulan</th>
                    <th colspan="2" style="border: 1px solid #000">PENCAPAIAN</th>
                </tr>
                <tr>
                	<th></th>
                	<th></th>
                	<th></th>
                	<th></th>
                	<th></th>
                    <th style="border: 1px solid #000">s.d Bulan</th>
                    <th style="border: 1px solid #000">n update</th>
                </tr>
                <tr>
                    <th style="border: 1px solid #000; text-align: center;">1</th>
                    <th style="border: 1px solid #000; text-align: center;">2</th>
                    <th style="border: 1px solid #000; text-align: center;">3</th>
                    <th style="border: 1px solid #000; text-align: center;">4</th>
                    <th style="border: 1px solid #000; text-align: center;">5</th>
                    <th style="border: 1px solid #000; text-align: center;">6=5/4</th>
                    <th style="border: 1px solid #000; text-align: center;">7=5/3</th>
                </tr>
	        </thead>
	        <tbody>
	        	<?php $count=0; ?>
                <?php $no_utama = $no_sub_1 = $no_sub_2 = $no_sub_3 = 0; ?>
                <?php $baris = 0; ?>
                @if(isset($lr_result))
                    <!-- Untuk produksi & penjualan -->
                    @foreach($lr_result as $i => $val)
                        @if($distrik->strategi_bisnis->name == 'UP' && $i>=65) )
                            <tr>
                                @foreach($settings as $key => $column_setting)
                                    @if( is_numeric( $val[$column_setting->judul_kolom] ))
                                        <td style="text-align: right; border: 1px solid #000">
                                            @if($i>65)
                                                {{ $val[$column_setting->judul_kolom] }}
                                            @endif
                                        </td>
                                    @else
                                        <td style="border: 1px solid #000">
                                            {{isset($val[$column_setting->judul_kolom]) ? $val[$column_setting->judul_kolom] : ''}}
                                        </td>
                                    @endif
                                @endforeach
                            </tr>
                        @endif
                    @endforeach
                    <!-- Untuk selain produksi & penjualan -->
                    <?php $no_utama = 1; ?>
                    @foreach($lr_result as $i => $val)
                        @if(($distrik->strategi_bisnis->name == 'OM' && ($i<15 || ($i>20 && $i<65))) || ($distrik->strategi_bisnis->name == 'UP' && ($i<21 || ($i>23 && $i<65))) )
                            <tr>
                                @foreach($settings as $key => $column_setting)
                                    @if( is_numeric( $val[$column_setting->judul_kolom] ))
                                        <td style="text-align: right; border: 1px solid #000">
                                            @if($column_setting->judul_kolom == 'PENCAPAIAN n update' || $column_setting->judul_kolom == 'PENCAPAIAN s.d Bulan')
                                                {{ $val[$column_setting->judul_kolom] }}%
                                            @else
                                                {{ $val[$column_setting->judul_kolom] }}
                                            @endif
                                        </td>
                                    @else
                                        <td style="border: 1px solid #000">{{isset($val[$column_setting->judul_kolom]) ? $val[$column_setting->judul_kolom] : ''}}</td>
                                    @endif
                                @endforeach
                            </tr>
                        @endif
                    @endforeach
                @endif
	        </tbody>
    	</table>
    </div>
</div>