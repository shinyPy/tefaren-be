<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;
    protected $table = 'kategori_barang';
    protected $fillable = ['kategori'];
    protected $primaryKey = 'id_kategori';

    public function kategori_barang()
    {
        return $this->belongsTo(KategoriBarang::class, 'id_kategori', 'id_kategori');
    }

    public function barang()
    {
        return $this->hasMany(Barang::class);
    }
}
