<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class GoogleDriveService
{
    /**
     * Mengunggah file ke Google Drive.
     * 
     * @param UploadedFile $file File yang diunggah dari request
     * @param string $folderName Nama subfolder tujuan di Google Drive (opsional)
     * @return string|null Mengembalikan path file jika berhasil, null jika gagal
     */
    public function uploadFile(UploadedFile $file, string $folderName = 'lampiran_laporan')
    {
        try {
            $disk = Storage::disk('google');

            $fileName = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            
            $filePath = $folderName . '/' . $fileName;

            $fileContent = file_get_contents($file->getRealPath());

            $disk->put($filePath, $fileContent);

            return $filePath;

        } catch (\Exception $e) {
            \Log::error('Gagal upload ke Google Drive: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Mengunggah banyak file ke Google Drive secara sekaligus.
     * 
     * @param array|mixed $files Daftar file dari request
     * @param string $folderName Nama subfolder tujuan di Google Drive
     * @return array Mengembalikan array berisi path file yang berhasil diunggah
     */
    public function uploadMultipleFiles($files, string $folderName = 'lampiran_laporan')
    {
        $uploadedPaths = [];

        if (!is_array($files)) {
            $files = [$files];
        }

        foreach ($files as $file) {
            if ($file instanceof UploadedFile && $file->isValid()) {
                $path = $this->uploadFile($file, $folderName);
                
                if ($path) {
                    $uploadedPaths[] = $path;
                }
            }
        }

        return $uploadedPaths;
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