<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject; // Use the custom namespace
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Pengguna extends Model implements Authenticatable, JWTSubject
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'pengguna';
    protected $fillable = ['email', 'password', 'nomorinduk_pengguna'];
    protected $primaryKey = 'id_pengguna';

    // Add the remember_token property to your model
    protected $remember_token;

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

    /**
     * Get the "remember me" token for the user.
     *
     * @return string|null
     */
    public function getRememberToken()
    {
        return $this->remember_token;
    }

    /**
     * Set the "remember me" token for the user.
     *
     * @param string $value
     */
    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan', 'id_jabatan');
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'id_jurusan', 'id_jurusan');
    }
    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    public function getJabatanAttribute()
    {
        return $this->id_jabatan ? Jabatan::find($this->id_jabatan)->only(['id_jabatan', 'jabatan']) : null;
    }

    public function getJurusanAttribute()
    {
        return $this->id_jurusan ? Jurusan::find($this->id_jurusan)->only(['id_jurusan', 'jurusan']) : null;
    }
    /**
     * Implement methods for the JWTSubject interface
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    // Define relationships and other model code...
}
