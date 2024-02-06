<table>
    <tr>
        <td colspan="9">
            <h3>Rekap Laba Rugi</h3>    
        </td>
    </tr>
    <tr>
        <th>Tahun Anggaran</th>
        <td class="pull-right">{{$input_tahun}}</td>
    </tr>
    <tr>
        <th>Strategi Bisnis</th>
        <td>{{$strategi_bisnis->name}}</td>
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
	            <tr role="row">
                    <th style="border: 1px solid #000; text-align: center;">Keterangan</th>
                    <th style="border: 1px solid #000; text-align: center;">RKAP N</th>
                    <th style="border: 1px solid #000; text-align: center;">RKAP N Update</th>
                    <th style="border: 1px solid #000; text-align: center;">RKAP Jan</th>
                    <th style="border: 1px solid #000; text-align: center;">RKAP Feb</th>
                    <th style="border: 1px solid #000; text-align: center;">RKAP Mar</th>
                    <th style="border: 1px solid #000; text-align: center;">RKAP Apr</th>
                    <th style="border: 1px solid #000; text-align: center;">RKAP May</th>
                    <th style="border: 1px solid #000; text-align: center;">RKAP Jun</th>
                    <th style="border: 1px solid #000; text-align: center;">RKAP Jul</th>
                    <th style="border: 1px solid #000; text-align: center;">RKAP Aug</th>
                    <th style="border: 1px solid #000; text-align: center;">RKAP Sep</th>
                    <th style="border: 1px solid #000; text-align: center;">RKAP Oct</th>
                    <th style="border: 1px solid #000; text-align: center;">RKAP Nov</th>
                    <th style="border: 1px solid #000; text-align: center;">RKAP Dec</th>
                </tr>
                <tr role="row">
                    <th style="border: 1px solid #000; text-align: center;">1</th>
                    <th style="border: 1px solid #000; text-align: center;">2</th>
                    <th style="border: 1px solid #000; text-align: center;">3</th>
                    <th style="border: 1px solid #000; text-align: center;">4</th>
                    <th style="border: 1px solid #000; text-align: center;">5</th>
                    <th style="border: 1px solid #000; text-align: center;">6</th>
                    <th style="border: 1px solid #000; text-align: center;">7</th>
                    <th style="border: 1px solid #000; text-align: center;">8</th>
                    <th style="border: 1px solid #000; text-align: center;">9</th>
                    <th style="border: 1px solid #000; text-align: center;">10</th>
                    <th style="border: 1px solid #000; text-align: center;">11</th>
                    <th style="border: 1px solid #000; text-align: center;">12</th>
                    <th style="border: 1px solid #000; text-align: center;">13</th>
                    <th style="border: 1px solid #000; text-align: center;">14</th>
                    <th style="border: 1px solid #000; text-align: center;">15</th>
                </tr>
	        </thead>
	        <tbody>
	        	<?php $count=0; ?>
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
                                                {{ $val[$column_setting->judul_kolom] }}
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