@if($kategori == 'harian')
    <h3>Laporan Harian</h3>
    <table>
        <thead>
            <tr>
                <th>tanggal</th>
                <th>hari</th>
                <th>guest</th>
                <th>user</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
                <tr>
                    <td>{{ $item->tanggal }}</td>
                    <td>{{ $item->hari }}</td>
                    <td>{{ $item->guest }}</td>
                    <td>{{ $item->user }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

@elseif($kategori == 'bulanan')
    <h3>Laporan Bulanan</h3>
    <table>
        <thead>
            <tr>
                <th>bulan</th>
                <th>tahun</th>
                <th>guest</th>
                <th>user</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
                <tr>
                    <td>{{ $item->bulan }}</td>
                    <td>{{ $item->tahun }}</td>
                    <td>{{ $item->guest }}</td>
                    <td>{{ $item->user }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

@elseif($kategori == 'tahunan')
    <h3>Laporan Tahunan</h3>
    <table>
        <thead>
            <tr>
                <th>tahun</th>
                <th>guest</th>
                <th>user</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
                <tr>
                    <td>{{ $item->tahun }}</td>
                    <td>{{ $item->guest }}</td>
                    <td>{{ $item->user }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif