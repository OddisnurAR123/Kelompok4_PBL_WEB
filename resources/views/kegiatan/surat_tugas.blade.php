<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Tugas</title>
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
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .p-1 {
            padding: 5px 1px;
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
        .border-all, .border-all th, .border-all td {
            border: 1px solid;
        }
        .content {
            margin-top: 30px;
        }
        .content p {
            text-align: justify;
            line-height: 1.5;
        }
        .table-container {
            margin-top: 20px;
            border-collapse: collapse;
            width: 100%;
        }
        .table-container th, .table-container td {
            padding: 8px;
            border: 1px solid black;
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
        .footer .sign {
            margin-top: 20px;
        }
        .header-info {
            margin-top: 10px;
            margin-bottom: 20px;
        }
        .header-info p {
            margin: 0;
        }
    </style>
</head>
<body>
    <table class="border-bottom-header">
        <tr>
            <td width="15%" class="text-center">
                <img src="{{ asset('polinema-bw.png') }}" alt="Logo Polinema">
            </td>
            <td width="85%">
                <span class="text-center d-block font-11 font-bold mb-1">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</span>
                <span class="text-center d-block font-13 font-bold mb-1">POLITEKNIK NEGERI MALANG</span>
                <span class="text-center d-block font-10">Jl. Soekarno-Hatta No. 9 Malang 65141</span>
                <span class="text-center d-block font-10">Telepon (0341) 404424 Pes. 101-105, 0341-404420, Fax. (0341) 404420</span>
                <span class="text-center d-block font-10">Laman: www.polinema.ac.id</span>
            </td>
        </tr>
    </table>

    <div class="header-info">
        <p>Nomor: </p>
        <p>Lampiran: -</p>
        <p>Perihal: Permohonan Surat Tugas</p>
    </div>

    <div class="content">
        <p>Kepada Yth.</p>
        <p>Wakil Direktur I Politeknik Negeri Malang<br>di Tempat</p>

        <p>Sehubungan dengan Kegiatan <strong>{{ $kegiatan->nama_kegiatan }}</strong> yang diselenggarakan oleh di Politeknik Negeri Malang pada tanggal {{ $kegiatan->tanggal_mulai }} - {{ $kegiatan->tanggal_selesai }}, maka dengan ini kami mohon dapat diperkenankan Surat Tugas kepada yang mengikuti kegiatan:</p>
        
        <table class="table-container">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>NIP</th>
                    <th>NAMA</th>
                    <th>JABATAN</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kegiatan->pengguna as $index => $peng)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $peng->nip }}</td>
                        <td>{{ $peng->nama_pengguna }}</td>
                        <td>{{ $peng->jenisPengguna->nama_jenis_pengguna }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
            
        <p>Demikian permohonan ini atas perhatian kami sampaikan terima kasih.</p>
    </div>

    <div class="footer">
        <p>Ketua Jurusan</p>

        <p class="sign">Dr.Eng. Rosa Andrie Asmara<br>NIP: 198010102005011001</p>
    </div>
</body>
</html>