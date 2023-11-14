<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori_barang'; // Specify the table name explicitly

    use HasFactory;
    public function barang()
{
    return $this->hasMany(Barang::class);
}

}
