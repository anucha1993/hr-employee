<div>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title mb-0">ข้อมูลพนักงาน</h4>
                    </div>
                    <div class="card-body">
                        <form wire:submit.prevent="save">
                            <div class="row">
                                <div class="col-md-6 mb-1">
                                    <label class="form-label">ชื่อ-นามสกุล <span class="text-danger"> *</span> </label>
                                    <input type="text" wire:model.defer="emp_name" class="form-control"
                                        placeholder="กรอกชื่อ-นามสกุล">
                                    @error('emp_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-1">
                                    <label class="form-label">เบอร์โทรติดต่อ <span class="text-danger"> *</span></label>
                                    <input type="text" wire:model.defer="emp_phone" class="form-control" required
                                        placeholder="กรอกเบอร์โทร">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">ชื่อเจ้าหน้าที่สรรหา</label>
                                    <select wire:model.defer="emp_recruiter_id" class="form-select">
                                        <option value="">- เลือก -</option>
                                        @foreach ($recruiterOptions as $item)
                                            <option value="{{ $item->id }}">{{ $item->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-1">
                                    <label class="form-label">รหัสพนักงาน</label>
                                    <input type="text" wire:model.defer="emp_code" class="form-control"
                                        placeholder="เช่น EMP001">
                                    @error('emp_code')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-1">
                                    <label class="form-label">แผนก</label>
                                    <input type="text" wire:model.defer="emp_department" class="form-control"
                                        placeholder="กรอกแผนก">
                                    @error('emp_department')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-1">
                                    <label class="form-label">เพศ</label>
                                    <select wire:model.defer="emp_gender" class="form-select">
                                        <option value="">- เลือก -</option>
                                        <option value="ชาย">ชาย</option>
                                        <option value="หญิง">หญิง</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-1">
                                    <label class="form-label">วันเกิด <span class="text-danger"> *</span></label>
                                    <input type="date" wire:model.live.defer="emp_birthdate" class="form-control"
                                        required>
                                </div>
                                <div class="col-md-6 mb-1">
                                    <label class="form-label">อายุ</label>
                                    <input type="text" value="{{ $this->empAge }} ปี" class="form-control bg-light"
                                        disabled>
                                </div>

                                <div class="col-md-6 mb-1">
                                    <label class="form-label">เลขบัตร ปชช. <span class="text-danger"> *</span></label>
                                    <input type="text" wire:model.defer="emp_idcard" maxlength="13"
                                        class="form-control" placeholder="13 หลัก">
                                    @error('emp_idcard')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-1">
                                    <label class="form-label">วุฒิการศึกษา</label>
                                    <select wire:model.defer="emp_education" class="form-select">
                                        <option value="">- เลือก -</option>
                                        @foreach ($educationOptions as $item)
                                            <option value="{{ $item->id }}">{{ $item->value }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-1">
                                    <label class="form-label">โรงงาน</label>
                                    <select wire:model.defer="emp_factory_id" class="form-select">
                                        <option value="">- เลือก -</option>
                                        @foreach ($factories as $factory)
                                            <option value="{{ $factory->id }}">{{ $factory->customer_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-1">
                                    <label class="form-label">สิทธิรักษาพยาบาล</label>
                                    <select wire:model.defer="emp_medical_right" class="form-select">
                                        <option value="">- เลือก -</option>
                                        @foreach ($medicalOptions as $item)
                                            <option value="{{ $item->id }}">{{ $item->value }}</option>
                                        @endforeach
                                    </select>
                                    @error('emp_medical_right')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-1">
                                    <label class="form-label">ที่อยู่ปัจจุบัน</label>
                                    <textarea wire:model.defer="emp_address_current" class="form-control" rows="2"
                                        placeholder="ที่อยู่ที่พักปัจจุบัน"></textarea>
                                    @error('emp_address_current')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-1">
                                    <label class="form-label">ที่อยู่ตามทะเบียนบ้าน</label>
                                    <textarea wire:model.defer="emp_address_register" class="form-control" rows="2"
                                        placeholder="ที่อยู่ตามทะเบียนบ้าน"></textarea>
                                    @error('emp_address_register')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-1">
                                    <label class="form-label">วันเริ่มงาน</label>
                                    <input type="date" wire:model.live.defer="emp_start_date"
                                        class="form-control">
                                    @error('emp_start_date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-1">
                                    <label class="form-label">อายุงาน</label>
                                    <input type="text" class="form-control bg-light"
                                        value="{{ implode(' ', array_filter([$this->empWorkdays[0] . ' ปี', $this->empWorkdays[1] . ' เดือน', $this->empWorkdays[2] . ' วัน'])) }}"
                                        disabled>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label class="form-label">สถานะพนักงาน <span class="text-danger"> *</span></label>
                                    <select wire:model="emp_status" class="form-select">
                                        <option value="">- เลือก -</option>
                                        @foreach ($statusOptions as $item)
                                            <option value="{{ $item->id }}">{{ $item->value }}</option>
                                        @endforeach
                                    </select>
                                    @error('emp_status')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- ประเภทสัญญาจ้าง -->
                                        <div class="mb-4">
                                            <label for="emp_contract_type" class="form-label">ประเภทสัญญาจ้าง</label>
                                            <select wire:model.live="emp_contract_type" id="emp_contract_type"
                                                class="form-select">
                                                <option value="สัญญาระยะยาว">สัญญาระยะยาว</option>
                                                <option value="สัญญาระยะสั้น">สัญญาระยะสั้น</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="emp_contract_start" class="form-label">วันที่เริ่มสัญญา</label>
                                        <input type="date" wire:model="emp_contract_start" id="emp_contract_start"
                                            class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        @if ($emp_contract_type === 'สัญญาระยะสั้น')
                                            <label for="emp_contract_end"
                                                class="form-label">วันที่สิ้นสุดสัญญา</label>
                                            <input type="date" wire:model="emp_contract_end" id="emp_contract_end"
                                                class="form-control">
                                    </div>
                                    @endif


                                </div>

                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label for="emp_resign_date" class="form-label">วันที่ลาออก</label>
                                        <input type="date" wire:model="emp_resign_date" class="form-control"
                                            id="emp_resign_date">
                                    </div>

                                    <!-- เหตุผลการลาออก -->
                                    <div class="mb-3 col-md-6">
                                        <label for="emp_resign_reason" class="form-label">เหตุผลการลาออก</label>
                                        <input type="text" wire:model="emp_resign_reason" class="form-control"
                                            placeholder="เหตุผลการลาออก" id="emp_resign_reason">

                                    </div>
                                </div>

                                @foreach ($emp_emergency_contacts as $index => $contact)
                                    <div class="col-md-4 mb-1">
                                        <label class="form-label">ชื่อผู้ติดต่อฉุกเฉิน</label>
                                        <input type="text"
                                            wire:model.defer="emp_emergency_contacts.{{ $index }}.name"
                                            class="form-control" placeholder="ชื่อ">
                                    </div>
                                    <div class="col-md-4 mb-1">
                                        <label class="form-label">เบอร์โทร</label>
                                        <input type="text"
                                            wire:model.defer="emp_emergency_contacts.{{ $index }}.phone"
                                            class="form-control" placeholder="เบอร์โทร">
                                    </div>
                                    <div class="col-md-4 mb-1">
                                        <label class="form-label">เกี่ยวข้องเป็น</label>
                                        <input type="text"
                                            wire:model.defer="emp_emergency_contacts.{{ $index }}.relation"
                                            class="form-control" placeholder="เกี่ยวข้องเป็น">
                                    </div>
                                @endforeach

                                <div class="mb-3">
                                    <label for="emp_files" class="form-label">แนบไฟล์</label>
                                    <input type="file" wire:model="emp_files" id="emp_files" class="form-control"
                                        multiple>
                                </div>
                                <div class="mb-3">

                                    <!-- แสดงไฟล์ที่อัปโหลดแล้ว (optional) -->
                                    @if ($emp_files)
                                       <ul class="list-group mt-2 mb-1">
                                            @foreach ($emp_files as $index => $file)
                                               <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    @if (is_object($file))
                                                        {{ $file->getClientOriginalName() }}
                                                    @else
                                                        <a href="{{ asset('storage/' . $file) }}" target="_blank">
                                                            {{ basename($file) }}
                                                        </a>
                                                      
                                                        <button type="button"
                                                            onclick="if (confirm('ยืนยันการลบ?')) { @this.call('removeFile', {{ $index }}) }"
                                                            class="btn btn-sm btn-danger btn-sm">
                                                            <i class="fa fa-edit"></i> ลบ
                                                        </button>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif


                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="mdi mdi-content-save"></i> บันทึกข้อมูล
                                        </button>
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
