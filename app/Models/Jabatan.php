<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $table = 'jabatan';
    protected $fillable = ['jabatan'];
    protected $primaryKey = 'id_jabatan';

    public function pengguna()
    {
        return $this->hasMany(Pengguna::class, 'id_jabatan', 'id_jabatan');
    }
}
