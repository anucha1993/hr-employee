@extends('layouts.vertical-main', ['title' => 'รายงานข้อมูลพนักงาน'])

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #3490dc;
        color: white;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">รายงานข้อมูลพนักงาน</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('reports.employee-report.export') }}" method="post">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="customers">บริษัท:</label>
                                    <select id="customers" name="customers[]" class="form-control select2" multiple>
                                        <option value="">-- เลือกทั้งหมด --</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="start_month">เดือนเริ่มงาน:</label>
                                    <select id="start_month" name="start_month" class="form-control">
                                        <option value="">-- เลือกเดือน --</option>
                                        @foreach($months as $key => $month)
                                            <option value="{{ $key }}">{{ $month }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="start_year">ปีเริ่มงาน:</label>
                                    <select id="start_year" name="start_year" class="form-control">
                                        <option value="">-- เลือกปี --</option>
                                        @foreach($years as $year)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>ช่วงวันที่เริ่มงาน:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control datepicker" name="date_from" id="date_from" placeholder="วันที่เริ่มต้น">
                                        <span class="input-group-text">ถึง</span>
                                        <input type="text" class="form-control datepicker" name="date_to" id="date_to" placeholder="วันที่สิ้นสุด">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="employee_status">สถานะพนักงาน:</label>
                                    <select id="employee_status" name="employee_status[]" class="form-control select2" multiple>
                                        <option value="">-- เลือกทั้งหมด --</option>
                                        @foreach($employeeStatuses as $status)
                                            <option value="{{ $status->id }}">{{ $status->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="recruited_employees">รายชื่อพนักงานที่ตรงกับรายชื่อสรรหา:</label>
                                    <select id="recruited_employees" name="recruited_employees[]" class="form-control select2" multiple>
                                        <option value="">-- เลือกทั้งหมด --</option>
                                        @foreach($recruitedEmployees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->emp_code }} - {{ $employee->emp_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-file-excel me-1"></i> ออกรายงาน Excel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>

<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            placeholder: "เลือกข้อมูล",
            allowClear: true
        });
        
        // Initialize datepicker with Thai locale
        $(".datepicker").flatpickr({
            dateFormat: "Y-m-d",
            locale: "th",
            allowInput: true
        });
    });
</script>
@endsection
