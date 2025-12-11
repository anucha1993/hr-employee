<?php

if (! function_exists('getStatusCutomerBadge')) {

   function getStatusCutomerBadge(string|int|null $status): string
{
    if ($status === null) {
        return '<span class="badge bg-light text-dark">ไม่ทราบสถานะ</span>';
    }
    
    return match ((int) $status) {
        1       => '<span class="badge bg-success">เปิดใช้งาน</span>',
        0       => '<span class="badge bg-danger text-white">ปิดใช้งาน</span>',
        default => '<span class="badge bg-light text-dark">ไม่ทราบสถานะ</span>',
    };
}
}