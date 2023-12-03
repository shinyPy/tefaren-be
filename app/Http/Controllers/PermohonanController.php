<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permohonan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use App\Models\Peminjaman; // Import the Peminjaman model
use App\Models\Barang;
use Illuminate\Database\Eloquent\Builder;

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
             return response()->json(['message' => 'Permohonan tidak diterima'], 403);
         }
     }
     

     public function index()
     {
         $permohonans = Permohonan::with('pengguna')->get();
         
         // Fetch barang details for each permohonan
         $permohonans->each(function ($permohonan) {
             $barangDetails = $permohonan->barangDetails;
             $permohonan->barang_details = $barangDetails;
         });
     
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

        $existingDiajukanPermohonan = Permohonan::where('id_pengguna', $request->input('id_pengguna'))
        ->where('status_permohonan', 'diajukan')
        ->first();

    if ($existingDiajukanPermohonan) {
        return response()->json(['message' => 'Kamu telah mensubmit lebih dari satu kali, tunggu admin mencek permohonanmu.'], 422);
    }

        $validator = Validator::make($request->all(), [
            'id_pengguna' => 'required|exists:pengguna,id',
            'nomor_wa' => 'required|string',
            'alasan_peminjaman' => 'required|string',
            'tanggal_peminjaman' => 'required|date',
            'lama_peminjaman' => 'required|string',
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
        $permohonan->id_pengguna = $request->input('id_pengguna');
        $permohonan->nomor_wa = $request->input('nomor_wa');
        $permohonan->alasan_peminjaman = $request->input('alasan_peminjaman');
        $permohonan->tanggal_peminjaman = $request->input('tanggal_peminjaman');
        $permohonan->lama_peminjaman = $request->input('lama_peminjaman');
    
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
    
        // $existingPermohonan = Permohonan::where('id_pengguna', $request->user()->id)
        //     ->where('status_permohonan', 'diajukan')
        //     ->where('id', '!=', $id) // Exclude the current permohonan being updated
        //     ->first();
    
        // if ($existingPermohonan && $request->input('status_permohonan') === 'diajukan') {
        //     return response()->json(['message' => 'You have already submitted a permohonan with status "diajukan".'], 422);
        // }
    
        // Continue with the rest of the update logic
    
        // Retrieve the Permohonan instance using the provided ID
        $permohonan = Permohonan::findOrFail($id);
    
        // Get the original status_permohonan value
        $originalStatus = $permohonan->status_permohonan;
    
        // Manually set the status_permohonan attribute
        $permohonan->status_permohonan = $request->input('status_permohonan');
    
        // Automatically generate and set a unique nomor_peminjaman
        $permohonan->nomor_peminjaman = 'PM' . now()->timestamp; // Adjust the prefix as needed
    
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
        } elseif ($originalStatus !== 'tolak' && $permohonan->status_permohonan === 'tolak') {
            // Log to help identify the issue
            Log::info('Status changed to tolak. Deleting related Peminjaman for Permohonan ID: ' . $id);
    
            // Delete related Peminjaman data
            $this->deletePeminjaman($permohonan);
            $this->updateBarangKetersediaan($permohonan);
    
            Log::info('Related Peminjaman deleted successfully for Permohonan ID: ' . $id);
        }
    
        return response()->json(['message' => 'Permohonan sukses diupdate', 'data' => $permohonan]);
    }
    

    private function updateBarangKetersediaan(Permohonan $permohonan)
{
    // Retrieve details_barang as an array of IDs
    $detailsBarang = json_decode($permohonan->details_barang);

    if (is_array($detailsBarang)) {
        foreach ($detailsBarang as $barangDetail) {
            // Get the actual Barang model
            $barangId = $barangDetail->id;
            $barang = Barang::find($barangId);

            if ($barang) {
                // Update barang status to "Tersedia"
                $barang->ketersediaan_barang = 'Tersedia';
                $barang->save();
            }
        }
    }
}
    
    private function deletePeminjaman(Permohonan $permohonan)
    {
        // Delete related Peminjaman data
        Peminjaman::where('id_permohonan', $permohonan->id)->delete();
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
            return response()->json(['message' => 'format barang invalid '], 422);
        }
    }
    
    

    public function destroy($id)
    {
        $permohonan = Permohonan::find($id);

        if (!$permohonan) {
            return response()->json(["message" => "Permohonan tidak ditemukan"], 404);
        }

        $permohonan->delete();

        return response()->json(["message" => "Permohonan telah dihapus"]);
    }
    }
