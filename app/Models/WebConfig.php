<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebConfig extends Model
{
    protected $fillable = [
        'site_name',
        'site_logo',
        'site_description',
        'contact_address',
        'contact_phone',
        'contact_email',
        'license_number',
        'line_id',
        'facebook_url',
        'tiktok_url',
        'about_us',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the logo URL
     */
    public function getLogoUrl(): string
    {
        if ($this->site_logo) {
            return asset('storage/logos/' . $this->site_logo);
        }
        return asset('images/default-logo.png');
    }

    /**
     * Get or create the first web config record
     */
    public static function getConfig(): self
    {
        return self::firstOrCreate(
            ['id' => 1],
            [
                'site_name' => 'CMS Touring',
                'site_description' => 'ระบบจัดการทัวร์ออนไลน์',
                'contact_address' => 'กรุงเทพมหานคร',
                'contact_phone' => '02-123-4567',
                'contact_email' => 'info@cmstouring.com',
                'about_us' => 'เราเป็นบริษัททัวร์ที่ให้บริการทัวร์คุณภาพสูง พร้อมทีมงานมืออาชีพ',
            ]
        );
    }
}
