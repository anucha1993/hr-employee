<?php

if (! function_exists('getStatusemployeeBadge')) {

     function getStatusemployeeBadge(string $status): string
    {
        return match (strtolower($status)) {
            0     => '<span class="badge bg-primary">สัมภาษณ์งาน</span>',
            1     => '<span class="badge text-dark success">เริ่มงาน</span>',
            2     => '<span class="badge bg-ลาออก">ไม่มาเริ่มงาน</span>',
             3   => '<span class="badge bg-danger text-dark">ลาออก</span>',
            default   => '<span class="badge bg-light text-dark">ไม่ทราบสถานะ</span>',
        };
    }
}