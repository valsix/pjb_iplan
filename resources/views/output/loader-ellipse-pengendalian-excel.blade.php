<table>
    <tr>
        <td colspan="9">
            <h3>Report Loader Ellipse Pengendalian</h3>
        </td>
    </tr>
    <tr>
        <th>Tahun Anggaran</th>
        <td class="pull-right">{{$input_tahun}}</td>
    </tr>
    <tr>
        <th>Strategi Bisnis</th>
        <td>{{$input_sb->name}}</td>
    </tr>
    <tr>
        <th>Distrik</th>
        <td>{{$input_distrik->name}}</td>
    </tr>
    <tr>
        <th>Lokasi</th>
        <td>
        @if($input_lokasi)
          <?php $val = null; ?>
          <?php $ival = null; ?>
          @foreach($input_lokasi as $l)
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
    <tr>
        <th>RKAU</th>
        <td>{{ $name_draft_rkau }}</td>
    </tr>
    <tr>
        <th>Form 6 Reimburse</th>
        <td>{{ $name_draft_form_6_reimburse }}</td>
    </tr>
    <tr>
        <th>Form 6 Rutin</th>
        <td>{{ $name_draft_form_6_rutin }}</td>
    </tr>
    <tr>
        <th>Form 10 Pengembangan Usaha</th>
        <td>{{ $name_draft_form_10_pu }}</td>
    </tr>
    <tr>
        <th>Form 10 Penguatan KIT</th>
        <td>{{ $name_draft_form_10_pk }}</td>
    </tr>
    <tr>
        <th>Form 10 PLN</th>
        <td>{{ $name_draft_form_10_pln }}</td>
    </tr>
    <tr>
        <th>Form Bahan Bakar</th>
        <td>{{ $name_draft_form_bahan_bakar }}</td>
    </tr>
    <tr>
        <th>Form Penyusutan</th>
        <td>{{ $name_draft_form_penyusutan }}</td>
    </tr>
</table>

