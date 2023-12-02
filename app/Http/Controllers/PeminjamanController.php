<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permohonan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use App\Models\Peminjaman; // Import the Peminjaman model
use App\Models\Pengembalian; // Import the Peminjaman model

use App\Models\Barang;
class PeminjamanController extends Controller
{
    public function update(Request $request, $id)
    {
        Log::info('Updating Peminjaman. ID: ' . $id);
    
        $validator = Validator::make($request->all(), [
            'status_peminjaman' => 'required|in:dipinjam,dikembalikan',
            'bukti_pengembalian' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // adjust validation as needed
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        // Retrieve the Peminjaman instance using the provided ID
        $peminjaman = Peminjaman::findOrFail($id);
    
        // Get the original status_peminjaman value
        $originalStatus = $peminjaman->status_peminjaman;
    
        // Manually set the status_peminjaman attribute
        $peminjaman->status_peminjaman = $request->input('status_peminjaman');
    
        // Save the Peminjaman instance
        $saved = $peminjaman->save();
    
        // Check if status_peminjaman has changed to "dikembalikan"
        if ($originalStatus !== 'dikembalikan' && $peminjaman->status_peminjaman === 'dikembalikan') {
            // Log to help identify the issue
            Log::info('Status changed to dikembalikan. ID: ' . $id);
    
            // Process pengembalian related to the peminjaman
            $response = $this->processPengembalian($peminjaman, $request->file('bukti_pengembalian'));
    
            // If there is an error in the response, return it
            if ($response->getStatusCode() !== 200) {
                return $response;
            }
        }
    
        return response()->json(['message' => 'Peminjaman updated successfully', 'data' => $peminjaman]);
    }
    
    
    private function processPengembalian(Peminjaman $peminjaman, $buktiPengembalian)
    {
        // Retrieve the corresponding Barang
        $barang = Barang::find($peminjaman->id_barang);
    
        // Log the ID before attempting to create Pengembalian
        Log::info('Peminjaman ID before processing Pengembalian: ' . $peminjaman->id);
    
        // Check if the status_peminjaman is "dikembalikan"
        if ($peminjaman && $peminjaman->status_peminjaman === 'dikembalikan') {
            // Log the ID before attempting to create Pengembalian
            Log::info('Processing Pengembalian for Peminjaman ID: ' . $peminjaman->id);
    
            if ($barang) {
                // Update ketersediaan_barang in the Barang table to 'Tersedia'
                $barang->update(['ketersediaan_barang' => 'Tersedia']);
    
                try {
                    // Log some additional information for debugging
                    Log::info('ID Pengembalian before creating: ' . $peminjaman->id);
    
                    // Save the image to storage (you may need to adjust this based on your storage setup)
                    $imagePath = $buktiPengembalian->store('bukti_pengembalian', 'public');
    
                    // Create a new Pengembalian record
                    $pengembalian = new Pengembalian([
                        'status_barang' => $barang->status_barang,
                        'bukti_pengembalian' => $imagePath, // Store the image path in the database
                        'status_pengembalian' => 'dicek',
                    ]);
    
                    // Save the Pengembalian instance with the association to Peminjaman
                    $peminjaman->pengembalian()->save($pengembalian);
    
                    Log::info('Pengembalian processed successfully for Peminjaman ID: ' . $peminjaman->id);
                    return response()->json(['message' => 'Pengembalian processed successfully']);
                } catch (\Exception $e) {
                    // Log the exception for further investigation
                    Log::error('Error creating Pengembalian: ' . $e->getMessage());
                    return response()->json(['message' => 'Error processing Pengembalian'], 500);
                }
            } else {
                Log::error('Barang not found for Peminjaman ID: ' . $peminjaman->id);
                return response()->json(['message' => 'Barang not found'], 404);
            }
        } else {
            Log::info('Peminjaman is not in "dikembalikan" status. Skipping Pengembalian process for Peminjaman ID: ' . $peminjaman->id);
        }
    }
    
    
    
    
    

}