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

                <div class="card shadow-sm border-0">
                    <!-- Compact Header -->
                    <div class="card-header bg-gradient bg-primary text-white py-2">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="mb-0 fw-bold"><i class="mdi mdi-account-group me-1"></i>พนักงาน</h5>
                            <span class="badge bg-white text-primary">{{ $employees->total() }} คน</span>
                        </div>
                    </div>

                    <div class="card-body p-3">
                        <!-- Search & Add Button -->
                        

                        <!-- Compact Statistics -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="card border bg-light">
                                    <div class="card-body py-2">
                                        <h6 class="mb-2 fw-bold text-dark"><i class="mdi mdi-chart-pie me-1"></i>สถิติ</h6>
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
                                            
                                            <!-- Total -->
                                            <div class="col-6 col-md-3">
                                                <div class="d-flex align-items-center p-2 bg-white rounded border">
                                                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-2"
                                                         style="width: 36px; height: 36px;">
                                                        <i class="mdi mdi-account-group text-white fs-6"></i>
                                                    </div>
                                                    <div>
                                                        <h5 class="mb-0 fw-bold" style="color: #212529;">{{ $totalEmployees }}</h5>
                                                        <small style="color: #6c757d;">ทั้งหมด</small>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Status cards -->
                                            @foreach($statusOptions as $statusOption)
                                                @php
                                                    $statusId = $statusOption->id;
                                                    $statusName = $statusOption->value;
                                                    $count = $statusGroups->has($statusId) ? $statusGroups[$statusId]->count() : 0;
                                                    $percentage = $totalEmployees > 0 ? round(($count / $totalEmployees) * 100) : 0;
                                                    $colorClass = $statusColors[$statusName] ?? 'secondary';
                                                @endphp
                                                <div class="col-6 col-md-3">
                                                    <div class="d-flex align-items-center p-2 bg-white rounded border">
                                                        <div class="rounded-circle bg-{{ $colorClass }} d-flex align-items-center justify-content-center me-2"
                                                             style="width: 36px; height: 36px;">
                                                            <span class="text-white fw-bold fs-6">{{ $count }}</span>
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold" style="color: #212529; font-size: 13px;">{{ $statusName }}</div>
                                                            <small class="text-{{ $colorClass }}">{{ $percentage }}%</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Compact Search & Actions -->
                        <div class="row mb-3">
                            <div class="col-md-7">
                                <div class="input-group">
                                    <input type="text" wire:model.live.debounce.500ms="search" 
                                           class="form-control"
                                           placeholder="🔍 ค้นหา...">
                                    <button class="btn btn-outline-secondary btn-sm" type="button" wire:click="toggleFilter">
                                        <i class="mdi mdi-filter"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-5 text-end">
                                <div class="btn-group btn-group-sm">
                                    @can('create employee')
                                    <a href="{{ route('employees.create') }}" class="btn btn-primary">
                                        <i class="mdi mdi-plus"></i> เพิ่ม
                                    </a>
                                    @endcan
                                     @can('export report')
                                    <button type="button" wire:click="exportExcel" class="btn btn-success">
                                        <i class="mdi mdi-microsoft-excel"></i> Excel
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

                        <!-- Compact Table -->
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle" style="font-size: 14px;">
                                <thead class="table-light">
                                    <tr style="border-bottom: 2px solid #dee2e6;">
                                        <th width="30" style="color: #495057; font-weight: 600;">#</th>
                                        <th width="200" style="color: #212529; font-weight: 600;">ชื่อพนักงาน</th>
                                        <th width="40" class="text-center" style="color: #212529; font-weight: 600;">อายุ</th>
                                        <th width="120" style="color: #212529; font-weight: 600;">ติดต่อ</th>
                                        <th width="180" style="color: #212529; font-weight: 600;">ที่อยู่</th>
                                        <th width="120" style="color: #212529; font-weight: 600;">โรงงาน</th>
                                        <th width="100" style="color: #212529; font-weight: 600;">เจ้าหน้าที่สรรหา</th>
                                        <th width="100" style="color: #212529; font-weight: 600;">วันที่สร้าง</th>
                                        <th width="80">สถานะ</th>
                                        <th width="120" class="text-center">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <tbody>
                        @forelse ($employees as $index => $emp)
                            <tr class="align-middle">
                                <td style="color: #495057; font-weight: 500; font-size: 13px;">{{ $employees->firstItem() + $index }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle text-white d-flex align-items-center justify-content-center me-2"
                                             style="min-width: 32px; height: 32px; font-size: 13px; background-color: #2196F3;">
                                            {{ mb_strtoupper(mb_substr($emp->emp_name, 0, 2, 'UTF-8'), 'UTF-8') }}
                                        </div>
                                        <div>
                                            <div class="fw-bold" style="color: #212529;">{{ $emp->emp_name }}</div>
                                            <div class="d-flex align-items-center" style="color: #6c757d; font-size: 12px;">
                                                <span class="badge bg-light" style="color: #212529; border: 1px solid #dee2e6; font-size: 11px;">{{ $emp->emp_code ?: '-' }}</span>
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
                                    <span class="badge rounded-pill bg-light" style="color: #212529; border: 1px solid #dee2e6; font-weight: 600; font-size: 12px;">{{ $age }}</span>
                                </td>
                                <td>
                                    <div style="color: #212529; font-size: 13px;">
                                        <div><i class="mdi mdi-phone text-success"></i><span class="ms-1 fw-medium">{{ $emp->emp_phone }}</span></div>
                                    </div>
                                </td>
                                <td>
                                    <div style="color: #495057; font-size: 12px;">
                                        <i class="mdi mdi-map-marker text-danger"></i>
                                        <span class="ms-1">{{ Str::limit($emp->full_registered_address, 50) }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if($emp->emp_factory_id)
                                        <span class="badge rounded-pill" style="background-color: #03A9F4; font-weight: 500; padding: 4px 10px; font-size: 12px;">
                                            {{ Str::limit($factories->firstWhere('id', $emp->emp_factory_id)?->customer_name ?? '-', 15) }}
                                        </span>
                                    @else
                                        <span style="color: #6c757d;">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span style="color: #495057; font-size: 12px;">
                                        {{ $emp->recruiter?->value ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <div style="color: #495057; font-size: 12px;">
                                        <div>{{ $emp->created_at ? $emp->created_at->format('d/m/Y') : '-' }}</div>
                                        @if($emp->createdBy)
                                        <small class="text-muted">{{ $emp->createdBy->name }}</small>
                                        @endif
                                    </div>
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
                                <td colspan="10" class="text-center py-4 text-muted">
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
