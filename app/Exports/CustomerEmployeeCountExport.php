<?php

namespace App\Exports;

use App\Models\Employees\EmployeeModel;
use App\Models\customers\CustomerModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CustomerEmployeeCountExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle, WithColumnWidths
{
    protected $data;

    public function __construct($data)
    {
        // รับข้อมูลที่จะแสดงในรายงานโดยตรง
        $this->data = $data;
    }
    
    public function collection()
    {
        // ส่งคืนข้อมูลที่ได้รับมา (ถ้าเป็น array ให้แปลงเป็น collection)
        if (is_array($this->data)) {
            return new Collection($this->data);
        }
        
        // ถ้าเป็น collection อยู่แล้วก็ส่งคืนเลย
        return $this->data;
    }
    
    public function headings(): array
    {
        return [
            'รหัสลูกค้า',
            'ชื่อบริษัท',
            'เลขที่ผู้เสียภาษี',
            'จังหวัด',
            'สถานะ',
            'จำนวนพนักงานทั้งหมด',
            'จำนวนพนักงานที่ทำงานอยู่',
            'จำนวนพนักงานที่ลาออกแล้ว'
        ];
    }
    
    public function map($item): array
    {
        // ข้อมูลมาจาก array หรือ object
        if (is_array($item)) {
            // ถ้าเป็น array ก็เข้าถึงข้อมูลแบบ array
            return [
                $item['id'] ?? '',
                $item['customer_name'] ?? '',
                $item['customer_taxid'] ?? '-',
                '-', // จังหวัด (ไม่มีข้อมูล)
                $this->getStatusLabel($item['customer_status'] ?? 0),
                $item['employee_count'] ?? 0,
                $item['active_count'] ?? 0,
                $item['inactive_count'] ?? 0
            ];
        } else {
            // ถ้าเป็น object ก็เข้าถึงข้อมูลแบบ object
            return [
                $item->id ?? '',
                $item->customer_name ?? '',
                $item->customer_taxid ?? '-',
                $item->customer_address_province ?? '-',
                $this->getStatusLabel($item->customer_status ?? 0),
                $item->employee_count ?? 0,
                $item->active_count ?? 0,
                $item->inactive_count ?? 0
            ];
        }
    }
    
    private function getStatusLabel($status)
    {
        switch ($status) {
            case 1: return 'ทำงานอยู่';
            case 2: return 'ลาออกแล้ว';
            case 0: return 'ยกเลิก';
            default: return 'ไม่ระบุ';
        }
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row (headers)
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFE0E0E0'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }
    
    public function title(): string
    {
        return 'รายงานจำนวนพนักงานตามบริษัท';
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 10,  // รหัสลูกค้า
            'B' => 40,  // ชื่อบริษัท
            'C' => 20,  // เลขที่ผู้เสียภาษี
            'D' => 15,  // จังหวัด
            'E' => 15,  // สถานะ
            'F' => 20,  // จำนวนพนักงานทั้งหมด
            'G' => 25,  // จำนวนพนักงานที่ทำงานอยู่
            'H' => 25,  // จำนวนพนักงานที่ลาออกแล้ว
        ];
    }
}
