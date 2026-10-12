<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class GoogleDriveService
{
    /**
     * Mengunggah file ke Google Drive.
     * 
     * @param UploadFile $file File yang diunggah dari request
     * @param string $folderName Nama subfolder tujuan di Google Drive (opsional)
     * @return string|null Mengembalikan path/ID file jika berhasil, null jika gagal
     */
    public function uploadFile(UploadedFile $file, string $folderName = 'lampiran_laporan')
    {
        try {
            // Menggunakan disk 'google' yang sudah dikonfigurasi di config/filesystems.php
            $disk = Storage::disk('google');

            // Generate nama file yang unik agar tidak bentrok
            $fileName = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());

            // Proses upload file ke Google Drive
            $filePath = $file->storeAs($folderName, $fileName, 'google');

            return $filePath;
        } catch (\Exception $e) {
            // Tangkap error jika upload gagal (bisa dicatat ke log jika perlu)
            \Log::error('Gagal upload ke Google Drive: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Menghapus file dari Google Drive berdasarkan path/ID.
     * 
     * @param string $filePath
     * @return bool
     */
    public function deleteFile(string $filePath)
    {
        try {
            return Storage::disk('google')->delete($filePath);
        } catch (\Exception $e) {
            \Log::error('Gagal hapus file dari Google Drive: ' . $e->getMessage());
            return false;
        }
    }
}