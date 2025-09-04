<!-- resources/views/livewire/users/user-form.blade.php -->

<x-modal id="modal-user-form" title="กำหนดโปรไฟล์ให้ผู้ใช้">
    <form wire:submit.prevent="save">
        <div class="modal-body">
            <div class="mb-3">
                <label for="role_id">เลือกโปรไฟล์</label>
                <select wire:model.defer="role_id" class="form-select">
                    <option value="">-- เลือก --</option>
                    @foreach ($allRoles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
                @error('role_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
            <button class="btn btn-primary" type="submit">บันทึก</button>
        </div>
    </form>
</x-modal>
