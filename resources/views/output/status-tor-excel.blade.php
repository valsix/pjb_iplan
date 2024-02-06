<table>
    <tr>
        <td colspan="9">
            <h1>Status TOR</h1>    
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
                <th colspan="8"><h2>SUMMARY STATUS TOR AVAILABLE</h2></th>
            </tr>
            <tr>
                <th colspan="2"></th>
                <th colspan="2">TOR Status</th>
                <th colspan="4">Review Status</th>
            </tr>
            <tr>
                <th>Parent</th>
                <th>TOR</th>
                <th>Submitted</th>
                <th>N/A</th>
                <th>Approved</th>
                <th>Revised</th>
                <th>Rejected</th>
                <th>Queue</th>
            </tr>
        </thead>
        <tbody>
            @foreach($summary_tor as $key => $summary)
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
                <th colspan="12"><h2>RINCIAN STATUS TOR</h2></th>
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
                <th>TOR Status</th>
                <th>Review Status</th>
                <th>Approved</th>
                <th>Rejected</th>
                <th>Revised</th>
                <th>Queue</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data_tor as $key => $tor)
                <tr>
                    <td>{{$tor['dokumen_id']}}</td>
                    <td>{{$tor['parent']}}</td>
                    <td>{{$tor['prk']}}</td>
                    <td>{{$tor['nama_prk']}}</td>
                    <td style="text-align: right">{{number_format($tor['anggaran'], 0, '.', ',')}}</td>
                    <td>{{$tor['tor_status']}}</td>
                    <td>{{$tor['review_status']}}</td>
                    <td>{{$tor['approved_at']}}</td>
                    <td>{{$tor['rejected_at']}}</td>
                    <td>{{$tor['revised_at']}}</td>
                    <td>{{$tor['submitted_at']}}</td>
                </tr>
            @endforeach
        </tbody>
        </table>
    </div>
</div>
