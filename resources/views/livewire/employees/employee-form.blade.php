<div class="employee-form-container">
    <div class="content-wrapper">
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
            border-radius: 8px;
            border: 1px solid #e9ecef;
            padding: 8px 12px;
            transition: all 0.3s ease;
            font-size: 14px;
        }
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.15rem rgba(102, 126, 234, 0.2);
        }
        .input-group-text {
            border-radius: 8px 0 0 8px;
            border: 1px solid #e9ecef;
            border-right: none;
            background: #f8f9fa;
            padding: 8px 12px;
            font-size: 14px;
        }
        .input-group .form-control {
            border-left: none;
            border-radius: 0 8px 8px 0;
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
            width: 35px;
            height: 35px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            margin-right: 12px;
            font-size: 16px;
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
            height: 1px;
            background: linear-gradient(90deg, transparent 0%, #dee2e6 50%, transparent 100%);
            margin: 20px 0;
        }

        /* ช่องค้นหาพร้อมรายการตัวเลือก */
        .custom-search-input {
            background-color: #fff;
            border-radius: 0 8px 8px 0;
        }
        
        /* แสดงไอคอนค้นหาในช่อง input */
        .custom-search-input-wrapper {
            position: relative;
        }
        
        .custom-search-input-wrapper::after {
            content: "🔍";
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
        }
        
        /* เน้นช่องค้นหาเมื่อกำลังใช้งาน */
        .custom-search-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.15rem rgba(102, 126, 234, 0.2);
        }
    </style>

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <div class="modern-card">
                    <div class="gradient-header text-white py-4 position-relative">
                        <div class="container-fluid">
                            <div class="d-flex align-items-center position-relative">
                                <div class="section-icon me-3">
                                    👤
                                </div>
                                <div>
                                    <h2 class="mb-1 fw-bold">ข้อมูลพนักงาน</h2>
                                    <p class="mb-0 opacity-90 fs-6">จัดการข้อมูลพนักงานอย่างครบถ้วนและมีประสิทธิภาพ</p>
                                </div>
                                <div class="ms-auto d-none d-md-block">
                                    <div class="d-flex align-items-center">
                                        <div class="status-badge bg-success bg-opacity-20 text-white me-3">
                                            ✅ ระบบออนไลน์
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <form wire:submit.prevent="save" class="needs-validation" novalidate>
                            
                            <!-- ข้อมูลส่วนตัว -->
                            <div class="section-card mb-3">
                                <div class="section-header p-2">
                                    <div class="d-flex align-items-center">
                                        <div class="section-icon">
                                            👤
                                        </div>
                                        <h4 class="mb-0 fw-bold text-primary">ข้อมูลส่วนตัว</h4>
                                    </div>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">ชื่อ-นามสกุล <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">👤</span>
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
                                                <span class="input-group-text">📱</span>
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
                                            <label class="form-label">เพศ <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="mdi mdi-gender-male-female"></i></span>
                                                <select wire:model.defer="emp_gender" class="form-select" required>
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
                                            <label class="form-label">อายุ <span class="text-secondary">Auto</span></label>
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
                                            <label class="form-label">วุฒิการศึกษา <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="mdi mdi-school"></i></span>
                                                <select wire:model.defer="emp_education" class="form-select" required>
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
                            <div class="section-card mb-3">
                                <div class="section-header p-2">
                                    <div class="d-flex align-items-center">
                                        <div class="section-icon">
                                            💼
                                        </div>
                                        <h4 class="mb-0 fw-bold text-primary">ข้อมูลการทำงาน</h4>
                                    </div>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-3">
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
                                                <div class="custom-search-input-wrapper flex-grow-1">
                                                    <select class="form-select select2" wire:model="emp_factory_id">
                                                        <option value="">- เลือกโรงงาน -</option>
                                                        @foreach ($factories as $factory)
                                                            <option value="{{ $factory->id }}">{{ $factory->customer_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            @error('emp_factory_id')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
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
                            <div class="section-card mb-3">
                                <div class="section-header p-2">
                                    <div class="d-flex align-items-center">
                                        <div class="section-icon">
                                            📄
                                        </div>
                                        <h4 class="mb-0 fw-bold text-primary">ข้อมูลสัญญาจ้าง</h4>
                                    </div>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-3">
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
                                        @if ($emp_contract_type === 'สัญญาระยะสั้น')
                                        <div class="col-md-6">
                                            <label class="form-label">เลขที่สัญญา</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="mdi mdi-file-document-edit-outline"></i></span>
                                                <input type="text" wire:model="emp_contract_number" class="form-control" 
                                                    placeholder="เช่น สัญญาฉบับที่ 3">
                                            </div>
                                        </div>
                                        @endif
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
                            <div class="section-card mb-3">
                                <div class="section-header p-2">
                                    <div class="d-flex align-items-center">
                                        <div class="section-icon">
                                            ℹ️
                                        </div>
                                        <h4 class="mb-0 fw-bold text-primary">ข้อมูลเพิ่มเติม</h4>
                                    </div>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">สิทธิรักษาพยาบาล</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="mdi mdi-medical-bag"></i></span>
                                                <div class="custom-search-input-wrapper flex-grow-1">
                                                    <select class="form-select select2" wire:model="emp_medical_right">
                                                        <option value="">- เลือกสิทธิรักษาพยาบาล -</option>
                                                        @foreach ($medicalOptions as $item)
                                                            <option value="{{ $item->id }}">{{ $item->value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            @error('emp_medical_right')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="divider"></div>

                            <!-- ที่อยู่ตามทะเบียนบ้าน -->
                            <div class="section-card mb-3">
                                <div class="section-header p-2">
                                    <div class="d-flex align-items-center">
                                        <div class="section-icon">
                                            📑
                                        </div>
                                        <h4 class="mb-0 fw-bold text-primary">ที่อยู่ตามทะเบียนบ้าน</h4>
                                    </div>
                                </div>
                                <div class="card-body p-3">
                                    <div class="col-12 mb-2">
                                            <label class="form-label">รายละเอียดที่อยู่ <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">📝</span>
                                                <textarea wire:model.defer="registered_address_details" class="form-control" rows="2" 
                                                    placeholder="บ้านเลขที่ หมู่บ้าน ซอย ถนน"></textarea>
                                            </div>
                                            @error('registered_address_details')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">จังหวัด <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                
                                                <select wire:model.live="registered_province_id" class="form-select select2" style="width: 10%">
                                                    <option value="">- เลือกจังหวัด -</option>
                                                    @foreach ($provinces as $province)
                                                        <option value="{{ $province->id }}">{{ $province->province_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            @error('registered_province_id')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror

                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">อำเภอ/เขต <span class="text-danger">*</span></label>
                                            <div class="input-group">
                   
                                                @if(count($registeredAmphures) > 0)
                                                    <select wire:model.live="registered_amphur_id" class="form-select select2">
                                                        <option value="">- เลือกอำเภอ/เขต -</option>
                                                        @foreach ($registeredAmphures as $amphur)
                                                            <option value="{{ $amphur->id }}">{{ $amphur->amphur_name }}</option>
                                                        @endforeach
                                                        <option value="custom">🖊️ อื่นๆ (กรอกเอง)</option>
                                                    </select>
                                                @else
                                                    <input type="text" wire:model.defer="registered_amphur_text" class="form-control" 
                                                        placeholder="กรอกชื่ออำเภอ/เขต (ไม่มีข้อมูลในระบบ)" 
                                                        {{ $registered_province_id ? '' : 'disabled' }}>
                                                @endif
                                            </div>
                                            
                                            @if(count($registeredAmphures) > 0 && $registered_amphur_id === 'custom')
                                                <div class="mt-2">
                                                    <input type="text" wire:model.defer="registered_amphur_text" class="form-control" 
                                                        placeholder="กรอกชื่ออำเภอ/เขต">
                                                </div>
                                            @endif
                                            
                                            @error('registered_amphur_id')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                            @error('registered_amphur_text')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                            @if(count($registeredAmphures) == 0 && $registered_province_id)
                                                <small class="text-info">⚠️ จังหวัดนี้ยังไม่มีข้อมูลอำเภอในระบบ กรุณากรอกด้วยตนเอง</small>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">ตำบล/แขวง <span class="text-danger">*</span></label>
                                            <div class="input-group">
               
                                                @if(count($registeredDistricts) > 0)
                                                    <select wire:model.live="registered_district_id" class="form-select select2">
                                                        <option value="">- เลือกตำบล/แขวง -</option>
                                                        @foreach ($registeredDistricts as $district)
                                                            <option value="{{ $district->id }}">{{ $district->district_name }}</option>
                                                        @endforeach
                                                        <option value="custom">🖊️ อื่นๆ (กรอกเอง)</option>
                                                    </select>
                                                @else
                                                    <input type="text" wire:model.defer="registered_district_text" class="form-control" 
                                                        placeholder="กรอกชื่อตำบล/แขวง (ไม่มีข้อมูลในระบบ)" 
                                                        {{ ($registered_province_id && (count($registeredAmphures) == 0 || $registered_amphur_id || $registered_amphur_text)) ? '' : 'disabled' }}>
                                                @endif
                                            </div>
                                            
                                            @if(count($registeredDistricts) > 0 && $registered_district_id === 'custom')
                                                <div class="mt-2">
                                                    <input type="text" wire:model.defer="registered_district_text" class="form-control" 
                                                        placeholder="กรอกชื่อตำบล/แขวง">
                                                </div>
                                            @endif
                                            
                                            @error('registered_district_id')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                            @error('registered_district_text')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                            @if(count($registeredDistricts) == 0 && ($registered_amphur_id || $registered_amphur_text))
                                                <small class="text-info">⚠️ อำเภอนี้ยังไม่มีข้อมูลตำบลในระบบ กรุณากรอกด้วยตนเอง</small>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">รหัสไปรษณีย์ <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">📮</span>
                                                <input type="text" wire:model="registered_zipcode" class="form-control" 
                                                    placeholder="รหัสไปรษณีย์" maxlength="5" 
                                                    {{ (count($registeredDistricts) > 0 && $registered_district_id !== 'custom') ? 'readonly' : '' }}>
                                            </div>
                                            @error('registered_zipcode')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                            @if(count($registeredDistricts) == 0 && ($registered_district_text || $registered_amphur_text))
                                                <small class="text-info">ℹ️ กรุณากรอกรหัสไปรษณีย์ 5 หลัก</small>
                                            @endif
                                            @if($registered_district_id === 'custom')
                                                <small class="text-info">ℹ️ กรุณากรอกรหัสไปรษณีย์ 5 หลัก</small>
                                            @endif
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>

                            <div class="divider"></div>

                            <!-- ผู้ติดต่อฉุกเฉิน -->
                            <div class="section-card mb-3">
                                <div class="section-header p-2">
                                    <div class="d-flex align-items-center">
                                        <div class="section-icon">
                                            🚨
                                        </div>
                                        <h4 class="mb-0 fw-bold text-primary">ผู้ติดต่อฉุกเฉิน</h4>
                                    </div>
                                </div>
                                <div class="card-body p-3">
                                    @foreach ($emp_emergency_contacts as $index => $contact)
                                    <div class="row g-3 mb-3 pb-3 border-bottom">
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
                            <div class="section-card mb-3">
                                <div class="section-header p-2">
                                    <div class="d-flex align-items-center">
                                        <div class="section-icon">
                                            📎
                                        </div>
                                        <h4 class="mb-0 fw-bold text-primary">ไฟล์แนบ</h4>
                                    </div>
                                </div>
                                <div class="card-body p-3">
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
                            @can('create employee')
                            <div class="text-center py-3">
                                <button type="submit" class="btn btn-primary btn-lg px-4">
                                    💾 บันทึกข้อมูลพนักงาน
                                </button>
                            </div>
                              @endcan
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
   {{-- <script>
    $('.select2').select2({
        theme: 'bootstrap-5',
       height: '1000px',
    });
       </script> --}}
</div>
