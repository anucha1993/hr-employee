<div>
    <div class="container-fluid px-0">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0 fw-bold text-primary">เพิ่มผู้ใช้งาน</h4>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> กลับ
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <form wire:submit.prevent="save" class="row g-3">
                    <!-- ชื่อ -->
                    <div class="col-md-6">
                        <label class="form-label">ชื่อ <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                            wire:model.defer="name" placeholder="กรอกชื่อ">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- อีเมล -->
                    <div class="col-md-6">
                        <label class="form-label">อีเมล <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                            wire:model.defer="email" placeholder="example@email.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- รหัสผ่าน -->
                    <div class="col-md-6">
                        <label class="form-label">รหัสผ่าน <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                            wire:model.defer="password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- ยืนยันรหัสผ่าน -->
                    <div class="col-md-6">
                        <label class="form-label">ยืนยันรหัสผ่าน <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" 
                            wire:model.defer="password_confirmation">
                    </div>

                    <!-- โปรไฟล์ -->
                    <div class="col-md-6">
                        <label class="form-label">โปรไฟล์ <span class="text-danger">*</span></label>
                        <select class="form-select @error('selectedRole') is-invalid @enderror" 
                            wire:model.defer="selectedRole">
                            <option value="">-- เลือกโปรไฟล์ --</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('selectedRole')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- ปุ่มบันทึก -->
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="bi bi-save me-1"></i> บันทึก
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
