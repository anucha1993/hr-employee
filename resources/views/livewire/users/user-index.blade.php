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

    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <form class="d-flex">
                        <div class="input-group">
                            <input type="text" class="form-control" wire:model.live="search" placeholder="ค้นหาผู้ใช้...">
                            <span class="input-group-text"><i class="ri-search-line"></i></span>
                        </div>
                    </form>
                </div>
                <h4 class="page-title">จัดการผู้ใช้งาน</h4>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('users.create') }}" class="btn btn-success">
                <i class="ri-add-circle-line me-1"></i> เพิ่มผู้ใช้งาน
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th>ชื่อ</th>
                                    <th>อีเมล</th>
                                    <th>โปรไฟล์</th>
                                    <th style="width: 250px;">จัดการ</th>
                                </tr>
                            </thead>

      <tbody>
        @forelse ($users as $user)
          <tr>
            <td class="text-center">{{ $user->name }}</td>
            <td class="text-center">{{ $user->email }}</td>
            <td class="text-center">
              <span class="badge bg-info text-dark px-2 py-1">
                {{ $user->roles->pluck('name')->implode(', ') ?: '-' }}
              </span>
            </td>
            <td class="text-center">
              <button class="btn btn-outline-warning btn-sm px-3 me-1" data-bs-toggle="modal" data-bs-target="#userModal" wire:click="edit({{ $user->id }})">
                <i class="bi bi-person-gear"></i> จัดการโปรไฟล์
              </button>
              <button class="btn btn-outline-primary btn-sm px-3 me-1" wire:click="editUser({{ $user->id }})">
                <i class="bi bi-pencil"></i> แก้ไข
              </button>
              <button class="btn btn-outline-info btn-sm px-3 me-1" wire:click="resetPassword({{ $user->id }})">
                <i class="bi bi-key"></i> รีเซ็ตรหัสผ่าน
              </button>
              <button class="btn btn-outline-danger btn-sm px-3" wire:click="deleteConfirm({{ $user->id }})">
                <i class="bi bi-trash"></i> ลบ
              </button>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="text-center text-muted">ไม่มีข้อมูลผู้ใช้</td>
          </tr>
        @endforelse
      </tbody>
    </table>
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