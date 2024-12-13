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
            margin-top: 20px;
            float: right;
            text-align: left;
        }
            .footer .sign {
            margin-top: 80px;
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
    <div style="display: flex; justify-content: center; margin-bottom: 20px;">
        <table style="text-align: center; border-bottom: 2px solid #000; width: 100%;">
            <tr>
                <td width="15%" style="vertical-align: middle;">
                    <img src="{{ asset('polinema-bw.jpg') }}" style="width: 80px; height: auto;">
                </td>
                <td width="85%" style="font-family: 'Times New Roman', Times, serif;">
                    <span style="font-size: 15px;">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET DAN</span><br>
                    <span style="font-size: 15px;">TEKNOLOGI</span><br>
                    <span style="font-size: 17px; font-weight: bold;">POLITEKNIK NEGERI MALANG</span><br>
                    <span style="font-size: 15px; font-weight: bold;">JURUSAN TEKNOLOGI INFORMASI</span><br>
                    <span style="font-size: 12px;">Jl. Soekarno Hatta No.9 Malang 65141</span><br>
                    <span style="font-size: 12px;">Telp (0341) 404424 – 404425 Fax (0341) 404420</span><br>
                    <span style="font-size: 12px;">Laman: <a href="https://www.polinema.ac.id" style="text-decoration: none; color: #0000EE;">www.polinema.ac.id</a></span>
                </td>
            </tr>
        </table>
    </div>

    <div class="header-info">
        <p>Nomor: </p>
        <p>Lampiran: -</p>
        <p>Perihal: Permohonan Surat Tugas</p>
    </div>

    <div class="content" style="font-family: 'Times New Roman', Times, serif; font-size: 15px; line-height: 15px; text-align: left; margin-top: 20px;">
        <p style="display: inline-block; margin-right: 20px; font-weight: bold;">Kepada Yth.</p>
        <p style="display: inline-block; line-height: 1.5; font-weight: bold; margin-left: 10px;">Pembantu Direktur I Politeknik Negeri Malang</p>
        <br>
        <p style="font-weight: bold;">di Tempat</p>
    </div>
    
    

        <p>Sehubungan dengan Kegiatan "<strong>{{ $kegiatan->nama_kegiatan }}</strong>" yang diselenggarakan di {{ $kegiatan->tempat_kegiatan }} pada tanggal {{ $kegiatan->tanggal_mulai }} - {{ $kegiatan->tanggal_selesai }}, maka dengan ini kami mohon dapat diperkenankan Surat Tugas kepada yang mengikuti kegiatan:</p>
        
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

    <<div class="footer">
        <p style="margin-bottom: 2px;">Ketua Jurusan</p>
        
        <p class="sign" style="margin-bottom: 2px;">
            Dr.Eng. Rosa Andrie Asmara
        </p>
        <hr style="border: 0.3px solid black; width: 200px; margin: 2px 0;">
        <p style="margin-top: 2px;">
            NIP: 198010102005011001
        </p>
    </div>
    
</body>
</html>