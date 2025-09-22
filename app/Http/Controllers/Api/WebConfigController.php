<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WebConfig;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class WebConfigController extends Controller
{
    /**
     * Get website configuration
     */
    public function index(): JsonResponse
    {
        try {
            $config = WebConfig::getConfig();
            
            return response()->json([
                'success' => true,
                'message' => 'ดึงข้อมูลการตั้งค่าเว็บไซต์สำเร็จ',
                'data' => [
                    'id' => $config->id,
                    'site_name' => $config->site_name,
                    'site_description' => $config->site_description,
                    'contact_address' => $config->contact_address,
                    'contact_phone' => $config->contact_phone,
                    'contact_email' => $config->contact_email,
                    'license_number' => $config->license_number,
                    'line_id' => $config->line_id,
                    'facebook_url' => $config->facebook_url,
                    'tiktok_url' => $config->tiktok_url,
                    'about_us' => $config->about_us,
                    'logo_url' => $config->getLogoUrl(),
                    'created_at' => $config->created_at,
                    'updated_at' => $config->updated_at
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการดึงข้อมูลการตั้งค่าเว็บไซต์',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific configuration by key
     */
    public function show(string $key): JsonResponse
    {
        try {
            $config = WebConfig::getConfig();
            
            $allowedKeys = [
                'site_name', 'site_description', 'contact_address', 'contact_phone',
                'contact_email', 'license_number', 'line_id', 'facebook_url',
                'tiktok_url', 'about_us', 'site_logo'
            ];

            if (!in_array($key, $allowedKeys)) {
                return response()->json([
                    'success' => false,
                    'message' => 'ไม่พบคีย์การตั้งค่าที่ระบุ',
                    'data' => null
                ], 400);
            }

            $value = $config->$key;
            
            // Handle logo URL
            if ($key === 'site_logo' && $value) {
                $value = $config->getLogoUrl();
            }

            return response()->json([
                'success' => true,
                'message' => 'ดึงข้อมูลการตั้งค่าเว็บไซต์สำเร็จ',
                'data' => [
                    'key' => $key,
                    'value' => $value
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการดึงข้อมูลการตั้งค่าเว็บไซต์',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get contact information
     */
    public function contact(): JsonResponse
    {
        try {
            $config = WebConfig::getConfig();
            
            return response()->json([
                'success' => true,
                'message' => 'ดึงข้อมูลการติดต่อสำเร็จ',
                'data' => [
                    'site_name' => $config->site_name,
                    'contact_email' => $config->contact_email,
                    'contact_phone' => $config->contact_phone,
                    'contact_address' => $config->contact_address,
                    'license_number' => $config->license_number
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการดึงข้อมูลการติดต่อ',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get social media links
     */
    public function social(): JsonResponse
    {
        try {
            $config = WebConfig::getConfig();
            
            $socialLinks = [];
            
            if ($config->facebook_url) {
                $socialLinks['facebook'] = $config->facebook_url;
            }
            if ($config->tiktok_url) {
                $socialLinks['tiktok'] = $config->tiktok_url;
            }
            if ($config->line_id) {
                $socialLinks['line'] = $config->line_id;
            }

            return response()->json([
                'success' => true,
                'message' => 'ดึงข้อมูลโซเชียลมีเดียสำเร็จ',
                'data' => $socialLinks
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการดึงข้อมูลโซเชียลมีเดีย',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get company information
     */
    public function company(): JsonResponse
    {
        try {
            $config = WebConfig::getConfig();
            
            return response()->json([
                'success' => true,
                'message' => 'ดึงข้อมูลบริษัทสำเร็จ',
                'data' => [
                    'site_name' => $config->site_name,
                    'site_description' => $config->site_description,
                    'about_us' => $config->about_us,
                    'logo_url' => $config->getLogoUrl(),
                    'license_number' => $config->license_number
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการดึงข้อมูลบริษัท',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update website configuration
     */
    public function update(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'site_name' => 'nullable|string|max:255',
                'site_description' => 'nullable|string|max:500',
                'contact_address' => 'nullable|string|max:500',
                'contact_phone' => 'nullable|string|max:20',
                'contact_email' => 'nullable|email|max:255',
                'license_number' => 'nullable|string|max:100',
                'line_id' => 'nullable|string|max:100',
                'facebook_url' => 'nullable|url|max:255',
                'tiktok_url' => 'nullable|url|max:255',
                'about_us' => 'nullable|string|max:1000',
                'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'ข้อมูลไม่ถูกต้อง',
                    'errors' => $validator->errors()
                ], 422);
            }

            $config = WebConfig::getConfig();
            $data = $request->only([
                'site_name', 'site_description', 'contact_address', 'contact_phone',
                'contact_email', 'license_number', 'line_id', 'facebook_url',
                'tiktok_url', 'about_us'
            ]);

            // Handle logo upload
            if ($request->hasFile('site_logo')) {
                // Delete old logo if exists
                if ($config->site_logo && Storage::disk('public')->exists('logos/' . $config->site_logo)) {
                    Storage::disk('public')->delete('logos/' . $config->site_logo);
                }

                // Upload new logo
                $logoFile = $request->file('site_logo');
                $logoName = 'logo_' . time() . '.' . $logoFile->getClientOriginalExtension();
                $logoPath = $logoFile->storeAs('logos', $logoName, 'public');
                $data['site_logo'] = $logoName;
            }

            $config->update($data);

            return response()->json([
                'success' => true,
                'message' => 'อัปเดตการตั้งค่าเว็บไซต์สำเร็จ',
                'data' => [
                    'id' => $config->id,
                    'site_name' => $config->site_name,
                    'site_description' => $config->site_description,
                    'contact_address' => $config->contact_address,
                    'contact_phone' => $config->contact_phone,
                    'contact_email' => $config->contact_email,
                    'license_number' => $config->license_number,
                    'line_id' => $config->line_id,
                    'facebook_url' => $config->facebook_url,
                    'tiktok_url' => $config->tiktok_url,
                    'about_us' => $config->about_us,
                    'logo_url' => $config->getLogoUrl(),
                    'updated_at' => $config->updated_at
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการอัปเดตการตั้งค่าเว็บไซต์',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset to default configuration
     */
    public function reset(): JsonResponse
    {
        try {
            $config = WebConfig::getConfig();
            
            $defaultData = [
                'site_name' => 'CMS Touring',
                'site_description' => 'ระบบจัดการทัวร์ออนไลน์',
                'contact_address' => 'กรุงเทพมหานคร',
                'contact_phone' => '02-123-4567',
                'contact_email' => 'info@cmstouring.com',
                'about_us' => 'เราเป็นบริษัททัวร์ที่ให้บริการทัวร์คุณภาพสูง พร้อมทีมงานมืออาชีพ',
                'license_number' => null,
                'line_id' => null,
                'facebook_url' => null,
                'tiktok_url' => null,
                'site_logo' => null
            ];

            $config->update($defaultData);

            return response()->json([
                'success' => true,
                'message' => 'รีเซ็ตการตั้งค่าเว็บไซต์เป็นค่าเริ่มต้นสำเร็จ',
                'data' => [
                    'id' => $config->id,
                    'site_name' => $config->site_name,
                    'site_description' => $config->site_description,
                    'contact_address' => $config->contact_address,
                    'contact_phone' => $config->contact_phone,
                    'contact_email' => $config->contact_email,
                    'license_number' => $config->license_number,
                    'line_id' => $config->line_id,
                    'facebook_url' => $config->facebook_url,
                    'tiktok_url' => $config->tiktok_url,
                    'about_us' => $config->about_us,
                    'logo_url' => $config->getLogoUrl(),
                    'updated_at' => $config->updated_at
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการรีเซ็ตการตั้งค่าเว็บไซต์',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}