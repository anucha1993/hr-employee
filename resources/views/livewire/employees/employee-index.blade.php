<div>
    <style>
        /* Fix Select2 search box and dropdown z-index issues */
        .select2-container--open .select2-dropdown {
            z-index: 10000 !important;
        }
        .select2-container {
            width: 100% !important;
        }
        .select2-container--bootstrap-5 .select2-selection--single {
            height: calc(1.5em + 0.75rem + 2px);
            padding-top: 0.375rem;
        }
        .select2-search--dropdown .select2-search__field {
            width: 100% !important;
            padding: 8px !important;
            display: block !important;
        }
        .select2-container--bootstrap-5 .select2-dropdown .select2-search .select2-search__field:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            border-color: #86b7fe;
        }
        .select2-container--bootstrap-5 .select2-selection {
            border-color: #dee2e6;
        }
        .select2-dropdown {
            border-color: #86b7fe;
        }
        /* Ensure the search box is fully visible */
        .select2-search {
            display: block !important;
            padding: 8px !important;
        }
    </style>
                             
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
                        

                        <!-- Status Statistics -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card border-0 bg-light">
                                    <div class="card-body py-3">
                                        <h5 class="mb-3 fw-bold text-dark"><i class="mdi mdi-chart-pie me-1"></i> สถิติพนักงานตามสถานะ</h5>
                                        <div class="row g-3">
                                            @php
                                                // Group employees by status
                                                $statusGroups = $allEmployeesForStats->groupBy('emp_status');
                                                $totalEmployees = $allEmployeesForStats->count();
                                                
                                                // Status colors matching our status badges
                                                $statusColors = [
                                                    'เริ่มงาน' => 'success',
                                                    'ลาออก' => 'danger',
                                                    'รอสัมภาษณ์' => 'warning',
                                                ];
                                            @endphp
                                            
                                            <!-- Total Employees Card -->
                                            <div class="col-md-3">
                                                <div class="card border-0 shadow-sm h-100">
                                                    <div class="card-body p-3 d-flex align-items-center">
                                                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-3"
                                                             style="width: 48px; height: 48px;">
                                                            <i class="mdi mdi-account-group text-white fs-4"></i>
                                                        </div>
                                                        <div>
                                                            <h3 class="mb-0 fw-bold">{{ $totalEmployees }}</h3>
                                                            <div class="text-muted">พนักงานทั้งหมด</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Status-specific cards -->
                                            @foreach($statusOptions as $statusOption)
                                                @php
                                                    $statusId = $statusOption->id;
                                                    $statusName = $statusOption->value;
                                                    $count = $statusGroups->has($statusId) ? $statusGroups[$statusId]->count() : 0;
                                                    $percentage = $totalEmployees > 0 ? round(($count / $totalEmployees) * 100) : 0;
                                                    $colorClass = $statusColors[$statusName] ?? 'secondary';
                                                @endphp
                                                <div class="col-md-3">
                                                    <div class="card border-0 shadow-sm h-100">
                                                        <div class="card-body p-3">
                                                            <div class="d-flex align-items-center">
                                                                <div class="rounded-circle bg-{{ $colorClass }} d-flex align-items-center justify-content-center me-3"
                                                                     style="width: 48px; height: 48px;">
                                                                    <i class="mdi mdi-account-circle text-white fs-4"></i>
                                                                </div>
                                                                <div>
                                                                    <h3 class="mb-0 fw-bold">{{ $count }}</h3>
                                                                    <div class="d-flex align-items-center">
                                                                        <span class="badge bg-{{ $colorClass }} me-2">{{ $statusName }}</span>
                                                                        <span class="text-muted">{{ $percentage }}%</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="input-group input-group-lg">
                                    <input type="text" wire:model.live.debounce.500ms="search" 
                                           class="form-control"
                                           placeholder="🔍 ค้นหาชื่อ, รหัสพนักงาน, หรือเบอร์โทร...">
                                    <button class="btn btn-outline-secondary" type="button" wire:click="toggleFilter">
                                        <i class="mdi mdi-filter-outline"></i> ตัวกรอง
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6 text-end">
                                <div class="btn-group">
                                    @can('create employee')
                                    <a href="{{ route('employees.create') }}" class="btn btn-primary btn-lg">
                                        <i class="mdi mdi-account-plus"></i> เพิ่มพนักงาน
                                    </a>
                                    @endcan
                                     @can('export report')
                                    <button type="button" wire:click="exportExcel" class="btn btn-success btn-lg">
                                        <i class="mdi mdi-microsoft-excel"></i> ส่งออก Excel
                                    </button>
                                    @endcan
                                </div>
                            </div>
                        </div>
                        
                        <!-- Filter Form -->
                        @if($isFilterOpen)
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card border shadow-sm">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0"><i class="mdi mdi-filter"></i> ตัวกรองข้อมูล</h5>
                                    </div>
                                    <div class="card-body">
                                        <form wire:submit.prevent="render">
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label class="form-label">สถานะ</label>
                                                    <select wire:model.live="filter_status" class="form-select">
                                                        <option value="">-- ทุกสถานะ --</option>
                                                        @foreach($statusOptions as $status)
                                                            <option value="{{ $status->id }}">{{ $status->value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">โรงงาน</label>
                                                    <div wire:ignore>
                                                        <select id="select2-factory" class="form-select select2" data-placeholder="-- ทุกโรงงาน --">
                                                            <option value=""></option>
                                                            @foreach($factories as $factory)
                                                                <option value="{{ $factory->id }}" {{ $filter_factory == $factory->id ? 'selected' : '' }}>
                                                                    {{ $factory->customer_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">แผนก</label>
                                                    <div wire:ignore>
                                                        <select id="select2-department" class="form-select select2" data-placeholder="-- ทุกแผนก --">
                                                            <option value=""></option>
                                                            @foreach($departments as $dept)
                                                                <option value="{{ $dept }}" {{ $filter_department == $dept ? 'selected' : '' }}>
                                                                    {{ $dept }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">อายุตั้งแต่</label>
                                                    <input type="number" wire:model.live="filter_age_from" class="form-control" placeholder="อายุจาก...">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">อายุถึง</label>
                                                    <input type="number" wire:model.live="filter_age_to" class="form-control" placeholder="อายุถึง...">
                                                </div>
                                                <div class="col-md-4 d-flex align-items-end">
                                                    <button type="button" wire:click="resetFilters" class="btn btn-outline-secondary w-100">
                                                        <i class="mdi mdi-refresh"></i> ล้างตัวกรอง
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Styled Table -->
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" style="font-size: 15px;">
                                <thead class="table-light">
                                    <tr>
                                        <th width="30" class="text-muted">#</th>
                                        <th width="230">ชื่อพนักงาน</th>
                                        <th width="50" class="text-center">อายุ</th>
                                        <th width="150">ข้อมูลติดต่อ</th>
                                        <th width="200">ที่อยู่</th>
                                        <th width="140">โรงงาน</th>
                                        <th width="90">สถานะ</th>
                                        <th width="100" class="text-center">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <tbody>
                        @forelse ($employees as $index => $emp)
                            <tr class="align-middle">
                                <td class="text-muted">{{ $employees->firstItem() + $index }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle text-white d-flex align-items-center justify-content-center me-2"
                                             style="min-width: 40px; height: 40px; font-size: 16px; background-color: #2196F3;">
                                            {{ mb_strtoupper(mb_substr($emp->emp_name, 0, 2, 'UTF-8'), 'UTF-8') }}
                                        </div>
                                        <div>
                                            <div class="fw-bold fs-5">{{ $emp->emp_name }}</div>
                                            <div class="d-flex align-items-center text-secondary">
                                                <i class="mdi mdi-card-account-details-outline"></i>
                                                <span class="ms-1 badge bg-light text-dark">{{ $emp->emp_code ?: '-' }}</span>
                                                @if($emp->emp_department)
                                                <span class="ms-1">{{ $emp->emp_department }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @php
                                        $birthdate = $emp->emp_birthdate ? \Carbon\Carbon::parse($emp->emp_birthdate) : null;
                                        $age = $birthdate ? $birthdate->age : '-';
                                    @endphp
                                    <span class="badge rounded-pill bg-light text-dark fs-6">{{ $age }} ปี</span>
                                </td>
                                <td>
                                    <div>
                                        <div><i class="mdi mdi-phone text-success"></i><span class="ms-1">{{ $emp->emp_phone }}</span></div>
                                        @if($emp->emp_email)
                                        <div class="mt-1"><i class="mdi mdi-email-outline text-primary"></i><span class="ms-1">{{ Str::limit($emp->emp_email, 20) }}</span></div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="text-muted">
                                        <i class="mdi mdi-map-marker text-danger"></i>
                                        <span class="ms-1">{{ Str::limit($emp->current_address_details ?? $emp->emp_address_current ?? '-', 38) }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if($emp->emp_factory_id)
                                        <span class="badge rounded-pill" style="background-color: #03A9F4; font-weight: normal; padding: 6px 12px; font-size: 14px;">
                                            {{ $factories->firstWhere('id', $emp->emp_factory_id)?->customer_name ?? '-' }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $status = $statusOptions->firstWhere('id', $emp->emp_status)?->value ?? 'ไม่ระบุ';
                                        $statusClass = match($status) {
                                            'เริ่มงาน' => 'success',
                                            'ลาออก' => 'danger',
                                            'รอสัมภาษณ์' => 'warning',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }} fs-6">{{ $status }}</span>
                                </td>
                                <td>
                                    @can('edit employee')
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('employees.edit', $emp->id) }}" 
                                           class="btn btn-outline-primary">
                                           <i class="mdi mdi-pencil"></i>
                                        </a>
                                        @endcan
                                         @can('edit employee')
                                        <button type="button"
                                                onclick="if (confirm('ต้องการลบพนักงาน {{ $emp->emp_name }} หรือไม่?')) { @this.call('delete', {{ $emp->id }}) }"
                                                class="btn btn-outline-danger">
                                                <i class="mdi mdi-delete"></i>
                                        </button>
                                         @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    😔 ไม่พบข้อมูลพนักงาน
                                    <br><small>ลองค้นหาด้วยคำอื่น หรือลองล้างตัวกรองข้อมูล</small>
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
                                  {{ $employees->links('pagination::bootstrap-5') }}
                   
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        console.log('DOM Content Loaded - Initializing Select2');
        
        // Function to properly initialize Select2
        function initializeSelect2() {
            console.log('Initializing Select2 Components');
            
            // Make sure jQuery and Select2 are available
            if (typeof $ === 'undefined' || typeof $.fn.select2 === 'undefined') {
                console.error('jQuery or Select2 is not loaded');
                return;
            }
            
            // First destroy any existing instances to prevent duplicates
            try {
                if ($('#select2-factory').length) {
                    $('#select2-factory').select2('destroy');
                }
                
                if ($('#select2-department').length) {
                    $('#select2-department').select2('destroy');
                }
            } catch (e) {
                console.log('Select2 not initialized yet, continuing...');
            }
            
            // Debug info
            console.log('Select2 Factory Element Found:', $('#select2-factory').length > 0);
            console.log('Select2 Department Element Found:', $('#select2-department').length > 0);
            
            // Initialize Select2 for factory dropdown with enhanced debugging
            if ($('#select2-factory').length) {
                console.log('Initializing Factory Select2');
                $('#select2-factory').select2({
                    theme: 'bootstrap-5',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('body'),
                    placeholder: '-- ทุกโรงงาน --',
                }).on('select2:open', function() {
                    console.log('Factory Select2 opened');
                    // Add an ID to the search field for debugging
                    setTimeout(function() {
                        $('.select2-search__field').attr('id', 'factory-search-field');
                        console.log('Search field visible:', $('.select2-search__field').is(':visible'));
                        console.log('Search field width:', $('.select2-search__field').width());
                    }, 100);
                }).on('change', function() {
                    console.log('Factory value changed to:', $(this).val());
                    @this.set('filter_factory', $(this).val() || '');
                });
            }
            
            // Initialize Select2 for department dropdown with enhanced debugging
            if ($('#select2-department').length) {
                console.log('Initializing Department Select2');
                $('#select2-department').select2({
                    theme: 'bootstrap-5',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('body'),
                    placeholder: '-- ทุกแผนก --',
                }).on('select2:open', function() {
                    console.log('Department Select2 opened');
                    // Add an ID to the search field for debugging
                    setTimeout(function() {
                        $('.select2-search__field').attr('id', 'department-search-field');
                        console.log('Search field visible:', $('.select2-search__field').is(':visible'));
                    }, 100);
                }).on('change', function() {
                    console.log('Department value changed to:', $(this).val());
                    @this.set('filter_department', $(this).val() || '');
                });
            }
            
            // Set initial values if they exist
            if (typeof @this !== 'undefined' && @this) {
                var factoryValue = @this.get('filter_factory');
                var departmentValue = @this.get('filter_department');
                
                if (factoryValue) {
                    console.log('Setting initial factory value:', factoryValue);
                    $('#select2-factory').val(factoryValue).trigger('change.select2');
                }
                
                if (departmentValue) {
                    console.log('Setting initial department value:', departmentValue);
                    $('#select2-department').val(departmentValue).trigger('change.select2');
                }
            }
        }
        
        // Initialize on first load
        initializeSelect2();

        // Re-initialize when Livewire updates the DOM
        document.addEventListener('livewire:init', function() {
            console.log('Livewire initialized');
            Livewire.hook('morph.updated', () => {
                console.log('Livewire morph updated, re-initializing Select2');
                setTimeout(initializeSelect2, 100);
            });
        });
        
        // Update when filter opens/closes
        window.addEventListener('filterToggled', event => {
            console.log('Filter toggled event received');
            setTimeout(initializeSelect2, 100);
        });
        
        // Listen for reset event from Livewire
        window.addEventListener('reset-select2', event => {
            console.log('Reset select2 event received');
            $('#select2-factory').val('').trigger('change.select2');
            $('#select2-department').val('').trigger('change.select2');
        });
        
        // Workaround for the dropdowns
        $(document).on('select2:open', () => {
            document.querySelector('.select2-search__field').focus();
        });
    });
</script>
