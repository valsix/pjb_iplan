<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Detail
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Tahun</th>
                        <th>Nama</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $form_bahan_bakar->tahun }}</td>
                        <td>{{ $form_bahan_bakar->name }}</td>
                    </tr>
                </tbody>
            </table>
        </div><br><br>

        <div class="x_content">
            <table id="datatable" class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th>Distrik</th>
                    <th>PRK</th>
                    <th>Januari</th>
                    <th>Februari</th>
                    <th>Maret</th>
                    <th>April</th>
                    <th>Mei</th>
                    <th>Juni</th>
                    <th>Juli</th>
                    <th>Agustus</th>
                    <th>September</th>
                    <th>Oktober</th>
                    <th>November</th>
                    <th>Desember</th>
                </tr>
                </thead>
                <tbody>
                @foreach($datas as $data)
                    <tr>
                        <td>{{ $data['distrik'] }}</td>
                        <td>{{ $data['prk'] }}</td>
                        @foreach($data['value'] as $value)
                            @for($i=0; $i < 12; $i++)
                              <td>{{ round($value[$data['prk']][$i], 2) }}</td>
                            @endfor
                        @endforeach
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>
