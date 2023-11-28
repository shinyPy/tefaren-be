<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permohonan extends Model
{
    use HasFactory;
    protected $table = 'permohonan'; // Specify the table name explicitly
    protected $fillable = [
        'nomor_peminjaman',
        'kesetujuan_syarat',
        'id_pengguna',
        'kelas_pengguna',
        'nomor_wa',
        'list_barang',
        'alasan_peminjaman',
        'jumlah_barang',
        'tanggal_peminjaman',
        'lama_peminjaman',
        'status_peminjaman',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id');
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'id_jurusan', 'id');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan', 'id');
    }
}
