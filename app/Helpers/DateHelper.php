<?php

if (!function_exists('formatThaiDate')) {
    /**
     * แปลงวันที่เป็นรูปแบบไทย
     */
    function formatThaiDate($date, $includeTime = false)
    {
        if (!$date) return '';
        
        $months = [
            1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => 'เมษายน',
            5 => 'พฤษภาคม', 6 => 'มิถุนายน', 7 => 'กรกฎาคม', 8 => 'สิงหาคม',
            9 => 'กันยายน', 10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม'
        ];
        
        $carbon = \Carbon\Carbon::parse($date);
        $day = $carbon->day;
        $month = $months[$carbon->month];
        $year = $carbon->year + 543; // แปลงเป็น พ.ศ.
        
        $result = "{$day} {$month} {$year}";
        
        if ($includeTime) {
            $result .= " {$carbon->format('H:i')} น.";
        }
        
        return $result;
    }
}

if (!function_exists('calculateDaysNights')) {
    /**
     * คำนวณจำนวนวันและคืน
     */
    function calculateDaysNights($departureDate, $returnDate)
    {
        if (!$departureDate || !$returnDate) return '';
        
        $departure = \Carbon\Carbon::parse($departureDate);
        $return = \Carbon\Carbon::parse($returnDate);
        
        $days = $departure->diffInDays($return) + 1; // รวมวันแรก
        $nights = $days - 1; // คืน = วัน - 1
        
        if ($days == 1) {
            return "1 วัน";
        } elseif ($nights == 1) {
            return "2 วัน 1 คืน";
        } else {
            return "{$days} วัน {$nights} คืน";
        }
    }
}
