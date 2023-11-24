<?php

use Illuminate\Http\Request;

use App\Http\Controllers\EnumFetchControllers;
use App\Http\Controllers\JabatanValuesControllers;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Middleware\AuthenticateUser;
use App\Http\Controllers\CountController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PermohonanController;

use App\Models\Kategori;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [AuthController::class, 'login'])->middleware(AuthenticateUser::class)->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');

// Route::get('/barangShow', [BarangController::class, 'barangShow']);
// Route::post('/barangAdd', [BarangController::class, 'createOrUpdate']);
// Route::patch('/barangUpdate/{id}', [BarangController::class, 'createOrUpdate']);
// Route::delete('/barangDelete/{nomor_barang}', [BarangController::class, 'delete']);
// Route::post('/barangShowByNomor', [BarangController::class, 'fetchByNomor']);

// Route::get('/jurusan-values', [EnumFetchControllers::class, 'getJurusanValues']);
// Route::get('/jabatan-values', [JabatanValuesControllers::class, 'getJabatanValues']);

// Route::get('count-usersbyjurusan', [CountController::class, 'countUsersByJurusan']);
// Route::get('count-usersbyjabatan', [CountController::class, 'countUsersByJabatan']);

// Route::get('count-barang', [CountController::class, 'countBarang']);
// Route::get('count-pengguna', [CountController::class, 'countPengguna']);
// Route::get('count-peminjaman', [CountController::class, 'countPeminjaman']);
// Route::get('count-pengembalian', [CountController::class, 'countPengembalian']);
// Route::get('count-permohonan', [CountController::class, 'countPermohonan']);
// Route::get('count-tipepengguna', [CountController::class, 'countTipePengguna']);
// Route::get('count-tipebarang', [CountController::class, 'countTipeBarang']);

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/check-email', [PenggunaController::class, 'checkEmail']);


Route::get('/jurusan-values', [EnumFetchControllers::class, 'getJurusanValues']);
Route::get('/jabatan-values', [JabatanValuesControllers::class, 'getJabatanValues']);

Route::middleware('auth:api')->group(function () {
    // Define your routes here that should use the 'auth:api' middleware.
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/kategori-values', [JabatanValuesControllers::class, 'getKategoriValues']);


    Route::get('/get-kategori', [KategoriController::class, 'index']);
    Route::post('/add-kategori', [KategoriController::class, 'store']);
    Route::put('/edit-kategori/{id}', [KategoriController::class, 'update']);
    Route::delete('/delete-kategori/{id}', [KategoriController::class, 'destroy']);

    Route::get('/get-jurusan', [JurusanController::class, 'index']);
    Route::post('/add-jurusan', [JurusanController::class, 'store']);
    Route::put('/edit-jurusan/{id}', [JurusanController::class, 'update']);
    Route::delete('/delete-jurusan/{id}', [JurusanController::class, 'destroy']);

    Route::get('/get-jabatan', [JabatanController::class, 'index']);
    Route::post('/add-jabatan', [JabatanController::class, 'store']);
    Route::put('/edit-jabatan/{id}', [JabatanController::class, 'update']);
    Route::delete('/delete-jabatan/{id}', [JabatanController::class, 'destroy']);

    Route::put('/barangUpdate/{id}', [BarangController::class, 'update']);
    Route::delete('/barangDelete/{id}', [BarangController::class, 'destroy']);
    Route::get('/barangShow', [BarangController::class, 'index']);
    Route::post('/barangAdd', [BarangController::class, 'store']);
    Route::post('/upload-gambar-barang', [BarangController::class, 'uploadGambarBarang']);

    Route::get('count-usersbyjurusan', [CountController::class, 'countUsersByJurusan']);
    Route::get('count-usersbyjabatan', [CountController::class, 'countUsersByJabatan']);

    Route::get('count-barang', [CountController::class, 'countBarang']);
    Route::get('count-pengguna', [CountController::class, 'countPengguna']);
    Route::get('count-peminjaman', [CountController::class, 'countPeminjaman']);
    Route::get('count-pengembalian', [CountController::class, 'countPengembalian']);
    Route::get('count-permohonan', [CountController::class, 'countPermohonan']);
    Route::get('count-tipepengguna', [CountController::class, 'countTipePengguna']);
    Route::get('count-tipebarang', [CountController::class, 'countTipeBarang']);

    Route::get('/count-barangkategori', [CountController::class, 'countBarangByKategori']);

    Route::get('/pengguna', [PenggunaController::class, 'index']);
    Route::get('/pengguna/{id}', [PenggunaController::class, 'show']);
    Route::put('/editpengguna/{nomorinduk_pengguna}', [PenggunaController::class, 'update']);
    Route::delete('/deletepengguna/{nomorinduk_pengguna}', [PenggunaController::class, 'destroy']);


Route::get('/permohonans', [PermohonanController::class, 'index']);
Route::get('/permohonans/{id}', [PermohonanController::class, 'show']);
Route::post('/permohonans', [PermohonanController::class, 'store']);
Route::put('/permohonans/{id}', [PermohonanController::class, 'update']);
Route::delete('/permohonans/{id}', [PermohonanController::class, 'destroy']);

});
