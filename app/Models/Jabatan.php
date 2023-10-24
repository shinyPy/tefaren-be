<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $table = 'jabatan'; // Specify the table name explicitly

    public function pengguna()
    {
        return $this->hasMany(Pengguna::class);
    }
    }
