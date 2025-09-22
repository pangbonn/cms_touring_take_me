<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BookingTerm;
use App\Models\User;

class BookingTermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get superadmin user for created_by
        $superadmin = User::where('email', 'superadmin@example.com')->first();
        
        if (!$superadmin) {
            // If superadmin doesn't exist, create one
            $superadmin = User::create([
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'email' => 'superadmin@example.com',
                'password' => bcrypt('password123'),
                'role' => 'superadmin',
                'is_active' => true,
                'must_change_password' => false,
            ]);
        }

        $bookingTerms = [
            [
                'term_title' => 'การยืนยันการจอง',
                'term_content' => 'การจองจะสำเร็จก็ต่อเมื่อได้เข้ากลุ่มแล้วเท่านั้น',
                'term_category' => 'booking',
                'sort_order' => 1,
                'is_active' => true,
                'is_required' => true,
                'additional_info' => 'ลูกค้าต้องรอการยืนยันจากทีมงานก่อนถือว่าการจองสำเร็จ',
                'created_by' => $superadmin->id,
            ],
            [
                'term_title' => 'ความรับผิดชอบต่อสิ่งของ',
                'term_content' => 'กรณีสิ่งของเสียหายหรือหาไม่เจอที่เกิดจากลูกหาบหรือให้ลูกหาบแบกของให้ทางเราไม่สามารถรับผิดชอบกับมูลค่าที่เกิดขึ้นได้',
                'term_category' => 'responsibility',
                'sort_order' => 2,
                'is_active' => true,
                'is_required' => true,
                'additional_info' => 'แนะนำให้ลูกค้าเก็บของมีค่าไว้กับตัวตลอดเวลา',
                'created_by' => $superadmin->id,
            ],
            [
                'term_title' => 'เอกสารการเดินทางต่างประเทศ',
                'term_content' => 'ทริปต่างประเทศ ต้องเตรียมพาสปอร์ตและเอกสารให้เรียบร้อย ถ้าไปถึงจุดที่ต้องผ่านแดนแล้วเอกสารไม่พร้อมเราไม่สามารถรับผิดชอบส่วนนี้ได้',
                'term_category' => 'travel',
                'sort_order' => 3,
                'is_active' => true,
                'is_required' => true,
                'additional_info' => 'ตรวจสอบความถูกต้องของพาสปอร์ตก่อนเดินทางอย่างน้อย 6 เดือน',
                'created_by' => $superadmin->id,
            ],
            [
                'term_title' => 'จำนวนสมาชิกขั้นต่ำ',
                'term_content' => 'เดินทางเมื่อสมาชิกครบ 8 คนขึ้นไป',
                'term_category' => 'group',
                'sort_order' => 4,
                'is_active' => true,
                'is_required' => true,
                'additional_info' => 'หากไม่ครบจำนวนอาจมีการยกเลิกหรือเลื่อนการเดินทาง',
                'created_by' => $superadmin->id,
            ],
            [
                'term_title' => 'การเลือกที่นั่ง',
                'term_content' => 'ให้สิทธิ์เลือกที่นั่งตามลำดับโอนมัดจำ',
                'term_category' => 'seat_selection',
                'sort_order' => 5,
                'is_active' => true,
                'is_required' => false,
                'additional_info' => 'ผู้ที่โอนมัดจำก่อนจะได้สิทธิ์เลือกที่นั่งก่อน',
                'created_by' => $superadmin->id,
            ],
            [
                'term_title' => 'การชำระเงินมัดจำ',
                'term_content' => 'ต้องชำระเงินมัดจำ 30% ของราคาทริปภายใน 3 วันหลังจากยืนยันการจอง',
                'term_category' => 'payment',
                'sort_order' => 6,
                'is_active' => true,
                'is_required' => true,
                'additional_info' => 'หากไม่ชำระเงินภายในกำหนดจะถือว่ายกเลิกการจองอัตโนมัติ',
                'created_by' => $superadmin->id,
            ],
            [
                'term_title' => 'การชำระเงินส่วนที่เหลือ',
                'term_content' => 'ชำระเงินส่วนที่เหลือก่อนเดินทางอย่างน้อย 7 วัน',
                'term_category' => 'payment',
                'sort_order' => 7,
                'is_active' => true,
                'is_required' => true,
                'additional_info' => 'หากไม่ชำระเงินครบถ้วนจะไม่สามารถเดินทางได้',
                'created_by' => $superadmin->id,
            ],
            [
                'term_title' => 'การยกเลิกการจอง',
                'term_content' => 'การยกเลิกการจองต้องแจ้งล่วงหน้าอย่างน้อย 15 วันก่อนวันเดินทาง',
                'term_category' => 'booking',
                'sort_order' => 8,
                'is_active' => true,
                'is_required' => false,
                'additional_info' => 'การยกเลิกภายใน 15 วันอาจมีการหักค่าธรรมเนียม',
                'created_by' => $superadmin->id,
            ],
            [
                'term_title' => 'การเปลี่ยนแปลงโปรแกรม',
                'term_content' => 'บริษัทขอสงวนสิทธิ์ในการเปลี่ยนแปลงโปรแกรมทัวร์ตามความเหมาะสม',
                'term_category' => 'travel',
                'sort_order' => 9,
                'is_active' => true,
                'is_required' => false,
                'additional_info' => 'การเปลี่ยนแปลงจะแจ้งให้ทราบล่วงหน้าและจะไม่กระทบต่อคุณภาพการบริการ',
                'created_by' => $superadmin->id,
            ],
            [
                'term_title' => 'การประกันภัย',
                'term_content' => 'แนะนำให้ทำประกันภัยการเดินทางเพื่อความปลอดภัย',
                'term_category' => 'travel',
                'sort_order' => 10,
                'is_active' => true,
                'is_required' => false,
                'additional_info' => 'บริษัทไม่รับผิดชอบต่อความเสียหายที่เกิดจากการไม่ทำประกันภัย',
                'created_by' => $superadmin->id,
            ],
            [
                'term_title' => 'การแต่งกาย',
                'term_content' => 'แต่งกายให้เหมาะสมกับสถานที่และสภาพอากาศ',
                'term_category' => 'travel',
                'sort_order' => 11,
                'is_active' => true,
                'is_required' => false,
                'additional_info' => 'แนะนำให้เตรียมเสื้อผ้าสำหรับสภาพอากาศที่เปลี่ยนแปลง',
                'created_by' => $superadmin->id,
            ],
            [
                'term_title' => 'การเดินทางด้วยรถประจำทาง',
                'term_content' => 'ห้ามสูบบุหรี่และดื่มเครื่องดื่มแอลกอฮอล์บนรถประจำทาง',
                'term_category' => 'travel',
                'sort_order' => 12,
                'is_active' => true,
                'is_required' => true,
                'additional_info' => 'การฝ่าฝืนอาจถูกขอให้ลงจากรถทันที',
                'created_by' => $superadmin->id,
            ],
            [
                'term_title' => 'การถ่ายภาพ',
                'term_content' => 'สามารถถ่ายภาพได้ตามความเหมาะสม แต่ต้องเคารพสถานที่และผู้คน',
                'term_category' => 'travel',
                'sort_order' => 13,
                'is_active' => true,
                'is_required' => false,
                'additional_info' => 'ห้ามถ่ายภาพในสถานที่ที่ห้ามถ่ายภาพ',
                'created_by' => $superadmin->id,
            ],
            [
                'term_title' => 'การเดินทางด้วยเรือ',
                'term_content' => 'ต้องสวมเสื้อชูชีพตลอดเวลาขณะอยู่บนเรือ',
                'term_category' => 'travel',
                'sort_order' => 14,
                'is_active' => true,
                'is_required' => true,
                'additional_info' => 'เพื่อความปลอดภัยของทุกคนบนเรือ',
                'created_by' => $superadmin->id,
            ],
            [
                'term_title' => 'การเดินทางด้วยเครื่องบิน',
                'term_content' => 'ต้องมาถึงสนามบินก่อนเวลาออกเดินทางอย่างน้อย 2 ชั่วโมง',
                'term_category' => 'travel',
                'sort_order' => 15,
                'is_active' => true,
                'is_required' => true,
                'additional_info' => 'เพื่อให้มีเวลาเพียงพอในการเช็คอินและผ่านการตรวจสอบ',
                'created_by' => $superadmin->id,
            ],
        ];

        // Create booking terms
        foreach ($bookingTerms as $termData) {
            BookingTerm::create($termData);
        }

        $this->command->info('Booking terms seeded successfully!');
    }
}