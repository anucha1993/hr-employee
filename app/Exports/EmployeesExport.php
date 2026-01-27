<?php

namespace App\Exports;

use App\Models\Employees\EmployeeModel;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Database\Eloquent\Builder;

class EmployeesExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = EmployeeModel::query()
            ->with(['factory', 'status']);
        
        // Apply recruiter_id filter for non-super admin users
        if (!empty($this->filters['recruiter_id'])) {
            $query->where('emp_recruiter_id', $this->filters['recruiter_id']);
        }
        
        // Apply filters
        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('emp_name', 'like', "%{$search}%")
                  ->orWhere('emp_code', 'like', "%{$search}%")
                  ->orWhere('emp_phone', 'like', "%{$search}%");
            });
        }

        if (!empty($this->filters['status'])) {
            $query->where('emp_status', $this->filters['status']);
        }

        if (!empty($this->filters['factory'])) {
            $query->where('emp_factory_id', $this->filters['factory']);
        }

        if (!empty($this->filters['age_from']) && is_numeric($this->filters['age_from'])) {
            $query->whereRaw('TIMESTAMPDIFF(YEAR, emp_birthdate, CURDATE()) >= ?', [$this->filters['age_from']]);
        }

        if (!empty($this->filters['age_to']) && is_numeric($this->filters['age_to'])) {
            $query->whereRaw('TIMESTAMPDIFF(YEAR, emp_birthdate, CURDATE()) <= ?', [$this->filters['age_to']]);
        }
        
        return $query;
    }

    public function headings(): array
    {
        return [
            'รหัสพนักงาน',
            'ชื่อพนักงาน',
            'อายุ',
            'เบอร์โทร',
            'อีเมล',
            'ไลน์',
            'ที่อยู่ปัจจุบัน',
            'โรงงาน',
            'สถานะ',
            'แผนก',
        ];
    }

    public function map($employee): array
    {
        $birthdate = $employee->emp_birthdate ? \Carbon\Carbon::parse($employee->emp_birthdate) : null;
        $age = $birthdate ? $birthdate->age : '-';
        
        return [
            $employee->emp_code,
            $employee->emp_name,
            $age . ' ปี',
            $employee->emp_phone,
            $employee->emp_email ?? '-',
            $employee->emp_line ?? '-',
            $employee->current_address_details ?? $employee->emp_address_current ?? '-',
            $employee->factory->customer_name ?? '-',
            $employee->status->value ?? 'ไม่ระบุ',
            $employee->emp_department ?? 'ไม่ระบุ',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true, 'size' => 14]],
        ];
    }
}
