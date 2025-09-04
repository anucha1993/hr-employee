<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">หน้าหลัก</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">จัดการผู้ใช้งาน</a></li>
                        <li class="breadcrumb-item active">เพิ่มผู้ใช้งาน</li>
                    </ol>
                </div>
                <h4 class="page-title">เพิ่มผู้ใช้งาน</h4>
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
                    <form wire:submit.prevent="create">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">ชื่อ-นามสกุล</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           wire:model="name" id="name" placeholder="กรอกชื่อ-นามสกุล">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">อีเมล</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           wire:model="email" id="email" placeholder="กรอกอีเมล">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">รหัสผ่าน</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           wire:model="password" id="password" placeholder="กรอกรหัสผ่าน">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">ยืนยันรหัสผ่าน</label>
                                    <input type="password" class="form-control" 
                                           wire:model="password_confirmation" id="password_confirmation" placeholder="ยืนยันรหัสผ่าน">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">บทบาท</label>
                                    @foreach($roles as $role)
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input @error('selectedRoles') is-invalid @enderror"
                                                   wire:model="selectedRoles" value="{{ $role->name }}" id="role_{{ $role->id }}">
                                            <label class="form-check-label" for="role_{{ $role->id }}">
                                                {{ $role->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                    @error('selectedRoles')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-success">บันทึก</button>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">ยกเลิก</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
