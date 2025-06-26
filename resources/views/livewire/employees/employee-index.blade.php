<div>
                             
        <div class="row">
            <div class="col-12">

                <div class="card shadow-sm border-0" style="border-radius: 12px;">
                    <!-- Header -->
                    <div class="card-header bg-primary text-white py-3" style="border-radius: 12px 12px 0 0;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="mb-0 fw-bold">
                                    <i class="mdi mdi-account-group me-2"></i>จัดการข้อมูลพนักงาน
                                </h3>
                            </div>
                            <div>
                                <span class="badge bg-light text-primary px-3 py-2">
                                    ทั้งหมด {{ $employees->total() }} คน
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <!-- Search & Add Button -->
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <input type="text" wire:model.live.debounce.500ms="search" 
                                       class="form-control form-control-lg"
                                       placeholder="🔍 ค้นหาชื่อ, รหัสพนักงาน, หรือเบอร์โทร...">
                            </div>
                            <div class="col-md-4 text-end">
                                <a href="{{ route('employees.create') }}" class="btn btn-primary btn-lg">
                                    ➕ เพิ่มพนักงาน
                                </a>
                            </div>
                        </div>

                        <!-- Simple Table -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50">#</th>
                                        <th>ชื่อพนักงาน</th>
                                        <th>รหัส</th>
                                        <th>เบอร์โทร</th>
                                        <th>โรงงาน</th>
                                        <th>สถานะ</th>
                                        <th width="150" class="text-center">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <tbody>
                        @forelse ($employees as $index => $emp)
                            <tr>
                                <td class="text-muted">{{ $employees->firstItem() + $index }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                             style="width: 35px; height: 35px; font-size: 14px; font-weight: 600;">
                                            {{-- {{ strtoupper(substr($emp->emp_name, 0, 2)) }} --}}
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $emp->emp_name }}</div>
                                            <small class="text-muted">{{ $emp->emp_department ?? 'ไม่ระบุแผนก' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $emp->emp_code ?: '-' }}</span>
                                </td>
                                <td>{{ $emp->emp_phone }}</td>
                                <td>{{ $factories->firstWhere('id', $emp->emp_factory_id)?->customer_name ?? '-' }}</td>
                                <td>
                                    @php
                                        $status = $statusOptions->firstWhere('id', $emp->emp_status)?->value ?? 'ไม่ระบุ';
                                        $statusClass = match($status) {
                                            'ทำงาน' => 'success',
                                            'ลาออก' => 'danger', 
                                            'พักงาน' => 'warning',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }}">{{ $status }}</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('employees.edit', $emp->id) }}" 
                                       class="btn btn-sm btn-outline-primary me-1">
                                        แก้ไข
                                    </a>
                                    <button type="button"
                                            onclick="if (confirm('ต้องการลบพนักงาน {{ $emp->emp_name }} หรือไม่?')) { @this.call('delete', {{ $emp->id }}) }"
                                            class="btn btn-sm btn-outline-danger">
                                        ลบ
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    😔 ไม่พบข้อมูลพนักงาน
                                    <br><small>ลองค้นหาด้วยคำอื่น หรือเพิ่มพนักงานใหม่</small>
                                </td>
                            </tr>
                        @endforelse
                        
                    </tbody>
                            </table>
                        </div>

                        <!-- Simple Pagination -->
                        @if($employees->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                            <div class="text-muted">
                                แสดง {{ $employees->firstItem() }}-{{ $employees->lastItem() }} 
                                จาก {{ $employees->total() }} รายการ
                            </div>
                            <div>
                                {{ $employees->links() }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
