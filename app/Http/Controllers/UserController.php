<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:superadmin']);
    }

    /**
     * Display a listing of users
     */
    public function index()
    {
        $users = User::with('role')->paginate(15);
        $roles = Role::all();
        
        return view('users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // 2MB = 2048KB
            'must_change_password' => 'boolean',
        ]);

        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'is_active' => true,
            'must_change_password' => $request->has('must_change_password'),
        ];

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = time() . '_' . $avatar->getClientOriginalName();
            
            // Check original file size
            $originalSizeKB = $avatar->getSize() / 1024;
            
            // Compress image without losing quality
            $this->compressImage($avatar, storage_path('app/public/avatars/' . $avatarName), 500);
            
            // Check compressed file size
            $compressedSizeKB = filesize(storage_path('app/public/avatars/' . $avatarName)) / 1024;
            
            $data['avatar'] = $avatarName;
            
            // Log compression info
            \Log::info("Image compressed: {$originalSizeKB}KB -> {$compressedSizeKB}KB");
        }

        User::create($data);

        $message = 'เพิ่มผู้ใช้เรียบร้อยแล้ว';
        if (isset($originalSizeKB) && isset($compressedSizeKB)) {
            $message .= " (รูปภาพถูกบีบอัดจาก {$originalSizeKB}KB เป็น {$compressedSizeKB}KB)";
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        $user->load('role');
        
        return response()->json([
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'full_name' => $user->getFullName(),
            'email' => $user->email,
            'role_id' => $user->role_id,
            'role_display_name' => $user->getRoleDisplayName(),
            'is_active' => $user->is_active,
            'must_change_password' => $user->must_change_password,
            'avatar_url' => $user->getAvatarUrl(),
            'created_at' => $user->created_at->format('d/m/Y H:i'),
            'updated_at' => $user->updated_at->format('d/m/Y H:i'),
        ]);
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        try {
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'password' => 'nullable|string|min:8|confirmed',
                'role_id' => 'required|exists:roles,id',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // 2MB = 2048KB
                'is_active' => 'boolean',
                'must_change_password' => 'boolean',
            ]);

            $data = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'role_id' => $request->role_id,
                'is_active' => $request->has('is_active'),
                'must_change_password' => $request->has('must_change_password'),
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                // Delete old avatar if exists
                if ($user->avatar && file_exists(storage_path('app/public/avatars/' . $user->avatar))) {
                    unlink(storage_path('app/public/avatars/' . $user->avatar));
                }
                
                $avatar = $request->file('avatar');
                $avatarName = time() . '_' . $avatar->getClientOriginalName();
                
                // Check original file size
                $originalSizeKB = $avatar->getSize() / 1024;
                
                // Compress image without losing quality
                $this->compressImage($avatar, storage_path('app/public/avatars/' . $avatarName), 500);
                
                // Check compressed file size
                $compressedSizeKB = filesize(storage_path('app/public/avatars/' . $avatarName)) / 1024;
                
                $data['avatar'] = $avatarName;
                
                // Log compression info
                \Log::info("Image compressed: {$originalSizeKB}KB -> {$compressedSizeKB}KB");
            }

            $user->update($data);

            $message = 'อัปเดตผู้ใช้เรียบร้อยแล้ว';
            if (isset($originalSizeKB) && isset($compressedSizeKB)) {
                $message .= " (รูปภาพถูกบีบอัดจาก " . round($originalSizeKB, 1) . "KB เป็น " . round($compressedSizeKB, 1) . "KB)";
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'ข้อมูลไม่ถูกต้อง',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('User update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการอัปเดตผู้ใช้'
            ], 500);
        }
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        // Prevent deleting superadmin users
        if ($user->isSuperAdmin()) {
            return redirect()->route('users.index')->with('error', 'ไม่สามารถลบผู้ดูแลระบบสูงสุดได้');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'ลบผู้ใช้เรียบร้อยแล้ว');
    }

    /**
     * Toggle user active status
     */
    public function toggleStatus(User $user)
    {
        // Prevent deactivating superadmin users
        if ($user->isSuperAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถปิดใช้งานผู้ดูแลระบบสูงสุดได้'
            ]);
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน';
        return response()->json([
            'success' => true,
            'message' => "{$status}ผู้ใช้เรียบร้อยแล้ว"
        ]);
    }

    /**
     * Compress image without losing quality
     */
    private function compressImage($image, $destination, $maxSizeKB)
    {
        $imagePath = $image->getPathname();
        $imageInfo = getimagesize($imagePath);
        
        if (!$imageInfo) {
            throw new \Exception('Invalid image file');
        }
        
        $mimeType = $imageInfo['mime'];
        $width = $imageInfo[0];
        $height = $imageInfo[1];
        
        // Create image resource based on type
        switch ($mimeType) {
            case 'image/jpeg':
                $sourceImage = imagecreatefromjpeg($imagePath);
                break;
            case 'image/png':
                $sourceImage = imagecreatefrompng($imagePath);
                break;
            case 'image/gif':
                $sourceImage = imagecreatefromgif($imagePath);
                break;
            default:
                throw new \Exception('Unsupported image type');
        }
        
        // Calculate new dimensions if needed
        $newWidth = $width;
        $newHeight = $height;
        
        // If image is too large, resize it proportionally
        if ($width > 800 || $height > 800) {
            $ratio = min(800 / $width, 800 / $height);
            $newWidth = intval($width * $ratio);
            $newHeight = intval($height * $ratio);
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
        
        // Resize image
        imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        
        // Save with quality optimization
        $quality = 85; // Start with high quality
        $tempFile = tempnam(sys_get_temp_dir(), 'avatar_');
        
        do {
            switch ($mimeType) {
                case 'image/jpeg':
                    imagejpeg($newImage, $tempFile, $quality);
                    break;
                case 'image/png':
                    // PNG compression level (0-9, 9 = highest compression)
                    $compression = intval((100 - $quality) / 10);
                    imagepng($newImage, $tempFile, $compression);
                    break;
                case 'image/gif':
                    imagegif($newImage, $tempFile);
                    break;
            }
            
            $fileSizeKB = filesize($tempFile) / 1024;
            
            if ($fileSizeKB <= $maxSizeKB) {
                break;
            }
            
            $quality -= 5;
        } while ($quality > 20 && $fileSizeKB > $maxSizeKB);
        
        // Copy to final destination
        copy($tempFile, $destination);
        
        // Clean up
        unlink($tempFile);
        imagedestroy($sourceImage);
        imagedestroy($newImage);
    }
}