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
            // Ensure logos directory exists
            $logosDir = storage_path('app/public/logos');
            if (!is_dir($logosDir)) {
                mkdir($logosDir, 0755, true);
            }
            
            // Delete old logo if exists
            if ($config->site_logo && file_exists(storage_path('app/public/logos/' . $config->site_logo))) {
                unlink(storage_path('app/public/logos/' . $config->site_logo));
            }
            
            $logo = $request->file('site_logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            
            // Check original file size
            $originalSizeKB = $logo->getSize() / 1024;
            
            // Try to compress image if GD extension is available
            if (extension_loaded('gd')) {
                try {
                    $this->compressImage($logo, storage_path('app/public/logos/' . $logoName), 500);
                    
                    // Check compressed file size
                    $compressedSizeKB = filesize(storage_path('app/public/logos/' . $logoName)) / 1024;
                    
                    // Log compression info
                    \Log::info("Logo compressed: {$originalSizeKB}KB -> {$compressedSizeKB}KB");
                } catch (\Exception $e) {
                    \Log::warning("Image compression failed: " . $e->getMessage());
                    // Fallback to copy without compression
                    $this->copyImageWithoutCompression($logo, storage_path('app/public/logos/' . $logoName));
                    $compressedSizeKB = $originalSizeKB;
                }
            } else {
                // GD extension not available, use copy without compression
                \Log::warning("GD extension not available, copying image without compression");
                $this->copyImageWithoutCompression($logo, storage_path('app/public/logos/' . $logoName));
                $compressedSizeKB = $originalSizeKB;
            }
            
            $data['site_logo'] = $logoName;
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
     * Copy image without compression (fallback method)
     */
    private function copyImageWithoutCompression($image, $destination)
    {
        $imagePath = $image->getPathname();
        
        // Create directory if it doesn't exist
        $destinationDir = dirname($destination);
        if (!is_dir($destinationDir)) {
            if (!mkdir($destinationDir, 0755, true)) {
                throw new \Exception('ไม่สามารถสร้างโฟลเดอร์ได้: ' . $destinationDir);
            }
        }
        
        // Simply copy the file to destination
        if (!copy($imagePath, $destination)) {
            throw new \Exception('ไม่สามารถคัดลอกไฟล์รูปภาพได้');
        }
        
        \Log::info("Image copied without compression: " . basename($destination));
    }

    /**
     * Compress image to specified size
     */
    private function compressImage($image, $destination, $maxSizeKB)
    {
        // Check if GD extension is loaded
        if (!extension_loaded('gd')) {
            throw new \Exception('PHP GD extension ไม่ได้ถูกติดตั้ง กรุณาติดตั้ง php-gd extension');
        }

        // Create directory if it doesn't exist
        $destinationDir = dirname($destination);
        if (!is_dir($destinationDir)) {
            if (!mkdir($destinationDir, 0755, true)) {
                throw new \Exception('ไม่สามารถสร้างโฟลเดอร์ได้: ' . $destinationDir);
            }
        }

        $imagePath = $image->getPathname();
        $imageInfo = getimagesize($imagePath);
        
        if (!$imageInfo) {
            throw new \Exception('ไม่สามารถอ่านไฟล์รูปภาพได้');
        }

        $width = $imageInfo[0];
        $height = $imageInfo[1];
        $mimeType = $imageInfo['mime'];

        // Create image resource based on mime type
        $sourceImage = null;
        switch ($mimeType) {
            case 'image/jpeg':
                if (!function_exists('imagecreatefromjpeg')) {
                    throw new \Exception('ฟังก์ชัน imagecreatefromjpeg ไม่พร้อมใช้งาน');
                }
                $sourceImage = imagecreatefromjpeg($imagePath);
                break;
            case 'image/png':
                if (!function_exists('imagecreatefrompng')) {
                    throw new \Exception('ฟังก์ชัน imagecreatefrompng ไม่พร้อมใช้งาน');
                }
                $sourceImage = imagecreatefrompng($imagePath);
                break;
            default:
                throw new \Exception('รองรับเฉพาะไฟล์ JPEG และ PNG เท่านั้น');
        }

        if (!$sourceImage) {
            throw new \Exception('ไม่สามารถสร้าง image resource ได้');
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
        if (!function_exists('imagecreatetruecolor')) {
            throw new \Exception('ฟังก์ชัน imagecreatetruecolor ไม่พร้อมใช้งาน');
        }
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
                if (!function_exists('imagejpeg')) {
                    throw new \Exception('ฟังก์ชัน imagejpeg ไม่พร้อมใช้งาน');
                }
                imagejpeg($newImage, $tempFile, $quality);
            } else {
                if (!function_exists('imagepng')) {
                    throw new \Exception('ฟังก์ชัน imagepng ไม่พร้อมใช้งาน');
                }
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
