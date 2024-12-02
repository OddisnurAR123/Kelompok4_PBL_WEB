<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: "Times New Roman", serif;
            margin: 20px;
            line-height: 1.5;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .header {
            text-align: center;
        }
        .header img {
            height: 80px;
        }
        .header div {
            font-size: 12px;
            margin-top: 5px;
        }
        .title {
            text-align: center;
            margin-top: 20px;
            font-size: 16px;
            font-weight: bold;
        }
        .content {
            margin-top: 20px;
            font-size: 14px;
            text-align: justify;
        }
        .signature {
            margin-top: 40px;
            text-align: right;
        }
        .signature div {
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('polinema-bw.png') }}" alt="Logo Polinema">
        <div>KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</div>
        <div>POLITEKNIK NEGERI MALANG</div>
        <div>Jl. Soekarno Hatta No.9 Malang 65141</div>
        <div>Telp. (0341) 404424 Fax. (0341) 404420</div>
        <div>Website: www.polinema.ac.id</div>
    </div>
    <div class="title">SURAT TUGAS</div>
    <div class="content">
        <p>Nomor: {{ $nomor }}</p>
        <p>Wakil Direktur III Politeknik Negeri Malang memberikan tugas kepada:</p>
        <ul>
            @foreach($nama_dosen as $dosen)
            <li>{{ $dosen }}</li>
            @endforeach
        </ul>
        <p>Untuk bertugas sebagai Dosen Pembimbing dan Peserta kegiatan “{{ $kegiatan }}” yang dilaksanakan pada tanggal {{ $tanggal }} secara {{ $tempat }}.</p>
        <p>Surat tugas ini dibuat untuk dilaksanakan dengan penuh tanggung jawab dan membuat laporan.</p>
    </div>
    <div class="signature">
        <div>Malang, {{ $tanggal_surat }}</div>
        <div>Wakil Direktur III,</div>
        <br><br><br>
        <div><b>Dr. Eng. Anggit Murdani, ST., M.Eng.</b></div>
        <div>NIP. 19710115 199903 1 001</div>
    </div>
</body>
</html>
