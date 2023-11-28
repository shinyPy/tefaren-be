<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{

    protected $table = 'barang';

    protected $fillable = ['kode_barang', 'id_kategori', 'nama_barang', 'ketersediaan_barang', 'status_barang', 'gambar_barang'];

    // Add any additional model logic or relationships here

    public static function createOrUpdate($data, $id = null)
    {
        if ($id) {
            // Update an existing record
            return static::where('nomor_barang', $id)->update($data);
        } else {
            // Create a new record
            return static::create($data);
        }
    }
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function getRouteKeyName()
    {
        return 'nomor_barang';
    }
}
