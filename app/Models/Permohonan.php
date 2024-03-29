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
        'id_pengguna',
        'kelas_pengguna',
        'nomor_wa',
        'details_barang',
        'alasan_peminjaman',
        'tanggal_peminjaman',
        'lama_peminjaman',
        'status_peminjaman',
    ];
    protected $primaryKey = 'id';

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id');
    }

    
    

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'id_jurusan', 'id_jurusan');
    }

    public function barangDetails()
    {
        return $this->hasMany(Barang::class, 'id_barang');
    }
    public function barangs()
    {
        return $this->hasMany(Barang::class, 'id_barang');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan', 'id_jabatan');
    }

    public function getBarangDetailsAttribute()
    {
        $details = json_decode($this->attributes['details_barang']);
        $barangIds = collect($details)->pluck('id');
        $barangs = Barang::whereIn('id_barang', $barangIds)->get();

        return $barangs;
    }

}
