<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            margin: 6px 20px 5px 20px;
            line-height: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            padding: 4px 3px;
        }

        th {
            text-align: left;
        }

        .d-block {
            display: block;
        }

        img.image {
            width: auto;
            height: 80px;
            max-width: 150px;
            max-height: 150px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .p-1 {
            padding: 5px 1px 5px 1px;
        }

        .font-10 {
            font-size: 10pt;
        }

        .font-11 {
            font-size: 11pt;
        }

        .font-12 {
            font-size: 12pt;
        }

        .font-13 {
            font-size: 13pt;
        }

        .border-bottom-header {
            border-bottom: 1px solid;
        }

        .border-all,
        .border-all th,
        .border-all td {
            border: 1px solid;
        }
    </style>
</head>

<body>
    <table class="border-bottom-header">
        <tr>
            <td width="15%" class="text-center"><img src="{{ asset('polinema-bw.png') }}" class="image"></td>
            <td width="85%">
                <span class="text-center d-block font-11 font-bold mb-1">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</span>
                <span class="text-center d-block font-13 font-bold mb-1">POLITEKNIK NEGERI MALANG</span>
                <span class="text-center d-block font-10">Jl. Soekarno-Hatta No. 9 Malang 65141</span>
                <span class="text-center d-block font-10">Telepon (0341) 404424 Pes. 101-105, 0341-404420, Fax. (0341) 404420</span>
                <span class="text-center d-block font-10">Laman: www.polinema.ac.id</span>
            </td>
        </tr>
    </table>
    
    <h3 class="text-center font-13">LAPORAN DATA KEGIATAN</h3>
    
    <table class="border-all">
        <thead>
            <tr>
                <th class="text-center font-12">No</th>
                <th class="font-12">Kode Kegiatan</th>
                <th class="font-12">Nama Kegiatan</th>
                <th class="text-center font-12">Tanggal Mulai</th>
                <th class="text-center font-12">Tanggal Selesai</th>
                <th class="font-12">Kategori</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kegiatan as $k)
                <tr>
                    <td class="text-center font-10">{{ $loop->iteration }}</td>
                    <td class="font-10">{{ $k->kode_kegiatan }}</td>
                    <td class="font-10">{{ $k->nama_kegiatan }}</td>
                    <td class="text-center font-10">{{ \Carbon\Carbon::parse($k->tanggal_mulai)->format('d/m/Y') }}</td>
                    <td class="text-center font-10">{{ \Carbon\Carbon::parse($k->tanggal_selesai)->format('d/m/Y') }}</td>
                    <td class="font-10">{{ $k->kategoriKegiatan->nama_kategori_kegiatan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>