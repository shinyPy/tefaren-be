<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permohonan extends Model
{
    use HasFactory;
    protected $table = 'permohonan'; // Specify the table name explicitly
    protected $fillable = [
        'kesetujuan_syarat',
        'nomorinduk_pengguna',
        'email',
        'nama_pengguna',
        'tipe_pengguna',
        'id_jurusan',
        'kelas_pengguna',
        'nomor_wa',
        'id_jabatan',
        'id_barang',
        'nama_barang',
        'alasan_peminjaman',
        'jumlah_barang',
        'tanggal_peminjaman',
        'lama_peminjaman',
        'status_peminjaman',
    ];
}
