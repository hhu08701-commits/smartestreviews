<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

trait DownloadsImages
{
    /**
     * Download image from URL and save to local storage
     */
    protected function downloadImageFromUrl($url, $folder, $prefix = '')
    {
        try {
            // Skip if not a URL
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                return null;
            }

            // Create upload directory if it doesn't exist
            $uploadPath = public_path("uploads/{$folder}");
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Get image content
            $response = Http::timeout(30)->get($url);
            
            if (!$response->successful()) {
                return null;
            }

            // Get file extension from URL or Content-Type
            $contentType = $response->header('Content-Type');
            $extension = 'jpg'; // default
            
            if (preg_match('/image\/(\w+)/', $contentType, $matches)) {
                $extension = $matches[1];
            } elseif (preg_match('/\.(\w+)(?:\?|$)/', $url, $matches)) {
                $extension = $matches[1];
            }

            // Clean extension
            $extension = strtolower($extension);
            if ($extension === 'jpeg') {
                $extension = 'jpg';
            }

            // Generate filename
            $filename = $prefix . '_' . time() . '_' . Str::random(10) . '.' . $extension;
            $filePath = $uploadPath . '/' . $filename;

            // Save file
            file_put_contents($filePath, $response->body());

            // Verify file was saved
            if (file_exists($filePath) && filesize($filePath) > 0) {
                return [
                    'filename' => $filename,
                    'original_name' => basename(parse_url($url, PHP_URL_PATH)),
                    'path' => $filePath,
                    'local_url' => '/uploads/' . $folder . '/' . $filename,
                ];
            }

            return null;
        } catch (\Exception $e) {
            \Log::error("Error downloading image from {$url}: " . $e->getMessage());
            return null;
        }
    }
}

