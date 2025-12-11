<div>
    <br>
    <div class="container-fluid">


        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif


        <div class="card">
            <div class="card-header py-2">
                <h5 class="mb-0" style="color: #212529; font-weight: 600;">รายชื่อลูกค้า</h5>
                <div class="d-flex justify-content-end gap-2">
                  @can('create customer')
                    <a href="{{ route('customer.create') }}" class="btn btn-sm btn-success">
                        <i class="fa fa-plus"></i> เพิ่มลูกค้าใหม่
                    </a>

                     <a href="{{ route('reports.customer-employee') }}" class="btn btn-sm btn-outline-primary">
                                <i class="fa fa-file-excel"></i> ออกรายงานพนักงาน
                            </a>
                    @endcan
                </div>
                
            </div>
            <div class="card-body">

                {{-- Search and Filter Section --}}
                <div class="row mb-3">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text" style="background-color: #f8f9fa;">
                                <i class="fa fa-search" style="color: #495057;"></i>
                            </span>
                            <input type="text" 
                                   wire:model.live.debounce.300ms="search" 
                                   class="form-control" 
                                   placeholder="ค้นหาชื่อบริษัท, เลขผู้เสียภาษี, จังหวัด...">
                            @if($search)
                            <button class="btn btn-outline-secondary" 
                                    wire:click="$set('search', '')" 
                                    type="button">
                                <i class="fa fa-times"></i>
                            </button>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select wire:model.live="statusFilter" class="form-select">
                            <option value="">ทุกสถานะ</option>
                            <option value="active">ใช้งาน</option>
                            <option value="inactive">ไม่ใช้งาน</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select wire:model.live="provinceFilter" class="form-select">
                            <option value="">ทุกจังหวัด</option>
                            @foreach($this->customers->pluck('customer_address_province')->unique()->filter()->sort() as $province)
                                <option value="{{ $province }}">{{ $province }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-bordered table-hover align-middle" style="font-size: 13px;">
                        <thead class="table-light">
                            <tr>
                                <th style="color: #212529; font-weight: 600;">#</th>
                                <th style="color: #212529; font-weight: 600;">ชื่อบริษัท</th>
                                <th style="color: #212529; font-weight: 600;">เลขผู้เสียภาษี</th>
                                <th style="color: #212529; font-weight: 600;">สาขา</th>
                                <th style="color: #212529; font-weight: 600;">จังหวัด</th>
                                <th style="color: #212529; font-weight: 600;">วันที่เหลือ</th>
                                <th style="color: #212529; font-weight: 600;">สถานะ</th>
                                <th style="color: #212529; font-weight: 600;">ดำเนินการ</th>
                            </tr>
                        </thead>
                        <tbody>
                             @forelse ($this->customers as $index => $customer)
                                <tr>
                                    <td style="color: #495057; font-weight: 500;">{{ $index + 1 }}</td>
                                    <td style="color: #212529; font-weight: 600;">{{ $customer->customer_name }}</td>
                                    <td style="color: #495057;">{{ $customer->customer_taxid }}</td>
                                    <td style="color: #495057;">{{ $customer->branch->value }}</td>
                                    <td style="color: #495057;">{{ $customer->customer_address_province }}</td>

                                    <td>
                                        @if ($customer->latestContract)
                                            @php
                                                $remaining = \Carbon\Carbon::parse(
                                                    $customer->latestContract->contract_end_date,
                                                )->diffInDays(now(), false);
                                            @endphp
                                            @if ($remaining < 0)
                                                <span class="badge bg-success" style="font-size: 11px;">เหลือ {{ abs($remaining) }} วัน</span>
                                            @elseif ($remaining === 0)
                                                <span class="badge bg-warning" style="font-size: 11px;">วันสุดท้าย</span>
                                            @else
                                                <span class="badge bg-danger" style="font-size: 11px;">หมดอายุ {{ $remaining }} วัน</span>
                                            @endif
                                        @else
                                            <span style="color: #6c757d;">-</span>
                                        @endif
                                    </td>
                                    <td>{!! getStatusCutomerBadge($customer->customer_status) !!}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                        @can('edit customer')
                                             <a href="{{ route('customer.edit', $customer->id) }}"
                                            class="btn btn-sm btn-warning"> แก้ไข</a>
                                        @endcan
                                        @can('delete customer')
                                        <button type="button"
                                            onclick="if (confirm('ยืนยันการลบ?')) { @this.call('delete', {{ $customer->id }}) }"
                                            class="btn btn-sm btn-danger">
                                           ลบ
                                        </button>
                                         @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">ไม่พบข้อมูลลูกค้า</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                  <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            {{ $this->customers->links('pagination::bootstrap-5') }}
                            
                           
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

</div>
