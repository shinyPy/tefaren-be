<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    protected $table = 'jurusan'; // Specify the table name explicitly
    protected $primaryKey = 'id_jurusan'; // Specify the custom primary key
    protected $fillable = ['jurusan'];

    use HasFactory;
    public function pengguna()
    {
        return $this->hasMany(Pengguna::class, 'id_jabatan', 'id_jabatan');
    }

}
