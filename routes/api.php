<?php

use Illuminate\Http\Request;
use App\Http\Controllers\EnumFetchControllers;
use App\Http\Controllers\JabatanValuesControllers;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\CountController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PermohonanController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;

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

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/check-nomorinduk', [PenggunaController::class, 'checkNomorInduk']);

Route::get('/check-email', [PenggunaController::class, 'checkEmail']);
Route::get('/check-nomorinduk', [PenggunaController::class, 'checkNomorInduk']);
Route::get('/barang-card', [BarangController::class, 'card']);

Route::get('/kategori-values-ps', [JabatanValuesControllers::class, 'getKategoriValues']);


Route::get('/kategori-values-ps', [JabatanValuesControllers::class, 'getKategoriValues']);

Route::get('/jurusan-values', [EnumFetchControllers::class, 'getJurusanValues']);
Route::get('/jabatan-values', [JabatanValuesControllers::class, 'getJabatanValues']);
Route::get('/barang-card', [BarangController::class, 'card']);


Route::middleware('JWTAuthentication')->group(function () {
    Route::get('/check-token', [AuthController::class, 'checkToken']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::middleware('AdminCheck')->group(function () {
        Route::get('/kategori-values', [KategoriController::class, 'getKategoriValues']);
        Route::get('/list-kategori', [KategoriController::class, 'list']);
        Route::get('/get-kategori', [KategoriController::class, 'index']);
        Route::post('/add-kategori', [KategoriController::class, 'store']);
        Route::put('/edit-kategori/{id}', [KategoriController::class, 'update']);
        Route::delete('/delete-kategori/{id}', [KategoriController::class, 'destroy']);

        Route::get('/list-jurusan', [JurusanController::class, 'list']);
        Route::get('/get-jurusan', [JurusanController::class, 'index']);
        Route::post('/add-jurusan', [JurusanController::class, 'store']);
        Route::put('/edit-jurusan/{id}', [JurusanController::class, 'update']);
        Route::delete('/delete-jurusan/{id}', [JurusanController::class, 'destroy']);

        Route::get('/list-jabatan', [JabatanController::class, 'list']);
        Route::get('/get-jabatan', [JabatanController::class, 'index']);
        Route::post('/add-jabatan', [JabatanController::class, 'store']);
        Route::put('/edit-jabatan/{id}', [JabatanController::class, 'update']);
        Route::delete('/delete-jabatan/{id}', [JabatanController::class, 'destroy']);

        Route::put('/barangUpdate/{id}', [BarangController::class, 'update']);
        Route::delete('/barangDelete/{id}', [BarangController::class, 'destroy']);
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

        Route::put('/edit-pengembalian/{id}', [PeminjamanController::class, 'updatePengembalian']);


        Route::get('/pengguna', [PenggunaController::class, 'index']);
        Route::get('/pengguna/{id}', [PenggunaController::class, 'show']);
        Route::put('/editpengguna/{nomorinduk_pengguna}', [PenggunaController::class, 'update']);
        Route::delete('/deletepengguna/{nomorinduk_pengguna}', [PenggunaController::class, 'destroy']);

        Route::put('/edit-permohonan/{id}', [PermohonanController::class, 'update']);
        Route::delete('/delete-permohonan/{id}', [PermohonanController::class, 'destroy']);
        
    });


    // Users
    Route::get('/barangShow', [BarangController::class, 'index']);
    Route::get('/show-permohonan', [PermohonanController::class, 'index']);
    Route::delete('/delete-peminjaman/{idPeminjaman}', [PeminjamanController::class, 'deletePeminjaman']);

    Route::put('/edit-peminjaman/{id}', [PeminjamanController::class, 'update']);
    Route::get('/show-peminjaman', [PeminjamanController::class, 'index']);

    Route::post('/add-permohonan', [PermohonanController::class, 'store']);
    Route::get('/show-pengembalian', [PengembalianController::class, 'index']);
    Route::delete('/delete-pengembalian/{id}', [PengembalianController::class, 'destroy']);


});
