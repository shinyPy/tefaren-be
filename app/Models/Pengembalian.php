<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    protected $table = 'pengembalian'; // Specify the table name explicitly
    protected $fillable = ['id_peminjaman', 'status_barang', 'bukti_pengembalian', 'status_pengembalian'];
    protected $primaryKey = 'id';


    public function peminjaman()
{
    return $this->belongsTo(Peminjaman::class, 'id_peminjaman');
}
}
// In the Pengembalian model
