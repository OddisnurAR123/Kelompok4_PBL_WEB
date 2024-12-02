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

        td, th {
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

        .font-10 {
            font-size: 10pt;
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
            <td width="15%" class="text-center"><img src="{{ asset('polinema-bw.jpg') }}" class="image"></td>
            <td width="85%">
                <span class="text-center d-block font-11 font-bold mb-1">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</span>
                <span class="text-center d-block font-13 font-bold mb-1">POLITEKNIK NEGERI MALANG</span>
                <span class="text-center d-block font-10">Jl. Soekarno-Hatta No. 9 Malang 65141</span>
                <span class="text-center d-block font-10">Telepon (0341) 404424 Pes. 101-105, 0341-404420, Fax. (0341) 404420</span>
                <span class="text-center d-block font-10">Laman: www.polinema.ac.id</span>
            </td>
        </tr>
    </table>
    
    <h3 class="text-center font-13">LAPORAN DATA DETAIL KEGIATAN</h3>
    
    <table class="border-all">
        <thead>
            <tr>
                <th class="text-center font-12">No</th>
                <th class="font-12">Nama Kegiatan</th>
                <th class="font-12">Keterangan</th>
                <th class="text-center font-12">Progres Kegiatan</th>
                <th class="font-12">Beban Kerja</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detail_kegiatan as $k)
                <tr>
                    <td class="text-center font-10">{{ $loop->iteration }}</td>
                    <td class="font-10">{{ $k->kegiatan->nama_kegiatan }}</td>
                    <td class="font-10">{{ $k->keterangan }}</td>
                    <td class="text-center font-10">{{ $k->progres_kegiatan }}%</td>
                    <td class="font-10">{{ $k->beban_kerja }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>