<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_peminjaman';
    protected $table = 'peminjaman'; // Specify the table name explicitly

    protected $fillable = [
        'id_permohonan',
        'id_peminjaman',
        'id_barang',
        'status_barang',
        'status_pengembalian',
    ];

    public function pengembalian()
{
    return $this->hasOne(Pengembalian::class, 'id_peminjaman');
}

public function permohonan()
{
    return $this->belongsTo(Permohonan::class, 'id_permohonan');
}

public function barang()
{
    return $this->belongsTo(Barang::class, 'id_barang');
}



}
