<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">หน้าหลัก</a></li>
                        <li class="breadcrumb-item active">จัดการผู้ใช้งาน</li>
                    </ol>
                </div>
                <h4 class="page-title">จัดการผู้ใช้งาน</h4>
            </div>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-5">
                            <a href="{{ route('users.create') }}" class="btn btn-danger mb-2"><i class="mdi mdi-plus-circle me-2"></i> เพิ่มผู้ใช้งาน</a>
                        </div>
                        <div class="col-sm-7">
                            <div class="text-sm-end">
                                <div class="input-group">
                                    <input wire:model.live="search" type="search" class="form-control" placeholder="ค้นหา...">
                                    <span class="input-group-text"><i class="ri-search-line search-icon"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered w-100 dt-responsive nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th>ลำดับ</th>
                                    <th wire:click="sortBy('name')" style="cursor: pointer;">
                                        ชื่อ-นามสกุล
                                        @if($sortField === 'name')
                                            <i class="ri-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-line"></i>
                                        @endif
                                    </th>
                                    <th wire:click="sortBy('email')" style="cursor: pointer;">
                                        อีเมล
                                        @if($sortField === 'email')
                                            <i class="ri-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-line"></i>
                                        @endif
                                    </th>
                                    <th>บทบาท</th>
                                    <th>สิทธิ์การใช้งาน</th>
                                    <th>จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $key => $user)
                                    <tr>
                                        <td>{{ $users->firstItem() + $key }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if(!empty($user->getRoleNames()))
                                                @foreach($user->getRoleNames() as $role)
                                                    <span class="badge bg-primary">{{ $role }}</span>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            @if(!empty($user->getPermissionNames()))
                                                @foreach($user->getPermissionNames() as $permission)
                                                    <span class="badge bg-info">{{ $permission }}</span>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-success btn-sm">แก้ไข</a>
                                                <button class="btn btn-danger btn-sm" onclick="confirm('คุณต้องการลบผู้ใช้งานนี้ใช่หรือไม่?') || event.stopImmediatePropagation()" wire:click="delete({{ $user->id }})">ลบ</button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">ไม่พบข้อมูล</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-end mt-3">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
