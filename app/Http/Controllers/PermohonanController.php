<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permohonan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use App\Models\Peminjaman; // Import the Peminjaman model
use App\Models\Barang;

use PDF;
use App\Models\Pengguna;

class PermohonanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


     public function cetakSurat($id)
     {
         // Retrieve the Permohonan with related data
         $permohonan = Permohonan::with('pengguna', 'pengguna.jabatan', 'pengguna.jurusan')->find($id);
     
         // Check if status_permohonan is "terima"
         if ($permohonan && $permohonan->status_permohonan === 'terima') {
             // Generate and return the PDF
             $pdf = PDF::loadview('cetak_surat_permohonan', ['permohonan' => $permohonan]);
             return $pdf->stream('cetak_surat_permohonan.pdf');
         } else {
             // Return a response indicating that the document cannot be generated
             return response()->json(['message' => 'Cannot generate document for non-terima status.'], 403);
         }
     }
     

     public function index()
     {
         // Eager load the related pengguna information
         $permohonans = Permohonan::with('pengguna')->get();
     
         return response()->json($permohonans);
     }
     
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kesetujuan_syarat' => 'required|in:setuju,tidak',
            'id_pengguna' => 'required|exists:pengguna,id',
            'kelas_pengguna' => 'required|string',
            'nomor_wa' => 'required|string',
            'alasan_peminjaman' => 'required|string',
            'jumlah_barang' => 'required|integer',
            'tanggal_peminjaman' => 'required|date',
            'lama_peminjaman' => 'required|string',
            'nomor_peminjaman' => 'required|string',
            'details_barang' => 'required|array',
            'details_barang.*' => ['required', 'exists:barang,id_barang'],
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        // Automatically insert the current date for tanggal_peminjaman
        $request->merge(['tanggal_peminjaman' => now()]);
    
        // Extract details_barang from the request
        $detailsBarangArray = $request->input('details_barang');
    
        // Convert details_barang IDs to an array of objects
        $detailsBarangObjects = collect($detailsBarangArray)->map(function ($id) {
            return ['id' => $id];
        })->toArray();
    
        // Create a new Permohonan instance
        $permohonan = new Permohonan();
        $permohonan->kesetujuan_syarat = $request->input('kesetujuan_syarat');
        $permohonan->id_pengguna = $request->input('id_pengguna');
        $permohonan->kelas_pengguna = $request->input('kelas_pengguna');
        $permohonan->nomor_wa = $request->input('nomor_wa');
        $permohonan->alasan_peminjaman = $request->input('alasan_peminjaman');
        $permohonan->jumlah_barang = $request->input('jumlah_barang');
        $permohonan->tanggal_peminjaman = $request->input('tanggal_peminjaman');
        $permohonan->lama_peminjaman = $request->input('lama_peminjaman');
        $permohonan->nomor_peminjaman = $request->input('nomor_peminjaman');
    
        // Convert details_barang to a JSON string and set it on the model
        $permohonan->details_barang = json_encode($detailsBarangObjects);
    
        // Save the model to the database
        $permohonan->save();
    
        return response()->json(['message' => 'Permohonan created successfully', 'data' => $permohonan]);
    }
    
    public function update(Request $request, $id)
    {
        Log::info('Updating Permohonan. ID: ' . $id);
    
        $validator = Validator::make($request->all(), [
            'status_permohonan' => 'required|in:diajukan,tolak,terima',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        // Retrieve the Permohonan instance using the provided ID
        $permohonan = Permohonan::findOrFail($id);
    
        // Get the original status_permohonan value
        $originalStatus = $permohonan->status_permohonan;
    
        // Manually set the status_permohonan attribute
        $permohonan->status_permohonan = $request->input('status_permohonan');
    
        // Save the Permohonan instance
        $saved = $permohonan->save();
    
        // Check if status_permohonan is changed to "terima"
        if ($originalStatus !== 'terima' && $permohonan->status_permohonan === 'terima') {
            // Log to help identify the issue
            Log::info('Status changed to terima. ID: ' . $id);
    
            // Process barang related to the permohonan
            $response = $this->processBarang($permohonan);
    
            // If there is an error in the response, return it
            if ($response->getStatusCode() !== 200) {
                return $response;
            }
        }
    
        return response()->json(['message' => 'Permohonan updated successfully', 'data' => $permohonan]);
    }
    
    private function processBarang(Permohonan $permohonan)
    {
        // Retrieve details_barang as an array of IDs
        $detailsBarang = json_decode($permohonan->details_barang);
    
        if (is_array($detailsBarang)) {
            foreach ($detailsBarang as $barangDetail) {
                // Get the actual Barang model
                $barangId = $barangDetail->id;
                $barang = Barang::find($barangId);
    
                if ($barang) {
                    // Check if a similar peminjaman entry already exists
                    $existingPeminjaman = Peminjaman::where('id_barang', $barangId)
                        ->where('status_peminjaman', 'dipinjam')
                        ->first();
    
                    if ($existingPeminjaman) {
                        // Similar peminjaman entry already exists
                        return response()->json(['message' => 'Barang sedang dipinjam.'], 422);
                    }
    
                    // Update barang status to "Dipinjam"
                    $barang->ketersediaan_barang = 'Dipinjam';
                    $barang->save();
    
                    // Create peminjaman record using Eloquent for each Barang
                    Peminjaman::create([
                        'id_permohonan' => $permohonan->id,
                        'id_barang' => $barangId,
                        'status_peminjaman' => 'dipinjam',
                        // Add other necessary fields
                    ]);
                }
            }
    
            return response()->json(['message' => 'Peminjaman records created successfully']);
        } else {
            return response()->json(['message' => 'Invalid details_barang format'], 422);
        }
    }
    
    

    public function destroy(Permohonan $permohonan)
    {
        $permohonan->delete();
        return response()->json(['message' => 'Permohonan deleted successfully']);
    }
}