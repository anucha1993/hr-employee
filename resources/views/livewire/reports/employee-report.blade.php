<div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">รายงานข้อมูลพนักงาน</h4>
                    </div>
                    <div class="card-body">
                        <form wire:submit.prevent="exportExcel">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group mb-3" wire:ignore>
                                        <label for="customer_ids">บริษัท:</label>
                                        <select id="customer_ids" name="customer_ids" class="form-control select2" multiple wire:model="customer_ids">
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
                                        <select id="start_month" name="start_month" class="form-control" wire:model="start_month">
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
                                        <select id="start_year" name="start_year" class="form-control" wire:model.live="start_year">
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
                                        <div class="input-group" wire:ignore>
                                            <input type="text" class="form-control datepicker" id="date_from" placeholder="วันที่เริ่มต้น" wire:model="date_from">
                                            <span class="input-group-text">ถึง</span>
                                            <input type="text" class="form-control datepicker" id="date_to" placeholder="วันที่สิ้นสุด" wire:model="date_to">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group" wire:ignore>
                                        <label for="employee_status">สถานะพนักงาน:</label>
                                        <select id="employee_status" name="employee_status" class="form-control select2" multiple wire:model="employee_status">
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
                                    <div class="form-group" wire:ignore>
                                        <label for="recruited_employees">รายชื่อสรรหา:</label>
                                        <select id="recruited_employees" name="recruited_employees" class="form-control select2" multiple wire:model="recruited_employees">
                                            <option value="">-- เลือกทั้งหมด --</option>
                                            @foreach($recruitedEmployees as $recruiter)
                                                <option value="{{ $recruiter->id }}">{{ $recruiter->value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-end mt-4">
                                <button type="button" class="btn btn-secondary me-2" wire:click="resetFilters">
                                    <i class="fa fa-refresh me-1"></i> ล้างตัวกรอง
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-file-excel me-1"></i> ออกรายงาน Excel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- แสดง Preview ข้อมูล -->
        @if($employees->count() > 0)
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">ตัวอย่างข้อมูลที่จะ Export ({{ $employees->total() }} รายการ)</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>รหัสพนักงาน</th>
                                        <th>ชื่อ-นามสกุล</th>
                                        <th>ตำแหน่ง</th>
                                        <th>บริษัท</th>
                                        <th>วันที่เริ่มงาน</th>
                                        <th>สถานะ</th>
                                        <th>ชื่อสรรหา</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($employees as $employee)
                                    <tr>
                                        <td>{{ $employee->emp_code }}</td>
                                        <td>{{ $employee->emp_name }}</td>
                                        <td>{{ $employee->emp_department }}</td>
                                        <td>{{ $employee->factory ? $employee->factory->customer_name : '-' }}</td>
                                        <td>{{ $employee->emp_start_date ? \Carbon\Carbon::parse($employee->emp_start_date)->format('d/m/Y') : '-' }}</td>
                                        <td>{{ $employee->status ? $employee->status->value : '-' }}</td>
                                        <td>{{ $employee->recruiter ? $employee->recruiter->value : '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $employees->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <script>
        document.addEventListener('livewire:initialized', function () {
            initSelect2();
            initDatepicker();
        });

        // Listen for filter reset
        document.addEventListener('livewire:updated', function () {
            setTimeout(function() {
                initSelect2();
                initDatepicker();
            }, 100);
        });

        function initSelect2() {
            $('.select2').select2({
                placeholder: "เลือกข้อมูล",
                allowClear: true
            }).on('change', function (e) {
                let data = $(this).val();
                @this.set($(this).attr('wire:model'), data);
            });
        }

        function initDatepicker() {
            $(".datepicker").flatpickr({
                dateFormat: "Y-m-d",
                locale: "th",
                allowInput: true,
                onChange: function(selectedDates, dateStr, instance) {
                    // ส่งค่าวันที่ไปยัง Livewire
                    @this.set(instance.element.getAttribute('wire:model'), dateStr);
                }
            });
        }
    </script>
</div>