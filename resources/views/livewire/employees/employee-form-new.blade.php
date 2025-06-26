<div>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-gradient-primary text-white py-4">
                        <div class="d-flex align-items-center">
                            <i class="mdi mdi-account-plus-outline fs-3 me-3"></i>
                            <div>
                                <h4 class="card-title mb-0 fw-bold">ข้อมูลพนักงานsss</h4>
                                <p class="mb-0 opacity-75">กรอกข้อมูลพนักงานให้ครบถ้วน</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <form wire:submit.prevent="save" class="needs-validation" novalidate>
                            
                            <!-- ข้อมูลส่วนตัว -->
                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-header bg-light border-0 py-3">
                                    <h6 class="mb-0 fw-bold text-primary">
                                        <i class="mdi mdi-account-circle me-2"></i>ข้อมูลส่วนตัว
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-lg-6">
                                            <label class="form-label fw-semibold">ชื่อ-นามสกุล <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="mdi mdi-account"></i></span>
                                                <input type="text" wire:model.defer="emp_name" class="form-control"
                                                    placeholder="กรอกชื่อ-นามสกุล">
                                            </div>
                                            @error('emp_name')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-lg-6">
                                            <label class="form-label fw-semibold">เบอร์โทรติดต่อ <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="mdi mdi-phone"></i></span>
                                                <input type="text" wire:model.defer="emp_phone" class="form-control" required
                                                    placeholder="กรอกเบอร์โทร">
                                            </div>
                                            @error('emp_phone')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="form-label fw-semibold">รหัสพนักงาน</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="mdi mdi-badge-account"></i></span>
                                                <input type="text" wire:model.defer="emp_code" class="form-control"
                                                    placeholder="เช่น EMP001">
                                            </div>
                                            @error('emp_code')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="form-label fw-semibold">ชื่อเจ้าหน้าที่สรรหา</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="mdi mdi-account-search"></i></span>
                                                <select wire:model.defer="emp_recruiter_id" class="form-select">
                                                    <option value="">- เลือก -</option>
                                                    @foreach ($recruiterOptions as $item)
                                                        <option value="{{ $item->id }}">{{ $item->value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="form-label fw-semibold">แผนก</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="mdi mdi-domain"></i></span>
                                                <input type="text" wire:model.defer="emp_department" class="form-control"
                                                    placeholder="กรอกแผนก">
                                            </div>
                                            @error('emp_department')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="form-label fw-semibold">เพศ</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="mdi mdi-gender-male-female"></i></span>
                                                <select wire:model.defer="emp_gender" class="form-select">
                                                    <option value="">- เลือก -</option>
                                                    <option value="ชาย">ชาย</option>
                                                    <option value="หญิง">หญิง</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="form-label fw-semibold">วันเกิด <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="mdi mdi-calendar"></i></span>
                                                <input type="date" wire:model.live.defer="emp_birthdate" class="form-control" required>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="form-label fw-semibold">อายุ</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="mdi mdi-numeric"></i></span>
                                                <input type="text" value="{{ $this->empAge }} ปี" class="form-control bg-light" disabled>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="form-label fw-semibold">เลขบัตร ปชช. <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="mdi mdi-card-account-details"></i></span>
                                                <input type="text" wire:model.defer="emp_idcard" maxlength="13"
                                                    class="form-control" placeholder="13 หลัก">
                                            </div>
                                            @error('emp_idcard')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="form-label fw-semibold">วุฒิการศึกษา</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="mdi mdi-school"></i></span>
                                                <select wire:model.defer="emp_education" class="form-select">
                                                    <option value="">- เลือก -</option>
                                                    @foreach ($educationOptions as $item)
                                                        <option value="{{ $item->id }}">{{ $item->value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ข้อมูลการทำงาน -->
                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-header bg-light border-0 py-3">
                                    <h6 class="mb-0 fw-bold text-primary">
                                        <i class="mdi mdi-briefcase me-2"></i>ข้อมูลการทำงาน
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-lg-6">
                                            <label class="form-label fw-semibold">โรงงาน</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="mdi mdi-factory"></i></span>
                                                <select wire:model.defer="emp_factory_id" class="form-select">
                                                    <option value="">- เลือก -</option>
                                                    @foreach ($factories as $factory)
                                                        <option value="{{ $factory->id }}">{{ $factory->customer_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="form-label fw-semibold">สิทธิรักษาพยาบาล</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="mdi mdi-medical-bag"></i></span>
                                                <select wire:model.defer="emp_medical_right" class="form-select">
                                                    <option value="">- เลือก -</option>
                                                    @foreach ($medicalOptions as $item)
                                                        <option value="{{ $item->id }}">{{ $item->value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('emp_medical_right')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="form-label fw-semibold">วันเริ่มงาน</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="mdi mdi-calendar-clock"></i></span>
                                                <input type="date" wire:model.live.defer="emp_start_date" class="form-control">
                                            </div>
                                            @error('emp_start_date')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="form-label fw-semibold">อายุงาน</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="mdi mdi-clock-outline"></i></span>
                                                <input type="text" class="form-control bg-light"
                                                    value="{{ implode(' ', array_filter([$this->empWorkdays[0] . ' ปี', $this->empWorkdays[1] . ' เดือน', $this->empWorkdays[2] . ' วัน'])) }}"
                                                    disabled>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="form-label fw-semibold">สถานะพนักงาน <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="mdi mdi-account-check"></i></span>
                                                <select wire:model="emp_status" class="form-select">
                                                    <option value="">- เลือก -</option>
                                                    @foreach ($statusOptions as $item)
                                                        <option value="{{ $item->id }}">{{ $item->value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('emp_status')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ข้อมูลสัญญาจ้าง -->
                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-header bg-light border-0 py-3">
                                    <h6 class="mb-0 fw-bold text-primary">
                                        <i class="mdi mdi-file-document me-2"></i>ข้อมูลสัญญาจ้าง
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-lg-6">
                                            <label for="emp_contract_type" class="form-label fw-semibold">ประเภทสัญญาจ้าง</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="mdi mdi-file-document-outline"></i></span>
                                                <select wire:model.live="emp_contract_type" id="emp_contract_type" class="form-select">
                                                    <option value="สัญญาระยะยาว">สัญญาระยะยาว</option>
                                                    <option value="สัญญาระยะสั้น">สัญญาระยะสั้น</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-3">
                                            <label for="emp_contract_start" class="form-label fw-semibold">วันที่เริ่มสัญญา</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="mdi mdi-calendar-start"></i></span>
                                                <input type="date" wire:model="emp_contract_start" id="emp_contract_start" class="form-control">
                                            </div>
                                        </div>

                                        @if ($emp_contract_type === 'สัญญาระยะสั้น')
                                        <div class="col-lg-3">
                                            <label for="emp_contract_end" class="form-label fw-semibold">วันที่สิ้นสุดสัญญา</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="mdi mdi-calendar-end"></i></span>
                                                <input type="date" wire:model="emp_contract_end" id="emp_contract_end" class="form-control">
                                            </div>
                                        </div>
                                        @endif

                                        <div class="col-lg-6">
                                            <label for="emp_resign_date" class="form-label fw-semibold">วันที่ลาออก</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="mdi mdi-calendar-remove"></i></span>
                                                <input type="date" wire:model="emp_resign_date" class="form-control" id="emp_resign_date">
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <label for="emp_resign_reason" class="form-label fw-semibold">เหตุผลการลาออก</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="mdi mdi-comment-text"></i></span>
                                                <input type="text" wire:model="emp_resign_reason" class="form-control"
                                                    placeholder="เหตุผลการลาออก" id="emp_resign_reason">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ที่อยู่ -->
                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-header bg-light border-0 py-3">
                                    <h6 class="mb-0 fw-bold text-primary">
                                        <i class="mdi mdi-home me-2"></i>ที่อยู่
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-lg-6">
                                            <label class="form-label fw-semibold">ที่อยู่ปัจจุบัน</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="mdi mdi-home-map-marker"></i></span>
                                                <textarea wire:model.defer="emp_address_current" class="form-control" rows="3"
                                                    placeholder="ที่อยู่ที่พักปัจจุบัน"></textarea>
                                            </div>
                                            @error('emp_address_current')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="form-label fw-semibold">ที่อยู่ตามทะเบียนบ้าน</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="mdi mdi-home-account"></i></span>
                                                <textarea wire:model.defer="emp_address_register" class="form-control" rows="3"
                                                    placeholder="ที่อยู่ตามทะเบียนบ้าน"></textarea>
                                            </div>
                                            @error('emp_address_register')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ข้อมูลผู้ติดต่อฉุกเฉิน -->
                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-header bg-light border-0 py-3">
                                    <h6 class="mb-0 fw-bold text-primary">
                                        <i class="mdi mdi-phone-alert me-2"></i>ข้อมูลผู้ติดต่อฉุกเฉิน
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @foreach ($emp_emergency_contacts as $index => $contact)
                                    <div class="row g-3 mb-3 p-3 border rounded bg-light">
                                        <div class="col-lg-4">
                                            <label class="form-label fw-semibold">ชื่อผู้ติดต่อฉุกเฉิน</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-white"><i class="mdi mdi-account-outline"></i></span>
                                                <input type="text" wire:model.defer="emp_emergency_contacts.{{ $index }}.name"
                                                    class="form-control" placeholder="ชื่อ">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="form-label fw-semibold">เบอร์โทร</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-white"><i class="mdi mdi-phone"></i></span>
                                                <input type="text" wire:model.defer="emp_emergency_contacts.{{ $index }}.phone"
                                                    class="form-control" placeholder="เบอร์โทร">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="form-label fw-semibold">เกี่ยวข้องเป็น</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-white"><i class="mdi mdi-account-heart"></i></span>
                                                <input type="text" wire:model.defer="emp_emergency_contacts.{{ $index }}.relation"
                                                    class="form-control" placeholder="เกี่ยวข้องเป็น">
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- ไฟล์เอกสาร -->
                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-header bg-light border-0 py-3">
                                    <h6 class="mb-0 fw-bold text-primary">
                                        <i class="mdi mdi-file-multiple me-2"></i>เอกสารประกอบ
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label for="emp_files" class="form-label fw-semibold">แนบไฟล์</label>
                                            <input type="file" wire:model="emp_files" id="emp_files" 
                                                   class="form-control form-control-lg" multiple>
                                            <small class="text-muted">สามารถเลือกไฟล์หลายไฟล์พร้อมกันได้</small>
                                        </div>

                                        @if ($emp_files)
                                        <div class="col-12">
                                            <div class="card border-0 bg-light">
                                                <div class="card-body">
                                                    <h6 class="card-title mb-3">
                                                        <i class="mdi mdi-file-check me-2"></i>ไฟล์ที่แนบ
                                                    </h6>
                                                    <div class="list-group list-group-flush">
                                                        @foreach ($emp_files as $index => $file)
                                                        <div class="list-group-item d-flex justify-content-between align-items-center bg-white border rounded mb-2">
                                                            <div class="d-flex align-items-center">
                                                                <i class="mdi mdi-file-document text-primary me-2"></i>
                                                                @if (is_object($file))
                                                                    <span>{{ $file->getClientOriginalName() }}</span>
                                                                @else
                                                                    <a href="{{ asset('storage/' . $file) }}" target="_blank" 
                                                                       class="text-decoration-none">
                                                                        {{ basename($file) }}
                                                                    </a>
                                                                @endif
                                                            </div>
                                                            @if (!is_object($file))
                                                            <button type="button"
                                                                onclick="if (confirm('ยืนยันการลบ?')) { @this.call('removeFile', {{ $index }}) }"
                                                                class="btn btn-sm btn-outline-danger">
                                                                <i class="mdi mdi-delete"></i> ลบ
                                                            </button>
                                                            @endif
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- ปุ่มบันทึก -->
                            <div class="row">
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary btn-lg px-5 py-3 shadow">
                                        <i class="mdi mdi-content-save me-2"></i>บันทึกข้อมูลพนักงาน
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
