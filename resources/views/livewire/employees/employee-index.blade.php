<div>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="text" wire:model.live.debounce.500ms="search" class="form-control"
                            placeholder="ค้นหาชื่อ / รหัส / เบอร์โทร">
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="{{ route('employees.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> เพิ่มพนักงาน
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">

                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>ชื่อพนักงาน</th>
                            <th>รหัสพนักงาน</th>
                            <th>เบอร์โทร</th>
                            <th>โรงงาน</th>
                            <th>สถานะ</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($employees as $index => $emp)
                            <tr>
                                <td>{{ $employees->firstItem() + $index }}</td>
                                <td>{{ $emp->emp_name }}</td>
                                <td>{{ $emp->emp_code }}</td>
                                <td>{{ $emp->emp_phone }}</td>
                                <td>{{ $factories->firstWhere('id', $emp->emp_factory_id)?->customer_name }}</td>
                                <td>
                                    {{ $statusOptions->firstWhere('id', $emp->emp_status)?->value ?? '-' }}
                                </td>
                                <td>
                                    <a href="{{ route('employees.edit', $emp->id) }}"
                                        class="btn btn-sm btn-warning btn-sm">แก้ไข</a>

                                        <button type="button"
                                            onclick="if (confirm('ยืนยันการลบ?')) { @this.call('delete', {{ $emp->id }}) }"
                                            class="btn btn-sm btn-danger btn-sm">
                                          <i class="fa fa-edit"></i>  ลบ
                                        </button>


                                        
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">ไม่พบข้อมูล</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        แสดง {{ $employees->firstItem() }} ถึง {{ $employees->lastItem() }} จาก
                        {{ $employees->total() }}
                        รายการ
                    </div>
                    <div>
                        {{ $employees->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    window.addEventListener('confirm-delete', event => {
        if (confirm('คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?')) {
            Livewire.dispatch('delete-employee', { id: event.detail.id });
        }
    });
</script>

</div>
