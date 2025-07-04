<div>
<div class="modern-report">
    <div class="container-fluid py-3">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="d-flex align-items-center">
                <div class="report-icon-circle mr-3">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <div>
                    <h1 class="h4 mb-1 font-weight-normal">{{ $title }}</h1>
                    <p class="text-muted mb-0 small">รายงานข้อมูลพนักงานตามบริษัท</p>
                </div>
                @if($showPreview && count($previewData) > 0)
                <div class="ml-3">
                    <span class="report-badge">
                        <i class="fas fa-database mr-1"></i> {{ count($previewData) }} รายการ
                    </span>
                </div>
                @endif
            </div>
            <div class="d-flex align-items-center">
                <span wire:loading class="mr-3">
                    <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                    <span class="ml-1 text-muted">กำลังประมวลผล...</span>
                </span>
                <a href="#" class="btn btn-light btn-with-icon" onclick="window.print()">
                    <i class="fas fa-print"></i> พิมพ์รายงาน
                </a>
            </div>
        </div>

        @if (session()->has('error'))
        <div class="notification notification-error">
            <div class="notification-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="notification-content">
                {{ session('error') }}
            </div>
            <button type="button" class="notification-close" data-bs-dismiss="alert" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        @endif
        
        @if (session()->has('success'))
        <div class="notification notification-success">
            <div class="notification-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="notification-content">
                {{ session('success') }}
            </div>
            <button type="button" class="notification-close" data-bs-dismiss="alert" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        @endif

        <div class="filter-section mb-4">
            <div class="filter-header">
                <div class="filter-title">
                    <i class="fas fa-filter"></i> ตัวกรอง
                </div>
                <button type="button" class="btn btn-light btn-sm" wire:click="resetFilters" wire:loading.attr="disabled">
                    <i class="fas fa-redo"></i> รีเซ็ต
                </button>
            </div>
            
            <div class="filter-body">
                <div class="filter-row">
                    <div class="filter-col filter-col-lg" wire:ignore>
                        <div class="form-group">
                            <label for="selected_customer_ids">
                                บริษัท 
                                @if(count($selected_customer_ids) > 0)
                                <span class="selected-count">{{ count($selected_customer_ids) }}</span>
                                @endif
                            </label>
                            <select class="form-control select2-custom" id="selected_customer_ids" name="selected_customer_ids" multiple wire:model="selected_customer_ids">
                                @foreach($customerList as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                                @endforeach
                            </select>
                            <div class="form-hint">เลือกบริษัทที่ต้องการดูข้อมูล</div>
                        </div>
                    </div>

                    <div class="filter-col filter-col-sm">
                        <div class="form-group">
                            <label for="employee_status">สถานะพนักงาน</label>
                            <select class="form-control" id="employee_status" wire:model="employee_status">
                                <option value="">ทั้งหมด</option>
                                @foreach($employeeStatuses as $status)
                                    <option value="{{ $status->id }}">{{ $status->value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="filter-col">
                        <div class="form-group">
                            <label for="date_from">วันที่เริ่มต้น</label>
                            <input type="date" class="form-control" id="date_from" wire:model="date_from">
                        </div>
                    </div>
                    
                    <div class="filter-col">
                        <div class="form-group">
                            <label for="date_to">วันที่สิ้นสุด</label>
                            <input type="date" class="form-control" id="date_to" wire:model="date_to">
                        </div>
                    </div>
                </div>

                <div class="action-buttons">
                    <button type="button" class="btn btn-primary btn-with-icon" wire:click="preview" wire:loading.attr="disabled">
                        <i class="fas fa-search"></i> แสดงข้อมูล
                        <span wire:loading wire:target="preview" class="button-loader"></span>
                    </button>
                    
                    <button type="button" class="btn btn-success btn-with-icon" wire:click="export" 
                            @if(!$showPreview) disabled @endif wire:loading.attr="disabled">
                        <i class="fas fa-file-excel"></i> ส่งออก Excel
                        <span wire:loading wire:target="export" class="button-loader"></span>
                    </button>
                    
                    <button type="button" class="btn btn-light btn-with-icon" wire:click="debugRelationship" wire:loading.attr="disabled">
                        <i class="fas fa-bug"></i> ตรวจสอบข้อมูล
                        <span wire:loading wire:target="debugRelationship" class="button-loader"></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Preview Results -->
        @if($showPreview)
        <div class="data-table-section">
            <div class="data-table-header">
                <div class="data-table-title">
                    <i class="fas fa-table"></i> ข้อมูลรายงาน
                </div>
                <div class="data-table-count">{{ count($previewData) }} รายการ</div>
            </div>
            <div class="data-table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th class="column-sm">#</th>
                            <th class="column-lg">ชื่อบริษัท</th>
                            <th>
                                <div class="column-header">
                                    <i class="fas fa-users text-primary"></i>
                                    <span>พนักงานทั้งหมด</span>
                                </div>
                            </th>
                            <th>
                                <div class="column-header">
                                    <i class="fas fa-user-check text-success"></i>
                                    <span>ทำงานอยู่</span>
                                </div>
                            </th>
                            <th>
                                <div class="column-header">
                                    <i class="fas fa-user-times text-danger"></i>
                                    <span>ลาออกแล้ว</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($previewData as $index => $customer)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <div class="customer-name">
                                        <i class="fas fa-building text-muted"></i>
                                        <span>{{ $customer['customer_name'] }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="count-badge count-primary">{{ $customer['employee_count'] }}</div>
                                    @if(isset($customer['debug_statuses']))
                                        <small class="count-detail">({{ implode(', ', $customer['debug_statuses'] ?: ['ไม่มี']) }})</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="count-badge count-success">{{ $customer['active_count'] }}</div>
                                </td>
                                <td class="text-center">
                                    <div class="count-badge count-danger">{{ $customer['inactive_count'] }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-data">
                                    <div class="empty-data-icon">
                                        <i class="fas fa-search"></i>
                                    </div>
                                    <h5>ไม่พบข้อมูล</h5>
                                    <p>กรุณาปรับเปลี่ยนเงื่อนไขการค้นหาและลองอีกครั้ง</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if(count($previewData) > 0)
                    <tfoot>
                        <tr class="total-row">
                            <td colspan="2" class="text-right">รวมทั้งหมด:</td>
                            <td class="text-center">
                                <div class="total-count total-primary">{{ $previewData->sum('employee_count') }}</div>
                            </td>
                            <td class="text-center">
                                <div class="total-count total-success">{{ $previewData->sum('active_count') }}</div>
                            </td>
                            <td class="text-center">
                                <div class="total-count total-danger">{{ $previewData->sum('inactive_count') }}</div>
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
        @endif

        @if(session()->has('debug_info'))
        <!-- Debug Information -->
        <div class="debug-section">
            <div class="debug-header" onclick="toggleDebugInfo()">
                <div class="debug-title">
                    <i class="fas fa-bug"></i> ข้อมูลสำหรับตรวจสอบ
                </div>
                <div class="debug-toggle">
                    <i class="fas fa-chevron-down" id="debugToggleIcon"></i>
                </div>
            </div>
            
            <div class="debug-content" id="debugInfo" style="display: none;">
                <div class="stat-cards">
                    <div class="stat-card stat-primary">
                        <div class="stat-card-content">
                            <div class="stat-card-title">จำนวนบริษัท</div>
                            <div class="stat-card-value">{{ session('debug_info')['customers_count'] }}</div>
                        </div>
                        <div class="stat-card-icon">
                            <i class="fas fa-building"></i>
                        </div>
                    </div>
                    
                    <div class="stat-card stat-success">
                        <div class="stat-card-content">
                            <div class="stat-card-title">พนักงานทั้งหมด</div>
                            <div class="stat-card-value">{{ session('debug_info')['employees_count'] }}</div>
                        </div>
                        <div class="stat-card-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    
                    <div class="stat-card stat-info">
                        <div class="stat-card-content">
                            <div class="stat-card-title">พนักงานที่เชื่อมโยงกับบริษัท</div>
                            <div class="stat-card-value">{{ session('debug_info')['linked_employees_count'] }}</div>
                        </div>
                        <div class="stat-card-icon">
                            <i class="fas fa-link"></i>
                        </div>
                    </div>
                </div>

                <div class="debug-tables">
                    <div class="debug-table">
                        <div class="debug-table-header">
                            <i class="fas fa-user-tag"></i> สถานะพนักงาน
                        </div>
                        <div class="debug-table-content">
                            <table class="debug-data-table">
                                <thead>
                                    <tr>
                                        <th>สถานะ</th>
                                        <th>จำนวน</th>
                                        <th>สัดส่วน</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $totalEmployees = session('debug_info')['employees_count']; @endphp
                                    @foreach(session('debug_info')['employee_status_counts'] as $status)
                                    <tr>
                                        <td>{{ $status->emp_status }}</td>
                                        <td class="text-center">
                                            <div class="status-count">{{ $status->count }}</div>
                                        </td>
                                        <td>
                                            @php $percentage = $totalEmployees > 0 ? ($status->count / $totalEmployees) * 100 : 0; @endphp
                                            <div class="progress-container">
                                                <div class="progress-bar" style="width: {{ $percentage }}%;"></div>
                                            </div>
                                            <div class="progress-value">{{ number_format($percentage, 1) }}%</div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="debug-table">
                        <div class="debug-table-header">
                            <i class="fas fa-building"></i> ตัวอย่างบริษัท (5 บริษัทแรก)
                        </div>
                        <div class="debug-table-content">
                            <table class="debug-data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>ชื่อบริษัท</th>
                                        <th>จำนวนพนักงาน</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(session('debug_info')['sample_customers'] as $customer)
                                    <tr>
                                        <td>{{ $customer->id }}</td>
                                        <td>{{ $customer->customer_name }}</td>
                                        <td class="text-center">
                                            <div class="employee-count">{{ $customer->employee_count }}</div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="debug-table full-width">
                        <div class="debug-table-header">
                            <i class="fas fa-id-card"></i> ตัวอย่างพนักงาน (5 คนแรก)
                        </div>
                        <div class="debug-table-content">
                            <table class="debug-data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>ชื่อ</th>
                                        <th>รหัสบริษัท</th>
                                        <th>สถานะ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(session('debug_info')['sample_employees'] as $employee)
                                    <tr>
                                        <td>{{ $employee->id }}</td>
                                        <td>{{ $employee->emp_name }}</td>
                                        <td>
                                            <div class="employee-code">{{ $employee->emp_factory_id }}</div>
                                        </td>
                                        <td>
                                            @if($employee->emp_status == 'ทำงานอยู่')
                                                <div class="status-badge status-active">{{ $employee->emp_status }}</div>
                                            @elseif($employee->emp_status == 'ลาออก')
                                                <div class="status-badge status-inactive">{{ $employee->emp_status }}</div>
                                            @else
                                                <div class="status-badge">{{ $employee->emp_status }}</div>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>


<style>
    :root {
        --color-primary: #3b7ddd;
        --color-success: #28a745;
        --color-danger: #dc3545;
        --color-warning: #ffc107;
        --color-info: #17a2b8;
        --color-bg-light: #f8f9fa;
        --color-border: #e9ecef;
        --color-text: #495057;
        --color-text-muted: #6c757d;
        --border-radius: 4px;
        --box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .modern-report {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        color: var(--color-text);
    }
    
    /* Header Styles */
    .report-icon-circle {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        background-color: var(--color-primary);
        color: white;
        border-radius: 50%;
        font-size: 20px;
    }
    
    .report-badge {
        display: inline-flex;
        align-items: center;
        background-color: var(--color-bg-light);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 14px;
        color: var(--color-primary);
        font-weight: 500;
    }
    
    /* Button Styles */
    .btn {
        border-radius: var(--border-radius);
        font-weight: 500;
        padding: 8px 16px;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-primary {
        background-color: var(--color-primary);
        color: white;
    }
    
    .btn-success {
        background-color: var(--color-success);
        color: white;
    }
    
    .btn-light {
        background-color: var(--color-bg-light);
        color: var(--color-text);
        border: 1px solid var(--color-border);
    }
    
    .btn-with-icon {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-with-icon i {
        font-size: 16px;
    }
    
    .button-loader {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 1s linear infinite;
        margin-left: 8px;
    }
    
    /* Notification Styles */
    .notification {
        display: flex;
        align-items: center;
        padding: 16px;
        border-radius: var(--border-radius);
        margin-bottom: 20px;
        position: relative;
    }
    
    .notification-error {
        background-color: rgba(220, 53, 69, 0.1);
        border-left: 4px solid var(--color-danger);
    }
    
    .notification-success {
        background-color: rgba(40, 167, 69, 0.1);
        border-left: 4px solid var(--color-success);
    }
    
    .notification-icon {
        font-size: 20px;
        margin-right: 16px;
    }
    
    .notification-error .notification-icon {
        color: var(--color-danger);
    }
    
    .notification-success .notification-icon {
        color: var(--color-success);
    }
    
    .notification-content {
        flex-grow: 1;
    }
    
    .notification-close {
        background: none;
        border: none;
        color: var(--color-text-muted);
        cursor: pointer;
        font-size: 14px;
    }
    
    /* Filter Section */
    .filter-section {
        background-color: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
    }
    
    .filter-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        border-bottom: 1px solid var(--color-border);
    }
    
    .filter-title {
        font-size: 16px;
        font-weight: 500;
        color: var(--color-primary);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .filter-body {
        padding: 20px;
    }
    
    .filter-row {
        display: flex;
        flex-wrap: wrap;
        margin: -8px;
    }
    
    .filter-col {
        padding: 8px;
        flex: 1 1 220px;
    }
    
    .filter-col-lg {
        flex: 2 1 320px;
    }
    
    .filter-col-sm {
        flex: 0 1 180px;
    }
    
    /* Form Controls */
    .form-group {
        margin-bottom: 16px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 6px;
        color: var(--color-text);
        font-weight: 500;
        font-size: 14px;
    }
    
    .form-control {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid var(--color-border);
        border-radius: var(--border-radius);
        font-size: 14px;
        color: var(--color-text);
        background-color: white;
        transition: border-color 0.15s ease-in-out;
    }
    
    .form-control:focus {
        border-color: var(--color-primary);
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(59, 125, 221, 0.25);
    }
    
    .form-hint {
        font-size: 12px;
        color: var(--color-text-muted);
        margin-top: 4px;
    }
    
    .selected-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 20px;
        height: 20px;
        background-color: var(--color-primary);
        color: white;
        border-radius: 50%;
        font-size: 12px;
        margin-left: 4px;
    }
    
    .action-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 20px;
    }
    
    /* Table Styles */
    .data-table-section {
        background-color: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        margin-bottom: 24px;
    }
    
    .data-table-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        border-bottom: 1px solid var(--color-border);
    }
    
    .data-table-title {
        font-size: 16px;
        font-weight: 500;
        color: var(--color-primary);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .data-table-count {
        background-color: var(--color-bg-light);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
    }
    
    .data-table-container {
        overflow-x: auto;
    }
    
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .data-table th,
    .data-table td {
        padding: 12px 16px;
        text-align: left;
        border-bottom: 1px solid var(--color-border);
    }
    
    .data-table thead th {
        background-color: var(--color-bg-light);
        font-weight: 500;
        color: var(--color-text-muted);
        white-space: nowrap;
    }
    
    .data-table tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
    
    .column-header {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .column-sm {
        width: 50px;
    }
    
    .column-lg {
        min-width: 250px;
    }
    
    .customer-name {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 500;
    }
    
    .count-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 30px;
        height: 24px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 500;
        padding: 0 10px;
    }
    
    .count-primary {
        background-color: rgba(59, 125, 221, 0.1);
        color: var(--color-primary);
    }
    
    .count-success {
        background-color: rgba(40, 167, 69, 0.1);
        color: var(--color-success);
    }
    
    .count-danger {
        background-color: rgba(220, 53, 69, 0.1);
        color: var(--color-danger);
    }
    
    .count-detail {
        display: block;
        color: var(--color-text-muted);
        font-size: 12px;
        margin-top: 4px;
    }
    
    .empty-data {
        padding: 40px 20px;
        text-align: center;
    }
    
    .empty-data-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 64px;
        height: 64px;
        background-color: var(--color-bg-light);
        border-radius: 50%;
        margin: 0 auto 16px;
        font-size: 24px;
        color: var(--color-text-muted);
    }
    
    .empty-data h5 {
        font-weight: 500;
        margin-bottom: 8px;
    }
    
    .empty-data p {
        color: var(--color-text-muted);
        max-width: 300px;
        margin: 0 auto;
    }
    
    .total-row {
        background-color: var(--color-bg-light);
        font-weight: 500;
    }
    
    .total-count {
        font-weight: 700;
        font-size: 16px;
    }
    
    .total-primary {
        color: var(--color-primary);
    }
    
    .total-success {
        color: var(--color-success);
    }
    
    .total-danger {
        color: var(--color-danger);
    }
    
    /* Debug Section */
    .debug-section {
        background-color: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        margin-bottom: 24px;
        overflow: hidden;
    }
    
    .debug-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        border-bottom: 1px solid var(--color-border);
        cursor: pointer;
    }
    
    .debug-title {
        font-size: 16px;
        font-weight: 500;
        color: var(--color-danger);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .debug-content {
        padding: 20px;
    }
    
    .stat-cards {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
    }
    
    .stat-card {
        flex: 1 1 250px;
        background-color: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        padding: 16px;
        display: flex;
        align-items: center;
        overflow: hidden;
        position: relative;
    }
    
    .stat-card::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
    }
    
    .stat-primary::before {
        background-color: var(--color-primary);
    }
    
    .stat-success::before {
        background-color: var(--color-success);
    }
    
    .stat-info::before {
        background-color: var(--color-info);
    }
    
    .stat-card-content {
        flex-grow: 1;
    }
    
    .stat-card-title {
        color: var(--color-text-muted);
        font-size: 14px;
        margin-bottom: 4px;
    }
    
    .stat-card-value {
        font-size: 24px;
        font-weight: 700;
    }
    
    .stat-card-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: rgba(0, 0, 0, 0.1);
    }
    
    .debug-tables {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
    }
    
    .debug-table {
        flex: 1 1 calc(50% - 8px);
        background-color: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        overflow: hidden;
        margin-bottom: 16px;
    }
    
    .full-width {
        flex: 1 1 100%;
    }
    
    .debug-table-header {
        background-color: var(--color-bg-light);
        padding: 12px 16px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
        border-bottom: 1px solid var(--color-border);
    }
    
    .debug-table-content {
        padding: 16px;
        overflow-x: auto;
    }
    
    .debug-data-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .debug-data-table th,
    .debug-data-table td {
        padding: 8px 12px;
        text-align: left;
        border-bottom: 1px solid var(--color-border);
    }
    
    .debug-data-table th {
        font-weight: 500;
        color: var(--color-text-muted);
    }
    
    .progress-container {
        height: 6px;
        background-color: var(--color-bg-light);
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: 4px;
    }
    
    .progress-bar {
        height: 100%;
        background-color: var(--color-primary);
    }
    
    .progress-value {
        font-size: 12px;
        color: var(--color-text-muted);
        text-align: right;
    }
    
    .status-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 24px;
        height: 24px;
        background-color: rgba(59, 125, 221, 0.1);
        color: var(--color-primary);
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
        padding: 0 8px;
    }
    
    .employee-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 24px;
        height: 24px;
        background-color: rgba(40, 167, 69, 0.1);
        color: var(--color-success);
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
        padding: 0 8px;
    }
    
    .employee-code {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 24px;
        height: 24px;
        background-color: rgba(23, 162, 184, 0.1);
        color: var(--color-info);
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
        padding: 0 8px;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 24px;
        height: 24px;
        background-color: rgba(108, 117, 125, 0.1);
        color: var(--color-text-muted);
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
        padding: 0 8px;
    }
    
    .status-active {
        background-color: rgba(40, 167, 69, 0.1);
        color: var(--color-success);
    }
    
    .status-inactive {
        background-color: rgba(220, 53, 69, 0.1);
        color: var(--color-danger);
    }
    
    /* Select2 Customization */
    .select2-container--default .select2-selection--multiple {
        border: 1px solid var(--color-border);
        border-radius: var(--border-radius);
    }
    
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: var(--color-bg-light);
        border: 1px solid var(--color-border);
        border-radius: var(--border-radius);
        padding: 2px 8px;
        margin: 3px;
        font-size: 13px;
        color: var(--color-text);
        display: flex;
        align-items: center;
    }
    
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: var(--color-text-muted);
        margin-right: 5px;
        order: -1;
    }
    
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: var(--color-primary);
    }
    
    .select2-container--default .select2-search--dropdown .select2-search__field {
        border: 1px solid var(--color-border);
        border-radius: var(--border-radius);
        padding: 8px;
    }
    
    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: var(--color-bg-light);
    }
    
    .select2-dropdown {
        border-color: var(--color-border);
        box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        border-radius: var(--border-radius);
    }
    
    .select2-container--open .select2-dropdown--below {
        margin-top: 3px;
    }
    
    /* Animations */
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .filter-col {
            flex: 1 1 100%;
        }
        
        .stat-card {
            flex: 1 1 100%;
        }
        
        .debug-table {
            flex: 1 1 100%;
        }
        
        .action-buttons {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .action-buttons .btn {
            width: 100%;
            margin-bottom: 8px;
        }
    }
