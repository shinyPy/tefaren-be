<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
class Pengguna extends Model implements Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens; // Make sure to include HasApiTokens
    protected $table = 'pengguna';

    protected $fillable = ['email', 'password', 'nomorinduk_pengguna'];

    protected $primaryKey = 'id_pengguna'; // Specify the primary key column name

    // Add the remember_token column to your table if not already added

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'id_pengguna'; // Replace with the actual column name for the user identifier
    }

    // Implement the methods related to "remember me" functionality

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }
    public function jurusan()
{
    return $this->belongsTo(Jurusan::class, 'id_jurusan'); // Specify the foreign key column
}

public function jabatan()
{
    return $this->belongsTo(Jabatan::class, 'id_jabatan'); // Specify the foreign key column
}

    
    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    // Rest of your model code...
}
