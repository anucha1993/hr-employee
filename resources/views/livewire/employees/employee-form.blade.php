<div>
    <!-- Custom Styles -->
    <style>
        .modern-card {
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
            overflow: hidden;
        }
        .gradient-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
        }
        .gradient-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" fill-opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" fill-opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" fill-opacity="0.15"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        .section-card {
            border-radius: 15px;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
            background: white;
        }
        .section-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        .section-header {
            background: linear-gradient(90deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 2px solid #dee2e6;
            border-radius: 15px 15px 0 0 !important;
        }
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .input-group-text {
            border-radius: 10px 0 0 10px;
            border: 2px solid #e9ecef;
            border-right: none;
            background: linear-gradient(90deg, #f8f9fa 0%, #e9ecef 100%);
        }
        .input-group .form-control {
            border-left: none;
            border-radius: 0 10px 10px 0;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }
        .section-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            margin-right: 15px;
        }
        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .divider {
            height: 2px;
            background: linear-gradient(90deg, transparent 0%, #dee2e6 50%, transparent 100%);
            margin: 30px 0;
        }
    </style>

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <div class="modern-card">
                    <div class="gradient-header text-white py-5 position-relative">
                        <div class="container-fluid">
                            <div class="d-flex align-items-center position-relative">
                                <div class="section-icon me-4">
                                    <i class="mdi mdi-account-plus fs-4"></i>
                                </div>
                                <div>
                                    <h1 class="mb-2 fw-bold">ข้อมูลพนักงาน</h1>
                                    <p class="mb-0 opacity-90 fs-6">จัดการข้อมูลพนักงานอย่างครบถ้วนและมีประสิทธิภาพ</p>
                                </div>
                                <div class="ms-auto d-none d-md-block">
                                    <div class="d-flex align-items-center">
                                        <div class="status-badge bg-success bg-opacity-20 text-white me-3">
                                            <i class="mdi mdi-check-circle me-1"></i>ระบบออนไลน์
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-2">
                        <form wire:submit.prevent="save" class="needs-validation" novalidate>
                            
                            <!-- ข้อมูลส่วนตัว -->
                            <div class="section-card mb-1">
                                <div class="section-header p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="section-icon">
                                            <i class="mdi mdi-account-circle"></i>
                                        </div>
                                        <h3 class="mb-0 fw-bold text-primary">ข้อมูลส่วนตัว</h3>
                                    </div>
                                </div>
                                <div class="card-body p-2">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label">ชื่อ-นามสกุล <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="mdi mdi-account"></i></span>
                                                <input type="text" wire:model.defer="emp_name" class="form-control"
                                                    placeholder="กรอกชื่อ-นามสกุล">
                                            </div>
                                            @error('emp_name')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">เบอร์โทรติดต่อ <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="mdi mdi-phone"></i></span>
                                                <input type="text" wire:model.defer="emp_phone" class="form-control" required
                                                    placeholder="กรอกเบอร์โทร">
                                            </div>
                                            @error('emp_phone')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">รหัสพนักงาน</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="mdi mdi-card-account-details"></i></span>
                                                <input type="text" wire:model.defer="emp_code" class="form-control"
                                                    placeholder="เช่น EMP001">
                                            </div>
                                            @error('emp_code')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">เพศ</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="mdi mdi-gender-male-female"></i></span>
                                                <select wire:model.defer="emp_gender" class="form-select">
                                                    <option value="">- เลือกเพศ -</option>
                                                    <option value="ชาย">ชาย</option>
                                                    <option value="หญิง">หญิง</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">วันเกิด <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                <input type="date" wire:model.live.defer="emp_birthdate" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">อายุ</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="mdi mdi-calendar-clock"></i></span>
                                                <input type="text" value="{{ $this->empAge }} ปี" class="form-control bg-light" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">เลขบัตร ปชช. <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="mdi mdi-card-account-details-outline"></i></span>
                                                <input type="text" wire:model.defer="emp_idcard" maxlength="13"
                                                    class="form-control" placeholder="13 หลัก">
                                            </div>
                                            @error('emp_idcard')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">วุฒิการศึกษา</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="mdi mdi-school"></i></span>
                                                <select wire:model.defer="emp_education" class="form-select">
                                                    <option value="">- เลือกวุฒิการศึกษา -</option>
                                                    @foreach ($educationOptions as $item)
                                                        <option value="{{ $item->id }}">{{ $item->value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="divider"></div>

                            <!-- ข้อมูลการทำงาน -->
                            <div class="section-card mb-1">
                                <div class="section-header p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="section-icon">
                                            <i class="mdi mdi-briefcase"></i>
                                        </div>
                                        <h3 class="mb-0 fw-bold text-primary">ข้อมูลการทำงาน</h3>
                                    </div>
                                </div>
                                <div class="card-body p-2">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label">ชื่อเจ้าหน้าที่สรรหา</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="mdi mdi-account-search"></i></span>
                                                <select wire:model.defer="emp_recruiter_id" class="form-select">
                                                    <option value="">- เลือกเจ้าหน้าที่สรรหา -</option>
                                                    @foreach ($recruiterOptions as $item)
                                                        <option value="{{ $item->id }}">{{ $item->value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">แผนก</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="mdi mdi-domain"></i></span>
                                                <input type="text" wire:model.defer="emp_department" class="form-control"
                                                    placeholder="กรอกแผนก">
                                            </div>
                                            @error('emp_department')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">โรงงาน</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="mdi mdi-factory"></i></span>
                                                <select wire:model.defer="emp_factory_id" class="form-select">
                                                    <option value="">- เลือกโรงงาน -</option>
                                                    @foreach ($factories as $factory)
                                                        <option value="{{ $factory->id }}">{{ $factory->customer_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">วันเริ่มงาน</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="mdi mdi-calendar-check"></i></span>
                                                <input type="date" wire:model.live.defer="emp_start_date" class="form-control">
                                            </div>
                                            @error('emp_start_date')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">อายุงาน</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="mdi mdi-calendar-clock"></i></span>
                                                <input type="text" class="form-control bg-light"
                                                    value="{{ implode(' ', array_filter([$this->empWorkdays[0] . ' ปี', $this->empWorkdays[1] . ' เดือน', $this->empWorkdays[2] . ' วัน'])) }}"
                                                    disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">สถานะพนักงาน <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="mdi mdi-account-check"></i></span>
                                                <select wire:model="emp_status" class="form-select">
                                                    <option value="">- เลือกสถานะ -</option>
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

                            <div class="divider"></div>

                            <!-- สัญญาจ้าง -->
                            <div class="section-card mb-1">
                                <div class="section-header p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="section-icon">
                                            <i class="mdi mdi-file-document"></i>
                                        </div>
                                        <h3 class="mb-0 fw-bold text-primary">ข้อมูลสัญญาจ้าง</h3>
                                    </div>
                                </div>
                                <div class="card-body p-2">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label">ประเภทสัญญาจ้าง</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="mdi mdi-file-document-outline"></i></span>
                                                <select wire:model.live="emp_contract_type" class="form-select">
                                                    <option value="สัญญาระยะยาว">สัญญาระยะยาว</option>
                                                    <option value="สัญญาระยะสั้น">สัญญาระยะสั้น</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="form-label">วันที่เริ่มสัญญา</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="mdi mdi-calendar-start"></i></span>
                                                <input type="date" wire:model="emp_contract_start" class="form-control">
                                            </div>
                                        </div>
                                        @if ($emp_contract_type === 'สัญญาระยะสั้น')
                                        <div class="col-lg-3">
                                            <label class="form-label">วันที่สิ้นสุดสัญญา</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="mdi mdi-calendar-end"></i></span>
                                                <input type="date" wire:model="emp_contract_end" class="form-control">
                                            </div>
                                        </div>
                                        @endif
                                        <div class="col-md-6">
                                            <label class="form-label">วันที่ลาออก</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="mdi mdi-calendar-remove"></i></span>
                                                <input type="date" wire:model="emp_resign_date" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">เหตุผลการลาออก</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="mdi mdi-comment-text"></i></span>
                                                <input type="text" wire:model="emp_resign_reason" class="form-control"
                                                    placeholder="เหตุผลการลาออก">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="divider"></div>

                            <!-- ข้อมูลเพิ่มเติม -->
                            <div class="section-card mb-1">
                                <div class="section-header p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="section-icon">
                                            <i class="mdi mdi-information"></i>
                                        </div>
                                        <h3 class="mb-0 fw-bold text-primary">ข้อมูลเพิ่มเติม</h3>
                                    </div>
                                </div>
                                <div class="card-body p-2">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label">สิทธิรักษาพยาบาล</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="mdi mdi-medical-bag"></i></span>
                                                <select wire:model.defer="emp_medical_right" class="form-select">
                                                    <option value="">- เลือกสิทธิรักษาพยาบาล -</option>
                                                    @foreach ($medicalOptions as $item)
                                                        <option value="{{ $item->id }}">{{ $item->value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('emp_medical_right')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">ที่อยู่ปัจจุบัน</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="mdi mdi-home"></i></span>
                                                <textarea wire:model.defer="emp_address_current" class="form-control" rows="2"
                                                    placeholder="ที่อยู่ที่พักปัจจุบัน"></textarea>
                                            </div>
                                            @error('emp_address_current')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">ที่อยู่ตามทะเบียนบ้าน</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="mdi mdi-home-outline"></i></span>
                                                <textarea wire:model.defer="emp_address_register" class="form-control" rows="2"
                                                    placeholder="ที่อยู่ตามทะเบียนบ้าน"></textarea>
                                            </div>
                                            @error('emp_address_register')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="divider"></div>

                            <!-- ผู้ติดต่อฉุกเฉิน -->
                            <div class="section-card mb-1">
                                <div class="section-header p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="section-icon">
                                            <i class="mdi mdi-phone-alert"></i>
                                        </div>
                                        <h3 class="mb-0 fw-bold text-primary">ผู้ติดต่อฉุกเฉิน</h3>
                                    </div>
                                </div>
                                <div class="card-body p-2">
                                    @foreach ($emp_emergency_contacts as $index => $contact)
                                    <div class="row g-4 mb-3 pb-3 border-bottom">
                                        <div class="col-lg-4">
                                            <label class="form-label">ชื่อผู้ติดต่อฉุกเฉิน</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="mdi mdi-account-heart"></i></span>
                                                <input type="text" wire:model.defer="emp_emergency_contacts.{{ $index }}.name"
                                                    class="form-control" placeholder="ชื่อผู้ติดต่อ">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="form-label">เบอร์โทร</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="mdi mdi-phone"></i></span>
                                                <input type="text" wire:model.defer="emp_emergency_contacts.{{ $index }}.phone"
                                                    class="form-control" placeholder="เบอร์โทร">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="form-label">ความสัมพันธ์</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="mdi mdi-heart"></i></span>
                                                <input type="text" wire:model.defer="emp_emergency_contacts.{{ $index }}.relation"
                                                    class="form-control" placeholder="เกี่ยวข้องเป็น">
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="divider"></div>

                            <!-- ไฟล์แนบ -->
                            <div class="section-card mb-1">
                                <div class="section-header p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="section-icon">
                                            <i class="mdi mdi-file-multiple"></i>
                                        </div>
                                        <h3 class="mb-0 fw-bold text-primary">ไฟล์แนบ</h3>
                                    </div>
                                </div>
                                <div class="card-body p-2">
                                    <div class="mb-3">
                                        <label class="form-label">แนบไฟล์เอกสาร</label>
                                        <input type="file" wire:model="emp_files" class="form-control" multiple>
                                    </div>
                                    
                                    @if ($emp_files)
                                    <div class="mt-3">
                                        <h6 class="text-muted mb-2">ไฟล์ที่แนบ:</h6>
                                        <div class="list-group">
                                            @foreach ($emp_files as $index => $file)
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <i class="mdi mdi-file-document me-2 text-primary"></i>
                                                    @if (is_object($file))
                                                        <span>{{ $file->getClientOriginalName() }}</span>
                                                    @else
                                                        <a href="{{ asset('storage/' . $file) }}" target="_blank" class="text-decoration-none">
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
                                    @endif
                                </div>
                            </div>

                            <!-- ปุ่มบันทึก -->
                            <div class="text-center py-4">
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="mdi mdi-content-save me-2"></i>
                                    บันทึกข้อมูลพนักงาน
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
