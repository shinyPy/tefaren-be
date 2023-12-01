<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{

    protected $table = 'barang';
    protected $primaryKey = 'id_barang'; // Specify the primary key column

    protected $fillable = ['kode_barang', 'id_kategori', 'nama_barang', 'ketersediaan_barang', 'status_barang', 'gambar_barang'];

    // Add any additional model logic or relationships here

  
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

  
}
