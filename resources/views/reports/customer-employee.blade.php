@extends('layouts.vertical-main', ['title' => 'รายงานจำนวนพนักงานตามบริษัท'])

@section('content')

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h4 class="mb-0">รายงานจำนวนพนักงานตามบริษัท</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('reports.customer-employee.export') }}" method="POST">
                        @csrf
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">สถานะลูกค้า</label>
                                    <select name="customer_status" class="form-select">
                                        <option value="">ทั้งหมด</option>
                                        <option value="1">ทำงานอยู่</option>
                                        <option value="0">ยกเลิก</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">สถานะพนักงาน</label>
                                    <select name="employee_status" class="form-select">
                                        <option value="">ทั้งหมด</option>
                                        @foreach($employeeStatuses as $status)
                                        <option value="{{ $status->id }}">{{ $status->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label">เลือกบริษัท (ไม่เลือก = ทั้งหมด)</label>
                                    <select name="customers[]" class="form-select" multiple size="8">
                                        @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">กด Ctrl เพื่อเลือกหลายบริษัท</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">วันที่เริ่มงานตั้งแต่</label>
                                    <input type="date" name="date_from" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">วันที่เริ่มงานถึง</label>
                                    <input type="date" name="date_to" class="form-control">
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-end mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-file-excel"></i> ส่งออก Excel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
