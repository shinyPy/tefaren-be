<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Barang;
use Illuminate\Support\Facades\Storage; // Import the Storage facade

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function barangShow()
    {
        $barangList = Barang::all();
    
        // Add image URLs to each Barang record
        foreach ($barangList as $barang) {
            $barang->gambar_barang_url = $barang->gambar_barang ? Storage::url($barang->gambar_barang) : null;
        }
    
        return response()->json($barangList, 200);
    }
    public function fetchByNomor(Request $request)
    {
        $nomor_barang = $request->input('nomor_barang');
        $barang = Barang::where('nomor_barang', $nomor_barang)->get();
        return response()->json($barang, 200);
    }
    
    public function delete($nomor_barang)
    {
        try {
            $barang = Barang::where('nomor_barang', $nomor_barang)->first(); // Find the record by 'nomor_barang'
    
            if (!$barang) {
                return response()->json(["message" => "Barang not found"], 404);
            }
    
            // Delete the record
            $barang->delete();
    
            return response()->json(["message" => "Barang deleted successfully"], 200);
        } catch (\Exception $e) {
            return response()->json(["message" => "An error occurred"], 500);
        }
    }
    
    public function createOrUpdate(Request $request, $id = null)
    {
        $validator = Validator::make($request->all(), [
            'kode_barang' => 'required|unique:barang,kode_barang,' . $id . ',nomor_barang|max:15',
            'nama_barang' => 'required',
            'ketersediaan_barang' => 'required|in:Tersedia,Dipinjam,Pemeliharaan,Dihapuskan',
            'status_barang' => 'required|in:baik,rusak',
        ]);
    
        // Check if gambar_barang is provided and meets image file validation rules
        if ($request->hasFile('gambar_barang')) {
            $validator->addRules([
                'gambar_barang' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
        }
    
        if ($validator->fails()) {
            return response()->json(["message" => "Invalid field", "errors" => $validator->errors()], 422);
        }
    
        $barangData = [
            'kode_barang' => $request->kode_barang,
            'nama_barang' => $request->nama_barang,
            'ketersediaan_barang' => $request->ketersediaan_barang,
            'status_barang' => $request->status_barang,
        ];
    
        // Check if gambar_barang is provided
        if ($request->hasFile('gambar_barang')) {
            // Handle the image upload and update the $barangData array
            $imagePath = $request->file('gambar_barang')->store('barang_images', 'public');
            $barangData['gambar_barang'] = $imagePath;
        } elseif ($id === null) {
            // If it's an insert operation and gambar_barang is not provided, set it to 'none'
            $barangData['gambar_barang'] = 'none';
        }
    
        if ($id === null) {
            // Insert operation
            $barang = new Barang($barangData);
            $barang->save();
        } else {
            // Update operation
            $existingBarang = Barang::find($id);
    
            if (!$existingBarang) {
                return response()->json(["message" => "Barang not found"], 404);
            }
    
            $existingBarang->update($barangData);
        }
    
        return response()->json(["message" => "Barang added/updated successfully"], 201);
    }
    

    
    /**
     * Remove the specified resource from storage.
     *
     * @param Barang $barang
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Barang $barang)
    {
        $barang->delete();
        return response()->json(["message" => "Barang deleted successfully"], 200);
    }
}
