<?php



namespace App\Livewire\Users;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    
    public $search = '';
    public $user_id;
    public $role_id;
    public $allRoles = [];
    
    // ตัวแปรสำหรับการแก้ไขข้อมูล
    public $editingUser = null;
    public $name;
    public $email;
    
    // ตัวแปรสำหรับเปลี่ยนรหัสผ่าน
    public $password;
    public $password_confirmation;
    
    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'password' => 'nullable|min:8|confirmed'
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->allRoles = Role::all();
    }

    public function edit($id)
    {
        $this->user_id = $id;
        $user = User::findOrFail($id);
        $this->role_id = $user->roles()->first()?->id;
    }

    // จัดการโปรไฟล์
    public function save()
    {
        $user = User::findOrFail($this->user_id);
        $role = Role::find($this->role_id);
        
        if (!$role) {
            session()->flash('error', 'ไม่พบโปรไฟล์ที่เลือก');
            return;
        }

        $user->syncRoles([$role->name]);
        session()->flash('message', 'บันทึกโปรไฟล์สำเร็จ');
        $this->dispatch('close-modal');
    }

    // แก้ไขข้อมูลผู้ใช้
    public function editUser($userId)
    {
        $this->editingUser = User::find($userId);
        $this->name = $this->editingUser->name;
        $this->email = $this->editingUser->email;
        $this->dispatch('show-edit-modal');
    }

    public function updateUser()
    {
        $this->rules['email'] = 'required|email|unique:users,email,' . $this->editingUser->id;
        
        $this->validate();

        $this->editingUser->update([
            'name' => $this->name,
            'email' => $this->email
        ]);

        session()->flash('message', 'อัพเดทข้อมูลผู้ใช้สำเร็จ');
        $this->dispatch('close-modal');
        $this->resetForm();
    }

    // เปลี่ยนรหัสผ่าน
    public function resetPassword($userId)
    {
        $this->user_id = $userId;
        $this->password = '';
        $this->password_confirmation = '';
        $this->dispatch('show-password-modal');
    }

    public function updatePassword()
    {
        $this->validate([
            'password' => 'required|min:8|confirmed'
        ]);

        $user = User::findOrFail($this->user_id);
        $user->update([
            'password' => bcrypt($this->password)
        ]);

        session()->flash('message', 'เปลี่ยนรหัสผ่านสำเร็จ');
        $this->dispatch('close-password-modal');
        $this->resetForm();
    }

    // ลบผู้ใช้
    public function deleteConfirm($userId)
    {
        $this->user_id = $userId;
        $this->dispatch('show-delete-confirmation');
    }

    public function deleteUser()
    {
        $user = User::find($this->user_id);
        
        if ($user->id === auth()->id()) {
            session()->flash('error', 'ไม่สามารถลบบัญชีของตัวเองได้');
            return;
        }

        $user->delete();
        session()->flash('message', 'ลบผู้ใช้สำเร็จ');
    }

    private function resetForm()
    {
        $this->editingUser = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
    }

    public function render()
    {
        $users = User::with('roles')
            ->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->paginate(15);
        return view('livewire.users.user-index', [
            'users' => $users
        ])->layout('layouts.vertical-main', ['title' => 'จัดการผู้ใช้งาน']);
    }
}
