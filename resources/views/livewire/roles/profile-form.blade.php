<div wire:ignore.self class="modal fade" id="modal-profile-form" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form wire:submit.prevent="save">
        <div class="modal-header">
          <h5 class="modal-title">{{ $role_id ? 'แก้ไขโปรไฟล์' : 'สร้างโปรไฟล์' }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label for="profile-name" class="form-label">ชื่อโปรไฟล์</label>
            <input wire:model.defer="name" type="text" class="form-control">
            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
          </div>
        </div>

        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
          <button class="btn btn-primary" type="submit">บันทึก</button>
        </div>
      </form>
    </div>
  </div>
</div>
