{{-- ตัวอย่างการใช้สิทธิ์ใน Blade --}}
@can('create user')
    <button class="btn btn-primary">สร้างผู้ใช้</button>
@endcan

@role('Admin')
    <div class="alert alert-info">เฉพาะแอดมินเท่านั้น</div>
@endrole
