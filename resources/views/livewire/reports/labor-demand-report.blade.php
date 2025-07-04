<div>
    <div class="card shadow">
        <div class="card-header bg-gradient-primary py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-white">
                <i class="fas fa-chart-bar mr-1"></i> {{ $title }}
            </h6>
        </div>

        <div class="card-body">
            <!-- Filter Section -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card card-body bg-light shadow-sm">
                        <form wire:submit.prevent="preview">
                            <div class="form-row">
                                <div class="col-md-3 mb-2">
                                    <label for="start_date" class="form-label">วันที่เริ่มต้น <span class="text-danger">*</span></label>
                                    <input type="date" wire:model="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror">
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label for="end_date" class="form-label">วันที่สิ้นสุด <span class="text-danger">*</span></label>
                                    <input type="date" wire:model="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror">
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label for="project_id" class="form-label">โครงการ</label>
                                    <select wire:model="project_id" id="project_id" class="form-control">
                                        <option value="">-- เลือกทั้งหมด --</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}">{{ $project->customer_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3 mb-2">
                                    <label for="employee_status" class="form-label">สถานะพนักงาน</label>
                                    <select wire:model="employee_status" id="employee_status" class="form-control">
                                        <option value="">-- เลือกทั้งหมด --</option>
                                        @foreach($employeeStatuses as $status)
                                            <option value="{{ $status->id }}">{{ $status->value }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2 mb-2 d-flex align-items-end">
                                    <div class="btn-group w-100">
                                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                            <span wire:loading.remove>
                                                <i class="fas fa-search mr-1"></i> ค้นหา
                                            </span>
                                            <span wire:loading wire:target="preview">
                                                <i class="fas fa-spinner fa-spin mr-1"></i> กำลังประมวลผล...
                                            </span>
                                        </button>
                                        <button type="button" wire:click="resetFilters" class="btn btn-outline-secondary">
                                           ล้างข้อมูล
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Display Options -->
            @if($showPreview && !empty($reportData))
                <div class="btn-group mb-4">
                    <button type="button" class="btn {{ $showType === 'table' ? 'btn-primary' : 'btn-outline-primary' }}" 
                        wire:click="setShowType('table')">
                        <i class="fas fa-table mr-1"></i> ตาราง
                    </button>
            
                </div>

                <!-- Results Section -->
                <div class="mb-3">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-list mr-1"></i> ผลลัพธ์รายงาน
                            </h6>
                            <div>
                                <button type="button" class="btn btn-sm btn-success" wire:click="exportExcel">
                                    <i class="fas fa-file-excel mr-1"></i> Export Excel
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Table View -->
                            @if($showType === 'table')
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="labor-demand-table">
                                        <thead class="bg-light">
                                            <tr>
                                                <th width="5%" class="text-center">ลำดับ</th>
                                                <th>โครงการ</th>
                                                <th width="12%" class="text-center">ความต้องการแรงงาน</th>
                                                <th width="12%" class="text-center">พนักงานเข้างาน</th>
                                                <th width="12%" class="text-center">พนักงานลาออก</th>
                                                <th width="12%" class="text-center">อัตราความสำเร็จ</th>
                                                <th width="12%" class="text-center">อัตราการคงอยู่</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($reportData as $index => $data)
                                                <tr class="{{ $data['customer_id'] === 'total' ? 'table-primary font-weight-bold' : '' }}">
                                                    <td class="text-center">{{ $data['customer_id'] === 'total' ? '' : $index + 1 }}</td>
                                                    <td>{{ $data['customer_name'] }}</td>
                                                    <td class="text-center">{{ $data['labor_demand'] }}</td>
                                                    <td class="text-center">{{ $data['employees_started'] }}</td>
                                                    <td class="text-center">{{ $data['employees_resigned'] }}</td>
                                                    <td class="text-center">{{ $data['success_rate'] }}%</td>
                                                    <td class="text-center">{{ $data['retention_rate'] }}%</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif

                            <!-- Bar Chart View -->
                            @if($showType === 'bar')
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <div>
                                            <canvas id="myChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div>
                                            <canvas id="myChart2"></canvas>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Pie Chart View -->
                            @if($showType === 'pie')
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <h6 class="font-weight-bold text-center mb-3">สัดส่วนความต้องการแรงงาน</h6>
                                        <div>
                                            <canvas id="myChart3"></canvas>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <h6 class="font-weight-bold text-center mb-3">สัดส่วนพนักงานเข้างาน</h6>
                                        <div>
                                            <canvas id="myChart4"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <h6 class="font-weight-bold text-center mb-3">สัดส่วนอัตราความสำเร็จ</h6>
                                        <div>
                                            <canvas id="myChart5"></canvas>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <h6 class="font-weight-bold text-center mb-3">สัดส่วนอัตราการคงอยู่</h6>
                                        <div>
                                            <canvas id="myChart6"></canvas>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @elseif($showPreview)
                <div class="alert alert-warning text-center">
                    <i class="fas fa-exclamation-triangle mr-1"></i> ไม่พบข้อมูลตามเงื่อนไขที่กำหนด
                </div>
            @endif
        </div>
    </div>

</div>
