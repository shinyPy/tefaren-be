<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::with('kategori')->get();

        // Decrypt sensitive data before returning
        $barang->transform(function ($item) {
            $item->kode_barang = Crypt::decryptString($item->kode_barang);
            $item->nomor_barang = Crypt::decryptString($item->nomor_barang);
            $item->nama_barang = Crypt::decryptString($item->nama_barang);
            return $item;
        });

        return response()->json($barang);
    }

    public function show($id)
    {
        $barang = Barang::with('kategori')->find($id);

        if (!$barang) {
            return response()->json(["message" => "Barang not found"], 404);
        }

        try {
            // Decrypt sensitive data before returning
            $barang->kode_barang = Crypt::decryptString($barang->kode_barang);
            $barang->nomor_barang = Crypt::decryptString($barang->nomor_barang);
            $barang->nama_barang = Crypt::decryptString($barang->nama_barang);
        } catch (\Exception $e) {
            // Log the error using the Log facade
            \Illuminate\Support\Facades\Log::error('Decryption error: ' . $e->getMessage());

            return response()->json(["error" => "Decryption error"], 500);
        }

        return response()->json($barang);
    }

    public function store(Request $request)
    
    {
        \Illuminate\Support\Facades\Log::info($request->all());

        $validator = Validator::make($request->all(), [
            'kategori' => 'required|exists:kategori_barang,kategori',
            'kode_barang' => 'required|unique:barang,kode_barang|max:25',
            'nomor_barang' => 'required|unique:barang,nomor_barang|max:25',
            'nama_barang' => 'required|max:100',
            'ketersediaan_barang' => 'required|in:Tersedia,Dipinjam,Pemeliharaan,Dihapuskan',
            'status_barang' => 'required|in:baik,rusak',
            'gambar_barang' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(["message" => "Invalid field", "errors" => $validator->errors()], 422);
        }

        $kategori = $request->input('kategori');
        $id_kategori = Kategori::where('kategori', $kategori)->value('id_kategori');

        $barang = new Barang([
            'id_kategori' => $id_kategori,
            'kode_barang' => Crypt::encryptString($request->kode_barang),
            'nomor_barang' => Crypt::encryptString($request->nomor_barang),
            'nama_barang' => Crypt::encryptString($request->nama_barang),
            'ketersediaan_barang' => $request->ketersediaan_barang,
            'status_barang' => $request->status_barang,
        ]);

        // Handle gambar_barang only if it's provided
        if ($request->hasFile('gambar_barang')) {
            $imagePath = $request->file('gambar_barang')->store('barang_images', 'public');
            $barang->gambar_barang = $imagePath;
        } else {
            // If gambar_barang is not provided, set it as "none"
            $barang->gambar_barang = 'none';
        }

        $barang->save();

        return response()->json(["message" => "Barang added successfully"], 201);
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json(["message" => "Barang not found"], 404);
        }

        $validator = Validator::make($request->all(), [
            'kategori' => 'required|exists:kategori_barang,kategori',
            'kode_barang' => 'required|unique:barang,kode_barang,' . $id . ',id_barang|max:25',
            'nomor_barang' => 'required|unique:barang,nomor_barang,' . $id . ',id_barang|max:25',
            'nama_barang' => 'required|max:100',
            'ketersediaan_barang' => 'required|in:Tersedia,Dipinjam,Pemeliharaan,Dihapuskan',
            'status_barang' => 'required|in:baik,rusak',
            'gambar_barang' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(["message" => "Invalid field", "errors" => $validator->errors()], 422);
        }

        // Handle image update if a new image is provided
        if ($request->hasFile('gambar_barang')) {
            $imagePath = $request->file('gambar_barang')->store('barang_images', 'public');
            $barang->gambar_barang = $imagePath;
        } else {
            // If gambar_barang is not provided, set it as "none"
            $barang->gambar_barang = 'none';
        }

        $kategori = $request->input('kategori');
        $id_kategori = Kategori::where('kategori', $kategori)->value('id_kategori');

        $barang->id_kategori = $id_kategori;
        $barang->kode_barang = Crypt::encryptString($request->kode_barang);
        $barang->nomor_barang = Crypt::encryptString($request->nomor_barang);
        $barang->nama_barang = Crypt::encryptString($request->nama_barang);
        $barang->ketersediaan_barang = $request->ketersediaan_barang;
        $barang->status_barang = $request->status_barang;

        $barang->save();

        return response()->json(["message" => "Barang updated successfully"]);
    }

    public function destroy($id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json(["message" => "Barang not found"], 404);
        }

        $barang->delete();

        return response()->json(["message" => "Barang deleted successfully"]);
    }
}
