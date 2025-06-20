<div>
    <br>
    <div class="container-fluid">


        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif


        <div class="card">
            <div class="card-header">
                <h2 class="">รายชื่อลูกค้า</h2>
                <div class="text-end">
                    <a href="{{ route('customer.create') }}" class="btn btn-success">
                        <i class="fa fa-plus"></i> เพิ่มลูกค้าใหม่
                    </a>
                </div>

            </div>
            <div class="card-body">



                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>ชื่อบริษัท</th>
                                <th>เลขผู้เสียภาษี</th>
                                <th>สาขา</th>
                                <th>จังหวัด</th>
                                <th>วันที่เหลือ</th>
                                <th>สถานะ</th>
                                <th>ดำเนินการ</th>
                            </tr>
                        </thead>
                        <tbody>
                             @forelse ($this->customers as $index => $customer)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $customer->customer_name }}</td>
                                    <td>{{ $customer->customer_taxid }}</td>
                                    <td>{{ $customer->branch->value }}</td>
                                    <td>{{ $customer->customer_address_province }}</td>

                                    <td>
                                        @if ($customer->latestContract)
                                            @php
                                                $remaining = \Carbon\Carbon::parse(
                                                    $customer->latestContract->contract_end_date,
                                                )->diffInDays(now(), false);
                                            @endphp
                                            @if ($remaining < 0)
                                                เหลือ {{ abs($remaining) }} วัน
                                            @elseif ($remaining === 0)
                                                วันสุดท้าย
                                            @else
                                                หมดอายุแล้ว {{ $remaining }} วัน
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{!! getStatusCutomerBadge($customer->customer_status) !!}</td>
                                    <td>
                                        {{-- <a href="#" class="btn btn-sm btn-info">ดู</a> --}}
                                        <a href="{{ route('customer.edit', $customer->id) }}"
                                            class="btn btn-sm btn-warning">แก้ไข</a>
                                        <button type="button"
                                            onclick="if (confirm('ยืนยันการลบ?')) { @this.call('delete', {{ $customer->id }}) }"
                                            class="btn btn-sm btn-danger">
                                            ลบ
                                        </button>
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

                         {{ $this->customers->links('pagination::bootstrap-5') }}

                        </div>
                </div>
                
            </div>
        </div>
    </div>

</div>
