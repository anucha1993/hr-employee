<!-- resources/views/livewire/roles/permission-assignment.blade.php -->

<x-modal id="modal-permission-assignment" title="กำหนดสิทธิ์โปรไฟล์">
    <form wire:submit.prevent="save">
        <div class="modal-body" style="max-height: 400px; overflow-y:auto;">
            @foreach ($groupedPermissions as $group => $permissions)
                <div class="mb-3">
                    <label class="fw-bold text-primary">{{ ucfirst($group) }}</label>
                    <div class="row">
                        @foreach ($permissions as $permission)
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        id="perm_{{ $permission->id }}"
                                        value="{{ $permission->name }}"
                                        wire:model.defer="selectedPermissions"
                                    >
                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                        {{ $permission->name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
            <button class="btn btn-primary" type="submit">บันทึกสิทธิ์</button>
        </div>
    </form>
</x-modal>
