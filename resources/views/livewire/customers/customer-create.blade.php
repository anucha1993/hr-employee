<div>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-success">
                    <label for="" class="text-white">จำนวนพนักงานในโครงการปัจจุบัน</label>
                </div>
                <div class="card-body">
                    <h3>0 คน</h3>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-header bg-danger">
                    <label for="" class="text-white">จำนวนพนักงานในโครงการที่ลาออก</label>
                </div>
                <div class="card-body">
                    <h3>0 คน</h3>
                </div>
            </div>
        </div>
    </div>


    <div class="card">
        <div class="card-header bg-info text-white">
            เพิ่มข้อมูลลูกค้า
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-2">
                    <label>ชื่อบริษัทนายจ้าง</label>
                    <input type="text" wire:model="customer_name" class="form-control @error('customer_name') is-invalid @enderror"
                        placeholder="ชื่อบริษัทนายจ้าง">
                        @error('customer_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                </div>
                <div class="col-md-6 mb-2">
                    <label for="">เลขประจำตัวผู้เสียภาษี</label>
                    <input type="text" wire:model="customer_taxid" class="form-control @error('customer_taxid') is-invalid @enderror" placeholder="0000-00000-000">
                    @error('customer_taxid')
                        <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                </div>
                <div class="col-md-6 mb-2">
                    <label for="">สำนักงาน</label>
                    <select wire:model="customer_branch" class="form-control">
                        <option value="">-- กรุณาเลือก --</option>
                        @foreach ($branchOptions as $code => $option)
                            <option value="{{ $option->id }}">{{ $option->value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-2">
                    <label for="">ชื่อสาขา</label>
                    <input type="text" wire:model="customer_branch_name" class="form-control" placeholder="ชื่อสาขา">
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <label for="">ที่อยู่ เลขที่ / หมูบ้าน / หมู่ / ซอย</label>
                    <input type="text" wire:model="customer_address_number" class="form-control"
                        placeholder="ที่อยู่ เลขที่ / หมูบ้าน / หมู่ / ซอย">
                </div>
                <div class="col-md-6 mb-2">
                    <label for="">ตำบล/แขวง</label>
                    <input type="text" wire:model="customer_address_district" class="form-control"
                        placeholder="ตำบล/แขวง">
                </div>
                <div class="col-md-6 mb-2">
                    <label for="">อำเภอ/เขต</label>
                    <input type="text" wire:model="customer_address_amphur" class="form-control"
                        placeholder="อำเภอ/เขต">
                </div>
                <div class="col-md-6 mb-2">
                    <label for="">จังหวัด</label>
                    <input type="text" wire:model="customer_address_province" class="form-control"
                        placeholder="จังหวัด">
                </div>
                <div class="col-md-6 mb-2">
                    <label for="">รหัสไปรษณีย์</label>
                    <input type="number" wire:model="customer_address_zipcode" class="form-control"
                        placeholder="รหัสไปรษณีย์">
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12 mb-2">
                    <label>สัญญาจ้าง</label>
                    <a href="#" class="btn btn-sm btn-primary" wire:click.prevent="addContract">
                        <i class="fa fa-plus"></i> เพิ่ม
                    </a>

                    <table class="table table-bordered mt-2">
                        <thead class="bg-light">
                            <tr>
                                <th>รอบสัญญา</th>
                                <th>เลขที่สัญญา</th>
                                <th>วันที่เริ่ม</th>
                                <th>วันที่สิ้นสุด</th>
                                <th>จำนวนวันที่เหลือ</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contracts as $index => $contract)
                                <tr>
                                    <td>
                                        <input type="text" wire:model="contracts.{{ $index }}.round"
                                            class="form-control">
                                    </td>
                                    <td>
                                        <input type="text" wire:model="contracts.{{ $index }}.number"
                                            class="form-control">
                                    </td>
                                    <td>
                                        <input type="date" wire:model="contracts.{{ $index }}.start_date"
                                            wire:change="calculateDuration({{ $index }})" class="form-control" />
                                    </td>
                                    <td>
                                        <input type="date" wire:model="contracts.{{ $index }}.end_date"
                                            wire:change="calculateDuration({{ $index }})" class="form-control" />
                                    </td>
                                    <td>
                                        {{ $contract['duration'] ?? '-' }}
                                    </td>
                                    <td>
                                        <button wire:click="removeContract({{ $index }})"
                                            class="btn btn-sm btn-danger">ลบ</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <hr>

                <div class="card">
                    <div class="card-header">
                        <h4 class="header-title"> Files Upload</h4>
                        <p class="text-muted mb-0">
                            คุณสามารถ upload files ได้หลายไฟล์พร้อมกัน
                        </p>
                    </div>
                    <div class="card-body">
                        <div class="fallback">
                            <div class="col-md-12 mb-2">
                                <label>แนบไฟล์ (เลือกได้หลายไฟล์)</label>
                                <input type="file" wire:model="files" multiple class="form-control">
                                @error('files.*')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        

                        <!-- Preview -->
                        <div class="dropzone-previews mt-3" id="file-previews"></div>

                    </div>




                </div>

                <div class="text-end mt-3">
                    <button wire:click="store" class="btn btn-success">
                        <i class="fa fa-save"></i> บันทึกข้อมูลลูกค้า
                    </button>
                </div>

            </div>
        </div>
