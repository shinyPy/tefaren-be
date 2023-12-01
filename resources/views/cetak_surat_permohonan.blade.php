<?php
use App\Models\Barang;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            /* Remove default margin */
        }

        .container {
            margin-top: -1.6rem;
            display: flex;
            flex-direction: column;
        }

        .header {
            text-align: center;
        }

        .container-2 {
            margin: 2%;
            text-align: left;
        }

        .font-bold {
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }
    </style>
    <title>SURAT PERMOHONAN</title>
</head>

<body>


    <?php
    
    // Mapping of English day names to Indonesian day names
    $dayMapping = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu',
    ];
    
    // Mapping of English month names to Indonesian month names
    $monthMapping = [
        'January' => 'Januari',
        'February' => 'Februari',
        'March' => 'Maret',
        'April' => 'April',
        'May' => 'Mei',
        'June' => 'Juni',
        'July' => 'Juli',
        'August' => 'Agustus',
        'September' => 'September',
        'October' => 'Oktober',
        'November' => 'November',
        'December' => 'Desember',
    ];
    
    // Get the English day and month names from the date
    $englishDay = date('l', strtotime($permohonan['tanggal_peminjaman']));
    $englishMonth = date('F', strtotime($permohonan['tanggal_peminjaman']));
    
    // Get the Indonesian day and month names from the mapping
    $indonesianDay = $dayMapping[$englishDay];
    $indonesianMonth = $monthMapping[$englishMonth];
    
    // Format the date with the Indonesian day and month names
    $date_in_indonesian = $indonesianDay . date(', j ') . $indonesianMonth . date(' Y', strtotime($permohonan['tanggal_peminjaman']));
    ?>

    <div class="container">
        <div class="header">
            <img src="images/kop_surat.png" width="750px" height="150px" alt="">
            <br>
            <div class="container-2 text-end">
                <span class="font-bold">No Peminjaman&nbsp;:&nbsp;<?= $permohonan['nomor_peminjaman'] ?>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            </div>
        </div>

        <div class="container-2">
            <h4 class="text-center">SURAT PERMOHONAN PEMINJAMAN ALAT LAB</h4>
            <span>
                <table>
                    <tr>
                        <td>Saya yang bertanda tangan di bawah ini:</td>
                    </tr>
                </table>


            </span>
            <table>
                <tr>
                    <td style="padding-bottom: 3%;">Nama</td>
                    <td style="padding-bottom: 3%;">:</td>
                    <td style="padding-bottom: 3%;">&nbsp;<?= $permohonan['pengguna']['nama_pengguna'] ?></td>
                </tr>
                <tr>
                    <td style="padding-bottom: 3%;">NIS</td>
                    <td style="padding-bottom: 3%;">:</td>
                    <td style="padding-bottom: 3%;">&nbsp;<?= $permohonan['pengguna']['nomorinduk_pengguna'] ?></td>
                </tr>
                <tr>
                    <td style="padding-bottom: 3%;">Kelas</td>
                    <td style="padding-bottom: 3%;">:</td>
                    <td style="padding-bottom: 3%;">&nbsp;<?= $permohonan['kelas_pengguna'] ?></td>
                </tr>
                <tr>
                    <td style="padding-bottom: 3%;">Keperluan Alat</td>
                    <td style="padding-bottom: 3%;">:</td>
                    <td style="padding-bottom: 3%;">&nbsp;<?= $permohonan['alasan_peminjaman'] ?></td>
                </tr>
                <tr>
                    <td style="padding-bottom: 3%;">Hari, Tanggal Pinjam</td>
                    <td style="padding-bottom: 3%;">:</td>
                    <td style="padding-bottom: 3%;">&nbsp;<?= $date_in_indonesian ?></td>
                </tr>
                <tr>
                    <td style="padding-bottom: 3%;">Lama Peminjaman</td>
                    <td style="padding-bottom: 3%;">:</td>
                    <td style="padding-bottom: 3%;">&nbsp;<?= $permohonan['lama_peminjaman'] ?></td>
                </tr>

            </table>
            <table>
                <tr>
                    <td>Dengan memohon untuk dipinjamkan alat sebagai berikut :</td>
                </tr>
            </table>
            <table border="1" style="border-collapse: collapse; width:100%; margin-top:0.8rem; margin-bottom:0.8rem">
                <tr style="text-align: center">
                    <th style="">No</th>
                    <th style="">Nama Alat</th>
                    <th style="">Jumlah</th>
                    <th style="">Keadaan Saat Alat Dipinjam</th>
                </tr>
            
                <?php $counter = 1; ?>
                <?php foreach(json_decode($permohonan['details_barang'], true) as $item): ?>
                <?php
                
                    // Fetch additional details for each item using the $item['id']
                    $barang = Barang::find($item['id']); // Assuming you have a Barang model
            
                    // Check if the Barang exists
                    if ($barang) {
                        $nama_alat = $barang->nama_barang;
                        $jumlah = 1; // Assuming you want to display the quantity as 1, modify as needed
                        $keadaan_saat_dipinjam = $barang->status_barang; // Example, change it based on your needs
                    } else {
                        $nama_alat = 'Not Found';
                        $jumlah = 'Not Found';
                        $keadaan_saat_dipinjam = 'Not Found';
                    }
                ?>
                <tr>
                    <td style="padding: 1.5%"><?= $counter++ ?></td>
                    <td><?= $nama_alat ?></td>
                    <td><?= $jumlah ?></td>
                    <td><?= $keadaan_saat_dipinjam ?></td>
                </tr>
            <?php endforeach; ?>
            </table>
            <span>
                Dan bertanggung jawab atas alat tersebut diatas, bila terjadi sesuatu yang menyebabkan alat tersebut
                dikembalikan dalam keadaan tidak seperti sebelumnya, dan bersedia mengantinya
            </span>

            <div class="text-end">
                <br>
                <br>
                Banjarbaru,……………… 2023 <br>
                Peminjam,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <br>
                <br>
                <br>
                (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
            </div>
        </div>
    </div>
</body>

</html>
