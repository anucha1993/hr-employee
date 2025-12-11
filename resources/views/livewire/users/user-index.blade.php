<div>
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container-fluid">
        <br>
        <div class="card shadow-sm">
            <div class="card-header py-2" style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <h5 class="mb-0" style="color: #212529; font-weight: 600;">
                            <i class="ri-user-settings-line me-2"></i>จัดการผู้ใช้งาน
                        </h5>
                    </div>
                    <div class="col-md-5">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text" style="background-color: #fff; border-right: 0;">
                                <i class="ri-search-line" style="color: #495057;"></i>
                            </span>
                            <input type="text" 
                                   class="form-control" 
                                   wire:model.live.debounce.300ms="search" 
                                   placeholder="ค้นหาชื่อ, อีเมล..."
                                   style="border-left: 0;">
                            @if($search)
                            <button class="btn btn-outline-secondary btn-sm" wire:click="$set('search', '')" type="button">
                                <i class="ri-close-line"></i>
                            </button>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3 text-end">
                        <a href="{{ route('users.create') }}" class="btn btn-sm btn-success">
                            <i class="ri-add-circle-line me-1"></i> เพิ่มผู้ใช้งาน
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0" style="font-size: 13px;">
                        <thead style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                            <tr>
                                <th style="color: #212529; font-weight: 600; padding: 12px;">#</th>
                                <th style="color: #212529; font-weight: 600; padding: 12px;">ชื่อผู้ใช้</th>
                                <th style="color: #212529; font-weight: 600; padding: 12px;">อีเมล</th>
                                <th style="color: #212529; font-weight: 600; padding: 12px; text-align: center;">บทบาท</th>
                                <th style="width: 180px; color: #212529; font-weight: 600; padding: 12px; text-align: center;">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $index => $user)
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td style="padding: 12px; color: #495057; font-weight: 500;">{{ $users->firstItem() + $index }}</td>
                                <td style="padding: 12px;">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle text-white d-flex align-items-center justify-content-center me-2"
                                             style="min-width: 32px; height: 32px; font-size: 13px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            {{ mb_strtoupper(mb_substr($user->name, 0, 2, 'UTF-8'), 'UTF-8') }}
                                        </div>
                                        <span style="color: #212529; font-weight: 600;">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td style="padding: 12px; color: #495057;">
                                    <i class="ri-mail-line me-1 text-primary"></i>{{ $user->email }}
                                </td>
                                <td style="padding: 12px; text-align: center;">
                                    @php
                                        $roleColors = [
                                            'Super Admin' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                                            'Admin' => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                                            'User' => 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
                                        ];
                                        $roleName = $user->roles->pluck('name')->first() ?: 'User';
                                        $bgStyle = $roleColors[$roleName] ?? 'linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)';
                                    @endphp
                                    <span class="badge text-white" style="background: {{ $bgStyle }}; font-size: 11px; font-weight: 500; padding: 5px 12px;">
                                        {{ $user->roles->pluck('name')->implode(', ') ?: 'User' }}
                                    </span>
                                </td>
                                <td style="padding: 12px; text-align: center;">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-sm btn-outline-warning" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#userModal" 
                                                wire:click="edit({{ $user->id }})"
                                                title="จัดการโปรไฟล์">
                                            <i class="bi bi-person-gear"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-primary" 
                                                wire:click="editUser({{ $user->id }})"
                                                title="แก้ไข">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-info" 
                                                wire:click="resetPassword({{ $user->id }})"
                                                title="รีเซ็ตรหัสผ่าน">
                                            <i class="bi bi-key"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" 
                                                wire:click="deleteConfirm({{ $user->id }})"
                                                title="ลบ">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center" style="padding: 40px; color: #6c757d;">
                                    <i class="ri-user-unfollow-line" style="font-size: 48px; opacity: 0.3;"></i>
                                    <div class="mt-2">ไม่มีข้อมูลผู้ใช้</div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            @if($users->hasPages())
            <div class="card-footer" style="background-color: #f8f9fa;">
                <div class="d-flex justify-content-between align-items-center">
                    <div style="color: #6c757d; font-size: 13px;">
                        แสดง {{ $users->firstItem() }} ถึง {{ $users->lastItem() }} จากทั้งหมด {{ $users->total() }} รายการ
                    </div>
                    {{ $users->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Modal Bootstrap --}}
    <div wire:ignore.self class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form wire:submit.prevent="save">
            <div class="modal-header">
              <h5 class="modal-title" id="userModalLabel">กำหนดโปรไฟล์ให้ผู้ใช้</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="role_id" class="form-label">เลือกโปรไฟล์</label>
                <select wire:model="role_id" id="role_id" class="form-select">
                  <option value="">-- เลือกโปรไฟล์ --</option>
                  @foreach ($allRoles as $role)
                      <option value="{{ $role->id }}">{{ $role->name }}</option>
                  @endforeach
                </select>
                @error('role_id') <span class="text-danger">{{ $message }}</span> @enderror
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
              <button type="submit" class="btn btn-primary">บันทึก</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    {{-- Edit User Modal --}}
    <div wire:ignore.self class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form wire:submit.prevent="updateUser">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel">แก้ไขข้อมูลผู้ใช้</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">ชื่อ</label>
                            <input type="text" wire:model="name" id="name" class="form-control">
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">อีเมล</label>
                            <input type="email" wire:model="email" id="email" class="form-control">
                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary">บันทึก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Reset Password Modal --}}
    <div wire:ignore.self class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form wire:submit.prevent="updatePassword">
                    <div class="modal-header">
                        <h5 class="modal-title" id="resetPasswordModalLabel">เปลี่ยนรหัสผ่าน</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="password" class="form-label">รหัสผ่านใหม่</label>
                            <input type="password" wire:model="password" id="password" class="form-control">
                            @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">ยืนยันรหัสผ่านใหม่</label>
                            <input type="password" wire:model="password_confirmation" id="password_confirmation" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary">บันทึก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.addEventListener('close-modal', event => {
            $('#userModal').modal('hide');
            $('#editUserModal').modal('hide');
            $('#resetPasswordModal').modal('hide');
        });
        
        window.addEventListener('show-edit-modal', event => {
            $('#editUserModal').modal('show');
        });
        
        window.addEventListener('show-password-modal', event => {
            $('#resetPasswordModal').modal('show');
        });

        window.addEventListener('show-delete-confirmation', event => {
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: 'คุณต้องการลบผู้ใช้นี้ใช่หรือไม่?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, ลบเลย!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.deleteUser();
                }
            });
        });
    </script>
    @endpush
</div>