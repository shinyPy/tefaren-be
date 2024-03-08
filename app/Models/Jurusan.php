<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    protected $table = 'jurusan';
    protected $fillable = ['jurusan'];
    protected $primaryKey = 'id_jurusan';

    use HasFactory;
    public function pengguna()
    {
        return $this->hasMany(Pengguna::class, 'id_jurusan', 'id_jurusan');
    }
}
