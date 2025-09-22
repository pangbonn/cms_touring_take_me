<?php

namespace App\Http\Controllers;

use App\Models\WebConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class WebConfigController extends Controller
{
    /**
     * Display the web configuration page
     */
    public function index()
    {
        $config = WebConfig::getConfig();
        return view('webconfig.index', compact('config'));
    }

    /**
     * Update the web configuration
     */
    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'contact_address' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'license_number' => 'nullable|string|max:50',
            'line_id' => 'nullable|string|max:50',
            'facebook_url' => 'nullable|url|max:255',
            'tiktok_url' => 'nullable|url|max:255',
            'about_us' => 'nullable|string|max:2000',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // 2MB
        ]);

        $config = WebConfig::getConfig();

        $data = [
            'site_name' => $request->site_name,
            'site_description' => $request->site_description,
            'contact_address' => $request->contact_address,
            'contact_phone' => $request->contact_phone,
            'contact_email' => $request->contact_email,
            'license_number' => $request->license_number,
            'line_id' => $request->line_id,
            'facebook_url' => $request->facebook_url,
            'tiktok_url' => $request->tiktok_url,
            'about_us' => $request->about_us,
        ];

        // Handle logo upload
        if ($request->hasFile('site_logo')) {
            // Delete old logo if exists
            if ($config->site_logo && file_exists(storage_path('app/public/logos/' . $config->site_logo))) {
                unlink(storage_path('app/public/logos/' . $config->site_logo));
            }
            
            $logo = $request->file('site_logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            
            // Check original file size
            $originalSizeKB = $logo->getSize() / 1024;
            
            // Compress image without losing quality
            $this->compressImage($logo, storage_path('app/public/logos/' . $logoName), 500);
            
            // Check compressed file size
            $compressedSizeKB = filesize(storage_path('app/public/logos/' . $logoName)) / 1024;
            
            $data['site_logo'] = $logoName;
            
            // Log compression info
            \Log::info("Logo compressed: {$originalSizeKB}KB -> {$compressedSizeKB}KB");
        }

        $config->update($data);

        $message = 'อัปเดตการตั้งค่าเว็บไซต์เรียบร้อยแล้ว';
        if (isset($originalSizeKB) && isset($compressedSizeKB)) {
            $message .= " (โลโก้ถูกบีบอัดจาก " . round($originalSizeKB, 1) . "KB เป็น " . round($compressedSizeKB, 1) . "KB)";
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Compress image to specified size
     */
    private function compressImage($image, $destination, $maxSizeKB)
    {
        $imagePath = $image->getPathname();
        $imageInfo = getimagesize($imagePath);
        
        if (!$imageInfo) {
            throw new \Exception('ไม่สามารถอ่านไฟล์รูปภาพได้');
        }

        $width = $imageInfo[0];
        $height = $imageInfo[1];
        $mimeType = $imageInfo['mime'];

        // Create image resource based on mime type
        switch ($mimeType) {
            case 'image/jpeg':
                $sourceImage = imagecreatefromjpeg($imagePath);
                break;
            case 'image/png':
                $sourceImage = imagecreatefrompng($imagePath);
                break;
            default:
                throw new \Exception('รองรับเฉพาะไฟล์ JPEG และ PNG เท่านั้น');
        }

        // Resize if image is too large (e.g., > 800px)
        if ($width > 800 || $height > 800) {
            $ratio = min(800 / $width, 800 / $height);
            $newWidth = intval($width * $ratio);
            $newHeight = intval($height * $ratio);
        } else {
            $newWidth = $width;
            $newHeight = $height;
        }

        // Create new image
        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Preserve transparency for PNG
        if ($mimeType === 'image/png') {
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
            $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
            imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
        }

        // Resample image
        imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        // Compress image
        $quality = 85; // Start with high quality
        $tempFile = tempnam(sys_get_temp_dir(), 'logo_');
        
        do {
            if ($mimeType === 'image/jpeg') {
                imagejpeg($newImage, $tempFile, $quality);
            } else {
                imagepng($newImage, $tempFile, 9 - intval($quality / 10));
            }
            
            $fileSizeKB = filesize($tempFile) / 1024;
            
            if ($fileSizeKB <= $maxSizeKB) {
                break;
            }
            
            $quality -= 5;
        } while ($quality > 20 && $fileSizeKB > $maxSizeKB);

        // Copy to destination
        copy($tempFile, $destination);
        
        // Cleanup
        unlink($tempFile);
        imagedestroy($sourceImage);
        imagedestroy($newImage);
    }
}
