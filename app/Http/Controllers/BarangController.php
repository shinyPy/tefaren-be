<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class BarangController extends Controller
{
    public function index()
    {
        $barangList = Barang::with(['kategori:id_kategori,kategori'])
            ->select(['id_barang', 'kode_barang', 'nomor_barang', 'id_kategori', 'nama_barang', 'ketersediaan_barang', 'status_barang', 'gambar_barang'])
            ->get()
            ->transform(function ($barang) {
                $data = [
                    'id_barang' => $barang->id_barang,
                    'kode_barang' => $barang->kode_barang,
                    'nomor_barang' => $barang->nomor_barang,
                    'nama_barang' => $barang->nama_barang,
                    'ketersediaan_barang' => $barang->ketersediaan_barang,
                    'status_barang' => $barang->status_barang,
                    'gambar_barang' => $barang->gambar_barang,
                ];
    
                if ($barang->kategori !== null) {
                    $kategori = [
                        'id_kategori' => data_get($barang->kategori, 'id_kategori'),
                        'kategori' => data_get($barang->kategori, 'kategori'),
                    ];
                    $data['kategori'] = array_filter($kategori, fn ($value) => $value !== null);
                } else {
                    $data['kategori'] = null;
                }
    
                return $data;
            });
    
        return response()->json($barangList);
    }
    
    
    
    


    public function show($id)
    {
        $barang = Barang::with('kategori')->find($id);

        if (!$barang) {
            return response()->json(["message" => "Barang not found"], 404);
        }

        return response()->json($barang);
    }

    public function store(Request $request)
    {
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
            'kode_barang' => $request->kode_barang,
            'nomor_barang' => $request->nomor_barang,
            'nama_barang' => $request->nama_barang,
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
    
        // Check the content type of the request
        $contentType = $request->header('Content-Type');
        
        if (strpos($contentType, 'multipart/form-data') !== false) {
            // Handle multipart/form-data request
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
                return response()->json(["message" => "Field invalid", "errors" => $validator->errors()], 422);
            }
    
            // Handle image update if a new image is provided
            if ($request->hasFile('gambar_barang')) {
                $imagePath = $request->file('gambar_barang')->store('barang_images', 'public');
                $barang->gambar_barang = $imagePath;
            } else {
                // kalau gambar_barang tidak di dinput set sebagai none
                $barang->gambar_barang = 'none';
            }
        } else {
            // Handle JSON request
            $validator = Validator::make($request->json()->all(), [
                'kategori' => 'required|exists:kategori_barang,kategori',
                'kode_barang' => 'required|unique:barang,kode_barang,' . $id . ',id_barang|max:25',
                'nomor_barang' => 'required|unique:barang,nomor_barang,' . $id . ',id_barang|max:25',
                'nama_barang' => 'required|max:100',
                'ketersediaan_barang' => 'required|in:Tersedia,Dipinjam,Pemeliharaan,Dihapuskan',
                'status_barang' => 'required|in:baik,rusak',
            ]);
    
            if ($validator->fails()) {
                return response()->json(["message" => "Field invalid", "errors" => $validator->errors()], 422);
            }
        }
    
        $kategori = $request->input('kategori');
        $id_kategori = Kategori::where('kategori', $kategori)->value('id_kategori');
    
        $barang->id_kategori = $id_kategori;
        $barang->kode_barang = $request->kode_barang;
        $barang->nomor_barang = $request->nomor_barang;
        $barang->nama_barang = $request->nama_barang;
        $barang->ketersediaan_barang = $request->ketersediaan_barang;
        $barang->status_barang = $request->status_barang;
    
        $barang->save();
    
        return response()->json(["message" => "Barang sukses diupdate"]);
    }
    
    public function destroy($id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json(["message" => "Barang tidak ditemukan"], 404);
        }

        $barang->delete();

        return response()->json(["message" => "Barang telah dihapus"]);
    }
}
