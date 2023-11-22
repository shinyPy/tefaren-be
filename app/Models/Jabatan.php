<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $table = 'jabatan'; // Specify the table name explicitly
    protected $primaryKey = 'id_jabatan'; // Specify the custom primary key
    protected $fillable = ['jabatan'];

    public function pengguna()
    {
        return $this->hasMany(Pengguna::class, 'id_jabatan', 'id_jabatan');
    }
    }