</style>

<script>
    document.addEventListener('livewire:initialized', function () {
        setTimeout(function() {
            initSelect2();
            // Pre-select values from wire:model after initialization
            let customerIds = @this.get('selected_customer_ids') || [];
            if (customerIds.length) {
                $('#selected_customer_ids').val(customerIds).trigger('change');
            }
        }, 100);
    });

    // Listen for filter reset and reinitialize Select2
    document.addEventListener('livewire:updated', function () {
        setTimeout(function() {
            initSelect2();
        }, 100);
    });

    function initSelect2() {
        $('.select2-custom').select2({
            placeholder: "เลือกบริษัทที่ต้องการ",
            allowClear: true,
            width: '100%',
            closeOnSelect: false,
            templateResult: formatCompanyOption,
            templateSelection: formatCompanySelection,
            dropdownCssClass: "select-dropdown",
            selectionCssClass: "select-selection"
        }).on('change', function (e) {
            let data = $(this).val();
            @this.set($(this).attr('wire:model'), data);
        });
    }

    // Format each option in the dropdown
    function formatCompanyOption(company) {
        if (!company.id) return company.text;
        
        var $option = $(
            '<div class="company-option">' +
                '<i class="fas fa-building mr-2"></i>' +
                '<span>' + company.text + '</span>' +
            '</div>'
        );
        
        return $option;
    }
    
    // Format selected options
    function formatCompanySelection(company) {
        if (!company.id) return company.text;
        return $(
            '<span><i class="fas fa-building mr-1"></i> ' + company.text + '</span>'
        );
    }

    function toggleDebugInfo() {
        const debugInfo = document.getElementById('debugInfo');
        const toggleIcon = document.getElementById('debugToggleIcon');
        
        if (debugInfo.style.display === 'none') {
            debugInfo.style.display = 'block';
            toggleIcon.classList.remove('fa-chevron-down');
            toggleIcon.classList.add('fa-chevron-up');
        } else {
            debugInfo.style.display = 'none';
            toggleIcon.classList.remove('fa-chevron-up');
            toggleIcon.classList.add('fa-chevron-down');
        }
    }

    // Add fade effect to alerts
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.classList.add('fade');
                setTimeout(function() {
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.remove();
                    }, 500);
                }, 5000);
            });
        }, 3000);
    });
</script>

