<?php

namespace Database\Seeders;

use App\Models\CancellationPolicy;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CancellationPolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first user as creator
        $user = User::first();
        if (!$user) {
            $this->command->error('No users found. Please run user seeder first.');
            return;
        }

        // Default Standard Policy
        CancellationPolicy::create([
            'policy_name' => 'นโยบายการยกเลิกมาตรฐาน',
            'policy_description' => 'นโยบายการยกเลิกทริปมาตรฐานที่ใช้สำหรับทริปทั่วไป',
            'policy_type' => 'standard',
            'cancellation_conditions' => CancellationPolicy::getDefaultCancellationConditions(),
            'force_majeure_conditions' => null,
            'applicable_locations' => null,
            'is_active' => true,
            'is_default' => true,
            'priority' => 100,
            'created_by' => $user->id,
        ]);

        // Force Majeure Policy
        CancellationPolicy::create([
            'policy_name' => 'นโยบายเหตุสุดวิสัย',
            'policy_description' => 'นโยบายการยกเลิกสำหรับเหตุการณ์ที่ไม่สามารถควบคุมได้',
            'policy_type' => 'force_majeure',
            'cancellation_conditions' => [
                [
                    'days_before' => 0,
                    'refund_percentage' => 100,
                    'description' => 'ยกเลิกเนื่องจากเหตุสุดวิสัย - คืนเงิน 100%'
                ]
            ],
            'force_majeure_conditions' => 'เหตุสุดวิสัยที่ครอบคลุม: ภัยธรรมชาติ, โรคระบาด, การเมือง',
            'applicable_locations' => null,
            'is_active' => true,
            'is_default' => false,
            'priority' => 90,
            'created_by' => $user->id,
        ]);

        // Location Specific Policy - Islands (simplified)
        CancellationPolicy::create([
            'policy_name' => 'นโยบายเกาะพิเศษ',
            'policy_description' => 'นโยบายการยกเลิกสำหรับทริปเกาะ',
            'policy_type' => 'location_specific',
            'cancellation_conditions' => [
                [
                    'days_before' => 7,
                    'refund_percentage' => 100,
                    'description' => 'ยกเลิกก่อน 7 วัน - คืนเงิน 100%'
                ],
                [
                    'days_before' => 3,
                    'refund_percentage' => 70,
                    'description' => 'ยกเลิกก่อน 3 วัน - คืนเงิน 70%'
                ],
                [
                    'days_before' => 0,
                    'refund_percentage' => 30,
                    'description' => 'ยกเลิกในวันเดินทาง - คืนเงิน 30%'
                ]
            ],
            'force_majeure_conditions' => null,
            'applicable_locations' => ['เกาะสมุย', 'เกาะเต่า'],
            'is_active' => true,
            'is_default' => false,
            'priority' => 80,
            'created_by' => $user->id,
        ]);

        // Location Specific Policy - Northern Thailand (simplified)
        CancellationPolicy::create([
            'policy_name' => 'นโยบายภาคเหนือ',
            'policy_description' => 'นโยบายการยกเลิกสำหรับทริปภาคเหนือ',
            'policy_type' => 'location_specific',
            'cancellation_conditions' => [
                [
                    'days_before' => 14,
                    'refund_percentage' => 100,
                    'description' => 'ยกเลิกก่อน 14 วัน - คืนเงิน 100%'
                ],
                [
                    'days_before' => 7,
                    'refund_percentage' => 80,
                    'description' => 'ยกเลิกก่อน 7 วัน - คืนเงิน 80%'
                ],
                [
                    'days_before' => 0,
                    'refund_percentage' => 40,
                    'description' => 'ยกเลิกในวันเดินทาง - คืนเงิน 40%'
                ]
            ],
            'force_majeure_conditions' => null,
            'applicable_locations' => ['เชียงใหม่', 'เชียงราย'],
            'is_active' => true,
            'is_default' => false,
            'priority' => 70,
            'created_by' => $user->id,
        ]);

        $this->command->info('Cancellation policies seeded successfully!');
    }
}