<div class="row">
    @php
        $columnMappings = [
            // 'LABEL' => 'COLUMN',
            'Nomor Project /PRK' => 'Nomor Project / PRK',
            'Deskripsi Project /PRK [40 karakter]' => 'Deskripsi Project /PRK [40 karakter]',
            'Ext.Description Line 1 [60 Karakter]' => 'Ext.Description Line 1 [60 Karakter]',
            'Ext.Description Line 2 [60 Karakter]' => 'Ext.Description Line 2 [60 Karakter]',
            'Parent Project' => 'Parent Project',

            'Raised Date (yyyymmdd)' => 'Raised Date (yyyymmdd)',
            'Originator' => 'Originator',
            'Account Code' => 'Account Code',
            'Authorize Employee' => 'Authorize Employee',
            'Authorize Date (yyyymmdd)' => 'Authorize Date (yyyymmdd)',
            'Nomer Rumah PRK' => 'Rumah PRK Number',
            'Years' => 'Years',
            'Version' => 'Version',
            'PRK Type' => 'PRK Type',
            'Plan Start Date (yyyymmdd)' => 'Plan Start Date (yyyymmdd)',
            'Plan Finish Date (yyyymmdd)' => 'Plan Finish Date (yyyymmdd)',

            'Schedule Start Date' => false,
            'Schedule Finish Date' => false,
            'Actual Start Date' => false,
            'Actual Finish Date' => false,
            'Build Method (T/B)' => false,
            'Budget Code' => false,
            'Direct Est Cost/Revenue' => false,
            'Category Code' => false,
            'Category Value' => false,

            // '*Beban (MAT)' => 'Beban (MAT)',
            // '*Cash (OTH)' => 'Cash (OTH)',
            // '*Ijin Proses (LAB)' => 'Ijin Proses (LAB)',

            'Classification' => 'Classification',
            'Estimator' => 'Estimator',
            'Years Estimate' => false,
            'Total Year Estimate' => 'Total Year Estimate',
            'Jan' => 'Jan',
            'Feb' => 'Feb',
            'Mar' => 'Mar',
            'Apr' => 'Apr',
            'Mei' => 'Mei',
            'Jun' => 'Jun',
            'Jul' => 'Jul',
            'Agt' => 'Agt',
            'Sep' => 'Sep',
            'Okt' => 'Okt',
            'Nov' => 'Nov',
            'Des' => 'Des',
            'UPLOAD STATUS' => false,

            // '*Spread Code' => 'Spread Code',
            // '*Tahun Disburse' => 'Tahun Disburse',
            // '*UPLOAD STATUS PROJECT' => 'UPLOAD STATUS PROJECT',
            // '*UPLOAD STATUS PROJECT ESTIMATE' => 'UPLOAD STATUS PROJECT ESTIMATE',
            // '*UPLOAD STATUS PERIOD PROJECT ESTIMATE' => 'UPLOAD STATUS PERIOD PROJECT ESTIMATE',
            // '*JUMLAH SUBMIT (KALI)' => 'JUMLAH SUBMIT (KALI)',
        ];
    @endphp
    <div class="col-md-12">
        <thead>
            <tr>
                @foreach ($columnMappings as $label => $column)
                    <th>{{ $label }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>

            @foreach($prk_parent_result as $key_parent => $parent)
                @if($key_parent!= '')
                    {{-- Buat row sendiri untuk tiap MAT, OTH, LAB --}}
                    @foreach(['Beban (MAT)'] as $k => $catcode)
                    <tr>
                        @foreach($columnMappings as $label => $column)
                            <td>
                                @if($column == 'Nomor Project / PRK')
                                    {{ preg_match('/E/', $key_parent) ? "'".$key_parent : $key_parent }}</td>
                                @else
                                    {{ loaderEllipsePgdlValPlain('parent', $catcode, $label, $column, $parent) }}
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    @endforeach

                    @foreach($prk_inti_result as $key_inti => $inti)
                        @if($key_inti!= '' && substr($key_inti,0,-2) == $key_parent)
                        {{-- Buat row sendiri untuk tiap MAT, OTH, LAB --}}
                        @foreach(['Beban (MAT)'] as $k => $catcode)
                        <tr>
                            @foreach($columnMappings as $label => $column)
                                <td>
                                    @php
                                        $value = loaderEllipsePgdlValPlain('inti', $catcode, $label, $column, $inti);
                                    @endphp
                                    @if($column == 'Nomor Project / PRK' || $column == 'Parent Project')
                                        {{ preg_match('/E/', $value) ? "'".$value : $value }}
                                    @else
                                        {{ $value }}
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        @endforeach

                        @foreach($prk_kegiatan_result as $key_kegiatan => $kegiatan)
                            @if(substr($kegiatan['Nomor Project / PRK'],0,6) == $key_inti || substr($kegiatan['Nomor Project / PRK'],0,-2) == $key_inti || substr($kegiatan['Nomor Project / PRK'],2, 6) == $key_inti)
                                {{-- Buat row sendiri untuk tiap MAT, OTH, LAB --}}
                                @foreach(['Beban (MAT)'] as $k => $catcode)
                                <tr>
                                    @foreach($columnMappings as $label => $column)
                                        <td>
                                            @php
                                                $value = loaderEllipsePgdlValPlain('kegiatan', $catcode, $label, $column, $kegiatan);
                                            @endphp
                                            @if($column == 'Nomor Project / PRK')
                                                @if(strlen($value)!=8)
                                                    {{ preg_match('/E/', $value) ? "'".substr($value,2,8) : substr($value,2,8) }}
                                                @else
                                                    {{ preg_match('/E/', $value) ? "'".$value : $value }}
                                                @endif
                                            @elseif($column == 'Parent Project')
                                                {{ preg_match('/E/', $value) ? "'".$value: $value }}
                                            @else
                                                {{ $value }}
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            @endif
                        @endforeach
                        @endif
                    @endforeach
                @endif
            @endforeach
        </tbody>
    </div>
</div>
