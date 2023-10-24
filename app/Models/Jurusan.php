<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    protected $table = 'jurusan'; // Specify the table name explicitly

    use HasFactory;
    public function pengguna()
{
    return $this->hasMany(Pengguna::class);
}

}
