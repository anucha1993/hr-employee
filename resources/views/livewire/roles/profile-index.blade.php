<div>
<div class="container-fluid px-0">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0 fw-bold text-primary">รายการโปรไฟล์</h4>
      @can('edit profile')
    <button class="btn btn-primary shadow-sm px-4 py-2" data-bs-toggle="modal" data-bs-target="#modal-profile-form">
      <i class="bi bi-plus-circle me-1"></i> สร้างโปรไฟล์
    </button>
    @endcan
  </div>

  <div class="table-responsive rounded shadow-sm">
    <table class="table table-hover align-middle mb-4 bg-white">
      
      <thead class="table-light">
        <tr>
          <th class="text-center" style="width:40%">ชื่อโปรไฟล์</th>
          <th class="text-center" style="width:60%">จัดการ</th>
        </tr>
      </thead>

      <tbody>
        @forelse ($roles as $role)
          <tr>
            <td class="text-center fw-semibold">{{ $role->name }}</td>
            <td class="text-center">
               @can('edit profile')
              <button class="btn btn-outline-info btn-sm me-2" wire:click="editProfile({{ $role->id }})" data-bs-toggle="modal" data-bs-target="#modal-profile-form">
                <i class="bi bi-pencil-square"></i> แก้ไข
              </button>
              @endcan

               @can('edit profile')
              <button class="btn btn-outline-warning btn-sm" wire:click="openPermissionModal({{ $role->id }})" data-bs-toggle="modal" data-bs-target="#modal-permission-assignment">
                <i class="bi bi-shield-lock"></i> กำหนดสิทธิ์
              </button>
                 @endcan
                 
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="2" class="text-center text-muted">ไม่มีโปรไฟล์</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<!-- Modal สร้าง/แก้ไขโปรไฟล์ -->
<div wire:ignore.self class="modal fade" id="modal-profile-form" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form wire:submit.prevent="saveProfile">
        <div class="modal-header">
          <h5 class="modal-title">จัดการโปรไฟล์</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="text" wire:model.defer="name" class="form-control" placeholder="ชื่อโปรไฟล์">
          @error('name') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
          <button class="btn btn-primary" type="submit">บันทึก</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal กำหนดสิทธิ์ -->
<div wire:ignore.self class="modal fade" id="modal-permission-assignment" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form wire:submit.prevent="savePermissions">
        <div class="modal-header">
          <h5 class="modal-title">กำหนดสิทธิ์</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          @foreach ($groupedPermissions as $group => $permissions)
            <div class="mb-3">
              <strong>{{ ucfirst($group) }}</strong>
              <div class="row">
                @foreach ($permissions as $permission)
                  <div class="col-md-4">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" wire:model.defer="selectedPermissions" value="{{ $permission['name'] }}" id="perm_{{ $permission['id'] }}">
                      <label class="form-check-label" for="perm_{{ $permission['id'] }}">
                        {{ $permission['name'] }}
                      </label>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          @endforeach
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
          <button class="btn btn-primary" type="submit">บันทึกสิทธิ์</button>
        </div>
      </form>
    </div>
  </div>
</div>
</div>
</div>
