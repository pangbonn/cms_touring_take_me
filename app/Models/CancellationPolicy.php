<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CancellationPolicy extends Model
{
    protected $fillable = [
        'policy_name',
        'policy_description',
        'policy_type',
        'cancellation_conditions',
        'force_majeure_conditions',
        'applicable_locations',
        'is_active',
        'is_default',
        'priority',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'cancellation_conditions' => 'array',
            'applicable_locations' => 'array',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the user who created this policy
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get policy type badge class
     */
    public function getPolicyTypeBadgeClassAttribute(): string
    {
        return match($this->policy_type) {
            'standard' => 'badge-primary',
            'force_majeure' => 'badge-warning',
            'location_specific' => 'badge-info',
            default => 'badge-neutral'
        };
    }

    /**
     * Get policy type label
     */
    public function getPolicyTypeLabelAttribute(): string
    {
        return match($this->policy_type) {
            'standard' => 'มาตรฐาน',
            'force_majeure' => 'เหตุสุดวิสัย',
            'location_specific' => 'เฉพาะสถานที่',
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
     * Get default icon
     */
    public function getDefaultIconAttribute(): string
    {
        return $this->is_default ? 'fas fa-star text-yellow-500' : 'fas fa-star text-gray-300';
    }

    /**
     * Get applicable locations as string
     */
    public function getApplicableLocationsStringAttribute(): string
    {
        if (!$this->applicable_locations || empty($this->applicable_locations)) {
            return 'ทุกสถานที่';
        }
        return implode(', ', $this->applicable_locations);
    }

    /**
     * Scope for active policies
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for default policy
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Scope for policy type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('policy_type', $type);
    }

    /**
     * Get default cancellation conditions
     */
    public static function getDefaultCancellationConditions(): array
    {
        return [
            [
                'days_before' => 30,
                'refund_percentage' => 100,
                'description' => 'ยกเลิกก่อน 30 วัน - คืนเงิน 100%'
            ],
            [
                'days_before' => 15,
                'refund_percentage' => 80,
                'description' => 'ยกเลิกก่อน 15 วัน - คืนเงิน 80%'
            ],
            [
                'days_before' => 7,
                'refund_percentage' => 50,
                'description' => 'ยกเลิกก่อน 7 วัน - คืนเงิน 50%'
            ],
            [
                'days_before' => 3,
                'refund_percentage' => 25,
                'description' => 'ยกเลิกก่อน 3 วัน - คืนเงิน 25%'
            ],
            [
                'days_before' => 0,
                'refund_percentage' => 0,
                'description' => 'ยกเลิกในวันเดินทาง - ไม่คืนเงิน'
            ]
        ];
    }

    /**
     * Get force majeure conditions template
     */
    public static function getForceMajeureConditionsTemplate(): array
    {
        return [
            'natural_disasters' => [
                'earthquake' => 'แผ่นดินไหว',
                'flood' => 'น้ำท่วม',
                'storm' => 'พายุ',
                'tsunami' => 'สึนามิ',
                'volcanic_eruption' => 'ภูเขาไฟระเบิด'
            ],
            'pandemic' => [
                'covid19' => 'โควิด-19',
                'epidemic' => 'โรคระบาด',
                'quarantine' => 'การกักกันโรค'
            ],
            'political' => [
                'coup' => 'รัฐประหาร',
                'war' => 'สงคราม',
                'terrorism' => 'การก่อการร้าย',
                'civil_unrest' => 'ความไม่สงบ'
            ],
            'infrastructure' => [
                'airport_closure' => 'ปิดสนามบิน',
                'road_blockage' => 'ถนนถูกปิด',
                'transportation_strike' => 'การนัดหยุดงานขนส่ง'
            ]
        ];
    }

    /**
     * Get common locations
     */
    public static function getCommonLocations(): array
    {
        return [
            'กรุงเทพมหานคร',
            'เชียงใหม่',
            'เชียงราย',
            'ภูเก็ต',
            'กระบี่',
            'พัทยา',
            'หัวหิน',
            'เกาะสมุย',
            'เกาะเต่า',
            'เกาะพะงัน',
            'เกาะช้าง',
            'เกาะเสม็ด',
            'เกาะกูด',
            'เกาะลันตา',
            'เกาะยาว',
            'เกาะพีพี',
            'เกาะหลีเป๊ะ',
            'เกาะตะรุเตา',
            'เกาะสิมิลัน',
            'เกาะสุรินทร์',
            'เกาะตาชัย',
            'เกาะราชา',
            'เกาะบอน',
            'เกาะไข่',
            'เกาะหินงาม',
            'เกาะหินซ้อน',
            'เกาะหินซ้อนน้อย',
            'เกาะหินซ้อนใหญ่',
            'เกาะหินซ้อนกลาง',
            'เกาะหินซ้อนเล็ก'
        ];
    }
}
