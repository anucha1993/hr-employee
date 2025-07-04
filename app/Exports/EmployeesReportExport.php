<?php

namespace App\Exports;

use App\Models\Employees\EmployeeModel;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class EmployeesReportExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle, WithColumnWidths
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query = EmployeeModel::query()
                ->with(['factory', 'status', 'recruiter']) // Eager load relationships
                ->orderBy('emp_code');
        
        // กรองตามบริษัทที่เลือก
        if (isset($this->filters['customer_ids']) && !empty($this->filters['customer_ids'])) {
            $query->whereIn('emp_factory_id', $this->filters['customer_ids']);
        }
        
        // กรองตามเดือนเริ่มงาน
        if (isset($this->filters['start_month']) && !empty($this->filters['start_month'])) {
            $query->whereMonth('emp_start_date', $this->filters['start_month']);
        }
        
        // กรองตามปีเริ่มงาน
        if (isset($this->filters['start_year']) && !empty($this->filters['start_year'])) {
            $query->whereYear('emp_start_date', $this->filters['start_year']);
        }
        
        // กรองตามช่วงวันที่เริ่มงาน
        if (isset($this->filters['date_from']) && !empty($this->filters['date_from'])) {
            $query->where('emp_start_date', '>=', $this->filters['date_from']);
        }
        
        if (isset($this->filters['date_to']) && !empty($this->filters['date_to'])) {
            $query->where('emp_start_date', '<=', $this->filters['date_to']);
        }
        
        // กรองตามสถานะพนักงาน
        if (isset($this->filters['employee_status']) && !empty($this->filters['employee_status'])) {
            $query->whereIn('emp_status', $this->filters['employee_status']);
        }
        
        // กรองตามรายชื่อสรรหา
        if (isset($this->filters['recruited_employees']) && !empty($this->filters['recruited_employees'])) {
            $query->whereIn('emp_recruiter_id', $this->filters['recruited_employees']);
        }
        
        return $query;
    }
    
    /**
     * @var EmployeeModel $employee
     */
    public function map($employee): array
    {
        // แปลง emp_department (ตำแหน่ง) - เป็นข้อความที่อ่านได้ (ถ้ามีความสัมพันธ์กับตาราง)
        $position = $employee->emp_department;
        
        // ชื่อบริษัท
        $companyName = $employee->factory ? $employee->factory->customer_name : '-';
        
        // สถานะพนักงาน
        $status = $employee->status ? $employee->status->value : $this->getStatusText($employee->emp_status);
        
        // ชื่อสรรหา
        $recruiterName = $employee->recruiter ? $employee->recruiter->value : '-';
        
        return [
            $employee->emp_code,
            $employee->emp_name,
            $position,
            $companyName,
            $employee->emp_start_date ? Carbon::parse($employee->emp_start_date)->format('d/m/Y') : '-',
            $status,
            $recruiterName
        ];
    }
    
    /**
     * คืนค่าข้อความสถานะกรณีที่ไม่ได้ดึงข้อมูลจาก relationship
     */
    private function getStatusText($status)
    {
        switch ($status) {
            case 0: return 'สัมภาษณ์งาน';
            case 1: return 'เริ่มงาน';
            case 2: return 'ไม่มาเริ่มงาน';
            case 3: return 'ลาออก';
            default: return 'ไม่ทราบสถานะ';
        }
    }
    
    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'รหัสพนักงาน',
            'ชื่อ-นามสกุล',
            'ตำแหน่ง',
            'บริษัท',
            'วันที่เริ่มงาน',
            'สถานะ',
            'ชื่อสรรหา'
        ];
    }
    
    /**
     * @return string
     */
    public function title(): string
    {
        return 'รายงานข้อมูลพนักงาน';
    }
    
    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // ให้ส่วนหัวตารางเป็นตัวหนา มีพื้นหลัง และจัดให้อยู่ตรงกลาง
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
    
    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 15,  // รหัสพนักงาน
            'B' => 30,  // ชื่อ-นามสกุล
            'C' => 20,  // ตำแหน่ง
            'D' => 35,  // บริษัท
            'E' => 15,  // วันที่เริ่มงาน
            'F' => 15,  // สถานะ
            'G' => 20,  // ชื่อสรรหา
        ];
    }
}
