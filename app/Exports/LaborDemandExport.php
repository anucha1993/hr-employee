<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LaborDemandExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithTitle, WithColumnWidths
{
    protected $reportData;

    public function __construct($reportData = [])
    {
        $this->reportData = $reportData;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // แปลงข้อมูลรายงานให้เป็นรูปแบบที่เหมาะสมสำหรับ Export
        $exportData = collect($this->reportData)->map(function ($row) {
            return [
                'ลำดับ' => $row['customer_id'] === 'total' ? '' : $loop = isset($loop) ? $loop + 1 : 1,
                'โครงการ' => $row['customer_name'],
                'ความต้องการแรงงาน' => $row['labor_demand'],
                'พนักงานเข้างาน' => $row['employees_started'],
                'พนักงานลาออก' => $row['employees_resigned'],
                'อัตราความสำเร็จ (%)' => $row['success_rate'],
                'อัตราการคงอยู่ (%)' => $row['retention_rate']
            ];
        });

        return $exportData;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ลำดับ',
            'โครงการ',
            'ความต้องการแรงงาน',
            'พนักงานเข้างาน',
            'พนักงานลาออก',
            'อัตราความสำเร็จ (%)',
            'อัตราการคงอยู่ (%)'
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'รายงานสถิติจำนวนความต้องการแรงงาน';
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 10,  // ลำดับ
            'B' => 40,  // โครงการ
            'C' => 20,  // ความต้องการแรงงาน
            'D' => 20,  // พนักงานเข้างาน
            'E' => 20,  // พนักงานลาออก
            'F' => 20,  // อัตราความสำเร็จ
            'G' => 20,  // อัตราการคงอยู่
        ];
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        $lastRow = count($this->reportData) + 1; // +1 สำหรับ header row
        
        // จัดรูปแบบของหัวตาราง
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['argb' => 'FFD9EAD3']
            ]
        ]);
        
        // จัดรูปแบบข้อมูล - ตัวเลขให้อยู่ตรงกลาง
        $sheet->getStyle('A2:A' . $lastRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C2:G' . $lastRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        // จัดรูปแบบแถวสุดท้าย (รวมทั้งหมด)
        $sheet->getStyle('A' . $lastRow . ':G' . $lastRow)->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['argb' => 'FFB4C6E7']
            ]
        ]);
        
        // กำหนดเส้นขอบ
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A1:G' . $lastRow)->applyFromArray($styleArray);
        
        // ปรับการแสดงผลตัวเลข
        $sheet->getStyle('C2:E' . $lastRow)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('F2:G' . $lastRow)->getNumberFormat()->setFormatCode('#,##0.00"%"');
    }
}
