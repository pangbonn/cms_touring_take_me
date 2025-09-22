<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingTerm extends Model
{
    protected $fillable = [
        'term_title',
        'term_content',
        'term_category',
        'sort_order',
        'is_active',
        'is_required',
        'additional_info',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_required' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the user who created this term
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get category badge class
     */
    public function getCategoryBadgeClassAttribute(): string
    {
        return match($this->term_category) {
            'booking' => 'badge-primary',
            'payment' => 'badge-success',
            'travel' => 'badge-info',
            'responsibility' => 'badge-warning',
            'group' => 'badge-secondary',
            'seat_selection' => 'badge-accent',
            default => 'badge-neutral'
        };
    }

    /**
     * Get category label
     */
    public function getCategoryLabelAttribute(): string
    {
        return match($this->term_category) {
            'booking' => 'การจอง',
            'payment' => 'การชำระเงิน',
            'travel' => 'การเดินทาง',
            'responsibility' => 'ความรับผิดชอบ',
            'group' => 'กลุ่มทัวร์',
            'seat_selection' => 'การเลือกที่นั่ง',
            default => 'ไม่ระบุ'
        };
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return $this->is_active ? 'badge-success' : 'badge-error';
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน';
    }

    /**
     * Get required badge class
     */
    public function getRequiredBadgeClassAttribute(): string
    {
        return $this->is_required ? 'badge-error' : 'badge-neutral';
    }

    /**
     * Get required label
     */
    public function getRequiredLabelAttribute(): string
    {
        return $this->is_required ? 'บังคับ' : 'ไม่บังคับ';
    }

    /**
     * Scope for active terms
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for required terms
     */
    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    /**
     * Scope for category
     */
    public function scopeOfCategory($query, $category)
    {
        return $query->where('term_category', $category);
    }

    /**
     * Scope ordered by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'asc');
    }

    /**
     * Get default booking terms
     */
    public static function getDefaultTerms(): array
    {
        return [
            [
                'term_title' => 'การยืนยันการจอง',
                'term_content' => 'การจองจะสำเร็จก็ต่อเมื่อได้เข้ากลุ่มแล้วเท่านั้น',
                'term_category' => 'booking',
                'sort_order' => 1,
                'is_required' => true,
                'additional_info' => 'ลูกค้าต้องรอการยืนยันจากทีมงานก่อนถือว่าการจองสำเร็จ'
            ],
            [
                'term_title' => 'ความรับผิดชอบต่อสิ่งของ',
                'term_content' => 'กรณีสิ่งของเสียหายหรือหาไม่เจอที่เกิดจากลูกหาบหรือให้ลูกหาบแบกของให้ทางเราไม่สามารถรับผิดชอบกับมูลค่าที่เกิดขึ้นได้',
                'term_category' => 'responsibility',
                'sort_order' => 2,
                'is_required' => true,
                'additional_info' => 'แนะนำให้ลูกค้าเก็บของมีค่าไว้กับตัวตลอดเวลา'
            ],
            [
                'term_title' => 'เอกสารการเดินทางต่างประเทศ',
                'term_content' => 'ทริปต่างประเทศ ต้องเตรียมพาสปอร์ตและเอกสารให้เรียบร้อย ถ้าไปถึงจุดที่ต้องผ่านแดนแล้วเอกสารไม่พร้อมเราไม่สามารถรับผิดชอบส่วนนี้ได้',
                'term_category' => 'travel',
                'sort_order' => 3,
                'is_required' => true,
                'additional_info' => 'ตรวจสอบความถูกต้องของพาสปอร์ตก่อนเดินทางอย่างน้อย 6 เดือน'
            ],
            [
                'term_title' => 'จำนวนสมาชิกขั้นต่ำ',
                'term_content' => 'เดินทางเมื่อสมาชิกครบ 8 คนขึ้นไป',
                'term_category' => 'group',
                'sort_order' => 4,
                'is_required' => true,
                'additional_info' => 'หากไม่ครบจำนวนอาจมีการยกเลิกหรือเลื่อนการเดินทาง'
            ],
            [
                'term_title' => 'การเลือกที่นั่ง',
                'term_content' => 'ให้สิทธิ์เลือกที่นั่งตามลำดับโอนมัดจำ',
                'term_category' => 'seat_selection',
                'sort_order' => 5,
                'is_required' => false,
                'additional_info' => 'ผู้ที่โอนมัดจำก่อนจะได้สิทธิ์เลือกที่นั่งก่อน'
            ]
        ];
    }

    /**
     * Get category options for select
     */
    public static function getCategoryOptions(): array
    {
        return [
            'booking' => 'การจอง',
            'payment' => 'การชำระเงิน',
            'travel' => 'การเดินทาง',
            'responsibility' => 'ความรับผิดชอบ',
            'group' => 'กลุ่มทัวร์',
            'seat_selection' => 'การเลือกที่นั่ง'
        ];
    }
}
