<table>
    <tr>
        <td colspan="9">
            <h1>Status DMR</h1>    
        </td>
    </tr>
    <tr>
        <th>Tahun Anggaran</th>
        <td>{{$input_tahun}}</td>
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
        <td>{{$input_lokasi->name}}</td>
    </tr>
</table>
<div class="row">
    <div class="col-md-12">
    <table>
        <thead>
            <tr>
                <th colspan="8"><h2>SUMMARY STATUS DMR AVAILABLE</h2></th>
            </tr>
            <tr>
                <th colspan="2"></th>
                <th colspan="2">DMR Status</th>
                <th colspan="4">Review Status</th>
            </tr>
            <tr>
                <th>Parent</th>
                <th>DMR</th>
                <th>Submitted</th>
                <th>N/A</th>
                <th>Approved</th>
                <th>Revised</th>
                <th>Rejected</th>
                <th>Queue</th>
            </tr>
        </thead>
        <tbody>
            @foreach($summary_dmr as $key => $summary)
                <tr>
                    <td>{{$key}}</td>
                    <td style="text-align: right">{{$summary['total']}}</td>
                    <td style="text-align: right">{{$summary['approved'] + $summary['revised'] + $summary['rejected'] + $summary['queue'] }}</td>
                    <td style="text-align: right">{{$summary['total'] - ($summary['approved'] + $summary['revised'] + $summary['rejected'] + $summary['queue']) }}</td>
                    <td style="text-align: right">{{$summary['approved']}}</td>
                    <td style="text-align: right">{{$summary['revised']}}</td>
                    <td style="text-align: right">{{$summary['rejected']}}</td>
                    <td style="text-align: right">{{$summary['queue']}}</td>
                  
                </tr>
            @endforeach
        </tbody>
        </table>
<table>
        <thead>
            <tr>
                <th colspan="12"><h2>RINCIAN STATUS DMR</h2></th>
            </tr>
            <tr>
                <th colspan="8"></th>
                <th colspan="4">Date</th>
            </tr>
            <tr>
                <th>Dokumen ID</th>
                <th>Parent</th>
                <th>No PRK</th>
                <th>Nama PRK</th>
                <th>Anggaran PRK</th>
                <th>DMR Status</th>
                <th>Review Status</th>
                <th>Approved</th>
                <th>Rejected</th>
                <th>Revised</th>
                <th>Queue</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data_dmr as $key => $dmr)
                <tr>
                    <td>{{$dmr['dokumen_id']}}</td>
                    <td>{{$dmr['parent']}}</td>
                    <td>{{$dmr['prk']}}</td>
                    <td>{{$dmr['nama_prk']}}</td>
                    <td style="text-align: right">{{number_format($dmr['anggaran'], 0, '.', ',')}}</td>
                    <td>{{$dmr['dmr_status']}}</td>
                    <td>{{$dmr['review_status']}}</td>
                    <td>{{$dmr['approved_at']}}</td>
                    <td>{{$dmr['rejected_at']}}</td>
                    <td>{{$dmr['revised_at']}}</td>
                    <td>{{$dmr['submitted_at']}}</td>
                </tr>
            @endforeach
        </tbody>
        </table>
    </div>
</div>
