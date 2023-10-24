<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang'; // Specify the table name if it's different from the model name
    protected $primaryKey = 'nomor_barang'; // Specify the primary key column

    protected $fillable = ['kode_barang', 'nama_barang', 'ketersediaan_barang', 'gambar_barang'];

    // Add any additional model logic or relationships here

    public static function createOrUpdate($data, $id = null)
    {
        if ($id) {
            // Update an existing record
            return static::where('nomor_barang', $id)->update($data);
        } else {
            // Create a new record
            return static::create($data);
        }
    }
    
    public function getRouteKeyName()
    {
        return 'nomor_barang';
    }
}
