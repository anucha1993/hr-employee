<div>
    <div class="container-fluid">
        @if (session()->has('message'))
            <div class="alert alert-success mt-2">{{ session('message') }}</div>
        @endif

        <div class="card card-primary card-outline mt-3">
            <div class="card-header d-flex justify-content-between ">
                <h3 class="card-title">Global Sets</h3>
                <!-- ปุ่มเปิด Modal -->
              <button type="button" class="btn btn-primary" wire:click="create" data-toggle="modal" data-target="#globalSetModal">
    <i class="fa fa-add"></i> เพิ่ม
</button>
            </div>
            <div class="card-body">
                <table class="table table">
                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
                            <th>ชื่อ</th>
                            <th>คำอธิบาย</th>
                            <th class="text-center">จำนวนค่า</th>
                            <th class="text-right">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($globalSets as $set)
                            <tr>
                                <td>{{ $set->id }}</td>
                                <td>{{ $set->name }}</td>
                                <td>{{ $set->description }}</td>
                                <td class="text-center">{{ $set->values_count }}</td>
                                <td class="text-right">
                                    <button wire:click="edit({{ $set->id }})" data-toggle="modal" data-target="#globalSetModal" class="btn btn-sm btn-warning">แก้ไข</button>
                                    <button wire:click="delete({{ $set->id }})" class="btn btn-sm btn-danger">ลบ</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">ไม่มีข้อมูล</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Bootstrap Modal แบบ standard -->
        <div wire:ignore.self class="modal fade" id="globalSetModal" tabindex="-1" role="dialog" aria-labelledby="globalSetModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form wire:submit.prevent="save">
                        <div class="modal-header">
                            <h5 class="modal-title" id="globalSetModalLabel">{{ $editingId ? 'แก้ไข' : 'เพิ่ม' }} Global Set</h5>
                            {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button> --}}
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>ชื่อ</label>
                                <input type="text" wire:model.defer="name" class="form-control">
                                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="form-group">
                                <label>คำอธิบาย</label>
                                <textarea wire:model.defer="description" class="form-control"></textarea>
                                @error('description') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <label class="font-weight-bold mt-2">Global Set Values</label>
                            @foreach($values as $index => $value)
                                <div class="row mb-2">
                                    <div class="col">
                                        <input type="text" wire:model="values.{{ $index }}.value" class="form-control" placeholder="ค่า">
                                    </div>
                                    <div class="col">
                                        <select wire:model="values.{{ $index }}.status" class="form-select">
                                            <option value="Enable">Enable</option>
                                            <option value="Disable">Disable</option>
                                        </select>
                                    </div>
                                    <div class="col-auto">
                                        <button type="button" wire:click="removeValue({{ $index }})" class="btn btn-sm btn-danger">ลบ</button>
                                    </div>
                                </div>
                            @endforeach
                            <button type="button" wire:click="addValue" class="btn btn-sm btn-secondary">+ เพิ่มค่า</button>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                            <button type="submit" class="btn btn-primary close">บันทึก</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
