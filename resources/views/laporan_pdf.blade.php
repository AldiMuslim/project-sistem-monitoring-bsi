<!DOCTYPE html>
<html>

<head>
    <title>Laporan Persediaan BSI</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #00676F;
            color: white;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>LAPORAN MUTASI BARANG (ATM & BUKU)</h2>
        <p>Bank Syariah Indonesia</p>
        <hr>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Barang</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Petugas</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($riwayat as $index => $r)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $r->tanggal->format('d/m/Y') }}</td>
                    <td>{{ $r->nama_barang }}</td>
                    <td>{{ $r->jenis }}</td>
                    <td>{{ $r->jumlah }}</td>
                    <td>{{ $r->petugas }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ $tgl_cetak }}</p>
        <br><br>
        <p>( {{ $admin }} )</p>
    </div>
</body>

</html>
