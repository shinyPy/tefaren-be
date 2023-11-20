<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori_barang'; // Specify the table name explicitly
    protected $primaryKey = 'id_kategori'; // Specify the custom primary key

    public function kategori_barang()
    {
        return $this->belongsTo(KategoriBarang::class, 'id_kategori', 'id_kategori');
    }
    use HasFactory;
    public function barang()
{
    return $this->hasMany(Barang::class);
}

}
